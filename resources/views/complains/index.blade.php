@extends('layouts.main')

@section('page_title', 'Complains')
@section('complains', 'active-link')
@section('content')
    <div id="app">
        <complains-crud-component/>

    </div>

@endsection
