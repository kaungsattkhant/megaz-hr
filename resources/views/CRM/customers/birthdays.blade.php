@extends('layouts.main')

@section('page_title', 'Customers')
@section('customer_birthdays', 'active-link')
@section('content')
    <div id="app">
        <crm-customer-birthdays-list-component/>
    </div>
@endsection
