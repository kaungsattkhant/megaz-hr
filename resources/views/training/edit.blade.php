@extends('layouts.main')

@section('page_title', 'Training')
@section('training', 'active-link')
@section('content')

<div id="app">
    <training-edit-component training-id={{$id}} />
</div>

@endsection
