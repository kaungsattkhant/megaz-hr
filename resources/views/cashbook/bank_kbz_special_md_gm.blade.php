@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('kbz_special_bank', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component
        :cash-account-id={{ '32' }}
        :cash-account-name={{ 'KBZ Special (MD-GM)' }}
        :cash-account-code={{ '1010' }} >
        </cashbook-crud-component>

    </div>

@endsection
