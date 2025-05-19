@extends('layouts.main')

@section('page_title', 'Salary Batch')

@section('salary_batch', 'active-link')
@section('content')

<div id="app">
    <salary-batch-edit-component salary-batch-id={{$id}} />
</div>

@endsection
