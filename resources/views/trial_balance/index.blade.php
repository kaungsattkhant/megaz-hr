@extends('layouts.main')

@section('page_title', 'Trial Balance')

@section('trial_balance', 'active-link')
@section('cash_flow_collapse', 'show')
@section('content')
    <trial-balance-component/>
@endsection
