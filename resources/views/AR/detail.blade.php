@extends('layouts.main')

@section('page_title', 'Ar')
@section('ar', 'active-link')
@section('content')
    <div id="app">
        <ar-detail-component ar-id={{$id}} />

    </div>

@endsection
