@extends('layouts.main')

@section('page_title', 'Menu Forecasting')
@section('menu_forecasting', 'active-link')
@section('content')

<div id="app">
    <menu-forecasting-edit-component menu-forecasting-id={{$id}} />
</div>

@endsection
