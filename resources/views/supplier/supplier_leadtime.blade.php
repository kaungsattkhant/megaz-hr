@extends('layouts.main')

@section('page_title', 'supplier lead time')
@section('supplier', 'active-link')
@section('content')

<div id="app">
    @php
        $supplier = \App\Models\Supplier::find($id);
    @endphp
    <supplier-lead-time-component :supplier="{{ json_encode($supplier) }}"/>
</div>

@endsection
