@extends('pos.layouts.master')

@section('pos-body-content')
    <div id="app" class="pos-main-container">
        @include('pos.layouts.sidebar')

        <div class="pos-main-content">
            <!-- @include('layouts.navbar') -->

            <main class="pos-inner-container bg-gray-100">
                @yield('content')
            </main>
        </div>
        <pos-notification-component/>
    </div>
@endsection
