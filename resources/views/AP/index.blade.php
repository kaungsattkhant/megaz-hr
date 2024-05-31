@extends('layouts.main')

@section('page_title', 'Account Payables')
@section('account_payables', 'active-link')
@section('content')

<div id="app">
    <ap-list-component/>
</div>

@endsection
