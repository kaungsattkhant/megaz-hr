@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('kpay_bank', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component
        :cash-account-id={{ '29' }}
        :cash-account-name={{ 'KBZ Pay' }}>
        </cashbook-crud-component>

    </div>

@endsection
