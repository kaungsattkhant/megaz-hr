@extends('layouts.main')

@section('page_title', 'Tasks')
@section('tasks_reports', 'active-link')
@section('content')
    <div id="app">
        <tasks-report-component/>
    </div>
@endsection
