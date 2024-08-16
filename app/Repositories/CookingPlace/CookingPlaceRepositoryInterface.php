<?php

namespace App\Repositories\CookingPlace;

use Illuminate\Http\Request;

interface CookingPlaceRepositoryInterface
{
    public function listAllCookingPlaces();

    public function createCookingPlace(Request $request);

    public function deleteCookingPlace(int $id);

}
