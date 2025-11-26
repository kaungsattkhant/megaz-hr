@extends('layouts.main')

@section('page_title', 'OKR Duty Edit')

@section('OKR_duty', 'active-link')
@section('content')

<div id="app">
    <okr-duty-edit-component okr-duty-id={{$id}} />
</div>

@endsection
