<?php

namespace App\Repositories\RoomSession;

use App\Models\Invoice;
use App\Models\RoomSession;
use Illuminate\Support\Facades\DB;

class RoomSessionRepository implements RoomSessionRepositoryInterface
{
    public function creaetRoomSession(array $data)
    {
        DB::beginTransaction();
        try {
            $roomSession = RoomSession::create($data);
            DB::commit();
            return $roomSession;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
