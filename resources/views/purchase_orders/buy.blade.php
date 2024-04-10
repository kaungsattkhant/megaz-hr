@extends('layouts.main')

@section('page_title', 'Purchase Orders')
@section('purchase_orders', 'active-link')
@section('content')

<div id="app">
    <purchase-order-buy-component purchase-order-id="{{$poId}}"/>
</div>

@endsection
