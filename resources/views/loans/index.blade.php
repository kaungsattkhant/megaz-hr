@extends('layouts.main')

@section('page_title', 'Loans')
@section('loans', 'active-link')
@section('content')
    <div id="app">
        <loans-list-component/>
    </div>

@endsection
