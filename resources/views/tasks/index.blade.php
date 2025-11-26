@extends('layouts.main')

@section('page_title', 'Tasks')
@section('tasks', 'active-link')
@section('content')
    <div id="app">
        <tasks-crud-component/>

    </div>

@endsection
