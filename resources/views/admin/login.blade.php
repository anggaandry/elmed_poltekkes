<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="lms" />
    <meta name="author" content="Poltekkes" />
    <meta name="robots" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Aplikasi LMS UNIVERSITAS {{ $university_data->name }}" />
    <meta property="og:title" content="Poltekkes" />
    <meta property="og:description" content="Poltekkes medan" />
    <meta property="og:image" content="{{ asset(LOGO_PATH . ($university_data->logo ? $university_data->logo : 'default.png')) }}" />
    <meta name="format-detection" content="telephone=no">

    <!-- PAGE TITLE HERE -->
    <title>ELMED {{ $university_data->name }} </title>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="{{ asset(LOGO_PATH . ($university_data->logo ? $university_data->logo : 'default.png')) }}" />
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">

</head>

<body class="vh-100" style="background-image: url('{{ asset('images/art/back3.jpg') }}'); background-size:cover;">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <div class="text-center mb-3">
                                        <a href="{{ url('/4dm1n') }}"><img src="{{ asset(LOGO_PATH . ($university_data->logo ? $university_data->logo : 'default.png')) }}" alt=""></a>
                                    </div>
                                    <h4 class="text-center mb-4">{{ tr('login admin') }} {{ $university_data->name }}</h4>
                                    <form action="{{ url('/4dm1n/login') }}" method="post">
                                        {{ csrf_field() }}
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>{{ tr('username') }}</strong></label>
                                            <input type="text" class="form-control" value="{{ old('nip') }}" name="nip" placeholder="nomor induk pegawai" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="mb-1"><strong>{{ tr('password') }}</strong></label>
                                            <input type="password" class="form-control" name="password" placeholder="password" required>
                                        </div>


                                        @if (Session::has('error_login'))
                                            <div class="mb-3">
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <span class="alert-inner--text"><b>{{ tr('peringatan!!') }}</b>
                                                        {{ Session::get('error_login') }}</span>

                                                </div>
                                            </div>
                                        @endif

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary btn-block">{{ tr('login') }}</button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--**********************************
 Scripts
***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('template/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('template/js/custom.js') }}"></script>
    <script src="{{ asset('template/js/dlabnav-theme-2.js') }}"></script>

</body>

</html>
