<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\BirthdayPromotion\BirthdayPromotionRepositoryInterface;
use Illuminate\Http\Request;

class BirthDayPromotionAPIController extends Controller
{
    //
    protected $bdpRepo;

    public function __construct(BirthdayPromotionRepositoryInterface $bdpRepo)
    {
        $this->bdpRepo = $bdpRepo;
    }

    public function getBirthdayPromotions(Request $request)
    {
        $birthdayPromotions = $this->bdpRepo->listAllData($request);
    }

    public function createBDPromotion(Request $request)
    {
        $birthdayPromotion = $this->bdpRepo->createData($request->all());
    }

    public function updateBDPromotion(Request $request, $id)
    {
        $birthdayPromotion = $this->bdpRepo->updateData($request->all(),$id);
    }

    public function deleteBDPromotion($id)
    {
        $birthdayPromotion = $this->bdpRepo->deleteData($id);
    }
}
