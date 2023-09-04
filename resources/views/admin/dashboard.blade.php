@extends('admin/master')

@section('title', 'Dashboard')

@section('content')
    @php
        $auth = auth()
            ->guard('admin')
            ->user()
            ->load(['university']);
    @endphp
    <div class="row">
        <div class="widget-heading d-flex justify-content-between align-items-center">
            <h3 class="m-0">Selamat datang, {{ $auth->name }}</h3>

        </div>
        <div class="row mx-1">
            <!-- Slider main container -->
            <div class="swiper course-slider">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    @if (can('Jurusan', 'view'))
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body">
                                    <div class="widget-courses align-items-center d-flex justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <img src="{{ asset('images/icon/major.png') }}" alt="" width="50"
                                                class="">
                                            <div class="flex-1 ms-3">
                                                <h4>Jurusan</h4>
                                                <span>{{ $total_jurusan }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ url('4dm1n/jurusan') }}"><i
                                                class="las la-angle-right text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- Slides -->
                    @if (can('Prodi Lengkap', 'view'))
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body">
                                    <div class="widget-courses align-items-center d-flex justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <img src="{{ asset('images/icon/study.png') }}" alt="" width="50">
                                            <div class="flex-1 ms-3">
                                                <h4>Prodi</h4>
                                                <span>{{ $total_prodi }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ url('4dm1n/prodi_lengkap') }}"><i
                                                class="las la-angle-right text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- Slides -->
                    @if (can('Mata kuliah', 'view'))
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body">
                                    <div class="widget-courses align-items-center d-flex justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <img src="{{ asset('images/icon/subject.png') }}" alt="" width="50"
                                                class="">
                                            <div class="flex-1 ms-3">
                                                <h4>Mata kuliah</h4>
                                                <span>{{ $total_matkul }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ url('4dm1n/matkul') }}"><i
                                                class="las la-angle-right text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Slides -->
                    @if (can('Dosen', 'view'))
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body">
                                    <div class="widget-courses align-items-center d-flex justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <img src="{{ asset('images/icon/lecturer.png') }}" alt=""
                                                width="50" class="">
                                            <div class="flex-1 ms-3">
                                                <h4>Dosen</h4>
                                                <span>{{ $total_dosen }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ url('4dm1n/dosen') }}"><i
                                                class="las la-angle-right text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Slides -->
                    @if (can('Mahasiswa', 'view'))
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body">
                                    <div class="widget-courses align-items-center d-flex justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <img src="{{ asset('images/icon/colleger.png') }}" alt=""
                                                width="50" class="">
                                            <div class="flex-1 ms-3">
                                                <h4>Mahasiswa</h4>
                                                <span>{{ $total_mahasiswa }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ url('4dm1n/mahasiswa') }}"><i
                                                class="las la-angle-right text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Slides -->
                    @if (can('Soal', 'view'))
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body">
                                    <div class="widget-courses align-items-center d-flex justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <img src="{{ asset('images/icon/question.png') }}" alt=""
                                                width="50" class="">
                                            <div class="flex-1 ms-3">
                                                <h4>Soal</h4>
                                                <span>{{ $total_soal }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ url('4dm1n/soal') }}"><i
                                                class="las la-angle-right text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Slides -->
                    @if (can('Materi', 'view'))
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body">
                                    <div class="widget-courses align-items-center d-flex justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <img src="{{ asset('images/icon/elearning.png') }}" alt=""
                                                width="50" class="">
                                            <div class="flex-1 ms-3">
                                                <h4>E-learning</h4>
                                                <span>{{ $total_elearning }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ url('4dm1n/materi') }}"><i
                                                class="las la-angle-right text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (can('Kuis', 'view'))
                        <!-- Slides -->
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body">
                                    <div class="widget-courses align-items-center d-flex justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <img src="{{ asset('images/icon/quiz.png') }}" alt="" width="50"
                                                class="">
                                            <div class="flex-1 ms-3">
                                                <h4>Kuis</h4>
                                                <span>{{ $total_kuis }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ url('4dm1n/kuis') }}"><i
                                                class="las la-angle-right text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif



                </div>
            </div>
        </div>


        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row" style="width:110%;">
                        <div class="col-7">
                            <h4>Grafik perkembangan LMS</h4>
                        </div>
                        <div class="col-5">

                            <select class="form-select" id="prodi_" onchange="chart_lms()"
                                @if (can_prodi()) disabled @endif>
                                <option value="">Semua prodi</option>
                                @foreach ($prodi_data as $item)
                                    <option value="{{ $item->id }}" @if ($prodi_id == $item->id) selected @endif>
                                        {{ $item->program->name }}
                                        {{ $item->study_program->name }} - {{ $item->category->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" id="loadGraphLMS">
                        <div class="col-md-12">
                            <div class="mb-5 mt-5">
                                <div class="text-center">
                                    <div class="spinner-border text-dark" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div><br>
                        </div>
                    </div>


                    <div class="row d-none" id="disGraphLMS">

                        <div class="col-md-12">
                            <figure class="highcharts-figure">
                                <div id="graphElearning"></div>

                            </figure>
                        </div>
                        <div class="col-md-12">
                            <figure class="highcharts-figure">
                                <div id="graphQuiz"></div>

                            </figure>
                        </div>
                    </div>



                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            chart_lms();
        });

        function chart_lms() {

            var loading = $("#loadGraphLMS");
            var display = $("#disGraphLMS");
            var prodi = $("#prodi_ option:selected");

            display.addClass('d-none');
            loading.removeClass('d-none');
            $.ajax({
                type: 'POST',
                url: "{{ url('4dm1n/ajax/chart/lms') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    prodi_id: prodi.val()
                },
                success: function(data) {
                    console.log(data);
                    var el = data.result;
                    Highcharts.chart('graphElearning', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Jumlah E-learning ' + prodi.text()
                        },
                        legend: {
                            enabled: false
                        },
                        xAxis: {
                            categories: el.y,
                            title: {
                                useHTML: true,
                                text: '<br>semester'
                            },
                            type: 'category',
                        },
                        yAxis: {
                            title: {
                                useHTML: true,
                                text: 'Jumlah E-learning'
                            },
                            min: 0,
                            tickInterval: 1
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },

                        series: [{
                            name: 'E-learning',
                            data: el.lms

                        }]
                    });

                    Highcharts.chart('graphQuiz', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Jumlah Kuis ' + prodi.text()
                        },
                        legend: {
                            enabled: false
                        },
                        xAxis: {
                            categories: el.y,
                            title: {
                                useHTML: true,
                                text: '<br>semester'
                            },
                            type: 'category',
                        },
                        yAxis: {
                            title: {
                                useHTML: true,
                                text: 'Jumlah Kuis'
                            },
                            min: 0,
                            tickInterval: 1
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },

                        series: [{
                            name: 'Kuis',
                            data: el.quiz

                        }]
                    });

                    display.removeClass('d-none');
                    loading.addClass('d-none');
                },
                error: function(request, status, error) {
                    console.error(request.responseText);
                }
            });
        }
    </script>
@endsection
