@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('owner_cash', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component
        :cash-account-id={{ '28' }}
        :cash-account-name={{ 'Owner Cash' }}
        :cash-account-code={{ '1006' }} >
        </cashbook-crud-component>

    </div>

@endsection
