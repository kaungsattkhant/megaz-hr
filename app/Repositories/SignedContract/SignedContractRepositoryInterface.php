<?php

namespace App\Repositories\SignedContract;

interface SignedContractRepositoryInterface
{
    public function list($request);

    public function updateOrCreate($request);

    public function detail($signedContract);
}
