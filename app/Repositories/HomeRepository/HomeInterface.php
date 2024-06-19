<?php

namespace App\Repositories\HomeRepository;

interface HomeInterface
{
    public function getHomeCategoryList();
    public function getHomeMenuList($request);
}
