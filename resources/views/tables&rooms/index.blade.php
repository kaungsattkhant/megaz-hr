@extends('layouts.main')

@section('page_title', 'Table')
@section('room', 'active-link')
@section('content')
    <div id="app">
        <room-crud-component/>
    </div>

@endsection
