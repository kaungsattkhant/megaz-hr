<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Booking\BookingRepositoryInterface;
use Illuminate\Http\Request;

class BookingAPIController extends Controller
{
    //
    protected $bookingRepo;
    public function __construct(BookingRepositoryInterface $bookingRepo)
    {
        $this->bookingRepo = $bookingRepo;
    }

    public function bookingList()
    {
        $this->bookingRepo->bookingList();
    }

    public function createBooking(Request $request)
    {
        $this->bookingRepo->createData($request->all());
    }

}
