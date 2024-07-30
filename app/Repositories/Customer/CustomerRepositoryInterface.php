<?php

namespace App\Repositories\Customer;

use Illuminate\Http\Request;

interface CustomerRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data, int $id);

    public function deleteData(int $id);

    public function getCustomersWithUpcomingBirthdays();

    public function listOfCustomer($request);

    public function customerDetail(int $id);

    // user app
    public function customerProfileData();

    public function customerAddressUpdate(int $id, array $data);

    public function createCustomerAddress(array $data);

    public function defaultCustomerAddress(int $id);

    public function customerProfileEdit(array $data);

    public function customerAddressList();

    public function deleteCustomerAddress(int $id);

    public function changePhoneNumberOTP($request);

    public function changePhone($request);

}
