@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('service_cash', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component
        :cash-account-id={{ '24' }}
        :cash-account-name={{ 'Service Cash' }}
        :cash-account-code={{ '1002' }} >
        </cashbook-crud-component>

    </div>

@endsection
