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

    public function getEntityWithInvoice(Request $request,$id)
    {
        $data = $request->all();
        $data['area_id'] = $id;
        $data['current_date'] = CurrentDate();
        $entity = $this->entityRepo->entityWithInvoice($data);
        ResponseData($entity);
    }

    public function entitySessionWithInvoiceDetail(Request $request, $id)
    {
        $data = $request->all();
        $data['current_date'] = CurrentDate();
        $entity = $this->entityRepo->entitySessionWithInvoice($data,$id);
        ResponseData($entity);
    }

    public function tableWithInvoiceDetail(Request $request, $id)
    {
        $data = $request->all();
        $data['current_date'] = CurrentDate();
        $entity = $this->entityRepo->tableWithInvoiceDetail($data,$id);
        ResponseData($entity);
    }
    

    public function getEntitySessionDetail(Request $request, int $id)
    {
        // if(!isset($request->invoice_id)){
        //     ResponseMessage('Invoice Id is required',419);
        // }
        $data = $request->all();
        $entity = $this->entityRepo->entityDetail($data, $id);

        ResponseData($entity);
    }

    public function getOnlyInactiveEntities(Request $request,$id)
    {
        $data = $request->all();
        $data['area_id'] = $id;
        $entities = $this->entityRepo->inactiveEntityList($data);
        ResponseData($entities);
    }



}
