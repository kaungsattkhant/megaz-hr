@extends('layouts.main')

@section('page_title', 'Project Instruction')
@section('project', 'active-link')
@section('content')

<div id="app">
    <project-instruction-component project-id={{$id}} />    
</div>

@endsection
