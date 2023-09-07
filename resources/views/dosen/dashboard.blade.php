@extends('dosen/master')

@section('title', 'Dashboard')



@section('content')
    @php
        $auth = auth()
            ->guard('dosen')
            ->user()
            ->load(['university']);
    @endphp
    <div class="row">
        <div class="col-xl-12 col-xxl-6">
            <div class="card dlab-join-card h-auto">
                <div class="card-body">
                    <div class="dlab-media d-flex justify-content-between">
                        <div class="dlab-content">
                            <h6 class="text-white">{{ tr('selamat datang di portal dosen') }}</h6>
                            <h4 class="text-white">{{ title_lecturer($auth) }} <br><small>
                                    {{ $auth->identity }}. {{ $auth->identity_number }}</small>
                            </h4>
                            <a href="{{ url('dosen/profil') }}" class="btn btn-sx btn-primary mt-5">{{ tr('lihat profil') }} >></a>

                        </div>
                        <div class="dlab-img">
                            <img src="images/egucation-girl.png" alt="">
                        </div>
                        <div class="dlab-icon">
                            <img src="{{ asset('images/art/presentation.png') }}" alt="" class="cal-img" height="150">

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
                                <a href="{{ url('dosen/absensi') }}" class="text-info">{{ tr('lihat absensi') }}</a>
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
                                            <a class="timeline-panel text-muted">
                                                <span class="d-block">{{ $item->class->name }} -
                                                    {{ $item->room->name }}
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
            <div class="row" style="z-index: 999999999999999; position: relative;">
                <div class="col-9">
                    <h3 class="mb-3">{{ tr('e-learning aktif') }}</h3>
                </div>
                <div class="col-3">
                    <a href="{{ url('dosen/elearning') }}" class=" text-primary float-end">{{ tr('lihat semua') }}</a>
                </div>
            </div>
            @if (count($elearning) == 0)
                <div class="text-center" width="100%" height="150">
                    <img src="{{ asset('images/art/empty1.png') }}" height="100" alt="">
                    <h6 class="mt-3">{{ tr('belum ada elearning aktif saat ini') }}</h6>
                    <a class="btn btn-primary btn-xs mb-3" href="{{ url('dosen/elearning') }}">{{ tr('lihat semua') }}</a>
                    <br>
                    <br>
                </div>
            @else
                <div class="owl-carousel owl-carousel owl-loaded front-view-slider ">
                    @foreach ($elearning as $item)
                        <div class="items">
                            <a href="{{ url('dosen/elearning/detail?id=' . $item->elearning_id . '&kelas=' . $item->id) }}">
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
            <div class="row" style="z-index: 999999999999999; position: relative;">
                <div class="col-9">
                    <h3 class="mb-3">{{ tr('kuis aktif') }}</h3>
                </div>
                <div class="col-3">
                    <a href="{{ url('dosen/kuis') }}" class=" text-primary float-end">{{ tr('lihat semua') }}</a>
                </div>
            </div>
            @if (count($quiz) == 0)
                <div class="text-center" width="100%" height="150">
                    <img src="{{ asset('images/art/empty1.png') }}" height="100" alt="">
                    <h6 class="mt-3">{{ tr('belum ada kuis aktif saat ini') }}</h6>
                    <a class="btn btn-primary btn-xs mb-3" href="{{ url('dosen/kuis') }}">{{ tr('lihat semua') }}</a>
                    <br>
                    <br>
                </div>
            @else
                <div class="owl-carousel owl-carousel owl-loaded front-view-slider ">
                    @foreach ($quiz as $item)
                        <div class="items">
                            <a href="{{ url('dosen/kuis/detail?id=' . $item->quiz_id . '&kelas=' . $item->id) }}">
                                <div class=" card">
                                    <div class="imgcard" style="background-image: url({{ url(QUIZ_G) . str_replace(' ', '_', $item->quiz->name) }});">
                                        <div class="m-2 float-end">
                                            @if (strtotime($item->end) > strtotime(date('Y-m-d h:i')))
                                                <span class="badge bg-success">{{ tr('aktif') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ tr('belum dikoreksi') }}</span>
                                            @endif
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
        </div>

        @if (count($exam) > 0)
            <div class="col-xl-12">
                <div class="row" style="z-index: 999999999999999; position: relative;">
                    <div class="col-9">
                        <h3 class="mb-3">{{ tr('ujian aktif') }}</h3>
                    </div>
                    <div class="col-3">
                        <a href="{{ url('dosen/ujian') }}" class="text-primary float-end">{{ tr('lihat semua') }}</a>
                    </div>
                </div>
                <div class="owl-carousel owl-carousel owl-loaded front-view-slider ">
                    @foreach ($exam as $item)
                        <div class="items">
                            <a href="{{ url('dosen/ujian/detail?id=' . $item->exam_id . '&kelas=' . $item->id) }}">
                                <div class=" card">
                                    <div class="imgcard" style="background-image: url({{ url(EXAM_G) . str_replace(' ', '_', $item->exam->name) }});">
                                        <div class="m-2 float-end">
                                            @if (strtotime($item->end) > strtotime(date('Y-m-d h:i')))
                                                <span class="badge bg-success">{{ tr('aktif') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ tr('belum dikoreksi') }}</span>
                                            @endif
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
            </div>
        @endif
    </div>


@endsection
