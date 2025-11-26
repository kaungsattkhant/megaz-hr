@extends('layouts.main')

@section('page_title', 'Product Tree Edit')

@section('ktv_product_tree', 'active-link')
@section('content')

<div id="app">
    <ktv-product-tree-edit-component product-tree-id={{$id}} />
</div>

@endsection
