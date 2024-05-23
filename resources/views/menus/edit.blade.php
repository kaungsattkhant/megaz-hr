@extends('layouts.main')

@section('page_title', 'Menu')
@section('menus', 'active-link')
@section('content')

<div id="app">
    <menu-edit-component menu-id={{$id}} />
</div>

@endsection
