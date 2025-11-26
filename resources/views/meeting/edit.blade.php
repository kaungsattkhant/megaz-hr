@extends('layouts.main')

@section('page_title', 'Meeting')
@section('meeting', 'active-link')
@section('content')

<div id="app">
    <meeting-edit-component meeting-id={{$id}} />
</div>

@endsection
