<?php

namespace App\Repositories\Booking;

use App\Models\Booking;
use App\Models\BookingMenu;
use App\Models\HeadCount;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;

class BookingRepository implements BookingRepositoryInterface
{

    public function bookingList()
    {
        $bookings = Booking::where('status','!=','cancel')->with(['customer', 'headCount', 'entity'])
            ->orderBy('created_at', 'desc')
            ->paginate(config('common.list'));
        ResponseData($bookings);
    }

    public function listAllDataUserApp()
    {
        $booking = Booking::where('customer_id', UserData()->id)->with(['customer', 'headCount', 'entity'])->orderBy('created_at', 'desc')->paginate(config('common.list'));
        ResponseData($booking);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $startDate = Carbon::parse($data['start_date']);
            $endDate = $startDate->copy()->addHours((int)$data['session']);

            $existingBookings = Booking::where('entity_id', $data['entity_id'])
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($query) use ($startDate, $endDate) {
                            $query->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                })
                ->first();

            if ($existingBookings != null) {
                ResponseData('There is an existing booking for this entity in this time frame', 422);
            }
            $headCount = $this->headCountCreate($data);
            $data['head_count_id'] = $headCount->id;
            $data['customer_id'] = UserData()->id;
            $data['date_time'] = CurrentTime();
            $data['end_date'] = $endDate->format('Y-m-d H:i:s');
            $data['booking_id'] = 1;
            $booking = Booking::create($data);
            if (isset($data['menus'])) {
                $menuData = json_decode($data['menus'], true);
                foreach ($menuData as $menu) {
                    if ($menu['is_package'] == 1) {
                        BookingMenu::create([
                            'quantity' => $menu['quantity'],
                            'menu_id' => $menu['menu_id'],
                            'booking_id' => $booking->id,
                            'price' => 0,
                            'discount_value' => 0
                        ]);
                    } else {
                        BookingMenu::create([
                            'quantity' => $menu['quantity'],
                            'menu_id' => $menu['menu_id'],
                            'booking_id' => $booking->id,
                            'price' => $menu['price'],
                            'discount_value' => $menu['discount_value']
                        ]);
                    }
                }
            }
            $booking->booking_id = sprintf('%05d', $booking->id);
            $booking->save();
            DB::commit();
            ResponseData($booking);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function headCountCreate(array $data)
    {
        $data['total_head_count'] = $data['female'] + $data['male'] + $data['child'];
        $headCount = HeadCount::create($data);
        return $headCount;
    }

    public function bookingStatusChange(array $data)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::find($data['id']);
            if ($booking) {
                if ($data['is_confirm'] == 1) {
                    $booking->status = 'confirm';
                    $booking->confirmed_at = CurrentTime();
                    $booking->confirmed_by = UserData()->id;
                    $booking->save();
                } else {
                    $booking->status = 'cancel';
                    $booking->cancelled_at = CurrentTime();
                    $booking->cancelled_by = UserData()->id;
                    $booking->save();
                }
                DB::commit();
                ResponseData($booking);
            }else{
                ResponseMessage('Booking not found', 422);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function activateBooking($request)
    {
        DB::beginTransaction();
        try{

            $booking = Booking::find($request->id);
            if($booking)
            {
                $booking->status = 'used';
                $booking->save();
                DB::commit();
                return $booking;
            }

        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
}
