<?php

namespace App\Repositories\Benefit;

use Illuminate\Http\Request;

interface BenefitInterface
{
    public function list($request);

    public function updateOrCreateBenefit($data);

    public function detailBenefit($id);

    public function requestBenefit($data);

    public function updateStatusBenefitRequest($data);

    public function getBenefitByType($type);

    public function createBenefitRequest($data);

    public function listBenefitRequest($data);
}