@extends('layouts.main')

@section('page_title', 'Accounting')
@section('accounting', 'active-link')
@section('content')
    <div id="app">
        <accounting-crud-component/>

    </div>

@endsection
