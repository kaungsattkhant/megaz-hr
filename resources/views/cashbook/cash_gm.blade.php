@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('gm_cash', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component
        :cash-account-id={{ '26' }}
        :cash-account-name={{ 'GM Cash' }}
        :cash-account-code={{ '1004' }} >
        </cashbook-crud-component>

    </div>

@endsection
