@extends('dosen/master')

@section('title', 'Materi E-learning')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dosen/elearning') }}">{{ tr('e-learning') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('detail e-learning') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">

        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-body">
                    <div class="course-content d-flex justify-content-between flex-wrap">

                        <div class="row" style="width:110%;">
                            <div class="col-xl-6 col-xxl-10">
                                <h3>{{ $data->name }}</h3>
                            </div>
                            <div class="col-xl-6 col-xxl-2">
                                <div class="float-end">
                                    <a href="{{ url('dosen/elearning/form/edit?id=' . $data->id . '&kelas=' . $class_id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> {{ tr('edit') }}</a>
                                    <a href="#delete" data-bs-toggle="modal" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                </div>

                                <div class="modal fade" id="delete">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">{{ tr('peringatan') }} !!</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <p>{{ tr('apakah anda ingin menghapus elearning') }}<b>{{ $data->name }}</b>

                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                <a href="{{ url('dosen/elearning/delete/' . $data->id) }}" class="btn btn-primary">{{ tr('hapus') }}</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2" style="width:110%;">
                            <div class="col-xl-12 col-xxl-8">
                                <ul class="d-flex align-items-center raiting my-0 flex-wrap">

                                    @if ($class_first)
                                        <li class="text-info">
                                            <i class="fa fa-check-circle"></i> {{ $class_first->class->name }}
                                        </li>
                                    @endif

                                    <li>{{ $data->sks->prodi->program->name . ' - ' . $data->sks->prodi->study_program->name . ' ' . $data->sks->prodi->category->name }}
                                    </li>
                                    <li>{{ title_lecturer($data->lecturer) }}
                                    </li>

                                    @if ($class_first)
                                        <li>{{ count($viewer_data) }} {{ tr('mahasiswa') }}</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-xl-12 col-xxl-4">
                                <div class="float-end ">
                                    <i class="fa fa-calendar"></i> {{ date_id($data->created_at, 2) }}
                                </div>

                            </div>
                        </div>

                    </div>
                    @if ($data->video)
                        <iframe src="https://www.youtube.com/embed/{{ $data->video }}" height="400" width="100%">
                        </iframe>
                    @else
                        <div style=" background-image: url('{{ $data->image ? asset(LMS_PATH . $data->image) : url(ELEARNING_G) . str_replace(' ', '_', $data->name) }}');
                        border: 1px solid #eee;
                        border-radius: 25px;
                        background-position: center center;
                        width: 100%;
                        height: 400px;">

                        </div>
                    @endif
                    <div class="row">
                        <div class="col-xl-12 col-xxl-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="mb-3">{{ tr('kelas peserta') }}<a class="text-success ms-1" data-bs-toggle="modal" href="#add-class"><i class="fa fa-plus-circle"></i></a>
                                    </h6>
                                    <div class="modal fade" id="add-class">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ tr('tambah kelas untuk elearning') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                                                </div>
                                                <form action="{{ url('/dosen/elearning/kelas/add') }}" method="post">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="elearning_id" value="{{ $data->id }}">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="mb-3 col-md-12">
                                                                <label class="form-label">{{ tr('kelas') }}</label>
                                                                <select class="form-select form-select-lg" name="class_id" required>
                                                                    <option value="">-- {{ tr('pilih kelas') }} --</option>
                                                                    @foreach ($class_data as $item)
                                                                        <option value="{{ $item->id }}">
                                                                            {{ $item->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mb-3 col-md-4">
                                                                <label class="form-label">{{ tr('mulai') }}</label>
                                                                <input type="date" name="date_start" class="form-control" required>
                                                            </div>

                                                            <div class="mb-3 col-md-2">
                                                                <br>
                                                                <input type="time" name="time_start" class="form-control mt-2" required>
                                                            </div>

                                                            <div class="mb-3 col-md-4">
                                                                <label class="form-label">{{ tr('berakhir') }}</label>
                                                                <input type="date" name="date_end" class="form-control" required>
                                                            </div>

                                                            <div class="mb-3 col-md-2">
                                                                <br>
                                                                <input type="time" name="time_end" class="form-control mt-2" required>
                                                            </div>

                                                            <div class="mb-3 col-md-12">
                                                                <label class="form-label">{{ tr('catatan kelas') }}</label>
                                                                <textarea name="note" class="form-control"></textarea>
                                                            </div>


                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                        <button type="submit" class="btn btn-primary">{{ tr('simpan') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @if (count($data->elearning_class) > 0)
                                        <ul>
                                            @foreach ($data->elearning_class as $item)
                                                <li>
                                                    <a href="{{ url('dosen/elearning/detail?id=' . $data->id . '&kelas=' . $item->id) }}" class="text-white">
                                                        <div class="card mt-1 @if ($class_id == $item->id) bg-info @endif" style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;">
                                                            <div class="pt-3 px-2 text-center">
                                                                <b>{{ $item->class->name }}</b>

                                                                <p class="mt-3"><small><i class="fa fa-stopwatch"></i>
                                                                        {{ date_id($item->start, 5) }}</small><br>
                                                                    <small><i class="fa fa-flag"></i>
                                                                        {{ date_id($item->end, 5) }}</small>
                                                                </p>


                                                                <p>
                                                                    <a class="text-primary px-1" data-bs-toggle="modal" href="#edit-class-{{ $item->id }}"><i class="fa fa-edit"></i></a>
                                                                    <a class="text-danger px-1" data-bs-toggle="modal" href="#delete-class-{{ $item->id }}"><i class="fa fa-trash"></i></a>
                                                                </p>

                                                                <div class="modal fade" id="edit-class-{{ $item->id }}">
                                                                    <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">{{ tr('edit kelas untuk elearning') }}</h5>

                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                                </button>
                                                                            </div>

                                                                            <form action="{{ url('/dosen/elearning/kelas/edit') }}" method="post">
                                                                                {{ csrf_field() }}
                                                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                                                <div class="modal-body">
                                                                                    <div class="row text-start">
                                                                                        <div class="mb-3 col-md-12">
                                                                                            <label class="form-label">{{ tr('kelas') }}</label>
                                                                                            <input type="text" value="{{ $item->class->name }}" class="form-control" disabled>
                                                                                        </div>

                                                                                        <div class="mb-3 col-md-4">
                                                                                            <label class="form-label">{{ tr('mulai') }}</label>
                                                                                            <input type="date" name="date_start" value="{{ date('Y-m-d', strtotime($item->start)) }}" class="form-control" required>
                                                                                        </div>

                                                                                        <div class="mb-3 col-md-2">
                                                                                            <br>
                                                                                            <input type="time" name="time_start" value="{{ date('H:i', strtotime($item->start)) }}" class="form-control mt-2" required>
                                                                                        </div>

                                                                                        <div class="mb-3 col-md-4">
                                                                                            <label class="form-label">{{ tr('berakhir') }}</label>
                                                                                            <input type="date" name="date_end" value="{{ date('Y-m-d', strtotime($item->end)) }}" class="form-control" required>
                                                                                        </div>

                                                                                        <div class="mb-3 col-md-2">
                                                                                            <br>
                                                                                            <input type="time" name="time_end" value="{{ date('H:i', strtotime($item->end)) }}" class="form-control mt-2" required>
                                                                                        </div>

                                                                                        <div class="mb-3 col-md-12">
                                                                                            <label class="form-label">{{ tr('catatan kelas') }}</label>

                                                                                            <textarea name="note" class="form-control">{{ $item->note }}</textarea>
                                                                                        </div>


                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                                    <button type="submit" class="btn btn-primary">{{ tr('simpan') }}</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal fade" id="delete-class-{{ $item->id }}">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title text-danger">{{ tr('peringatan') }} !!</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <p>{{ tr('apakah anda ingin menghapus kelas') }}<b>{{ $item->class->name }} {{ tr('elearning') }}</b>
                                                                                    {{ tr('dari elearning') }}
                                                                                    <b>{{ $data->name }}</b>

                                                                                </p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                                <a href="{{ url('dosen/elearning/kelas/delete/' . $item->id) }}" class="btn btn-primary">{{ tr('hapus') }}</a>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </a>


                                                </li>
                                            @endforeach

                                        </ul>
                                    @else
                                        <div class="mt-5 text-center">
                                            <br>
                                            <img src="{{ asset('images/art/empty3.png') }}" height="50" alt="">
                                            <br>
                                            <br>
                                            <i class="mt-3">{{ tr('kelas elearning belum ada') }}</i>
                                            <!-- <a class="btn btn-primary btn-xs mb-3 mt-3" href="{{ url('dosen/elearning/form/add') }}"><i class="fa fa-plus-circle"></i> {{ tr('buat elearning') }}</a> -->


                                        </div>
                                    @endif

                                </div>
                            </div>


                        </div>
                        <div class="col-xl-12 col-xxl-9">
                            <div class="course-details-tab style-2 mt-4">
                                <nav>
                                    <div class="nav nav-tabs tab-auto" id="nav-tab" role="tablist">
                                        <button class="nav-link {{ $tab == 1 ? 'active' : '' }}" id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about" type="button" role="tab" aria-controls="nav-about" aria-selected="true">{{ tr('penjelasan') }}</button>

                                        <button class="nav-link {{ $tab == 2 ? 'active' : '' }}" id="nav-views-tab" data-bs-toggle="tab" data-bs-target="#nav-views" type="button" role="tab" aria-controls="nav-views" aria-selected="false">{{ tr('viewer') }}</button>
                                        <button class="nav-link {{ $tab == 3 ? 'active' : '' }}" id="nav-discussion-tab" data-bs-toggle="tab" data-bs-target="#nav-discussion" type="button" onclick="load_discuss(0);" role="tab" aria-controls="nav-discussion" aria-selected="false">{{ tr('diskusi') }}</button>
                                        <button class="nav-link {{ $tab == 4 ? 'active' : '' }}" id="nav-quiz-tab" data-bs-toggle="tab" data-bs-target="#nav-quiz" type="button" role="tab" aria-controls="nav-quiz" aria-selected="false">{{ tr('kuis') }}</button>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade {{ $tab == 1 ? 'show active' : '' }}" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                        <div class="about-content">

                                            @if ($class_first)
                                                @if ($class_first->note)
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h6>{{ tr('catatan kelas dosen') }}</h6>
                                                            <p>{{ $class_first->note }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                            @php echo $data->description @endphp

                                            <div class="text-center w-100">
                                                @if ($data->file1 || $data->file1)
                                                    <br>
                                                    <h4 class="text-center">{{ tr('lampiran elearning') }}</h4>
                                                @endif

                                                @if ($data->file1)
                                                    <a href="{{ asset(DOC_PATH . $data->file1) }}" class="btn btn-primary m-3" download>{{ $data->file1 }} <span class="btn-icon-end"><i class="fa fa-download"></i></span>
                                                    </a>
                                                @endif
                                                @if ($data->file2)
                                                    <a href="{{ asset(DOC_PATH . $data->file2) }}" class="btn btn-primary m-3" download>{{ $data->file2 }} <span class="btn-icon-end"><i class="fa fa-download"></i></span>
                                                    </a>
                                                @endif

                                                @if ($data->file1 || $data->file1)
                                                    <br><br>
                                                @endif

                                            </div>

                                        </div>
                                    </div>

                                    <div class="tab-pane fade {{ $tab == 2 ? 'show active' : '' }}" id="nav-views" role="tabpanel" aria-labelledby="nav-views-tab">
                                        <div class="about-content">
                                            @if ($class_first)
                                                @php $percentage_p= $presence == 0 ? 0 : ($presence * 100) / count($viewer_data); @endphp
                                                @if (strtotime($class_first->start) < strtotime(date('Y-m-d H:i')))
                                                    <div class="row w-100">
                                                        <div class="col-9">
                                                            <div class="progress mt-2">
                                                                <div class="progress-bar {{ $percentage_p > 50 ? 'bg-success' : 'bg-danger' }}" style="width: {{ $percentage_p }}%; height:16px;" role="progressbar">

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-3">
                                                            <h5 class="{{ $percentage_p > 50 ? 'text-dark' : 'text-danger' }}">
                                                                <small>{{ tr('kehadiran') }}</small>
                                                                {{ $percentage_p }}%
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif


                                                <div class="row p-1 mt-3">
                                                    @foreach ($viewer_data as $item)
                                                        <div class="col-xxl-3 col-xl-6">
                                                            <div class="card text-center" style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;">
                                                                <div class="p-2 pb-3">
                                                                    <div class="cropcircle-lg" style="background-image: url({{ $item->colleger->avatar ? asset(AVATAR_PATH . $item->colleger->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $item->colleger->name) }});">
                                                                    </div>
                                                                    <h5 class="my-1">{{ $item->colleger->name }}
                                                                        <br><small>NIM. {{ $item->colleger->nim }}</small>
                                                                    </h5>
                                                                    @if ($item->status)
                                                                        <small><i class="fa fa-check-square text-success"></i>
                                                                            {{ ' ' . date_id($item->status, 5) }}</small>
                                                                    @else
                                                                        @if (strtotime($class_first->start) < strtotime(date('Y-m-d H:i')) && strtotime($class_first->end) > strtotime(date('Y-m-d H:i')))
                                                                            <small class="text-muted"><i>{{ tr('belum hadir') }}</i></small>
                                                                        @elseif(strtotime($class_first->end) < strtotime(date('Y-m-d H:i')))
                                                                            <small class="text-danger"><i class="fa fa-times-square"></i> {{ tr('tidak hadir') }}</small>
                                                                        @endif
                                                                    @endif



                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="w-100 text-center p-5" style="height:500px;">
                                                    <br>
                                                    <img src="{{ asset('images/art/empty3.png') }}" height="100" alt="">
                                                    <br>
                                                    <i class="text-center">{{ tr('pilih kelas terlebih dahulu') }}</i>
                                                </div>
                                            @endif
                                        </div>


                                    </div>
                                    <div class="tab-pane fade {{ $tab == 3 ? 'show active' : '' }}" id="nav-discussion" role="tabpanel" aria-labelledby="nav-discussion-tab">
                                        <div class="about-content">


                                            @if ($class_id != '')
                                                <div class="d-none" id="displaydiscuss" style="height:500px; overflow-y:auto; width: 100%; overflow-x: hidden">
                                                    <div class="row" style="width:100%;">

                                                    </div>
                                                </div>
                                                <button class="btn btn-success btn-xs my-5 d-none" id="btndiscuss" onclick="comments()"><i class="fa fa-comment"></i> {{ tr('kirim komentar') }}</button>


                                                <div class="w-100 text-center p-5" id="loaddiscuss" style="height:500px;">
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <div class="mt-5">
                                                        <div class="spinner-border" role="status">
                                                            <span class="visually-hidden">{{ tr('loading...') }}</span>
                                                        </div>
                                                        <br>
                                                        <small>{{ tr('loading diskusi..') }}</small>
                                                    </div>
                                                </div>

                                                <div class="w-100 text-center p-5 d-none" id="nodiscuss" style="height:500px;">
                                                    <br>
                                                    <img src="{{ asset('images/art/empty1.png') }}" height="100" alt="">
                                                    <br>
                                                    <i class="text-center">{{ tr('belum ada diskusi di kelas sini') }}</i>
                                                    <br>
                                                    <button class="btn btn-success btn-xs mt-3 mb-5" onclick="comments()"><i class="fa fa-comment"></i> {{ tr('mulai diskusi') }}</button>

                                                </div>
                                            @else
                                                <div class="w-100 text-center p-5" style="height:500px;">
                                                    <br>
                                                    <img src="{{ asset('images/art/empty3.png') }}" height="100" alt="">
                                                    <br>
                                                    <i class="text-center">{{ tr('pilih kelas terlebih dahulu') }}</i>
                                                </div>
                                            @endif

                                            <div class="modal fade" id="comment">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="titledis">{{ tr('buat komentar elearning') }}</h5>

                                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                            </button>
                                                        </div>
                                                        <form action="{{ url('/dosen/elearning/discussion/send') }}" method="post" enctype="multipart/form-data" id="form_discuss">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="elearning_class_id" value="{{ $class_id }}">
                                                            <input type="hidden" name="elearning_id" value="{{ $data->id }}">
                                                            <input type="hidden" name="discussion_id" id="id_discuss" value="">
                                                            <input type="hidden" name="lecturer_id" value="{{ akun('dosen')->id }}">
                                                            <div class="modal-body">

                                                                <div class="row" id="main_discuss">

                                                                </div>

                                                                <div class="row">

                                                                    <div class="mb-3 col-md-6">
                                                                        <label class="form-label">{{ tr('gambar') }}</label>
                                                                        <input type="file" class="dropify" name="image" height="100" />
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label class="form-label">{{ tr('file') }}</label>
                                                                        <input type="file" class="dropify" name="file" height="100" />
                                                                    </div>
                                                                    <div class="mb-3 col-md-12">
                                                                        <label class="form-label">{{ tr('komentar') }}</label>
                                                                        <textarea name="comment" rows="4" class="form-control"></textarea>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                <button type="submit" class="btn btn-primary">
                                                                    <div class="spinner-border spinner-border-sm d-none" role="status" id="load_send">
                                                                        <span class="visually-hidden">{{ tr('loading...') }}</span>
                                                                    </div> {{ tr('kirim') }}
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>




                                        </div>
                                    </div>
                                    <div class="tab-pane fade {{ $tab == 4 ? 'show active' : '' }}" id="nav-quiz" role="tabpanel" aria-labelledby="nav-quiz-tab">
                                        <div class="about-content">
                                            <form action="{{ url('dosen/elearning/kuis/add') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="elearning_id" value="{{ $data->id }}">
                                                <input type="hidden" name="kelas" value="{{ $class_id }}">
                                                <div class="row mb-3">

                                                    <div class="col-8">

                                                        <select class="form-select form-select-lg" name="quiz_id" required>
                                                            <option value="">-- {{ tr('pilih kuis') }} --</option>
                                                            @foreach ($quiz_data as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <button class="btn  btn-outline-success mt-1" type="submit"><i class="fa fa-plus"></i>
                                                            {{ tr('tambah kuis') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                            @if (count($data->elearning_quiz) > 0)
                                                <div class="card pt-3" style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;">

                                                    <div class="table-responsive">
                                                        <table class="display table text-center table-borderless">

                                                            <tbody>
                                                                @php $i=1; @endphp
                                                                @foreach ($data->elearning_quiz as $item)
                                                                    <tr>
                                                                        <td width="10%" class="align-middle">
                                                                            {{ $i++ }}.</td>
                                                                        <td class="align-middle text-start" width="25%">
                                                                            <img src="{{ url(QUIZ_G . str_replace(' ', '_', $item->quiz->name)) }}" alt="" height="75">
                                                                        </td>
                                                                        <td class="align-middle text-start">
                                                                            <h5 class="text-start">
                                                                                {{ $item->quiz->name }}
                                                                            </h5>
                                                                        </td>

                                                                        <td class="align-middle">
                                                                            <a href="#delete-quiz-{{ $item->id }}" data-bs-toggle="modal" class="btn btn-danger btn-xs float-end me-3"><i class="fa fa-trash"></i> {{ tr('delete') }}</a>
                                                                            <div class="modal fade" id="delete-quiz-{{ $item->id }}">
                                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title text-danger">
                                                                                                {{ tr('peringatan') }} !!</h5>
                                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                                            </button>
                                                                                        </div>

                                                                                        <div class="modal-body">
                                                                                            <p>{{ tr('apakah anda ingin menghapus kuis') }}<b>{{ $item->quiz->name }}</b>{{ tr('dari elearning') }}<b>{{ $data->name }}</b>

                                                                                            </p>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                                            <a href="{{ url('dosen/elearning/kuis/delete?id=' . $item->id . '&kelas=' . $class_id) }}" class="btn btn-primary">{{ tr('hapus') }}</a>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>

                                                </div>
                                            @else
                                                <div class="w-100 text-center p-5">
                                                    <br>
                                                    <img src="{{ asset('images/art/empty1.png') }}" height="100" alt="">
                                                    <br>
                                                    <i class="text-center">{{ tr('belum ada kuis disini') }}</i>
                                                </div>
                                            @endif
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
@endsection

@section('script')
    <script>
        var class_id = "{{ $class_id }}";
        var last = 0;
        $(document).ready(function() {
            $('#data-table-1').DataTable({
                createdRow: function(row, data, index) {
                    $(row).addClass('selected')
                },
                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    },
                    info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
                }
            });

            $(document).on('submit', '#form_discuss', function(event) {
                event.preventDefault(); // avoid to execute the actual submit of the form.

                var formData = new FormData(this);
                var actionUrl = $(this).attr('action');
                $('#load_send').removeClass('d-none');
                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: formData, // serializes the form's elements.
                    success: function(data) {
                        if (data.message == "success") {
                            if ($('#id_discuss').val() == "") {
                                show_toast(1, "{{ tr('komentar berhasil dikirim') }}");
                                load_discuss(1);
                            } else {
                                show_toast(1, "{{ tr('balasan komentar berhasil dikirim') }}");
                                load_discuss(2);
                            }


                            $(':input', '#form_discuss')
                                .not(':button, :submit, :reset, :hidden')
                                .val('');

                            $(':input', '#form_discuss').parent().find(".dropify-clear")
                                .trigger('click');
                            $('#comment').modal('hide');

                        } else {
                            if ($('#id_discuss').val() == "") {
                                show_toast(1, "{{ tr('komentar gagal dikirim') }}");
                            } else {
                                show_toast(1, "{{ tr('balasan gagal berhasil dikirim') }}");
                            }
                        }
                        $('#load_send').addClass('d-none');



                    },
                    error: function(request, status, error) {
                        console.error(request.responseText);
                        //alert(request.responseText);
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

                //return false;

            });

            if (class_id != "") {
                load_discuss(0);
                setInterval(function() {
                    load_discuss(3);
                }, 10000);
            }

        });

        function reply(id, name) {
            $('#id_discuss').val(id);
            $('#titledis').html("{{ tr('balas komentar') }} " + name);
            $('#comment').modal('show');
        }

        function comments() {
            $('#id_discuss').val("");
            $('#titledis').html("{{ tr('buat komentar elearning') }}");
            $('#comment').modal('show');
        }

        function load_discuss(mode) {

            var loading_ = $('#loaddiscuss');
            var display_ = $('#displaydiscuss');
            var nodata_ = $('#nodiscuss');
            var btn_ = $('#btndiscuss');

            if (mode == 0) {
                loading_.removeClass('d-none');
                display_.addClass('d-none');
                btn_.addClass('d-none');
                nodata_.addClass('d-none');
            }

            $.ajax({
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    _class_id: class_id,
                },
                url: "{{ url('dosen/elearning/ajax/discussion/list') }}",
                success: function(data) {
                    console.log(data);
                    var obj = data.result.discussion;
                    display_.find(".row").empty();

                    for (let i = 0; i < obj.length; i++) {
                        const item = obj[i];

                        var ava = "";
                        if (item.status == 1) {
                            ava = `<div class="cropcircle text-center pt-1"
                                    style="background-image: url(${item.avatar});">
                                    <br>
                                    <span
                                        class="badge badge-primary badge-xs mt-3">{{ tr('dosen') }}
                                        <br></span>
                                </div>`
                        } else {
                            ava = `<div class="cropcircle"
                                    style="background-image: url(${item.avatar});">
                                </div>`
                        }

                        var sub = ``;
                        if (item.sub.length > 0) {
                            for (let j = 0; j < item.sub.length; j++) {
                                const subitem = item.sub[j];
                                if (j == 0) {
                                    sub += `<tr><td></td><td colspan="2">`;

                                }
                                var ava2 = "";
                                if (subitem.status == 1) {
                                    ava2 = `<div class="cropcircle text-center pt-1"
                                                style="background-image: url(${subitem.avatar});">
                                                <br>
                                                <span
                                                    class="badge badge-primary badge-xs mt-3">{{ tr('dosen') }}
                                                    <br></span>
                                            </div>`;
                                } else {
                                    ava2 = `<div class="cropcircle"
                                                style="background-image: url(${subitem.avatar});">
                                            </div>`;
                                }

                                sub += `<div class="table-responsive">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td width="15%" rowspan="2"
                                                        class="align-top">
                                                        ${ava2}
                                                    </td>
                                                    <td width="65%"
                                                        class="align-middle p-0 m-0">
                                                        <b class=""
                                                            style="font-size: 16px; margin-top:50%;">
                                                            ${subitem.name}
                                                        </b>
                                                    </td>
                                                    <td width="20%"
                                                        class="align-middle px-2 m-0">
                                                        <small class="float-end">
                                                            ${subitem.time}
                                                        </small>
                                                    </td>
                                                </tr>
                                                
                                                <tr>

                                                    <td class="align-top p-0 m-0"
                                                        colspan="2">
                                                        ${subitem.image}
                                                        <p>${subitem.comment}
                                                        </p>
                                                        ${subitem.file}
                                                    </td>

                                                </tr>
                                                

                                            </table>

                                        </div>`;
                                if (j == (item.sub.length - 1)) {

                                    sub += `</td></tr>`;
                                }
                            }
                        }

                        var reply = ``;
                        if (!data.result.passed) {
                            reply = `<tr>
                                        <td></td>
                                        <td class="align-top pt-2 px-0 pb-0 m-0"
                                            colspan="2">
                                            <button class="btn btn-danger btn-xs" onclick="reply(${item.id},'${item.status==1?"{{ tr('dosen') }}":item.name}')"><i class="fa fa-reply-all"></i> {{ tr('balas') }}</button>
                                        </td>

                                    </tr>`;
                        }

                        var content =
                            ` <div class="col-12">
                                <div class="card">
                                    <div class="p-1">
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td width="15%" rowspan="2"
                                                        class="align-top">
                                                        ${ava}
                                                    </td>
                                                    <td width="65%"
                                                        class="align-middle p-0 m-0">
                                                        <b class=""
                                                            style="font-size: 16px; margin-top:50%;">
                                                            ${item.name}
                                                        </b>
                                                    </td>
                                                    <td width="20%"
                                                        class="align-middle px-2 m-0">
                                                        <small class="float-end">
                                                            ${item.time}
                                                        </small>
                                                    </td>
                                                </tr>
                                                
                                                <tr>

                                                    <td class="align-top p-0 m-0"
                                                        colspan="2">
                                                        ${item.image}
                                                        <p>${item.comment}
                                                        </p>
                                                        ${item.file}
                                                    </td>

                                                </tr>
                                                ${sub}
                                                ${reply}
                                               
                                                
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>`;

                        display_.find(".row").append(content);

                    }


                    loading_.addClass('d-none');

                    if (obj.length > 0) {
                        display_.removeClass('d-none');
                        if (data.result.passed) {
                            btn_.addClass('d-none');
                        } else {
                            btn_.removeClass('d-none');
                        }
                        nodata_.addClass('d-none');
                        //show_toast(0, last + " - " + obj.length);
                        if (mode == 1 || mode == 2 || (mode == 3 && last != obj.length)) {
                            var audio = new Audio("{{ asset('audio/notif.mp3') }}");
                            audio.play();
                        }
                        if (mode < 2) {
                            setTimeout(function() {
                                display_.scrollTop(display_[0].scrollHeight + 500);
                            }, 500);
                        }

                        last = obj.length;
                    } else {
                        display_.addClass('d-none');
                        nodata_.removeClass('d-none');
                        btn_.addClass('d-none');
                    }

                },
                error: function(request, status, error) {
                    console.error(request.responseText);
                    //alert(request.responseText);
                }
            });
        }

        function change_class() {
            var class_id = $('#class_').val();
            window.location.href = "{{ url('dosen/elearning/detail?id=' . $data->id) }}&tab=4&kelas=" + class_id;
        }
    </script>
@endsection
