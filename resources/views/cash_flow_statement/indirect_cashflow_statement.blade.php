@extends('layouts.main')

@section('page_title', 'Cash Flow Statement')
@section('indirect_cashflow_statement', 'active-link')
@section('cash_flow_collapse', 'show')
@section('content')
    <div id="app">
        <indirect-cash-flow-statement-component/>

    </div>

@endsection
