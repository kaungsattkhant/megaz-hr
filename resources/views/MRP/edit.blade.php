@extends('layouts.main')

@section('page_title', 'MRP')
@section('mrp', 'active-link')
@section('content')

<div id="app">
    <mrp-edit-component mrp-id={{$id}} />
</div>

@endsection
