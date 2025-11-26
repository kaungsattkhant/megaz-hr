@extends('layouts.main')

@section('page_title', 'Inventory Transfers')
@section('inventory_transfers', 'active-link')
@section('content')

<div id="app">
    <inventory-transfers-list-component/>
</div>

@endsection
