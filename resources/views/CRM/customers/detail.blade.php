@extends('layouts.main')

@section('page_title', 'Customer Detail')
@section('crm_customer_list', 'active-link')
@section('content')
    <div id="app">
        <crm-customer-detail-component customer-id={{$id}}/>
    </div>
@endsection
