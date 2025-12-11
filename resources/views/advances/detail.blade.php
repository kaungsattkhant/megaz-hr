@extends('layouts.main')

@section('page_title', 'Advanced')
@section('advances', 'active-link')
@section('content')
    <div id="app">
        <advances-detail-component advance-id={{$id}} />

    </div>

@endsection
