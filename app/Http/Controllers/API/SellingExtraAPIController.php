<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\SellingExtra\SellingExtraRepositoryInterface;

class SellingExtraAPIController extends Controller
{
    //
    private $repo;

    public function __construct(SellingExtraRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getSellingExtraCategories(Request $request)
    {
        $data = $this->repo->listAllCategories($request);
        ResponseData($data);
    }

    public function createSellingExtraCategory(Request $request)
    {
        $data = $request->all();
        ResponseData($this->repo->createCategory($data), 201);
    }

    public function getSellingExtras(Request $request)
    {
        $data = $this->repo->listAllData($request);
        ResponseData($data);
    }

    public function createSellingExtras(Request $request)
    {
        $data = $request->all();
        ResponseData($this->repo->create($data), 201);
    }

    public function updateSellingExtras(Request $request, $id)
    {
        $data = $request->all();
        ResponseData($this->repo->update($data,$id));
    }

    public function getSellingExtrasFromPos(Request $request)
    {
        ResponseData($this->repo->listAllData($request, true));
    }
}
