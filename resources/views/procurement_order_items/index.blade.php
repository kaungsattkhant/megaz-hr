@extends('layouts.main')

@section('page_title', 'Procurement Order')
@section('procurement_order', 'active-link')
@section('content')

<div id="app">
    <procurement-order-items-component/>
</div>

@endsection
