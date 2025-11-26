@extends('layouts.main')

@section('page_title', 'Package')
@section('packages', 'active-link')
@section('content')

<div id="app">
    <packages-edit-component package-id={{$id}} />
</div>

@endsection
