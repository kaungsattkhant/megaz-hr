@extends('layouts.main')

@section('page_title', 'Cashbook')
@section('cashbook', 'active-link')
@section('content')
    <div id="app">
        <cashbook-crud-component/>

    </div>

@endsection
