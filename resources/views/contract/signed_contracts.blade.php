@extends('layouts.main')

@section('page_title', 'Contract')
@section('contract', 'active-link')
@section('content')

<div id="app">
    <signed-contracts-list-component/>
</div>

@endsection
