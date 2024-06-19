@extends('layouts.main')

@section('page_title', 'Customers')
@section('crm_customer_list', 'active-link')
@section('content')
    <div id="app">
        <crm-customer-list-component/>
    </div>
@endsection
