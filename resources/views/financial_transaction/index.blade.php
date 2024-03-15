@extends('layouts.main')

@section('page_title', 'Accounting')
@section('accounting', 'active-link')
@section('content')
    <div id="app">
        <financial-Transaction-crud-component/>

    </div>

@endsection
