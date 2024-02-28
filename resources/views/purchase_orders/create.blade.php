@extends('layouts.main')

@section('page_title', 'Create Purchase Order')
@section('purchase_orders', 'active-link')
@section('content')

<div id="app">
    <purchase-order-create-component/>
</div>

@endsection
