<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\CookingPlace\CookingPlaceRepositoryInterface;
use Illuminate\Http\Request;

class CookingPlaceAPIController extends Controller
{
    //
    protected $cookingPlaceRepo;

    public function __construct(CookingPlaceRepositoryInterface $cookingPlaceRepo)
    {
        $this->cookingPlaceRepo = $cookingPlaceRepo;
    }

    public function listAllCookingPlaces()
    {
        $this->cookingPlaceRepo->listAllCookingPlaces();
    }

    public function createCookingPlace(Request $request)
    {
        $this->cookingPlaceRepo->createCookingPlace($request);
    }

    public function updateCookingPlace(Request $request,int $id)
    {
        $this->cookingPlaceRepo->updateCookingPlace($request, $id);
    }

    public function deleteCookingPlace(int $id)
    {
        $this->cookingPlaceRepo->deleteCookingPlace($id);
    }
}
