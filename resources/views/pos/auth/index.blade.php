
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
</head>

<body class="small-scrollbar">

    <div class="w-full relative block h-[100vh]" style="background-image: url(../img/megaz.jpg);background-position:center;
    background-repeat:no-repeat;background-size:cover;background-color:black;">
        <div class="w-10/12 lg:w-2/6 mx-auto min-h-[80vh] pt-36">

            <div class="mb-6">
                <div class="px-8">
                    <div class=" mb-4">
                        <label for="username" class="text-sm text-white mb-2 block">
                            Username
                        </label>
                        <input type="text" id="username" autocomplete="off" class=" border border-gray-400 bg-white w-full rounded">
                    </div>
                    <div class=" mb-12">
                        <label for="password" class="text-sm text-white mb-2 block">
                            Password
                        </label>
                        <input type="password" id="password" autocomplete="off" class=" border border-gray-400 bg-white w-full rounded">
                    </div>
                    <div class="w-full text-center">
                        <button
                            class="bg-[#0BA348] w-full mx-auto text-white text-sm rounded-md px-8 py-2 block mb-2.5">
                            Login
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
{{-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/forms@0.5.7/src/index.min.js"></script> --}}

</html>

 