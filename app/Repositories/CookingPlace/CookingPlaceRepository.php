<?php

namespace App\Repositories\CookingPlace;

use App\Models\AvailableCookingPlace;
use App\Models\CookingPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CookingPlaceRepository implements CookingPlaceRepositoryInterface
{
    public function listAllCookingPlaces()
    {
        $cookingPlaces = CookingPlace::with('area','availableCookingPlaces.cookingPlaceable')->orderBy('created_at','desc')->paginate(config('common.list_count'));

        ResponseData($cookingPlaces);
    }

    public function createCookingPlace(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = $request->all();
            $data['created_by'] = UserData()->id;
            $cookingPlace = CookingPlace::create($data);
            $availableCookingPlaces = json_decode($data['availableCookingPlaces'],true);
            foreach ($availableCookingPlaces as $availableCookingPlace) {
                AvailableCookingPlace::create([
                    'cooking_placeable_id' => $availableCookingPlace['id'],
                    'cooking_placeable_type' => $availableCookingPlace['type'],
                    'cooking_place_id' => $cookingPlace->id
                ]);
            }

            DB::commit();
            ResponseData($cookingPlace);
        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function updateCookingPlace(Request $request,int $id)
    {
        DB::beginTransaction();
        try{
            $data = $request->all();
            $cookingPlace = CookingPlace::find($id);
            $cookingPlace->update($data);

            $availableCookingPlaces = json_decode($data['availableCookingPlaces'],true);
            $cookingPlace->availableCookingPlaces()->delete();
            foreach ($availableCookingPlaces as $availableCookingPlace) {
                AvailableCookingPlace::create([
                    'cooking_placeable_id' => $availableCookingPlace['id'],
                    'cooking_placeable_type' => $availableCookingPlace['type'],
                    'cooking_place_id' => $cookingPlace->id
                ]);
            }

            DB::commit();
            ResponseData($cookingPlace);

        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function deleteCookingPlace(int $id)
    {
        DB::beginTransaction();
        try{
            $cookingPlace = CookingPlace::find($id);
            $cookingPlace->delete();
            DB::commit();
            ResponseMessage('Cooking Place Deleted Successfully');
        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

}
