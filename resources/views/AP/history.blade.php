@extends('layouts.main')

@section('page_title', 'Account Payables Transactions')
@section('account_payables', 'active-link')
@section('content')

<div id="app">
    <ap-history-component supplier-id={{$supplierId}} />
</div>

@endsection
