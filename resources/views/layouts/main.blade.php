@extends('layouts.master')

@section('body-content')
    <div id="app" class="main-container">
        @include('layouts.sidebar')

        <div class="main-content">
            @include('layouts.navbar')

            <main class="inner-container bg-[#f0f1f700] py-3">
                @yield('content')
            </main>
        </div>

    </div>
@endsection
