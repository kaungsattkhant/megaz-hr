@extends('layouts.main')

@section('page_title', 'Ktv Report')
@section('ktv_report', 'active-link')
@section('content')
    <div id="app">
        <ktv-report-chart-component/>
    </div>

@endsection
