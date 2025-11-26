<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="preconnect" href="https://fonts.gstatic.com">

    <title> @yield('page_title')</title>
    @vite('resources/js/app.js')

    <link rel="stylesheet" href="{{asset('css/style_web.css')}}">
    <link rel="stylesheet" href="{{asset('fontawesome/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/normalize.css')}}">

    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="https://www.gstatic.com/firebasejs/10.10.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.10.0/firebase-messaging-compat.js"></script>

    <!-- Date Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body class="small-scrollbar">
    <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-analytics.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        // const firebaseConfig = {
        //     apiKey: "AIzaSyBvqlnvze8StG22p3oulB9foQm1dO3cuu8",
        //     authDomain: "megaz-erp.firebaseapp.com",
        //     projectId: "megaz-erp",
        //     storageBucket: "megaz-erp.appspot.com",
        //     messagingSenderId: "609911397064",
        //     appId: "1:609911397064:web:ec83c2db74e8ece72463fd"
        // };
        const firebaseConfig = {
            apiKey: "AIzaSyCvUQJCbpIMC02ceunLnZI7Dyq5VrEjw2M",
            authDomain: "megaz-78046.firebaseapp.com",
            projectId: "megaz-78046",
            storageBucket: "megaz-78046.firebasestorage.app",
            messagingSenderId: "144736503266",
            appId: "1:144736503266:web:50908d34738ad48d93a5bb",
            measurementId: "G-20WGQM0S3T"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
    </script>
    @yield('pos-body-content')

</body>
{{--
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/forms@0.5.7/src/index.min.js"></script> --}}
@yield('script_index')

</html>