@extends('layouts.main')

@section('page_title', 'packages')
@section('packages', 'active-link')
@section('content')

<div id="app">
    <packages-list-component/>
</div>

@endsection
