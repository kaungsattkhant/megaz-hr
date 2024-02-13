@extends('layouts.main')

@section('page_title', 'Tasks')
@section('roles', 'active-link')
@section('content')
    <div id="app">
        <roles-crud-component/>

    </div>

@endsection
