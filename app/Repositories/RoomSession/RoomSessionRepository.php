<?php

namespace App\Repositories\RoomSession;

use App\Models\Invoice;
use App\Models\RoomSession;

class RoomSessionRepository implements RoomSessionRepositoryInterface
{
    public function creaetRoomSession(array $data)
    {
        $roomSession = RoomSession::create($data);
        return $roomSession;
    }


}
