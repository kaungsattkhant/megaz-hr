@extends('pos.layouts.main')

@section('page_title', 'Home')
@section('home', 'pos-active-link')
@section('content')
    <div id="app">
        <pos-order-component order-id={{$id}} >
    </div>

@endsection
