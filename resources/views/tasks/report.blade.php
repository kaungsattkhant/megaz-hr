@extends('layouts.main')

@section('page_title', 'Tasks')
@section('tasks_report', 'active-link')
@section('content')
    <div id="app">
        <task-report-component/>
    </div>
@endsection
