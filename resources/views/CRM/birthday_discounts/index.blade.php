@extends('layouts.main')

@section('page_title', 'Birthday Promotions')
@section('birthday_promotions', 'active-link')
@section('content')
    <div id="app">
        <birthday-promotion-crud-component/>
    </div>
@endsection
