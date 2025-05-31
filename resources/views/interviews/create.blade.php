@extends('layouts.main')

@section('page_title', 'Interview List')

@section('interview', 'active-link')
@section('content')
    <interview-create-component interview-id={{$id}} />
    
@endsection
