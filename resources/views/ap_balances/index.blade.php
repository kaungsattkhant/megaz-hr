@extends('layouts.main')

@section('page_title', 'AP Balances')

@section('ap_balances', 'active-link')
@section('cash_flow_collapse', 'show')
@section('content')
    <ap-balances-component/>
@endsection
