@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('agm_cash', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component
        :cash-account-id={{ '25' }}
        :cash-account-name={{ 'AGM Cash' }}>
        </cashbook-crud-component>

    </div>

@endsection
