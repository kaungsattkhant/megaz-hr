<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Customer::count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $customers = Customer::with(['addresses' => function ($query) {
                $query->where('is_default', 1)->with('township.latestDeliveryCharge');
            }])->where('is_active', 1)->skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'customers');
            $paginationData['customers'] = $customers;
            return $paginationData;
        } else {
            $customers = Customer::where('is_active', 1)->with(['addresses' => function ($query) {
                $query->where('is_default', 1)->with('township.latestDeliveryCharge');
            }])->get();

            return $customers;
        }
    }

    public function getCustomersWithUpcomingBirthdays()
    {
        $today = Carbon::today();
        $dateAfter7Days = $today->copy()->addDays(7);

        if ($dateAfter7Days->year > $today->year) {
            $endOfYear = Carbon::create($today->year, 12, 31);
            $startOfYear = Carbon::create($dateAfter7Days->year, 1, 1);

            $bdCus = Customer::with(['addresses' => function ($query) {
                $query->where('is_default', 1)->with('township');
            }])->where(function ($query) use ($today, $endOfYear) {
                $query->whereBetween(DB::raw('DAYOFYEAR(birthdate)'), [$today->dayOfYear, $endOfYear->dayOfYear]);
            })->orWhere(function ($query) use ($dateAfter7Days, $startOfYear) {
                $query->whereBetween(DB::raw('DAYOFYEAR(birthdate)'), [$startOfYear->dayOfYear, $dateAfter7Days->dayOfYear]);
            })->get();
            ResponseData($bdCus);
        } else {
            return Customer::with(['addresses' => function ($query) {
                $query->where('is_default', 1)->with('township');
            }])->whereBetween(
                DB::raw('DAYOFYEAR(birthdate)'),
                [$today->dayOfYear, $dateAfter7Days->dayOfYear]
            )->get();
        }
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            if (isset($data['image'])) {
                $imageData = $data['image'];
                $extension = $imageData->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $data['image_path'] = $imageData->storeAs('images/customers', $hashedName, 'public');
                $data['image_url'] = Storage::url($data['image_path']);
            }
            $data['password'] = 'default_password';
            $data['otp'] = '000000';
            $data['is_verified'] = 1;

            $customer = Customer::create($data);
            if (isset($data['address'])) {
                CustomerAddress::create([
                    'name' => $data['address_name'],
                    'customer_id' => $customer->id,
                    'address' => 'addresss',
                    'is_default' => 1,
                    'township_id' => $data['township_id']
                ]);
            }
            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateData(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::find($id);
            if ($customer) {
                if (isset($data['image'])) {
                    $imageData = $data['image'];
                    $extension = $imageData->getClientOriginalExtension();
                    $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                    $data['image_path'] = $imageData->storeAs('images', $hashedName, 'public');
                    $data['image_url'] = Storage::url($data['image_path']);
                }
                $customer->update($data);
            }
            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function deleteData(int $id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->is_active = 0;
            $customer->save();
            return true;
        }
        return false;
    }

    public function listOfCustomer($request)
    {
        $customers = DB::table('customers')
            ->leftJoin('invoices', 'customers.id', '=', 'invoices.customer_id')
            ->leftJoin('customer_addresses', 'customers.id', '=', 'customer_addresses.customer_id')
            ->leftJoin('townships', 'customer_addresses.township_id', '=', 'townships.id')
            ->select(
                'customers.id',
                'customers.name',
                'customers.phone_number',
                'customers.rentation',
                'customers.birthdate',
                'customers.email',
                'customer_addresses.address as customer_address',
                'townships.name as township_name',
                DB::raw('COALESCE(SUM(invoices.total), 0) as total_amount')
            )
            ->groupBy(
                'customers.id',
                'customers.name',
                'customers.phone_number',
                'customers.rentation',
                'customers.birthdate',
                'customers.email',
                'customer_addresses.address',
                'townships.name'
            )
            ->paginate();

        return $customers;
    }



    public function customerDetail(int $id)
    {
        $customer = Customer::with(['addresses' => function ($query) {
            $query->where('is_default', 1)->with('township');
        }, 'invoices.orders.orderItems.menu', 'invoices.sessions.entity'])
        ->find($id);

        if ($customer == null) {
            return ResponseMessage('Customer not found', 404);
        }

        $customerDetail = DB::table('invoices')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->leftJoin('customer_addresses', 'customers.id', '=', 'customer_addresses.customer_id')
            ->leftJoin('townships', 'customer_addresses.township_id', '=', 'townships.id')
            ->select(
                'customers.id',
                'customers.name',
                'customers.rentation',
                'customers.phone_number',
                'customers.birthdate',
                'customers.email',
                'customer_addresses.address as customer_address',
                'townships.name as township_name',
                DB::raw('SUM(invoices.total) as total_amount')
            )
            ->where('customers.id', $id)
            ->groupBy(
                'customers.id',
                'customers.name',
                'customers.rentation',
                'customers.phone_number',
                'customers.birthdate',
                'customers.email',
                'customer_addresses.address',
                'townships.name'
            )
            ->first();

        $customerData = [
            'customer' => $customer,
            'customer_detail' => $customerDetail
        ];

        return ResponseData($customerData);
    }


    // user app
    public function customerProfileData()
    {
        $customer = Customer::where('id', UserData()->id)->with(['addresses' => function ($query) {
            $query->with('township.latestDeliveryCharge');
        }])->first();
        if ($customer == null) {
            ResponseMessage("Customer not found", 404);
        }

        ResponseData($customer);
    }

    public function customerAddressUpdate(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $customerAddress = CustomerAddress::find($id);
            if ($customerAddress == null) {
                ResponseMessage("Customer address not found", 404);
            }
            $customerAddress->update($data);
            DB::commit();
            ResponseData($customerAddress, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function createCustomerAddress(array $data)
    {
        DB::beginTransaction();
        try {
            $data['customer_id'] = UserData()->id;
            $data['is_default'] = 0;
            $customerAddress = CustomerAddress::create($data);
            DB::commit();
            ResponseData($customerAddress, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function defaultCustomerAddress(int $id)
    {
        DB::beginTransaction();
        try {
            CustomerAddress::where('customer_id', UserData()->id)->update(['is_default' => 0]);
            $customerAddress = CustomerAddress::find($id);
            $customerAddress->is_default = 1;
            $customerAddress->save();

            DB::commit();
            ResponseMessage('The selected address has been successfully set as your default address.', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function customerProfileEdit(array $data)
    {
        DB::beginTransaction();
        try{
            $customer = Customer::find(UserData()->id);
            $customer->update($data);
            DB::commit();
            ResponseData($customer);
        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function customerAddressList()
    {
        $customerAddress = CustomerAddress::where('customer_id', UserData()->id)->with('township')->get();
        ResponseData($customerAddress);
    }

    public function deleteCustomerAddress(int $id)
    {
        DB::beginTransaction();
        try{
            $customerAddress = CustomerAddress::find($id);
            if($customerAddress == null)
            {
                ResponseMessage("Customer address not found", 404);
            }
            $customerAddress->delete();
            DB::commit();
            ResponseMessage('The selected address has been successfully deleted.', 200);
        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function changePhoneNumberOTP($request)
    {
        DB::beginTransaction();
        try{

            $customer = Customer::find(UserData()->id);
            if(!$customer)
            {
                ResponseMessage('Customer not found', 404);
            }
            $customer->otp = 000000;
            $customer->save();
            DB::commit();
            ResponseMessage('OTP code sent, please check your SMS',200);

        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function changePhone($request)
    {
        DB::beginTransaction();
        try{

            $customer = Customer::find(UserData()->id);
            if(!$customer)
            {
                ResponseMessage('Customer not found', 404);
            }

            if($customer->otp == $request->otp)
            {
                if(Hash::check($request->password, $customer->password))
                {
                    $existingCustomer = Customer::where('phone_number', $request->phone_number)->first();
                    if($existingCustomer)
                    {
                        ResponseMessage('Phone number already exist', 402);
                    }
                    $customer->phone_number = $request->phone_number;
                    $customer->save();
                }else{
                    ResponseMessage('Current password not match', 402);
                }

                DB::commit();
                ResponseMessage('Phone number changed successfully',200);
            }else{
                ResponseMessage('OTP code not match, please try again', 402);
            }


        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

}
