<?php

namespace App\Repositories\Booking;

interface BookingRepositoryInterface
{

    public function listAllData();

    public function createData(array $data);

}
