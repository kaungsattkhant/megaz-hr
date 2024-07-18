<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Booking\BookingRepositoryInterface;
use App\Repositories\Invoice\InvoiceRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingAPIController extends Controller
{
    //
    protected $bookingRepo;
    protected $invoiceRepo;
    protected $orderRepo;

    public function __construct(BookingRepositoryInterface $bookingRepo, InvoiceRepositoryInterface $invoiceRepo, OrderRepository $orderRepo)
    {
        $this->bookingRepo = $bookingRepo;
        $this->invoiceRepo = $invoiceRepo;
        $this->orderRepo = $orderRepo;
    }

    public function bookingList()
    {
        $this->bookingRepo->bookingList();
    }

    public function createBooking(Request $request)
    {
        $this->bookingRepo->createData($request->all());
    }

    public function bookingStatusChange(Request $request,int $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        $this->bookingRepo->bookingStatusChange($data);
    }

    public function bookingActivate(Request $request)
    {
        // booking status to used
        DB::beginTransaction();
        try{
            $booking = $this->bookingRepo->activateBooking($request);
            $roomOpenData = [
                'entity_id' => $booking->entity_id,
                'customer_id' => $booking->customer_id,
                'invoice_date' => $booking->date_time,
                'session_duration' => $booking->session,
                'type' => $booking->session_type,
                'female' => $request->female,
                'male' => $request->male,
                'child' => $request->child,
                'package_id' => $booking->package_id
            ];

            if ($booking->session_type == 'package') {
                $roomOpenData['package_id'] = $booking->package_id;
            }

        $invoice = $this->invoiceRepo->createData($roomOpenData);
        foreach($booking->bookingMenus as $bookingMenu){
            $menuArray[] = [
                "menu_id" => $bookingMenu->menu_id,
                "quantity" => $bookingMenu->quantity,
                "original_price" => $bookingMenu->price,
                "menu_category_id" => $bookingMenu->menu->menu_category_id,
                "remark" => $bookingMenu->remark,
            ];
        }
        $orderData['invoice_id'] = $invoice->id;
        $orderData['menuArray'] = $menuArray;
        $order = $this->orderRepo->createMultipleOrder($orderData);
        DB::commit();

        ResponseMessage('Booking activated successfully', 200);
        }catch(\Exception $e){
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }


    }

}
