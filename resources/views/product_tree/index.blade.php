@extends('layouts.main')

@section('page_title', 'Product Tree')
@section('product_tree', 'active-link')
@section('content')
    <div id="app">
        <product-tree-component/>
    </div>

@endsection
