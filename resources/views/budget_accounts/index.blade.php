@extends('layouts.main')

@section('page_title', 'Budget Accounts')
@section('budget_accounts', 'active-link')
@section('content')
    <div id="app">
        <budget-account-crud-component/>
    </div>

@endsection
