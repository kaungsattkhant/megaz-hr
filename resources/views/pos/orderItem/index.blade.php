@extends('pos.layouts.main')

@section('page_title', 'Order')
@section('foodOrders', 'pos-active-link')
@section('content')
<div id="app">
    <pos-order-item-list/>
</div>


@endsection
