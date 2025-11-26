<?php

namespace App\Repositories\PoOrder;

use Illuminate\Http\Request;

interface PoOrderRepositoryInterface
{

  public function getPoOrderItems(Request $request);

  public function test($request);

  public function storePoOrderItems($validatedData);

  public function getPoOrderArrivalList(Request $request);

  public function getPoOrderArrivalListByItemId($itemId);

  public function getInvoiceBySupplier($supplierId);

  public function storePoArrivalItems($validatedData);

  public function getSupplierLeadTime($supplierId);

  public function getInvoices(Request $request);

  public function processInvoiceTransaction($request);

  public function updateArrivalList($arrivalId, $validatedData);

  public function getInvoiceById($invoiceId);
  
  public function getSuppliersListsByItem($itemId);
}
