@extends('layouts.main')

@section('page_title', 'Cashflow')
@section('budget_accounts', 'active-link')
@section('content')
    <div id="app">
        <cashflow-report-component/>
    </div>

@endsection
