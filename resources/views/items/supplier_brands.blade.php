@extends('layouts.main')

@section('page_title', 'Item Suppliers')

@section('items', 'active-link')
@section('content')

<div id="app">
    <supplier-brands-component supplier-id={{$supplierId}} />
</div>

@endsection
