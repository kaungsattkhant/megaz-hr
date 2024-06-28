<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Repositories\Entity\EntityRepositoryInterface;
use Illuminate\Http\Request;

class EntityAPIContorller extends Controller
{
    //
    protected $entityRepo;
    public function __construct(EntityRepositoryInterface $entityRepo)
    {
      $this->entityRepo = $entityRepo;
    }

    public function roomList()
    {
        $rooms = $this->entityRepo->roomListForUserApp();
        ResponseData($rooms);
    }
}
