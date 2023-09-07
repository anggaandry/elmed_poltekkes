@extends('mahasiswa/master')

@section('title', 'Dashboard')



@section('content')
    @php
        $auth = auth()
            ->guard('mahasiswa')
            ->user()
            ->load(['university']);
        $ac_ = active_class();
    @endphp
    <div class="row">
        <div class="col-xl-12 col-xxl-6">
            <div class="card dlab-join-card h-auto">
                <div class="card-body">
                    <div class="dlab-media d-flex justify-content-between">
                        <div class="dlab-content">
                            <h6 class="text-white">{{ tr('selamat datang di portal mahasiswa') }}</h6>
                            <h4 class="text-white">{{ $auth->name }} <br><small>
                                    NIM. {{ $auth->nim }}</small>
                            </h4>
                            <p><span class="badge badge-dark">{{ tr('Kelas') }} {{ $ac_->name }}
                                </span>
                                @if ($ac_->last)
                                    <br><small>
                                        ({{ tr('semester') }} {{ $ac_->semester . ' ' . $ac_->year }})</small>
                                @endif
                            </p>
                            <a href="{{ url('mahasiswa/profil') }}" class="btn btn-sx bg-white text-dark mt-4">{{ tr('lihat profil') }} >></a>


                        </div>
                        <div class="dlab-img">
                            <img src="images/egucation-girl.png" alt="">
                        </div>
                        <div class="dlab-icon">
                            <img src="{{ asset('images/art/art4.png') }}" alt="" class="cal-img" height="200">

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xl-12 col-xxl-6">
            <div class="card">

                <div class="card-body">
                    <div id="DZ_W_TimeLine11" class="widget-timeline">

                        <div class="d-flex justify-content-between side-border">
                            <h4 class="mb-0 fs-18 font-w500">{{ date_id(date('Y-m-d H:i'), 3) }} </h4>
                            <div class="dropdown custom-dropdown mb-0 mt-1">
                                <a href="{{ url('mahasiswa/absensi') }}" class="text-info">{{ tr('lihat absensi') }}</a>

                            </div>

                        </div>
                        @if (count($schedule) == 0)
                            <div class="text-center p-5" width="100%" height="200">
                                <img src="{{ asset('images/art/holiday.png') }}" height="100" alt="">
                                <h5 class="text-danger mt-3">{{ $holiday_name }}</h5>
                            </div>
                        @else
                            <ul class="timeline-active style-4 mt-3">
                                @foreach ($schedule as $item)
                                    <li class="d-flex align-items-center">
                                        <span class="time-1"> {{ date('H:i', strtotime($item->start)) }} <br>
                                            {{ date('H:i', strtotime($item->end)) }}</span>
                                        <div class="panel">
                                            <div class="line-color bg-{{ random_color() }}"></div>
                                            <a class="timeline-panel text-muted" href="#">
                                                <span class="d-block">{{ $item->room->name }} -
                                                    {{ $item->lecturer }}
                                                </span>
                                                <h4 class="mb-0">{{ $item->sks->subject->name }} @if ($item->moved)
                                                        <span class="text-danger">[{{ tr('jadwal pindahan') }}]</span>
                                                    @endif
                                                </h4>
                                            </a>
                                        </div>
                                    </li>
                                @endforeach



                            </ul>
                        @endif

                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="row" style="z-index: 100; position: relative;">
                <div class="col-9">
                    <h3 class="mb-3">{{ tr('e-learning aktif') }}</h3>
                </div>
                <div class="col-3">
                    <a href="{{ url('mahasiswa/elearning') }}" class="text-primary float-end">{{ tr('lihat semua') }}</a>
                </div>
            </div>
            @if (count($elearning) == 0)
                <div class="text-center" width="100%" height="150">
                    <img src="{{ asset('images/art/empty1.png') }}" height="100" alt="">
                    <h6 class="mt-3">{{ tr('belum ada elearning aktif saat ini') }}</h6>
                    <a class="btn btn-primary btn-xs mb-3" href="{{ url('mahasiswa/elearning') }}">{{ tr('lihat semua') }}</a>
                    <br>
                    <br>
                </div>
            @else
                <div class="owl-carousel owl-carousel owl-loaded front-view-slider ">
                    @foreach ($elearning as $item)
                        <div class="items">
                            <a href="{{ url('mahasiswa/elearning/detail?id=' . $item->id) }}">
                                <div class=" card">
                                    <div class="imgcard" style="background-image: url({{ $item->elearning->image ? asset(LMS_PATH . $item->elearning->image) : url(ELEARNING_G) . str_replace(' ', '_', $item->elearning->name) }});">
                                        <div class="m-2 float-end">
                                            <span class="badge bg-success">{{ tr('active') }}</span>
                                            <span class="badge bg-primary"><i class="fa fa-comment"></i>
                                                {{ count($item->elearning_discussion) }}</span>
                                        </div>

                                    </div>

                                    <div class="px-3 py-2">
                                        <h4 data-bs-toggle="tooltip" data-bs-placement="bottom" title="Elearning :{{ $item->elearning->name }}">{{ $item->elearning->name }}
                                        </h4>
                                        <p>{{ $item->class->name }}</p>
                                        <p>
                                            <small><i class="fa fa-stopwatch"></i> {{ date_id($item->start, 2) }}
                                                </i></small>
                                            <br>
                                            <small><i class="fa fa-flag"></i> {{ date_id($item->end, 2) }}</small>
                                        </p>

                                    </div>

                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-xl-12">
            <div class="row" style="z-index: 100; position: relative;">
                <div class="col-9">
                    <h3 class="mb-3">{{ tr('kuis aktif') }}</h3>
                </div>
                <div class="col-3">
                    <a href="{{ url('mahasiswa/kuis') }}" class="text-primary float-end">{{ tr('lihat semua') }}</a>
                </div>
            </div>
            @if (count($quiz) == 0)
                <div class="text-center" width="100%" height="150">
                    <img src="{{ asset('images/art/empty1.png') }}" height="100" alt="">
                    <h6 class="mt-3">{{ tr('belum ada kuis aktif saat ini') }}</h6>
                    <a class="btn btn-primary btn-xs mb-3" href="{{ url('mahasiswa/kuis') }}">{{ tr('lihat semua') }}</a>
                    <br>
                    <br>
                </div>
            @else
                <div class="owl-carousel owl-carousel owl-loaded front-view-slider ">
                    @foreach ($quiz as $item)
                        <div class="items">
                            <a href="javascript:void(0)" onclick="show_quiz({{ $item->id }})">
                                <div class=" card">
                                    <div class="imgcard" style="background-image: url({{ url(QUIZ_G) . str_replace(' ', '_', $item->quiz->name) }});">
                                        <div class="m-2 float-end">
                                            <span class="badge bg-success">{{ tr('active') }}</span>
                                            <span class="badge bg-secondary"><i class="fa fa-users"></i>
                                                {{ count($item->quiz_absence) }}/{{ count($item->class->colleger_class) }}</span>
                                        </div>

                                    </div>

                                    <div class="px-3 py-2">
                                        <h4 data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kuis :{{ $item->quiz->name }}">{{ $item->quiz->name }}</h4>
                                        <p>{{ $item->class->name }}</p>
                                        <p>
                                            <small><i class="fa fa-stopwatch"></i> {{ date_id($item->start, 2) }}
                                                </i></small>
                                            <br>
                                            <small><i class="fa fa-flag"></i> {{ date_id($item->end, 2) }}</small>
                                        </p>

                                    </div>

                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="modal fade" id="quiz">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ tr('detail kuis') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td colspan="4" id="image_quiz" class="text-center"></td>
                                        </tr>
                                        <tr>
                                            <th>{{ tr('judul kuis') }}</th>
                                            <td id="name_quiz"></td>
                                            <th>{{ tr('dosen pengampu') }}</th>
                                            <td id="lecturer_quiz"></td>
                                        </tr>

                                        <tr>
                                            <th>{{ tr('mata kuliah') }}</th>
                                            <td id="subject_quiz"></td>
                                            <th>{{ tr('skor') }}</th>
                                            <td id="score_quiz"></td>
                                        </tr>

                                        <tr>
                                            <th>{{ tr('mulai') }}</th>
                                            <td id="start_quiz"></td>
                                            <th>{{ tr('berakhir') }}</th>
                                            <td id="end_quiz"></td>
                                        </tr>

                                        <tr>
                                            <th>{{ tr('deskripsi') }}</th>
                                            <td id="description_quiz" colspan="3"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                            <a href="" class="btn btn-primary" id="btn_quiz">

                            </a>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        @if (count($exam) > 0)
            <div class="col-xl-12">
                <div class="row" style="z-index: 100; position: relative;">
                    <div class="col-9">
                        <h3 class="mb-3">{{ tr('ujian aktif') }}</h3>
                    </div>
                    <div class="col-3">
                        <a href="{{ url('mahasiswa/ujian') }}" style="z-index: 999999;" class="text-primary float-end">{{ tr('lihat semua') }}</a>
                    </div>
                </div>

                <div class="owl-carousel owl-carousel owl-loaded front-view-slider ">
                    @foreach ($exam as $item)
                        <div class="items">
                            <a href="javascript:void(0)" onclick="show_exam({{ $item->id }})"">
                                <div class=" card">
                                    <div class="imgcard" style="background-image: url({{ url(EXAM_G) . str_replace(' ', '_', $item->exam->name) }});">
                                        <div class="m-2 float-end">
                                            <span class="badge bg-success">{{ tr('active') }}</span>

                                            <span class="badge bg-dark"><i class="fa fa-users"></i>
                                                {{ count($item->exam_absence) }}/{{ count($item->class->colleger_class) }}</span>
                                        </div>

                                    </div>

                                    <div class="px-3 py-2">
                                        <h4 data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ujian :{{ $item->exam->name }}">{{ $item->exam->name }}</h4>
                                        <p>{{ $item->class->name }}

                                        </p>
                                        <p>
                                            <small><i class="fa fa-stopwatch"></i> {{ date_id($item->start, 2) }}
                                                </i></small>
                                            <br>
                                            <small><i class="fa fa-flag"></i> {{ date_id($item->end, 2) }}</small>
                                        </p>

                                    </div>

                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="modal fade" id="exam">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ tr('detail ujian') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td colspan="4" id="image_exam" class="text-center"></td>
                                            </tr>
                                            <tr>
                                                <th>{{ tr('judul ujian') }}</th>
                                                <td id="name_exam"></td>
                                                <th>{{ tr('dosen pengampu') }}</th>
                                                <td id="lecturer_exam"></td>
                                            </tr>

                                            <tr>
                                                <th>{{ tr('mata kuliah') }}</th>
                                                <td id="subject_exam"></td>
                                                <th>{{ tr('skor') }}</th>
                                                <td id="score_exam"></td>
                                            </tr>

                                            <tr>
                                                <th>{{ tr('mulai') }}</th>
                                                <td id="start_exam"></td>
                                                <th>{{ tr('berakhir') }}</th>
                                                <td id="end_exam"></td>
                                            </tr>

                                            <tr>
                                                <th>{{ tr('deskripsi') }}</th>
                                                <td id="description_exam" colspan="3"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                <a href="" class="btn btn-primary" id="btn_exam">

                                </a>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>


