@extends('layouts.main')
@section('page_title', 'Inventories')
@section('inventories', 'active-link')

@section('content')
    <div id="app">
        <inventory-ledgers-component :inventory_id="{{ $inventory_id}}"/>
    </div>
@endsection
