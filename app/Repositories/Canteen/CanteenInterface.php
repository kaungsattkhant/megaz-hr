<?php
namespace App\Repositories\Canteen;
interface CanteenInterface 
{
    public function list($request);

    public function store($request);
    
}