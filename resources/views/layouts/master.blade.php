<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

	<link rel="preconnect" href="https://fonts.gstatic.com">

	<title> @yield('page_title')</title>
    @vite('resources/js/app.js')

    <link rel="stylesheet" href="{{asset('css/style_web.css')}}">
    <link rel="stylesheet" href="{{asset('fontawesome/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/normalize.css')}}">

    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.10.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.10.0/firebase-messaging-compat.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
     <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-analytics.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional

        const firebaseConfig = {
            apiKey: "AIzaSyA_RiMlzMDYxKF_iT8wBxPAW3NpKxEPxas",
            authDomain: "megaz-project.firebaseapp.com",
            projectId: "megaz-project",
            storageBucket: "megaz-project.appspot.com",
            messagingSenderId: "25949618078",
            appId: "1:25949618078:web:86369862c2a1fc084ca08f",
            measurementId: "G-M6MF9D78DD"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
    </script>
    @yield('body-content')
</body>

@yield('script_index')
<script type="application/javascript">
    $(document).ready(function(){
            $('#toggleBtn').on('click', function() {
                $('#sidebar_admin').toggleClass('!w-0');
                $('#toggleBtn i').toggleClass('rotate-180');
                console.log('testing sidenav');
                $('#content_collapse').toggleClass('!ml-0 w-full');
            });
        });
</script>
<script type="application/javascript">
    $(document).ready(function () {
        function scrollToActiveLink() {
            const $activeLink = $("#sidebar_admin a.active-link");
            if ($activeLink.length) {
            $activeLink[0].scrollIntoView({
                behavior: "smooth", // Smooth scrolling animation
                block: "center",    // Center the active link in the view
            });
            }
        }
        const currentPath = window.location.pathname;
            $("#sidebar_admin a").each(function () {
                if ($(this).attr("href") === currentPath) {
                    $("#sidebar_admin a").removeClass("active-link"); // Remove 'active' from all links
                    $(this).addClass("active-link"); // Add 'active' to the matching link
                }
            });
        // console.log(currentPath)
        $(window).on('load', function() {
            scrollToActiveLink();
        });

    });
</script>
<script>
        window.$ = window.jQuery;
    </script>
</html>
