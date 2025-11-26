
@extends('layouts.main')

@section('page_title', 'Purchase Order Invoices')
@section('purchase_order_invoices', 'active-link')
@section('content')

<div id="app">
    <purchase-order-invoice-detail-component po-invoice-id={{$id}} />
</div>

@endsection
