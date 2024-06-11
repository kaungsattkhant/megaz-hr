@extends('layouts.main')

@section('page_title', 'Customers')
@section('customers', 'active-link')
@section('content')
    <div id="app">
        <crm-customer-list-component/>
    </div>
@endsection
