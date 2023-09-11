<!DOCTYPE html>
<html lang="en">
@php
    $auth = auth()
        ->guard('dosen')
        ->user()
        ->load(['university', 'religion']);
@endphp

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="lms" />
    <meta name="author" content="Poltekkes" />
    <meta name="robots" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Aplikasi LMS UNIVERSITAS {{ $auth->university->name }}" />
    <meta property="og:title" content="Poltekkes" />
    <meta property="og:description" content="Poltekkes medan" />
    <meta property="og:image" content="{{ asset(LOGO_PATH . ($auth->university->logo ? $auth->university->logo : 'default.png')) }}" />
    <meta name="format-detection" content="telephone=no">

    <!-- PAGE TITLE HERE -->
    <title>ELMED {{ $auth->university->name }} </title>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="{{ asset(LOGO_PATH . ($auth->university->logo ? $auth->university->logo : 'default.png')) }}" />
    <!-- Datatable -->
    <link href="{{ asset('template/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/vendor/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet">
    <link href="{{ asset('template/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/vendor/toastr/css/toastr.min.css') }}">
    <!-- Style css -->
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('template/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/vendor/dropify-master/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/vendor/swiper/css/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <style>
        text {
            font-size: 64px;
            font-family: Arial Black;
            dominant-baseline: central;
            text-anchor: middle;
        }

        a.mm-active {
            font-weight: bold !important;
            font-size: 14px !important;

        }

        @media (max-width: 767px) {
            .mobile-show {
                display: block !important;

            }

            .mobile-hide {
                display: none !important;

            }


        }

        .select2-selection__rendered {
            line-height: 41px !important;

        }

        .select2-container .select2-selection--single {
            height: 45px !important;
            border-radius: 10px;
        }

        .select2-selection__arrow {
            height: 44px !important;
        }

        .cropcircle {
            display: table;
            margin: auto;
            width: 48px;
            height: 48px;
            border-radius: 100%;
            background: #eee no-repeat center;
            background-size: cover;
        }

        .cropcircle-lg {
            display: table;
            margin: auto;
            width: 100px;
            height: 100px;
            border-radius: 100%;
            border-color: #555;
            background: #eee no-repeat center;
            background-size: cover;
        }

        .imgcard {
            width: 100%;
            height: 130px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background: #eee no-repeat center;
            background-size: cover;
        }

        .dropify-wrapper .dropify-message span.file-icon p {
            font-size: 20px;
            color: #CCC;
        }

        body {
            scroll-behavior: smooth;
        }

        .limit-text {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* number of lines to show */
            line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        td {
            white-space: normal !important;
            word-wrap: break-word;
        }

        .badge {
            vertical-align: middle !important;
        }

        input[type=radio] {
            border: 2px solid black;
        }
    </style>

    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 310px;
            max-width: 800px;
            margin: 1em auto;
        }

        #container {
            height: 400px;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>

    @yield('style')

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        <div class="ellipse">
            <svg class="green-line" width="669" height="487" viewBox="0 0 669 487" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M237.231 -68.6319V-68.6021L237.233 -68.5724C240.62 -11.7125 250.024 41.8582 269.813 81.245C289.627 120.683 319.922 146 365 146C385.587 146 411.761 133.509 439.623 113.32C467.532 93.0977 497.301 65.0267 525.114 33.5967C552.929 2.16452 578.809 -32.6519 598.929 -66.3803C619.03 -100.077 633.422 -132.754 638.209 -159.92C641.588 -173.074 642.414 -182.818 640.908 -189.917C639.382 -197.111 635.464 -201.562 629.562 -204.027C623.75 -206.455 616.074 -206.932 607.015 -206.43C598.241 -205.944 588.029 -204.527 576.749 -202.962L575.574 -202.799C528.514 -196.273 462.757 -187.599 400.301 -230.953C363.87 -256.242 335.385 -267.371 313.122 -267.543C290.75 -267.716 274.81 -256.826 263.567 -238.544C252.361 -220.322 245.792 -194.726 242.013 -165.305C238.231 -135.864 237.231 -102.487 237.231 -68.6319Z"
                    stroke="url(#paint0_linear_1146_121)" stroke-opacity="0.2" stroke-width="2" />
                <path
                    d="M287.231 -67.4176V-67.3879L287.233 -67.3582C289.553 -28.4105 294.217 9.84134 306.007 38.3782C311.906 52.6574 319.615 64.5666 329.764 72.9092C339.931 81.2668 352.495 86 368 86C375.138 86 383.313 83.7364 392.143 79.7017C400.983 75.6628 410.535 69.8223 420.443 62.6034C440.259 48.1655 461.567 28.1615 481.528 5.85989C501.491 -16.4438 520.129 -41.0702 534.597 -64.767C549.044 -88.4293 559.379 -111.238 562.673 -129.918C564.991 -138.942 565.57 -145.674 564.523 -150.609C563.457 -155.638 560.702 -158.775 556.561 -160.504C552.509 -162.197 547.187 -162.52 540.969 -162.175C534.942 -161.841 527.931 -160.869 520.207 -159.797L519.394 -159.684C487.137 -155.211 442.184 -149.29 399.489 -178.927C374.503 -196.272 354.915 -203.942 339.561 -204.061C324.099 -204.18 313.08 -196.642 305.327 -184.036C297.612 -171.489 293.103 -153.893 290.511 -133.715C287.916 -113.517 287.231 -90.6247 287.231 -67.4176Z"
                    stroke="url(#paint1_linear_1146_121)" stroke-opacity="0.2" stroke-width="2" />
                <path
                    d="M332.533 -59.8058V-59.776L332.535 -59.7463L332.561 -59.3074C333.782 -38.8127 335.056 -17.4149 340.066 -1.10762C342.592 7.11435 346.095 14.1385 351.098 19.115C356.131 24.1204 362.63 27 371 27C374.892 27 379.512 25.6578 384.58 23.3203C389.663 20.9759 395.271 17.5951 401.161 13.4356C412.942 5.11595 425.92 -6.37057 438.178 -19.0723C450.436 -31.7753 461.997 -45.7173 470.932 -58.9594C479.838 -72.158 486.203 -84.7593 487.978 -94.7885C489.212 -99.6027 489.418 -103.264 488.58 -105.974C487.709 -108.787 485.755 -110.446 483.07 -111.289C480.454 -112.109 477.117 -112.169 473.319 -111.873C469.69 -111.59 465.525 -110.971 461.007 -110.299C460.777 -110.265 460.547 -110.231 460.315 -110.197C441.081 -107.34 415.338 -103.772 392.855 -119.379C379.566 -128.603 369.074 -132.739 360.782 -132.803C352.383 -132.868 346.393 -128.756 342.208 -121.95C338.06 -115.205 335.656 -105.786 334.278 -95.0551C332.897 -84.3042 332.533 -72.1297 332.533 -59.8058Z"
                    stroke="url(#paint2_linear_1146_121)" stroke-opacity="0.2" stroke-width="2" />
                <path
                    d="M714.469 -193.085L714.52 -193.184L714.548 -193.292C718.948 -210.237 720.013 -222.748 718.067 -231.833C716.102 -241.007 711.07 -246.658 703.492 -249.792C696.003 -252.89 686.083 -253.509 674.316 -252.863C662.922 -252.238 649.658 -250.416 634.987 -248.4L633.466 -248.191C572.289 -239.789 486.688 -228.599 405.386 -284.489C358.04 -317.036 321.07 -331.322 292.22 -331.542C263.264 -331.764 242.631 -317.817 228.057 -294.348C213.521 -270.94 204.987 -238.034 200.075 -200.168C195.161 -162.282 193.861 -119.324 193.861 -75.7413V-75.7112L193.863 -75.6812C198.264 -2.5202 207.565 70.3105 230.324 124.875C241.708 152.167 256.483 174.95 275.753 190.915C295.041 206.894 318.783 216 348 216C374.693 216 406.34 199.175 439.83 171.987C473.364 144.762 508.921 107.001 543.46 64.8156C612.54 -19.5574 677.647 -121.752 714.469 -193.085Z"
                    stroke="url(#paint3_linear_1146_121)" stroke-opacity="0.2" stroke-width="2" />
                <path
                    d="M906.856 -268.482L906.907 -268.581L906.936 -268.689C913.93 -295.624 915.596 -315.423 912.529 -329.743C909.442 -344.152 901.565 -352.985 889.688 -357.898C877.901 -362.773 862.227 -363.766 843.522 -362.74C825.417 -361.747 804.332 -358.849 780.976 -355.64L778.569 -355.309C681.231 -341.942 544.808 -324.092 415.233 -413.166C339.93 -464.932 281.223 -487.584 235.495 -487.933C189.659 -488.283 157.005 -466.228 133.905 -429.029C110.842 -391.89 97.2761 -339.631 89.4644 -279.406C81.6501 -219.16 79.5816 -150.835 79.5815 -81.4937V-81.4637L79.5833 -81.4337C86.5855 34.9724 101.382 150.775 137.556 237.5C155.647 280.872 179.109 317.032 209.673 342.354C240.256 367.691 277.901 382.134 324.256 382.134C366.513 382.134 416.731 355.48 470.003 312.232C523.319 268.948 579.869 208.896 634.815 141.786C744.708 7.56412 848.284 -155.013 906.856 -268.482Z"
                    stroke="url(#paint4_linear_1146_121)" stroke-opacity="0.2" stroke-width="2" />
                <path
                    d="M1039.25 -331.574L1039.3 -331.673L1039.33 -331.781C1048.11 -365.589 1050.19 -390.401 1046.35 -408.323C1042.49 -426.333 1032.66 -437.356 1017.82 -443.491C1003.08 -449.59 983.447 -450.841 959.968 -449.553C937.246 -448.306 910.781 -444.67 881.449 -440.639L878.434 -440.225C756.219 -423.441 584.831 -401.009 422.046 -512.912C327.51 -577.9 253.849 -606.308 196.508 -606.746C139.061 -607.185 98.1362 -579.551 69.1699 -532.906C40.2412 -486.321 23.2138 -420.748 13.4069 -345.14C3.59735 -269.512 1.00002 -183.735 1 -96.6728V-96.6427L1.0018 -96.6127C9.79359 49.5443 28.3705 194.909 73.773 303.76C96.4782 358.194 125.917 403.558 164.251 435.317C202.604 467.091 249.814 485.206 307.96 485.206C360.924 485.206 423.919 451.791 490.8 397.494C557.725 343.161 628.717 267.774 697.702 183.517C835.673 15.0012 965.715 -189.117 1039.25 -331.574Z"
                    stroke="url(#paint5_linear_1146_121)" stroke-opacity="0.2" stroke-width="2" />
                <path
                    d="M777.519 -227.649L777.552 -227.724L777.571 -227.803C782.943 -249.496 784.23 -265.462 781.867 -277.025C779.488 -288.671 773.405 -295.844 764.206 -299.833C755.097 -303.784 743.01 -304.58 728.639 -303.753C714.726 -302.952 698.527 -300.619 680.599 -298.036L678.745 -297.768C604.007 -287.007 499.36 -272.656 399.96 -344.302C342.131 -385.984 297.002 -404.26 261.807 -404.542C226.497 -404.824 201.356 -386.999 183.595 -357.01C165.868 -327.078 155.449 -284.979 149.451 -236.488C143.45 -187.977 141.861 -132.964 141.861 -77.1375V-77.1089L141.863 -77.0803C147.239 16.6307 158.111 109.056 185.407 178.077C199.059 212.596 216.843 241.327 240.165 261.434C263.507 281.558 292.347 293 328 293C346.517 293 368.215 285.663 391.751 272.816C415.303 259.96 440.781 241.54 466.882 219.263C519.085 174.706 573.858 114.65 620.798 52.6033C687.102 -35.0413 716.088 -81.361 733.602 -117.678C742.36 -135.838 748.243 -151.482 754.482 -168.503C754.99 -169.887 755.499 -171.28 756.013 -172.685C761.818 -188.551 768.149 -205.853 777.519 -227.649Z"
                    stroke="url(#paint6_linear_1146_121)" stroke-opacity="0.2" stroke-width="2" />
                <defs>
                    <linearGradient id="paint0_linear_1146_121" x1="439.431" y1="-266.545" x2="439.431" y2="145" gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="#39B992" />
                        <stop offset="0.0001" stop-color="#4CBC9A" />
                        <stop offset="0.484375" stop-color="#4CBC9A" />
                        <stop offset="1" stop-color="var(--secondary)" />
                    </linearGradient>
                    <linearGradient id="paint1_linear_1146_121" x1="426.128" y1="-203.062" x2="426.128" y2="85" gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="#39B992" />
                        <stop offset="0.0001" stop-color="var(--secondary)" />
                        <stop offset="0.484375" stop-color="var(--secondary)" />
                        <stop offset="1" stop-color="var(--secondary)" />
                    </linearGradient>
                    <linearGradient id="paint2_linear_1146_121" x1="410.81" y1="-131.804" x2="410.81" y2="26" gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="#39B992" />
                        <stop offset="0.0001" stop-color="var(--secondary)" />
                        <stop offset="0.484375" stop-color="var(--secondary)" />
                        <stop offset="1" stop-color="var(--secondary)" />
                    </linearGradient>
                    <linearGradient id="paint3_linear_1146_121" x1="456.431" y1="-330.545" x2="456.431" y2="215" gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="#39B992" />
                        <stop offset="0.0001" stop-color="var(--secondary)" />
                        <stop offset="0.484375" stop-color="var(--secondary)" />
                        <stop offset="1" stop-color="var(--secondary)" />
                    </linearGradient>
                    <linearGradient id="paint4_linear_1146_121" x1="496.791" y1="-486.937" x2="496.791" y2="381.134" gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="#39B992" />
                        <stop offset="0.0001" stop-color="var(--secondary)" />
                        <stop offset="0.484375" stop-color="var(--secondary)" />
                        <stop offset="1" stop-color="var(--secondary)" />
                    </linearGradient>
                    <linearGradient id="paint5_linear_1146_121" x1="524.596" y1="-605.751" x2="524.596" y2="484.206" gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="#39B992" />
                        <stop offset="0.0001" stop-color="var(--secondary)" />
                        <stop offset="0.484375" stop-color="var(--secondary)" />
                        <stop offset="1" stop-color="var(--secondary)" />
                    </linearGradient>
                    <linearGradient id="paint6_linear_1146_121" x1="462.431" y1="-403.545" x2="462.431" y2="292" gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="#39B992" />
                        <stop offset="0.0001" stop-color="var(--secondary)" />
                        <stop offset="0.484375" stop-color="var(--secondary)" />
                        <stop offset="1" stop-color="var(--secondary)" />
                    </linearGradient>
                </defs>
            </svg>
            <svg class="red-line" width="1131" height="455" viewBox="0 0 1131 455" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1132 6.00001C1008.33 -8.33332 722 0.399994 566 150C371 337 309 482 1 527M1132 124C1020 112 787 85 659 177C516.839 279.178 430 455 134 527M1132 243C1039.33 220.667 824 177 659 289C457.942 425.476 308 527 213 527M1132 380C1043 354.667 891 278 685 355C509.757 420.504 405 516 297 527" stroke="url(#paint0_linear_1145_531)" stroke-width="2" />
                <defs>
                    <linearGradient id="paint0_linear_1145_531" x1="566.5" y1="1.10791" x2="566.5" y2="527" gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="var(--primary)" stop-opacity="0.2" />
                        <stop offset="0.526042" stop-color="var(--primary)" />
                        <stop offset="1" stop-color="var(--primary)" stop-opacity="0.2" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{ url('dosen') }}" class="brand-logo">


                <img src="{{ asset(LOGO_PATH . ($auth->university->logo ? $auth->university->logo : 'default.png')) }}" class="logo-abbr" style="margin: 50%" height="65" alt="" />

                <svg width="304px" height="50px" class="brand-title" xmlns="http://www.w3.org/2000/svg" viewBox="122.7984375 27.5 254.403125 95" style="background: rgba(0, 0, 0, 0);" preserveAspectRatio="xMidYMid">
                    <defs>
                        <linearGradient id="editing-sticker-gradient" x1="0.5" y1="0.2" x2="0.5" y2="0.8">
                            <stop offset="0" stop-color="#fd9"></stop>
                            <stop offset="1" stop-color="#9df"></stop>
                        </linearGradient>
                        <filter id="editing-sticker" x="-100%" y="-100%" width="300%" height="300%">
                            <feMorphology operator="erode" radius="1" in="SourceAlpha" result="alpha-erode">
                            </feMorphology>
                            <feConvolveMatrix order="3,3" divisor="1" kernelMatrix="0 1 0 1 1 1 0 1 0" in="alpha-erode" result="alpha-round"></feConvolveMatrix>
                            <feMorphology operator="dilate" radius="3.5" in="alpha-round" result="dilate-shadow">
                            </feMorphology>
                            <feGaussianBlur in="dilate-shadow" stdDeviation="1.5" result="shadow"></feGaussianBlur>
                            <feFlood flood-color="#fff" result="flood-sticker"></feFlood>
                            <feComposite operator="in" in="flood-sticker" in2="alpha-round" result="comp-sticker">
                            </feComposite>
                            <feMorphology operator="dilate" radius="3" in="comp-sticker" result="morph-sticker">
                            </feMorphology>
                            <feConvolveMatrix order="3,3" divisor="1" kernelMatrix="0 1 0 1 1 1 0 1 0" in="morph-sticker" result="sticker"></feConvolveMatrix>
                            <feMerge>
                                <feMergeNode in="shadow"></feMergeNode>
                                <feMergeNode in="sticker"></feMergeNode>
                                <feMergeNode in="SourceGraphic"></feMergeNode>
                            </feMerge>
                        </filter>
                    </defs>
                    <g filter="url(#editing-sticker)">
                        <g transform="translate(163.15000534057617, 91.58500099182129)">
                            <path
                                d="M3.59 0L3.59-32.20L6.16-32.20L6.16-2.30L20.19-2.30L20.19 0L3.59 0ZM45.91 0L45.91-24.61L37.54 0L34.73 0L26.40-24.61L26.40 0L23.83 0L23.83-32.20L26.40-32.20L36.11-3.96L45.91-32.20L48.48-32.20L48.48 0L45.91 0ZM56.49-8.19L56.49-8.19Q56.63-6.62 57.66-5.20L57.66-5.20L57.66-5.20Q58.70-3.77 60.51-2.88L60.51-2.88L60.51-2.88Q62.33-1.98 64.68-1.98L64.68-1.98L64.68-1.98Q68.22-1.98 70.59-3.56L70.59-3.56L70.59-3.56Q72.96-5.15 72.96-8.60L72.96-8.60L72.96-8.60Q72.96-10.63 71.81-11.98L71.81-11.98L71.81-11.98Q70.66-13.34 68.95-14.14L68.95-14.14L68.95-14.14Q67.25-14.95 64.31-16.01L64.31-16.01L64.31-16.01Q61.13-17.11 59.23-18.05L59.23-18.05L59.23-18.05Q57.32-19.00 56.01-20.61L56.01-20.61L56.01-20.61Q54.69-22.22 54.69-24.75L54.69-24.75L54.69-24.75Q54.69-26.77 55.84-28.59L55.84-28.59L55.84-28.59Q56.99-30.41 59.32-31.53L59.32-31.53L59.32-31.53Q61.64-32.66 65.04-32.66L65.04-32.66L65.04-32.66Q67.30-32.66 68.77-32.34L68.77-32.34L68.77-32.34Q70.24-32.02 71.76-31.28L71.76-31.28L70.98-28.84L70.98-28.84Q68.36-30.18 65.14-30.18L65.14-30.18L65.14-30.18Q61.59-30.18 59.48-28.70L59.48-28.70L59.48-28.70Q57.36-27.23 57.36-24.61L57.36-24.61L57.36-24.61Q57.36-22.95 58.42-21.83L58.42-21.83L58.42-21.83Q59.48-20.70 61.02-20.01L61.02-20.01L61.02-20.01Q62.56-19.32 65.32-18.35L65.32-18.35L65.32-18.35Q68.68-17.20 70.75-16.21L70.75-16.21L70.75-16.21Q72.82-15.23 74.27-13.41L74.27-13.41L74.27-13.41Q75.72-11.59 75.72-8.83L75.72-8.83L75.72-8.83Q75.72-4.60 73.03-2.05L73.03-2.05L73.03-2.05Q70.33 0.51 64.72 0.51L64.72 0.51L64.72 0.51Q61.32 0.51 58.90-0.76L58.90-0.76L58.90-0.76Q56.49-2.02 55.22-4.03L55.22-4.03L55.22-4.03Q53.96-6.03 53.87-8.19L53.87-8.19L56.49-8.19ZM80.04-16.47L80.04-19.00L94.76-19.00L94.76-16.47L80.04-16.47ZM100.42 0L100.42-32.20L110.58-32.20L110.58-32.20Q114.36-32.20 116.52-30.27L116.52-30.27L116.52-30.27Q118.68-28.34 118.68-24.38L118.68-24.38L118.68-24.38Q118.68-22.59 118.08-20.98L118.08-20.98L118.08-20.98Q117.48-19.37 115.64-18.12L115.64-18.12L115.60-18.03L115.60-17.99L115.60-17.99Q117.62-17.07 118.98-15.00L118.98-15.00L118.98-15.00Q120.34-12.93 120.34-9.61L120.34-9.61L120.34-9.61Q120.34-5.66 118.20-2.83L118.20-2.83L118.20-2.83Q116.06 0 111.00 0L111.00 0L100.42 0ZM110.49-19.00L110.49-19.00Q113.07-19.00 114.54-20.38L114.54-20.38L114.54-20.38Q116.01-21.76 116.01-24.38L116.01-24.38L116.01-24.38Q116.01-29.76 110.22-29.76L110.22-29.76L102.99-29.76L102.99-19.00L110.49-19.00ZM110.91-2.44L110.91-2.44Q113.99-2.44 115.78-4.09L115.78-4.09L115.78-4.09Q117.58-5.75 117.58-9.80L117.58-9.80L117.58-9.80Q117.58-13.02 115.83-14.74L115.83-14.74L115.83-14.74Q114.08-16.47 111.69-16.47L111.69-16.47L102.99-16.47L102.99-2.44L110.91-2.44ZM141.86 0L141.22-1.89L141.22-1.89Q138.83 0.46 134.55 0.46L134.55 0.46L134.55 0.46Q131.97 0.46 129.77-0.51L129.77-0.51L129.77-0.51Q127.56-1.47 126.20-3.54L126.20-3.54L126.20-3.54Q124.84-5.61 124.84-8.69L124.84-8.69L124.84-23.14L124.84-23.14Q124.84-26.36 125.88-28.31L125.88-28.31L125.88-28.31Q126.91-30.27 128.85-31.46L128.85-31.46L128.85-31.46Q130.87-32.61 133.81-32.61L133.81-32.61L133.81-32.61Q137.40-32.61 140.30-31.10L140.30-31.10L139.56-29.35L139.56-29.35Q136.94-30.31 134.60-30.31L134.60-30.31L134.60-30.31Q131.79-30.31 129.60-28.93L129.60-28.93L129.60-28.93Q127.42-27.55 127.42-23.55L127.42-23.55L127.42-8.60L127.42-8.60Q127.42-5.52 129.42-3.77L129.42-3.77L129.42-3.77Q131.42-2.02 134.50-2.02L134.50-2.02L134.50-2.02Q137.63-2.02 139.47-3.82L139.47-3.82L139.47-3.82Q141.31-5.61 141.31-8.56L141.31-8.56L141.31-11.27L138.28-11.27L137.40-13.80L143.75-13.80L143.75 0L141.86 0ZM150.88-8.19L150.88-8.19Q151.02-6.62 152.05-5.20L152.05-5.20L152.05-5.20Q153.09-3.77 154.90-2.88L154.90-2.88L154.90-2.88Q156.72-1.98 159.07-1.98L159.07-1.98L159.07-1.98Q162.61-1.98 164.98-3.56L164.98-3.56L164.98-3.56Q167.35-5.15 167.35-8.60L167.35-8.60L167.35-8.60Q167.35-10.63 166.20-11.98L166.20-11.98L166.20-11.98Q165.05-13.34 163.35-14.14L163.35-14.14L163.35-14.14Q161.64-14.95 158.70-16.01L158.70-16.01L158.70-16.01Q155.53-17.11 153.62-18.05L153.62-18.05L153.62-18.05Q151.71-19.00 150.40-20.61L150.40-20.61L150.40-20.61Q149.09-22.22 149.09-24.75L149.09-24.75L149.09-24.75Q149.09-26.77 150.24-28.59L150.24-28.59L150.24-28.59Q151.39-30.41 153.71-31.53L153.71-31.53L153.71-31.53Q156.03-32.66 159.44-32.66L159.44-32.66L159.44-32.66Q161.69-32.66 163.16-32.34L163.16-32.34L163.16-32.34Q164.63-32.02 166.15-31.28L166.15-31.28L165.37-28.84L165.37-28.84Q162.75-30.18 159.53-30.18L159.53-30.18L159.53-30.18Q155.99-30.18 153.87-28.70L153.87-28.70L153.87-28.70Q151.75-27.23 151.75-24.61L151.75-24.61L151.75-24.61Q151.75-22.95 152.81-21.83L152.81-21.83L152.81-21.83Q153.87-20.70 155.41-20.01L155.41-20.01L155.41-20.01Q156.95-19.32 159.71-18.35L159.71-18.35L159.71-18.35Q163.07-17.20 165.14-16.21L165.14-16.21L165.14-16.21Q167.21-15.23 168.66-13.41L168.66-13.41L168.66-13.41Q170.11-11.59 170.11-8.83L170.11-8.83L170.11-8.83Q170.11-4.60 167.42-2.05L167.42-2.05L167.42-2.05Q164.73 0.51 159.11 0.51L159.11 0.51L159.11 0.51Q155.71 0.51 153.29-0.76L153.29-0.76L153.29-0.76Q150.88-2.02 149.61-4.03L149.61-4.03L149.61-4.03Q148.35-6.03 148.26-8.19L148.26-8.19L150.88-8.19Z"
                                fill="url(#editing-sticker-gradient)" stroke="#000" stroke-width="2.5"></path>
                        </g>
                    </g>

                </svg>




            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->



        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                @yield('title')
                            </div>
                        </div>
                        <div class="navbar-nav header-right">
                            <div class="nav-item d-flex align-items-center">
                                @if (semester_now())
                                    <h6 class="text-end mobile-hide" style="color: #aaaaaa">{{ tr('Semester') }}
                                        {{ semester_now()->odd == 1 ? tr('ganjil') : tr('genap') }} {{ tr('TA') }}
                                        {{ semester_now()->year }}/{{ semester_now()->year + 1 }} <br>
                                        <small>{{ date_id(semester_now()->start, 4) }} -
                                            {{ date_id(semester_now()->end, 4) }}</small>
                                    </h6>
                                @endif
                            </div>
                            <div class="dlab-side-menu">
                                <div class="search-coundry d-flex align-items-center">
                                    <a class="btn btn-danger" href="{{ url('dosen/logout') }}"><i class="flaticon-381-turn-off"></i></a>
                                </div>
                                <div class="sidebar-social-link ">
                                    <ul>

                                        <li class="nav-item ">
                                            <a class="btn btn-dark m-1 " data-bs-toggle="modal" href="#password_header"><i class="flaticon-381-lock-1"></i></a>
                                        </li>
                                        <li class="nav-item ">
                                            @if ($auth->lang == 'id')
                                                <a class="btn btn-info m-1 px-3 py-2" href="{{ url('/dosen/auth/lang?id=' . $auth->id . '&lang=en') }}"><img src="{{ asset('images/icon/english.png') }}" height="25" alt="language"></a>
                                            @else
                                                <a class="btn btn-info m-1 px-3 py-2" href="{{ url('/dosen/auth/lang?id=' . $auth->id . '&lang=id') }}"><img src="{{ asset('images/icon/bahasa.png') }}" height="25" alt="language"></a>
                                            @endif
                                        </li>
                                        <li class="nav-item ">
                                            <a class="btn btn-secondary m-1" href="{{ url('dosen/profil') }}"><i class="flaticon-381-user-7"></i></a>
                                        </li>

                                        <li class="nav-item ">
                                            <a class="btn btn-danger m-1 mobile-show" href="{{ url('dosen/logout') }}" style="display: none;"><i class="flaticon-381-turn-off"></i></a>
                                        </li>




                                    </ul>
                                </div>
                                <ul>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link" href="{{ url('dosen/profil') }}" role="button">

                                            <div class="cropcircle" style="background-image: url('@php echo $auth->avatar ? asset(AVATAR_PATH . $auth->avatar) : 'https://ui-avatars.com/api/?background=FFFFFF&name=' . str_replace(' ', '+', $auth->name) @endphp');">
                                            </div>
                                        </a>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav">
            <div class="dlabnav-scroll">
                <ul class="metismenu" id="menu">
                    <li><a class="" href="{{ url('dosen/dashboard') }}" aria-expanded="false">
                            <i class="bi bi-grid"></i>
                            <span class="nav-text">{{ tr('dashboard') }}</span>
                        </a>
                    </li>

                    <li>
                        <a class="" href="{{ url('dosen/jadwal') }}" aria-expanded="false">
                            <i class="bi bi-clock"></i>
                            <span class="nav-text">{{ tr('jadwal') }}</span>
                        </a>
                    </li>

                    <li>
                        <a class="" href="{{ url('dosen/absensi') }}" aria-expanded="false">
                            <i class="bi bi-pencil"></i>
                            <span class="nav-text">{{ tr('absensi') }}</span>
                        </a>
                    </li>

                    <li>
                        <a class="" href="{{ url('dosen/elearning') }}" aria-expanded="false">
                            <i class="bi bi-laptop"></i>
                            <span class="nav-text">{{ tr('materi') }}<br>{{ tr('pembelajaran') }}</span>
                        </a>
                    </li>



                    <li>
                        <a class="" href="{{ url('dosen/kuis') }}" aria-expanded="false">
                            <i class="bi bi-patch-question"></i>
                            <span class="nav-text">{{ tr('kuis') }}</span>
                        </a>
                    </li>

                    <li>
                        <a class="" href="{{ url('dosen/ujian') }}" aria-expanded="false">
                            <i class="bi bi-card-text"></i>
                            <span class="nav-text">{{ tr('ujian') }}</span>
                        </a>
                    </li>

                    <li>
                        <a class="" href="{{ url('dosen/soal') }}" aria-expanded="false">
                            <i class="bi bi-server"></i>
                            <span class="nav-text">{{ tr('bank soal') }}</span>
                        </a>
                    </li>


                    <br>
                    <br>

                    <p class="text-white text-center"><strong>{{ tr('elmed') }}<br>{{ tr('lms-dosen') }}</strong>
                    </p>
                    <br>

                </ul>

                <div class="copyright mt-3">


                </div>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                @yield('breadcrumb')
                @yield('content')


                <div class="modal fade" id="password_header">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ tr('ganti password') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <form action="{{ url('/dosen/auth/password') }}" method="post" onsubmit="return checkHeaderPass()" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="{{ $auth->id }}">
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label">{{ tr('password lama') }}</label>
                                        <div class="col-sm-8">
                                            <input type="password" name="old_password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label">{{ tr('password baru') }}</label>
                                        <div class="col-sm-8">
                                            <input type="password" name="new_password" id="header_newpass" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label">{{ tr('konfirmasi password baru') }}</label>
                                        <div class="col-sm-8">
                                            <input type="password" id="header_renewpass" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class=" d-none" id="header_display_pass_error">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span class="alert-inner--text" id="display_pass_error">
                                            </span>

                                        </div>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ tr('update') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>




            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed by <a href="http://www.poltekkes-medan.ac.id/" target="_blank">{{ $auth->university->name }}</a>2022</p>
            </div>
        </div>

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('template/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('template/vendor/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-nice-select/js/jquery.nice-select.min.js') }}"></script>

    <script src="{{ asset('template/vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('template/js/plugins-init/select2-init.js') }}"></script>
    <script src="{{ asset('template/vendor/swiper/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('template/js/dlab.carousel.js') }}"></script>

    <script src="{{ asset('template/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/js/plugins-init/datatables.init.js') }}"></script>
    <script src="{{ asset('template/vendor/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('template/vendor/owl-carousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('template/vendor/ckeditor/ckeditor.js') }}"></script>
    <!-- Dashboard 1 -->
    <script src="{{ asset('template/js/dashboard/instructor-courses.js') }}"></script>
    <script src="{{ asset('template/js/dlab.carousel.js') }}"></script>

    <script src="{{ asset('template/js/custom.js') }}"></script>
    <script src="{{ asset('template/js/dlabnav-theme-3.js') }}"></script>
    <script src="{{ asset('template/vendor/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('template/vendor/dropify-master/dist/js/dropify.min.js') }}"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    <script>
        $(document).ready(function() {
            // Basic
            $('.dropify').dropify();
            $(".sel2").select2();
            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });

            // Used events
            var drEvent = $('#input-file-events').dropify();

            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function(event, element) {
                alert('File deleted');
            });

            drEvent.on('dropify.errors', function(event, element) {
                console.log('Has Errors');
            });

            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function(e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })

            online();
            setInterval(function() {
                online();
            }, 30000);
        });
    </script>

    <script>
        $('.table-responsive').on('show.bs.dropdown', function() {
            $('.table-responsive').css("min-height", "400px");
        });

        $('.table-responsive').on('hide.bs.dropdown', function() {
            $('.table-responsive').css("min-height", "0");
        })
    </script>

    <script>
        function show_toast(status, text) {
            if (status == 1) {
                toastr.success("", text, {
                    positionClass: "toast-bottom-right",
                    timeOut: 5e3,
                    closeButton: !0,
                    debug: !1,
                    newestOnTop: !0,
                    progressBar: !0,
                    preventDuplicates: !1,
                    onclick: null,
                    showDuration: "300",
                    hideDuration: "1000",
                    extendedTimeOut: "1000",
                    showEasing: "swing",
                    hideEasing: "linear",
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    tapToDismiss: !1
                });
            } else {
                toastr.error("", text, {
                    positionClass: "toast-bottom-right",
                    timeOut: 5e3,
                    closeButton: !0,
                    debug: !1,
                    newestOnTop: !0,
                    progressBar: !0,
                    preventDuplicates: !1,
                    onclick: null,
                    showDuration: "300",
                    hideDuration: "1000",
                    extendedTimeOut: "1000",
                    showEasing: "swing",
                    hideEasing: "linear",
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    tapToDismiss: !1
                });
            }

        }
    </script>


    @if (Session::has('success'))
        <script>
            show_toast(1, "{{ Session::get('success') }}");
        </script>
    @endif

    @if (Session::has('failed'))
        <script>
            show_toast(0, "{{ Session::get('failed') }}");
        </script>
    @endif


    <script>
        function checkHeaderPass() {
            var password = document.getElementById("header_newpass").value;
            var confirmPassword = document.getElementById("header_renewpass").value;
            $('#header_display_pass_error').addClass('d-none');
            if (password.length < 8) {
                $('#header_display_pass_error').removeClass('d-none');
                $('#display_pass_error').html('<b>{{ tr('peringatan') }}!!!</b> {{ tr('password minimal 8 karakter') }}');
                return false;
            }

            if (password != confirmPassword) {
                $('#header_display_pass_error').removeClass('d-none');
                $('#display_pass_error').html('<b>{{ tr('peringatan') }}!!!</b> {{ tr('password tidak cocok') }}');
                return false;
            }

            return true;
        }

        function online() {

            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: '{{ $auth->id }}'
                },
                url: "{{ url('dosen/auth/online') }}",
                success: function(data) {
                    if (data.message != "success") {
                        console.error(data.message);
                    }

                },
                error: function(request, status, error) {
                    console.error(request.responseText);
                }
            });
        }
    </script>

    <script type="text/javascript" src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js"></script>
    @yield('script')

</body>

</html>
