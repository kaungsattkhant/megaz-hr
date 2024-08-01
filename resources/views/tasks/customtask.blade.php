@extends('layouts.main')

@section('page_title', 'Custom Tasks')
@section('custom_tasks', 'active-link')
@section('content')
    <div id="app">
        <custom-task-crud-component/>
    </div>

@endsection
