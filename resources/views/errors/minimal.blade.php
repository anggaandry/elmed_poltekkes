<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="lms" />
    <meta name="author" content="Poltekkes" />
    <meta name="robots" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Aplikasi LMS UNIVERSITAS {{ univ()->name }}" />
    <meta property="og:title" content="Poltekkes" />
    <meta property="og:description" content="Poltekkes medan" />
    <meta property="og:image" content="{{ asset(LOGO_PATH . (univ()->logo ? univ()->logo : 'default.png')) }}" />
    <meta name="format-detection" content="telephone=no">

    <!-- PAGE TITLE HERE -->
    <title>ELMED {{ univ()->name }} </title>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png"
        href="{{ asset(LOGO_PATH . (univ()->logo ? univ()->logo : 'default.png')) }}" />
    <link href="{{ asset('template/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/style.css ') }}" rel="stylesheet">

</head>

<body class="vh-100">
    @yield('content')

    <!--**********************************
 Scripts
***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('template/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('template/js/custom.js') }}"></script>
    <script src="{{ asset('template/js/dlabnav-theme-2.js') }}"></script>

</body>

</html>
