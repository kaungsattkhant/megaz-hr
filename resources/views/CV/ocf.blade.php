@extends('layouts.main')

@section('page_title', 'Open Communication Form')

@section('cv', 'active-link')
@section('content')
    <open-communication-form-component staff-id={{$id}} />
    
@endsection
