@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('office_cash', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component
        :cash-account-id={{ '23' }} :cash-account-code={{ "1001" }} >
        </cashbook-crud-component>

    </div>

@endsection
