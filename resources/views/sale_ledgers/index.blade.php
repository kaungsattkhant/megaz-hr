@extends('layouts.main')

@section('page_title', 'Sale Ledger')

@section('sale_ledger', 'active-link')
@section('cash_flow_collapse', 'show')
@section('content')
    <sale-ledger-component/>
@endsection
