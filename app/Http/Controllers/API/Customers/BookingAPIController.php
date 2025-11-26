<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Repositories\Booking\BookingRepositoryInterface;
use Illuminate\Http\Request;

class BookingAPIController extends Controller
{
    //
    private $bookingRepo;

    public function __construct(BookingRepositoryInterface $bookingRepo)
    {
        $this->bookingRepo = $bookingRepo;
    }

    public function listAllBookings()
    {
        $books = $this->bookingRepo->listAllDataUserApp();
    }

    public function createBooking(Request $request)
    {
        $booking = $this->bookingRepo->createData($request->all());
    }
}
