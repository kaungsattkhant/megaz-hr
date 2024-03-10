<?php

namespace App\Repositories\HeadAccount;


class HeadAccountRepository implements HeadAccountInterface
{

    public function updateOrCreate($request){
        dd($request->all());
    }
}
