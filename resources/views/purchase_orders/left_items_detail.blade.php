@extends('layouts.main')

@section('page_title', 'Purchase Orders with Left Items')
@section('purchase_order_left_items', 'active-link')
@section('content')

<div id="app">
    <left-item-list-component purchase-order-id="{{$poId}}" />
</div>

@endsection
