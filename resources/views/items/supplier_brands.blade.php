@extends('layouts.main')

@section('page_title', 'Item Suppliers')

@section('items', 'active-link')
@section('content')

<div id="app">
    <supplier-brands-component item-id={{ $id }} supplier-id={{$supplierId}} />
</div>

@endsection
