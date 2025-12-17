<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

	<link rel="preconnect" href="https://fonts.gstatic.com">

	<title> Mega Z ERP | @yield('page_title')</title>
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

    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

        <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</head>

<body>
    <!-- <div id="global-loader">
        <div class="spinner-wrapper">
            <div class="spinner-ring"></div>
            <img src="{{asset('img/logo.png')}}" alt="Loading..." width="100">
        </div>
    </div> -->


     <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-analytics.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional

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
    @yield('body-content')
</body>

@yield('script_index')
<script type="application/javascript">
    $(document).ready(function(){
            $('#toggleBtn').on('click', function() {

                let sidebar = $('#sidebar_admin');
                let content = $('#content_collapse');
                let logout = $('#logout');
                let sidebar_scroll_list = $('#sidebar_scroll_list');
                let icon_sidebar = $('#icon_sidebar');

                if (sidebar.width() > 100) {
                     // collapse
                    sidebar.animate({ width: "80px"}, 300);
                    logout.animate({ width: "0", opacity: '0'  }, 500);
                    sidebar_scroll_list.animate({ opacity: '0'  }, 500);
                    content.animate({ marginLeft: "80px", width: "100%" }, 700);
                    setTimeout(() => {
                        $('#icon_sidebar').addClass('shadow-xl');
                        $('#icon_sidebar').removeClass('border-r');
                    }, 600);
                } else {
                    // expand
                    sidebar.animate({ width: "336px" }, 100);
                    logout.animate({ width: "336px", opacity: '100'  }, 300);
                    sidebar_scroll_list.animate({ opacity: '100'  }, 300);
                    content.animate({ marginLeft: "336px" }, 300);
                    setTimeout(() => {
                        $('#icon_sidebar').removeClass('shadow-xl');
                        $('#icon_sidebar').addClass('border-r');
                    }, 0);
                }

                // rotate the icon
                $('#toggleBtn i').toggleClass('rotate-180');
                // $('#sidebar_admin').toggleClass('!w-0');
                // $('#toggleBtn i').toggleClass('rotate-180');
                // console.log('testing sidenav');
                // $('#content_collapse').toggleClass('!ml-0 w-full');
            });
        });
</script>
<script>
    $(document).ready(function () {
        // Show the first tab by default
        // $('.tab-content:first').show();
        // $('.tab-btn:first').addClass('!text-blue-500 border-blue-500');

        // Tab click handler
        $('.tab-btn').click(function () {
            const $btn = $(this);
            const targetId = $btn.data('tab');
            const $target = $('#' + targetId);

            // Change active button color
            $('.tab-btn')
            .removeClass('!text-blue-500 border-blue-500 bg-blue-100')
            .addClass('text-gray-500 border-transparent');

            $btn
            .removeClass('text-gray-500 border-transparent')
            .addClass('!text-blue-500 border-blue-500 bg-blue-100');

            // Fade out the current tab and fade in the new one
            const $visible = $('.tab-content:visible');
            if ($visible.attr('id') === targetId) return; // skip if same tab

            $visible.stop(true, true).animate({ opacity: 0 }, 100, function () {
                $visible.hide();
                $target.css({ opacity: 0, display: 'block' }).animate({ opacity: 1 }, 100);
            });


            let sidebar = $('#sidebar_admin');
            let content = $('#content_collapse');
            let logout = $('#logout');
            let sidebar_scroll_list = $('#sidebar_scroll_list');
            let icon_sidebar = $('#icon_sidebar');

            if (sidebar.width() < 100) {
                 // expand
                sidebar.animate({ width: "336px" }, 100);
                logout.animate({ width: "336px", opacity: '100'  }, 300);
                sidebar_scroll_list.animate({ opacity: '100'  }, 300);
                content.animate({ marginLeft: "336px" }, 300);
                setTimeout(() => {
                    $('#icon_sidebar').removeClass('shadow-xl');
                    $('#icon_sidebar').addClass('border-r');
                }, 0);
                $('#toggleBtn i').toggleClass('rotate-180');
            }
        });


        // for active tab and link in sidebar
        const currentPath = window.location.pathname;
        $("#sidebar_admin a").each(function () {

            console.log('outter' + currentPath)
            const href = $(this).attr("href");

            // if current path starts with the link's href (e.g., '/staff' matches '/staff/4/edit')
            if (currentPath === href || currentPath.startsWith(href + '/')) {

                $("#sidebar_admin a").removeClass("active-link"); // Remove 'active' from all links
                $(this).addClass("active-link"); // Add 'active' to the matching link
                console.log($(this))

                $("#sidebar_admin .tab-content").removeClass("show"); // Remove 'active' from all links
                $(this).closest(".tab-content").addClass("show");

                const $tabContent = $(this).closest(".tab-content");
                const tabId = $tabContent.attr("id"); // e.g. "tab-salary"
                $(`.tab-btn[data-tab="${tabId}"]`).addClass("!text-blue-500 bg-blue-100");
            }
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

        // console.log(currentPath)
        $(window).on('load', function() {
            scrollToActiveLink();
        });

    });
</script>
<script>
        window.$ = window.jQuery;
</script>

<script>
    // Wait until everything is ready
    window.addEventListener('load', () => {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.classList.add('hide');
            setTimeout(() => loader.remove(), 0);
        }
    });
</script>
</html>
