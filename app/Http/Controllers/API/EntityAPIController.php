<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\Entity\EntityCreateRequest;
use App\Http\Requests\Entity\EntityUpdateRequest;

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

    public function invoiceWithRooms(Request $request)
    {
        $data = $request->all();
        $data['current_date'] = CurrentDate();
        $entity = $this->entityRepo->roomWithInvoice($data);
        ResponseData($entity);
    }



}
