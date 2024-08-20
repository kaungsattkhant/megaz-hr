@extends('layouts.main')

@section('page_title', 'Advanced')
@section('advanced', 'active-link')
@section('content')
    <div id="app">
        <advanced-detail-component staff-id={{$id}} />

    </div>

@endsection
