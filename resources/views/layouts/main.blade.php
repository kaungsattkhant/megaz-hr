@extends('layouts.master')

@section('body-content')
    <div id="app" class="main-container">
        @include('layouts.sidebar')

        <div class="content-container">
            @include('layouts.navbar')

            <main class="inner-container">
                @yield('content')
            </main>
        </div>

    </div>
@endsection
