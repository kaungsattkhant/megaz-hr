<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $customers = Customer::where('is_active')->skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'customers');
            $paginationData['customers'] = $customers;
            return $paginationData;
        } else {
            $customers = Customer::all();

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

            $bdCus = Customer::where(function ($query) use ($today, $endOfYear) {
                $query->whereBetween(DB::raw('DAYOFYEAR(birthdate)'), [$today->dayOfYear, $endOfYear->dayOfYear]);
            })->orWhere(function ($query) use ($dateAfter7Days, $startOfYear) {
                $query->whereBetween(DB::raw('DAYOFYEAR(birthdate)'), [$startOfYear->dayOfYear, $dateAfter7Days->dayOfYear]);
            })->get();
            ResponseData($bdCus);
        } else {
            return Customer::whereBetween(
                DB::raw('DAYOFYEAR(birthdate)'),
                [$today->dayOfYear, $dateAfter7Days->dayOfYear]
            )->get();
        }
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::create($data);
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
}
