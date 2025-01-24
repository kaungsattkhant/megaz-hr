@extends('layouts.main')

@section('page_title', 'Purchase Order')
@section('purchase_orders', 'active-link')
@section('content')

<div id="app">
    <purchase-order-edit-component po-id={{$id}} />
</div>

@endsection
