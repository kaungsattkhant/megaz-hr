@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('owner_cash', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component
        :cash-account-id={{ '28' }}
        :cash-account-name={{ 'Owner Cash' }}>
        </cashbook-crud-component>

    </div>

@endsection
