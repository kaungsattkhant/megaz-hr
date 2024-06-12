@extends('layouts.main')

@section('page_title', 'Customer Level Discounts')
@section('customer_level_discounts', 'active-link')
@section('content')
    <div id="app">
        <level-discount-crud-component/>
    </div>
@endsection
