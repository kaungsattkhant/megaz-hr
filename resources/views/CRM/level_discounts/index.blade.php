@extends('layouts.main')

@section('page_title', 'Customers')
@section('customers', 'active-link')
@section('content')
    <div id="app">
        <level-discount-crud-component/>
    </div>
@endsection
