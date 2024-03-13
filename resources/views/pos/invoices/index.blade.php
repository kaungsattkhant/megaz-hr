@extends('pos.layouts.main')

@section('page_title', 'Invoices')
@section('invoices', 'pos-active-link')
@section('content')
<div id="app">
    <invoice-list-component>
</div>
    

@endsection
