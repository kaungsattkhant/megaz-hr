@extends('layouts.main')

@section('page_title', 'Item Suppliers')

@section('items', 'active-link')
@section('content')

<div id="app">
    <item-suppliers-component item-id={{$id}} />
</div>

@endsection
