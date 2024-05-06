@extends('layouts.main')

@section('page_title', 'Purchase Orders')
@section('confirm_purchase_order_items', 'active-link')
@section('content')

<div id="app">
    <confirm-purchase-order-items-component />
</div>

@endsection
