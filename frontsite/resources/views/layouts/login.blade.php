<!DOCTYPE html>
<html lang="en-gb" dir="ltr">

<head>
    <meta charset="UTF-8"/>
    <title>DAIRI - Login Page</title>
    <meta name="description" content="{{ $config['sitemetadesc']}}" />
    <meta name="keywords" content="{{ $config['sitemetakeyword']}}" />
    <meta name="generator" content="DAIRI" />
    <meta name="robots" content="index, follow" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="baseurl" content="{{ url('/')}}">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <!-- font-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!--  Second Font  -->
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- ICON -->
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/fontawesome/fontawesome-all.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/flaticon/flaticon.css') }}">

    <!--Style-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}?as">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/owl/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-confirm.css') }}">
    @yield('styles')
</head>

<body>
    @yield('contents')

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('js/owl/owl.carousel.js') }}"></script>
    <script src="{{ asset('js/jquery-confirm.js') }}"></script>

    <!-- Theme JS-->
    <script src="{{ asset('js/theme.js') }}"></script>
    @yield('scripts')

</body>
</html>