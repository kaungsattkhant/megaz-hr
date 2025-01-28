@extends('layouts.main')

@section('page_title', 'Procurement Order')
@section('procurement_order_items', 'active-link')
@section('content')

<div id="app">
    <procurement-order-items-component/>
</div>

@endsection
