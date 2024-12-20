@extends('layouts.main')

@section('page_title', 'Creditor Transactions')
@section('creditor', 'active-link')
@section('content')

<div id="app">
    <creditor-history-component creditor-id={{$creditorId}} />
</div>

@endsection
