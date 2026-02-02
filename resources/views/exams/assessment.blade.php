@extends('layouts.main')

@section('page_title', 'Exam Test')

@section('exam', 'active-link')
@section('content')

<exam-assessment-component staff-id={{ $id }} exam-id={{$assessId}} />
    
    
@endsection
