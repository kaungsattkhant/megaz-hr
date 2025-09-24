<?php

namespace App\Repositories\Event;

interface EventRepositoryInterface
{
    public function eventLists();

    public function createOrUpdateEvent(array $data);
}
