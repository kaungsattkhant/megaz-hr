@extends('layouts.main')

@section('page_title', 'Duty Create')

@section('duty', 'active-link')
@section('content')
    <duty-edit-component  duty-id={{$id}} />
@endsection
