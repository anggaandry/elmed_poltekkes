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
    <meta property="og:image" content="{{ asset('images/logo/' . $university_data->logo) }}" />
    <meta name="format-detection" content="telephone=no">

    <!-- PAGE TITLE HERE -->
    <title>LMS DOSEN - {{ $university_data->name }} </title>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo/' . $university_data->logo) }}" />
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">

</head>

<body class="body  h-100" style="background-image: url('{{ asset('images/art/back6.jpg') }}'); background-size:cover;">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-contain-center">
            <div class="col-xl-12 mt-3">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row m-0">
                            <div class="col-xl-6 col-md-6 sign text-center">
                                <div>
                                    <img src="{{ asset('images/art/art2.png') }}" class="education-img" />
                                    <div class="text-center my-5">
                                        <a href="{{ url('dosen/') }}"><img width="120"
                                                src="{{ asset('images/logo/poltekkes-medan.png') }}" alt=""></a>
                                    </div>

                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="sign-in-your">
                                    <h4 class="fs-20 font-w800 text-black">Login untuk masuk ke LMS</h4>
                                    <span>Selamat datang di halaman dosen ELMED Poltekkes Medan</span>
                                    <div class="mt-3">
                                        <form action="{{ url('/dosen/login') }}" method="post">
                                            {{ csrf_field() }}
                                            <div class="mb-3">
                                                <label class="mb-1"><strong>NIDN/NUP/NIDK</strong></label>
                                                <input type="text" name="identity_number"
                                                    value="{{ old('identity_number') }}" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="mb-1"><strong>Password</strong></label>
                                                <input type="password" name="password" class="form-control" required>
                                            </div>
                                            <div class="row d-flex justify-content-between mt-4 mb-2">
                                                <div class="mb-3">
                                                    <div class="form-check custom-checkbox ms-1">
                                                        <input type="checkbox" name="remember" class="form-check-input"
                                                            id="rm_checkbox_1">
                                                        <label class="form-check-label" for="rm_checkbox_1">Remember
                                                            me</label>
                                                    </div>
                                                </div>
                                                @if (Session::has('error_login'))
                                                    <div class="mb-3">
                                                        <div class="alert alert-danger alert-dismissible fade show"
                                                            role="alert">
                                                            <span class="alert-inner--text"><b>PERINGATAN!! </b>
                                                                {{ Session::get('error_login') }}</span>

                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <a href="#">Forgot Password? Contact Admin</a>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-success btn-block">Sign Me
                                                    In</button>
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
    </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('template/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('template/js/custom.js') }}"></script>
    <script src="{{ asset('template/js/dlabnav-init.js') }}"></script>

</body>

</html>
