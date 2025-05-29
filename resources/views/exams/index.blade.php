@extends('layouts.main')

@section('page_title', 'Exams')
@section('exams', 'active-link')
@section('content')

<div id="app">
    <exam-list-component/>
</div>

@endsection
