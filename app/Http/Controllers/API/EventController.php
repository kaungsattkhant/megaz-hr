<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Event\EventRepositoryInterface;

class EventController extends Controller
{
  protected $eventRepo;
  public function __construct(EventRepositoryInterface $eventRepo)
  {
    $this->eventRepo = $eventRepo;
  }
  public function eventLists()
  {
    $events = $this->eventRepo->eventLists();
    return ResponseData($events);
  }
  public function createOrUpdateEvent(Request $request)
  {
    $data = $request->all();
    $event = $this->eventRepo->createOrUpdateEvent($data);
    return ResponseData($event);
  }
}
