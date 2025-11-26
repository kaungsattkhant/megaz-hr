@extends('layouts.main')

@section('page_title', 'Asset Items')
@section('asset_items', 'active-link')
@section('content')

<div id="app">
    <asset-item-crud-component/>
</div>

@endsection
