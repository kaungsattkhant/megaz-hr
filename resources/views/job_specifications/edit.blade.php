@extends('layouts.main')

@section('page_title', 'JS')

@section('js', 'active-link')
@section('content')
    <div id="app">
        <js-edit-component js-id={{$id}} />
    </div>

@endsection
