@extends('layouts.main')

@section('page_title', 'Item Pricing History')

@section('items', 'active-link')
@section('content')

<div id="app">
    <item-pricing-history-component item-id={{$id}} />
</div>

@endsection
