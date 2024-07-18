<?php

namespace App\Repositories\Booking;

use Illuminate\Http\Request;

interface BookingRepositoryInterface
{

    public function listAllDataUserApp();

    public function createData(array $data);

    public function bookingList();

    public function bookingStatusChange(array $data);

    public function activateBooking($request);

}
