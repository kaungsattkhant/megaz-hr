@extends('layouts.main')

@section('page_title', 'Sale Target Menu')
@section('sale_target_menu', 'active-link')
@section('sale_target', 'show')
@section('content')

<div id="app">
    <sale-target-menu-list-component/>
</div>

@endsection
