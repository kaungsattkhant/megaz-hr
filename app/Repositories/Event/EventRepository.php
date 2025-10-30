<?php

namespace App\Repositories\Event;


use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Repositories\Event\EventRepositoryInterface;

class EventRepository implements EventRepositoryInterface
{
    public function eventLists()
    {
        return Event::orderBy('id', 'desc')->get();
    }

    public function createOrUpdateEvent(array $data)
    {
        DB::beginTransaction();
        try {
            if (!isset($data['id'])) {
                $data['id'] = null;
            }
            $event = Event::updateOrCreate(['id' => $data['id']], $data);
            DB::commit();
            return $event;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
