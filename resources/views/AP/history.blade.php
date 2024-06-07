@extends('layouts.main')

@section('page_title', 'Account Payable Transactions')
@section('ap_history', 'active-link')
@section('content')

<div id="app">
    <ap-history-component/>
</div>

@endsection
