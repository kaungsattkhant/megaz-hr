<?php

namespace App\Repositories\CookingPlace;

use Illuminate\Http\Request;

interface CookingPlaceRepositoryInterface
{
    public function listAllCookingPlaces();

    public function createCookingPlace(Request $request);

    public function updateCookingPlace(Request $request,int $id);

    public function deleteCookingPlace(int $id);

    public function cookingPlaceDetail(int $id);

}

