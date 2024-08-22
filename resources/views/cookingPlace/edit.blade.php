@extends('layouts.main')

@section('page_title', 'Cooking Place Edit')

@section('cooking_place', 'active-link')
@section('content')
    <cooking-place-edit-component cp-id={{$id}} />
@endsection
