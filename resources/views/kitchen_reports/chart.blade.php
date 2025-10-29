@extends('layouts.main')

@section('page_title', 'Kitchen Report')
@section('kitchen_report', 'active-link')
@section('content')
    <div id="app">
        <kitchen-report-chart-component/>
    </div>

@endsection
