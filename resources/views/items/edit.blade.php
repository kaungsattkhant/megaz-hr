@extends('layouts.main')

@section('page_title', 'Items')

@section('items', 'active-link')
@section('content')

<div id="app">
    <item-edit-component item-id="{{$id}}" />
</div>

@endsection
