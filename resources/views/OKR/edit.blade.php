@extends('layouts.main')

@section('page_title', 'OKR Edit')

@section('OKR', 'active-link')
@section('content')

<div id="app">
    <okr-edit-component okr-id={{$id}} />
</div>

@endsection
