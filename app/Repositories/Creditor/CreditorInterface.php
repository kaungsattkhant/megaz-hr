<?php
namespace App\Repositories\Creditor;
interface CreditorInterface{

    public function list($request);
    
    public function getCreditorAccountList();

    public function createCreditorAccount($request);
    
}