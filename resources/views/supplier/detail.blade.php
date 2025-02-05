@extends('layouts.main')

@section('page_title', 'suppliers')
@section('supplier', 'active-link')
@section('content')

<div id="app">
    <supplier-detail-component supplier-id="{{$id}}" > </supplier-detail-component>
</div>

@endsection
