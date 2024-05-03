@extends('layouts.main')

@section('page_title', 'staff')
@section('staffs', 'active-link')
@section('content')

<div id="app">
    <staff-edit-component staff-id={{$id}} />
</div>

@endsection
