@extends('layouts.main')

@section('page_title', 'suppliers')
@section('supplier', 'active-link')
@section('content')

<div id="app">
    <supplier-update-component :supplier-id={{ json_encode($id) }}> </supplier-update-component>
</div>

@endsection
