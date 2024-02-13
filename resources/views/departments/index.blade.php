@extends('layouts.main')

@section('page_title', 'Tasks')
@section('departments', 'active-link')
@section('content')
    <div id="app">
        <departments-crud-component/>

    </div>

@endsection
