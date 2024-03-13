@extends('layouts.main')

@section('page_title', 'Item Usage Forecasts')

@section('item_usage_forecasts', 'active-link')
@section('content')

<div id="app">
    <item-usage-forecast-detail-component forecast-id="{{$forecastId}}"/>
</div>

@endsection
