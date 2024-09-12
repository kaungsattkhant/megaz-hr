@extends('layouts.main')

@section('page_title', 'Sale Target Position')
@section('sale_target_position', 'active-link')
@section('sale_target', 'show')
@section('content')

<div id="app">
    <sale-target-position-edit-component position-id="{{$id}}" />
</div>

@endsection