@endsection

@section('script')
    <script>
        function show_quiz(id) {

            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                url: "{{ url('mahasiswa/kuis/ajax/id') }}",
                success: function(data) {
                    console.log(data);
                    if (data.message == "success") {
                        var el = data.result;
                        $('#image_quiz').html(`<img src='${el.image}' width="35%" class="text-center"/>`);
                        $('#name_quiz').html(el.quiz.name);
                        $('#subject_quiz').html(el.subject);
                        $('#lecturer_quiz').html(el.lecturer);
                        $('#start_quiz').html(el.start);
                        $('#end_quiz').html(el.end);
                        $('#score_quiz').html(el.score);
                        $('#description_quiz').html(`${el.quiz.description}`);

                        $('#btn_quiz').addClass("d-none");
                        if (el.passed) {
                            if (el.publish) {
                                $('#btn_quiz').removeClass("d-none");
                                $('#btn_quiz').removeClass("btn-primary");
                                $('#btn_quiz').addClass("btn-success");
                                $('#btn_quiz').html("<i class='fa fa-file'></i> lihat hasil");
                                $("#btn_quiz").attr("href", "{{ url('mahasiswa/kuis/result') }}/" + id)
                            }

                        } else {

                            $('#btn_quiz').removeClass("d-none");
                            $('#btn_quiz').removeClass("btn-success");
                            $('#btn_quiz').addClass("btn-primary");
                            $('#btn_quiz').html("<i class='fa fa-pencil'></i> kerjakan");
                            $("#btn_quiz").attr("href", "{{ url('mahasiswa/kuis/do') }}/" + id);
                        }




                        $('#quiz').modal('show');
                    } else {
                        alert(data.message);
                    }
                },
                error: function(request, status, error) {
                    console.error(request.responseText);
                    alert(request.responseText);
                }
            });
        }
    </script>

    @if (count($exam) > 0)
        <script>
            function show_exam(id) {

                $.ajax({
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    url: "{{ url('mahasiswa/ujian/ajax/id') }}",
                    success: function(data) {
                        console.log(data);
                        if (data.message == "success") {
                            var el = data.result;
                            $('#image_exam').html(`<img src='${el.image}' width="35%" class="text-center"/>`);
                            $('#name_exam').html(el.exam.name);
                            $('#subject_exam').html(el.subject);
                            $('#lecturer_exam').html(el.lecturer);
                            $('#start_exam').html(el.start);
                            $('#end_exam').html(el.end);
                            $('#score_exam').html(el.score);
                            $('#description_exam').html(`${el.exam.description}`);

                            $('#btn_exam').addClass("d-none");
                            if (el.passed) {
                                if (el.publish) {
                                    $('#btn_exam').removeClass("d-none");
                                    $('#btn_exam').removeClass("btn-primary");
                                    $('#btn_exam').addClass("btn-success");
                                    $('#btn_exam').html("<i class='fa fa-file'></i> lihat hasil");
                                    $("#btn_exam").attr("href", "{{ url('mahasiswa/ujian/result') }}/" + id)
                                }

                            } else {

                                $('#btn_exam').removeClass("d-none");
                                $('#btn_exam').removeClass("btn-success");
                                $('#btn_exam').addClass("btn-primary");
                                $('#btn_exam').html("<i class='fa fa-pencil'></i> kerjakan");
                                $("#btn_exam").attr("href", "{{ url('mahasiswa/ujian/do') }}/" + id);
                            }




                            $('#exam').modal('show');
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function(request, status, error) {
                        console.error(request.responseText);
                        alert(request.responseText);
                    }
                });
            }
        </script>
    @endif
@endsection
