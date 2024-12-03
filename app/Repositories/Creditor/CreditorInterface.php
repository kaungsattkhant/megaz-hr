<?php
namespace App\Repositories\Creditor;
interface CreditorInterface{

    public function list($request);
    
    public function getCreditorAccountList();

    public function createCreditorAccount($request);

    public function listOfCreditorTransaction($request);

    public function createCreditorTransaction($request);
    
}