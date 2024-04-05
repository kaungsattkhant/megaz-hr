<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

	<link rel="preconnect" href="https://fonts.gstatic.com">

	<title> test</title>
    @vite('resources/js/app.js')

    <link rel="stylesheet" href="{{asset('css/style_web.css')}}">
    <link rel="stylesheet" href="{{asset('fontawesome/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/normalize.css')}}">

    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="https://www.gstatic.com/firebasejs/10.10.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.10.0/firebase-messaging-compat.js"></script>
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
        // const firebaseConfig = {
        //     apiKey: "AIzaSyBvqlnvze8StG22p3oulB9foQm1dO3cuu8",
        //     authDomain: "megaz-erp.firebaseapp.com",
        //     projectId: "megaz-erp",
        //     storageBucket: "megaz-erp.appspot.com",
        //     messagingSenderId: "609911397064",
        //     appId: "1:609911397064:web:ec83c2db74e8ece72463fd"
        // };
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

</html>
