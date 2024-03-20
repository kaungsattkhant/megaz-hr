@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('advance_cash', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component
        :cash-account-id={{ '27' }}
        :cash-account-name={{ 'Advance Cash' }}>
        </cashbook-crud-component>

    </div>

@endsection
