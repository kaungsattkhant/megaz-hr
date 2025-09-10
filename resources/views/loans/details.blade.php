@extends('layouts.main')

@section('page_title', 'Loan Details')
@section('loans', 'active-link')
@section('content')
    <div id="app">
        <loan-details-component account-id={{ $id }}/>
    </div>

@endsection
