@extends('layouts.main')

@section('page_title', 'Journal')
@section('journals', 'active-link')
@section('content')
    <div id="app">
        <journals-crud-component/>

    </div>

@endsection
