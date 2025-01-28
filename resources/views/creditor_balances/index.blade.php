@extends('layouts.main')

@section('page_title', 'Creditor Balances')

@section('creditor_balances', 'active-link')
@section('cash_flow_collapse', 'show')
@section('content')
    <creditor-balances-component/>
@endsection
