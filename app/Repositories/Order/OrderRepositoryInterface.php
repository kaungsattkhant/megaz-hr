<?php

namespace App\Repositories\Order;

use Illuminate\Http\Request;

interface OrderRepositoryInterface
{
    public function createOrder(array $data);

    public function createMultipleOrder(array $data);

    public function orderItemStatusChange(array $data);

    public function getOrderItemData(Request $request);

    public function getOrderItemByPos();

    public function orderItemAreaConfirm(int $id, Request $request);

    public function orderByInvoiceId(int $invoiceId);

}
