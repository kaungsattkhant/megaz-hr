<?php
namespace App\Repositories\Creditor;
interface CreditorInterface{

    public function getCreditorAccountList();

    public function createCreditorAccount($request);
    
}