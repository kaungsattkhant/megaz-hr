@extends('layouts.main')

@section('page_title', 'Location')

@section('locations', 'active-link')
@section('content')
    <location-detail-component location-id={{ $id }} floor-id={{$floorId}}  />
    
@endsection
