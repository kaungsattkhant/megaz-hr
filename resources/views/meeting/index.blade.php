@extends('layouts.main')

@section('page_title', 'Meeting')
@section('meeting', 'active-link')
@section('content')

<div id="app">
    <meeting-list-component/>
</div>

@endsection
