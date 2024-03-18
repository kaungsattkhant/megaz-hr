@extends('layouts.main')

@section('page_title', 'Financial Transactions')
@section('financial_transactions', 'active-link')
@section('content')
    <div id="app">
        <financial-transaction-crud-component/>

    </div>

@endsection
