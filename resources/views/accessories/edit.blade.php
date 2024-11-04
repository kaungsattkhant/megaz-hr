@extends('layouts.main')

@section('page_title', 'Accessories')
@section('accessories', 'active-link')
@section('content')

<div id="app">
    <accessories-edit-component accessories-id={{$id}} />
</div>

@endsection
