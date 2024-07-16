<?php

namespace App\Repositories\Booking;

interface BookingRepositoryInterface
{

    public function listAllDataUserApp();

    public function createData(array $data);

    public function bookingList();

}
