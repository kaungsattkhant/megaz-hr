
@extends('layouts.main')

@section('page_title', 'Org News')
@section('org_news', 'active-link')
@section('content')

<div id="app">
    <org-news-edit-component org-news-id={{$id}} />
</div>

@endsection
