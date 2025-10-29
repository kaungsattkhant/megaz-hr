@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('ktv_project_cash', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component
        :cash-account-id={{ '31' }}
        :cash-account-name={{ 'KTV Project' }}
        :cash-account-code={{ '1009' }} >
        </cashbook-crud-component>

    </div>

@endsection
