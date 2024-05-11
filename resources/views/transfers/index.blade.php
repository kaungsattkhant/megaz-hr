@extends('layouts.main')

@section('page_title', 'Inventory Transfer Histories')
@section('inventory_histories', 'active-link')
@section('content')

<div id="app">
    <inventory-transfer-history-list-component/>
</div>

@endsection
