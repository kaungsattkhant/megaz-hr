@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('kbz_old_gm_bank', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component
        :cash-account-id={{ '30' }}
        :cash-account-name={{ 'KBZ Old (GM)' }}
        :cash-account-code={{ '1008' }} >
        </cashbook-crud-component>

    </div>

@endsection
