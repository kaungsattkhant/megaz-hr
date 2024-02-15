@extends('layouts.main')

@section('page_title', 'Inventories')
@section('inventories', 'active-link')
@section('content')
    <div id="app">
        <inventories-crud-component/>

    </div>

@endsection
