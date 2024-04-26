@extends('layouts.main')

@section('page_title', 'Purchase Orders with Left Items')
@section('purchase_order_left_items', 'active-link')
@section('content')

<div id="app">
    <purchase-order-with-left-items-component/>
</div>

@endsection
