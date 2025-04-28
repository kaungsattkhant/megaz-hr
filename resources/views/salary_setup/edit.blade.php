@extends('layouts.main')

@section('page_title', 'Salary Setup')
@section('salary_setup', 'active-link')
@section('content')

<div id="app">
    <salary-setup-edit-component salary-setup-id={{$id}} />
</div>

@endsection
