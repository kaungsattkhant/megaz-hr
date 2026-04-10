@extends('layouts.main')

@section('page_title', 'Meeting Detail')
@section('meeting', 'active-link')
@section('content')

<div id="app">
    <meeting-detail-component meeting-id={{$id}} />
</div>

@endsection
