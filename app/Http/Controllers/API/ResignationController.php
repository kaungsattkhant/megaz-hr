<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Resign\ResignCreateRequest;
use App\Http\Requests\Resign\ResignCategoryRequest;
use App\Repositories\Resignation\ResignationRepositoryInterface;

class ResignationController extends Controller
{
    private ResignationRepositoryInterface $resignationRepository;
    public function __construct(ResignationRepositoryInterface $resignationRepository)
    {
        $this->resignationRepository = $resignationRepository;
    }
    public function getResignationCategoryLists()
    {
        $data = $this->resignationRepository->getResignationCategoryLists();
        ResponseData($data);
    }
    public function createResignationCategory(ResignCategoryRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->resignationRepository->createResignationCategory($validatedData);
        ResponseData($data);
    }
    public function getAllResignations(Request $request)
    {
        $data = $this->resignationRepository->getAllResignations($request);
        ResponseData($data);
    }
    public function createResignation(ResignCreateRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->resignationRepository->createResignation($validatedData);
        ResponseData($data);
    }
    public function getResignationById($id)
    {
        $data = $this->resignationRepository->getResignationById($id);
        ResponseData($data);
    }
    public function updateResignation(Request $request, $id)
    {
        // $validatedData = $request->validated();
        $data = $this->resignationRepository->updateResignation($request->all(), $id);
        ResponseData($data);
    }

    public function getResignationByStaffId($staffId)
    {
        $data = $this->resignationRepository->getResignationByStaffId($staffId);
        ResponseData($data);
    }
}
