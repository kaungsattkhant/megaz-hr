@extends('layouts.main')

@section('page_title', 'Asset Items')
@section('fixed_asset', 'active-link')
@section('content')

<div id="app">
    <asset-item-crud-component/>
</div>

@endsection
