<?php

namespace App\Repositories\Service;

use Illuminate\Http\Request;

interface ServiceInterface
{
    public function list($request);
    public function updateOrCreate($request);

    public function detail($service);

    public function getService($request);

}