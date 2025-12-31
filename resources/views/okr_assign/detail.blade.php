@extends('layouts.main')

@section('page_title', 'OKR Assign')

@section('okr_assign', 'active-link')
@section('content')
    <okr-assign-detail-component okr-assign-id={{$id}} />
@endsection
