@extends('layouts.master')

@section('body-content')
    <div id="app" class="main-container">
        @include('layouts.sidebar')

        <div class="main-content" id="content_collapse">
            @include('layouts.navbar')

            <main class="inner-container py-3">
                @yield('content')
            </main>
        </div>

    </div>
@endsection
