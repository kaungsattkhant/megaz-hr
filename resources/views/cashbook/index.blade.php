@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('cashbook', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component
        :cash-account-id={{ '23' }}
        :cash-account-name={{ 'Office Cash' }}>
        </cashbook-crud-component>

    </div>

@endsection
