<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\Entity\EntityCreateRequest;
use App\Http\Requests\Entity\EntityUpdateRequest;
use App\Models\Area;
use App\Repositories\Entity\EntityRepositoryInterface;

class EntityAPIController extends Controller
{
    //
    protected $entityRepo;
    public function __construct(EntityRepositoryInterface $entityRepo)
    {
        $this->entityRepo = $entityRepo;
    }
    public function getEntityData(Request $request)
    {
        $entityType = 'all';
        if($request->type){
            $entityType = $request->type;
        }
        $entities = $this->entityRepo->listAllData($request, $entityType);

        ResponseData($entities);
    }


    public function createEntity(EntityCreateRequest $request)
    {
        $entity = $this->entityRepo->createData($request->all());
        ResponseData($entity);
    }

    public function updateEntity(EntityUPdateRequest $request, $id)
    {
        $entity = $this->entityRepo->updateData($request->all(),$id);
        ResponseData($entity);

    }

    public function deleteEntity($id)
    {
        $entity = $this->entityRepo->deleteData($id);
        if($entity==true)
        {
            ResponseMessage('Entity deleted');
        }else{
            ResponseMessage('Entity not found or some error occur');
        }
    }

    public function getRoomsWithInvoice(Request $request)
    {
        $data = $request->all();
        $data['current_date'] = CurrentDate();
        $entity = $this->entityRepo->roomsWithInvoice($data);
        ResponseData($entity);
    }

    public function getTablesWithInvoice(Request $request)
    {
        $data = $request->all();
        $data['current_date'] = CurrentDate();
        $entity = $this->entityRepo->tablesWithInvoice($data);
        ResponseData($entity);
    }

    public function getRoomDetail(Request $request, int $id)
    {
        $data = $request->all();
        $entity = $this->entityRepo->roomDetail($data, $id);

        ResponseData($entity);
    }

    public function getOnlyInactiveRooms(Request $request)
    {
        $entities = $this->entityRepo->inactiveRoomsList($request);

        ResponseData($entities);
    }

}
