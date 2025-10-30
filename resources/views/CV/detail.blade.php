@extends('layouts.main')

@section('page_title', 'CV List')

@section('cv', 'active-link')
@section('content')
    <cv-detail-component cv-id={{$id}} />
    
@endsection
