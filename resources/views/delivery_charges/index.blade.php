@extends('layouts.main')

@section('page_title', 'Delivery Charges')
@section('delivery_charges', 'active-link')
@section('content')
    <div id="app">
        <delivery-charges-crud-component/>

    </div>

@endsection
