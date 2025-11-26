@extends('layouts.main')

@section('page_title', ' Accrual')

@section('accruals', 'active-link')
@section('content')
    <accruals-crud-component accruals-id={{$id}} />
@endsection
