<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = Customer::count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $customers = Customer::where('is_active')->skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'customers');
            $paginationData['customers'] = $customers;

            return $paginationData;
        }
        else{
            $customers = Customer::all();

            return $customers;
        }
    }

    public function createData(array $data)
    {
        $customer = Customer::create($data);

        return $customer;
    }

    public function updateData(array $data,int $id)
    {
        $customer = Customer::find($id);
        if($customer)
        {
            $customer->update($data);
        }
        return $customer;
    }

    public function deleteData(int $id)
    {
        $customer = Customer::find($id);
        if($customer)
        {
            $customer->is_active=0;
            $customer->save();
            return true;
        }
        return false;

    }
}
