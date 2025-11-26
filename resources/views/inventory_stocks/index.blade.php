@extends('layouts.main')

@section('page_title', 'Inventory Stocks')

@section('inventory_stocks', 'active-link')
@section('content')

<div id="app">
    <inventory-ledgers-component/>
</div>

@endsection
