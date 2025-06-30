@extends('layouts.main')

@section('page_title', 'Exams')
@section('exam', 'active-link')
@section('content')

    <exam-edit-component exam-id={{$id}} />
    
@endsection
