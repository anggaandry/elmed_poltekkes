@extends('dosen/master')

@section('title', 'Ujian')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dosen/ujian') }}">{{ tr('ujian') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('detail ujian') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-5">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div
                            style=" background-image: url('{{ asset(EXAM_G . $data->name) }}');
                                    border: 1px solid #eee;
                                   background-position:center;
                                    background-size:cover;
                                    border-radius: 25px;
                                    width: 100%;
                                    height: 200px;">
                        </div>

                        <div class="mt-3">
                            <table class="table">
                                <tr>
                                    <th>{{ tr('mata kuliah') }}</th>
                                    <td><span class="float-end">{{ $data->sks->subject->name }}</span></td>
                                </tr>
                            </table>

                            <table class="table">
                                <tr>
                                    <th>{{ tr('kelas peserta') }}</th>

                                    <td colspan="2">
                                        <a class="btn btn-rounded btn-success btn-xs ms-1 float-end" data-bs-toggle="modal" href="#add-class"><i class="fa fa-plus-circle"></i> {{ tr('tambah') }}</a>

                                        <div class="modal fade" id="add-class">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ tr('tambah kelas untuk ujian') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                        </button>
                                                    </div>
                                                    <form action="{{ url('/dosen/ujian/kelas/add') }}" method="post">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="exam_id" value="{{ $data->id }}">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="mb-3 col-md-12">
                                                                    <label class="form-label">{{ tr('kelas') }}</label>
                                                                    <select class="form-select form-select-lg" name="class_id" required>
                                                                        <option value="">-- {{ tr('pilih kelas') }} -- --</option>

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
                                    </td>
                                </tr>


                            </table>
                            @if (count($data->exam_class) > 0)
                                @php $i=1;@endphp
                                @foreach ($data->exam_class as $item)
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="5%" @if (strtotime($class_first->end) <= strtotime(date('Y-m-d H:i'))) rowspan="2" @endif class="align-middle {{ $class_id == $item->id ? 'bg-info' : '' }}">
                                                <a class="{{ $class_id == $item->id ? 'text-white' : '' }}" href="{{ url('dosen/ujian/detail?id=' . $data->id . '&kelas=' . $item->id) }}">{{ $i++ }}</a>
                                            </th>
                                            <td>
                                                <a class="" href="{{ url('dosen/ujian/detail?id=' . $data->id . '&kelas=' . $item->id) }}">{{ $item->class->name }}
                                                    <br>
                                                    <small>{{ date_id($item->start, 5) }} -
                                                        {{ date_id($item->end, 5) }}</small>
                                                </a>
                                            </td>
                                            <td width="10%">
                                                <a class="btn btn-rounded btn-primary btn-xs " data-bs-toggle="modal" href="#edit-class-{{ $item->id }}"><i class="fa fa-edit"></i>
                                                </a>
                                                <br>
                                                <a class="btn btn-rounded btn-danger btn-xs mt-1" data-bs-toggle="modal" href="#delete-class-{{ $item->id }}"><i class="fa fa-trash"></i>
                                                </a>

                                                <div class="modal fade" id="edit-class-{{ $item->id }}">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{ tr('edit kelas untuk ujian') }}</h5>

                                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                </button>
                                                            </div>

                                                            <form action="{{ url('/dosen/ujian/kelas/edit') }}" method="post">
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
                                                                <h5 class="modal-title text-danger">{{ tr('Peringatan') }} !!</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <p>{{ tr('apakah anda ingin menghapus kelas') }}<b>{{ $item->class->name }}
                                                                        {{ tr('ujian') }}
                                                                    </b>
                                                                    {{ tr('dari ujian') }}
                                                                    <b>{{ $data->name }}</b>
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                <a href="{{ url('dosen/ujian/kelas/delete/' . $item->id) }}" class="btn btn-primary">{{ tr('hapus') }}</a>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        @if (strtotime($class_first->end) <= strtotime(date('Y-m-d H:i')))
                                            <tr>

                                                <td colspan="2">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" {{ $item->publish == 1 ? 'checked' : '' }} onchange="publish({{ $item->id }})" id="fs{{ $item->id }}">
                                                        <label class="form-check-label" for="fs{{ $item->id }}">{{ tr('publikasi nilai') }}</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                @endforeach
                            @else
                                <div class="mt-5 text-center">
                                    <br>
                                    <img src="{{ asset('images/art/empty3.png') }}" height="50" alt="">
                                    <br>
                                    <br>
                                    <i class="mt-3">{{ tr('kelas untuk ujian ini belum ada') }}</i>
                                    <!-- <a class="btn btn-primary btn-xs mb-3 mt-3" href="{{ url('dosen/elearning/form/add') }}"><i class="fa fa-plus-circle"></i> {{ tr('buat elearning') }}</a> -->


                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card  course-dedails-bx">
                <div class="card-header border-0 pb-0">
                    <div class="row" style="width:110%;">
                        <div class="col-8">
                            <h2>{{ $data->name }}</h2>
                        </div>
                        <div class="col-4">
                            <div class="float-end">
                                <button class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#edit"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#delete"><i class="fa fa-trash"></i></button>
                            </div>
                            <div class="modal fade" id="edit">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ tr('edit ujian') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                            </button>
                                        </div>
                                        <form action="{{ url('/dosen/ujian/edit') }}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{ $data->id }}">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">{{ tr('mata kuliah') }}</label>
                                                        <select class="form-select form-select-lg" name="sks_id" required>
                                                            <option value="">-- {{ tr('pilih mata kuliah') }} --</option>
                                                            @foreach ($subject_data as $item)
                                                                <option value="{{ $item->id }}" @if ($data->sks_id == $item->id) selected @endif>
                                                                    {{ $item->subject->name }} ({{ tr('prodi') }}
                                                                    {{ $item->prodi->program->name }}
                                                                    {{ $item->prodi->study_program->name }} -
                                                                    {{ $item->prodi->category->name }},
                                                                    {{ tr('semester') }} {{ $item->semester }}, {{ $item->value }}
                                                                    {{ tr('sks') }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">{{ tr('judul ujian') }}</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $data->name }}" required>
                                                    </div>


                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">{{ tr('deskripsi') }}</label>
                                                        <textarea name="description" class="form-control" required>{{ $data->description }}</textarea>
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

                            <div class="modal fade" id="delete">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger">{{ tr('peringatan') }} !!</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <p>{{ tr('apakah anda ingin menghapus ujian') }}<b>{{ $data->name }}</b>
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                            <a href="{{ url('dosen/ujian/delete/' . $data->id) }}" class="btn btn-primary">{{ tr('hapus') }}</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body pt-0">
                    <div class="description">
                        <p>{{ $data->description }}</p>
                        <ul class="d-flex align-items-center raiting flex-wrap">
                            @if ($class_first)
                                <li class="text-info">
                                    <i class="fa fa-check-circle"></i> {{ $class_first->class->name }}
                                </li>
                            @endif
                            <li>{{ count($data->exam_question) }} {{ tr('soal') }}</li>
                            @if ($class_first)
                                <li>{{ count($cc) }} {{ tr('mahasiswa') }}</li>
                            @endif
                        </ul>
                        <div class=" mb-3">
                            <table>
                                <tr>
                                    <td>
                                        <div class="cropcircle" style="background-image: url({{ $data->lecturer->avatar ? asset(AVATAR_PATH . $data->lecturer->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $data->lecturer->name) }});">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bolder ms-3">{{ title_lecturer($data->lecturer) }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="course-details-tab style-2">
                        <nav>
                            <div class="nav nav-tabs justify-content-start tab-auto" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-question-tab" data-bs-toggle="tab" data-bs-target="#nav-question" type="button" role="tab" aria-controls="nav-question" aria-selected="true">{{ tr('soal') }}</button>
                                <button class="nav-link " id="nav-participant-tab" data-bs-toggle="tab" data-bs-target="#nav-participant" type="button" role="tab" aria-controls="nav-participant" aria-selected="false">{{ tr('peserta') }}</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-question" role="tabpanel" aria-labelledby="nav-question-tab">
                                <div class="about-content">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="float-end mb-3">
                                                <a class="btn btn-success btn-xs" href="{{ url('dosen/ujian/soal/form/add?id=' . $data->id . '&kelas=' . $class_id) }}">
                                                    <i class="fa fa-plus-circle"></i> {{ tr('buat soal') }}</a>

                                                <button class="btn btn-info btn-xs" data-bs-target="#bank" data-bs-toggle="modal"><i class="fa fa-database"></i> {{ tr('ambil dari bank soal') }}</button>

                                            </div>

                                            <div class="modal fade" id="bank">
                                                <div class="modal-dialog  modal-fullscreen" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ tr('ambil dari bank soal') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="table-responsive">
                                                                <table id="data-table-bank" class="display text-center table-striped">
                                                                    <thead class="">
                                                                        <tr>
                                                                            <th width="5%">#</th>
                                                                            <th class="text-start">{{ tr('soal') }}</th>

                                                                            <th width="20%">{{ tr('aksi') }}</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            @if (count($data->exam_question) > 0)

                                                <div class="table-responsive">
                                                    <table class="display table table-bordered">
                                                        <tbody>
                                                            @foreach ($data->exam_question as $item)
                                                                <tr>
                                                                    <th width="5%">{{ $item->sort }}.</th>
                                                                    <td class="align-top">

                                                                        @php echo $item->question->question @endphp
                                                                        @if ($item->question->choice)
                                                                            @php $options=json_decode($item->question->choice,false); @endphp
                                                                            <table class="table table-borderless">
                                                                                @foreach ($options as $sub)
                                                                                    <tr>
                                                                                        <th width="5%" class="p-0 m-0">
                                                                                            <p>{{ $sub->choice }}</p>
                                                                                        </th>
                                                                                        <td class="p-0 m-0">
                                                                                            @php echo $sub->desc @endphp
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </table>
                                                                        @endif


                                                                        @if ($item->question->file)
                                                                            <b>{{ tr('file') }} :</b><a class="text-info" href="{{ asset(DOC_PATH . $item->question->file) }}" download>
                                                                                {{ $item->question->file }} </a><br>
                                                                        @endif

                                                                        @switch($item->question->type)
                                                                            @case(0)
                                                                                <br><span class="badge badge-info">{{ tr('essay') }}</span>
                                                                            @break

                                                                            @case(1)
                                                                                <br><span class="badge badge-success">{{ tr('pilihan berganda') }}</span>
                                                                            @break

                                                                            @case(2)
                                                                                <br><span class="badge badge-danger">{{ tr('upload file') }}</span>
                                                                            @break

                                                                            @default
                                                                        @endswitch

                                                                    </td>
                                                                    <td width="10%" class="align-top">
                                                                        <button class="btn btn-warning btn-xs" data-bs-toggle="modal" data-bs-target="#detail-question-{{ $item->id }}"><i class="fa fa-eye"></i></button>
                                                                        <a class="btn btn-primary btn-xs mt-1" href="{{ url('dosen/ujian/soal/form/edit?id=' . $item->id . '&kelas=' . $class_id) }}"><i class="fa fa-edit"></i></a>
                                                                        <button class="btn btn-danger btn-xs mt-1" data-bs-toggle="modal" data-bs-target="#delete-question-{{ $item->id }}"><i class="fa fa-trash"></i></button>
                                                                        <div class="modal fade" id="detail-question-{{ $item->id }}">
                                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title">{{ tr('detail soal') }}</h5>

                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                                        </button>
                                                                                    </div>

                                                                                    <div class="modal-body">
                                                                                        <div class="table-responsive">
                                                                                            <table class="table">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <th>{{ tr('jenis soal') }}</th>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            @switch($item->question->type)
                                                                                                                @case(0)
                                                                                                                    {{ tr('essay') }}
                                                                                                                @break

                                                                                                                @case(1)
                                                                                                                    {{ tr('pilihan berganda') }}
                                                                                                                @break

                                                                                                                @case(1)
                                                                                                                    {{ tr('upload file') }}
                                                                                                                @break

                                                                                                                @default
                                                                                                            @endswitch
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <th>{{ tr('soal') }}</th>

                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>

                                                                                                            @php echo $item->question->question @endphp
                                                                                                            @if ($item->question->choice)
                                                                                                                @php $options=json_decode($item->question->choice,false); @endphp
                                                                                                                <table class="table table-borderless">
                                                                                                                    @foreach ($options as $sub)
                                                                                                                        <tr>
                                                                                                                            <th width="5%" class="p-0 m-0">
                                                                                                                                <p>{{ $sub->choice }}
                                                                                                                                </p>
                                                                                                                            </th>
                                                                                                                            <td class="p-0 m-0">
                                                                                                                                @php echo $sub->desc @endphp
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    @endforeach
                                                                                                                </table>
                                                                                                            @endif


                                                                                                            @if ($item->question->file)
                                                                                                                <b>File
                                                                                                                    :</b><a class="text-info" href="{{ asset(DOC_PATH . $item->question->file) }}" download>
                                                                                                                    {{ $item->question->file }}
                                                                                                                </a><br>
                                                                                                            @endif



                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <th>{{ tr('referensi jawaban') }}</th>


                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            @if ($item->question->type == 1)
                                                                                                                <b>{{ tr('JAWABAN') }} :
                                                                                                                    {{ $item->question->choice_answer }}</b><br>
                                                                                                            @endif
                                                                                                            @php echo $item->question->answer @endphp
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>


                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>

                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal fade" id="delete-question-{{ $item->id }}">
                                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title text-danger">{{ tr('peringatan') }} !!</h5>

                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                                        </button>
                                                                                    </div>

                                                                                    <div class="modal-body">
                                                                                        <p>{{ tr('Apakah anda ingin menghapus soal no') }}
                                                                                            {{ $item->sort }} {{ tr('ujian') }}
                                                                                            <b>{{ $data->name }}</b>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                                        <a href="{{ url('dosen/ujian/soal/delete/' . $item->id) }}" class="btn btn-primary">{{ tr('hapus') }}</a>
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
                                            @else
                                                <div class="pt-5 text-center" style="height:400px;">
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <img src="{{ asset('images/art/empty3.png') }}" height="50" class="mt-5" alt="">
                                                    <br>
                                                    <br>
                                                    <i class="mt-3">{{ tr('soal untuk ujian ini belum ada') }}</i>
                                                    <!-- <a class="btn btn-primary btn-xs mb-3 mt-3" href="{{ url('dosen/elearning/form/add') }}"><i class="fa fa-plus-circle"></i> {{ tr('buat elearning') }}</a> -->
                                                    <br>
                                                    <br>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade " id="nav-participant" role="tabpanel" aria-labelledby="nav-participant-tab">
                                <div class="about-content">

                                    @if ($class_id != '')
                                        <div class="row" class="d-none" id="displaycc" style="width:100%;">

                                        </div>

                                        <div class="w-100 text-center p-5" id="loadcc" style="height:500px;">
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
                                                <small>{{ tr('loading peserta..') }}</small>
                                            </div>
                                        </div>

                                        <div class="w-100 text-center p-5 d-none" id="nocc" style="height:500px;">
                                            <br>
                                            <img src="{{ asset('images/art/empty1.png') }}" height="100" alt="">
                                            <br>
                                            <i class="text-center">{{ tr('tidak ada siswa di kelas ini') }}</i>
                                            <br>

                                        </div>
                                    @else
                                        <div class="w-100 text-center p-5" style="height:500px;">
                                            <br>
                                            <img src="{{ asset('images/art/empty3.png') }}" height="100" alt="">
                                            <br>
                                            <i class="text-center">{{ tr('tidak ada kelas yang dipilih') }}</i>
                                        </div>
                                    @endif

                                    <div class="modal fade" id="correction_modal">
                                        <div class="modal-dialog modal-fullscreen" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ tr('koreksi jawaban siswa') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <th>{{ tr('nama mahasiswa') }}</th>
                                                                    <td id="name_c"></td>
                                                                    <th>{{ tr('nim mahasiswa') }}</th>
                                                                    <td id="nim_c"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>{{ tr('mulai mengerjakan') }}</th>
                                                                    <td id="start_c" colspan="3"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class=" bg-info-light">
                                                                <tr>
                                                                    <th width="5%">#</th>
                                                                    <th class="text-start">{{ tr('soal') }}</th>
                                                                    <th class="text-center" width="5%">{{ tr('bobot') }}</th>
                                                                    <th class="text-center" width="25%">{{ tr('referensi jawaban') }}</th>
                                                                    <th class="text-center" width="25%">{{ tr('jawaban mahasiswa') }}</th>
                                                                    <th width="10%" class="text-center">% {{ tr('skor') }}</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($data->exam_question as $item)
                                                                    <tr>
                                                                        <th width="5%">{{ $item->sort }}.
                                                                        </th>
                                                                        <td class="align-top">

                                                                            @php echo $item->question->question @endphp
                                                                            @if ($item->question->choice)
                                                                                @php $options=json_decode($item->question->choice,false); @endphp
                                                                                <table class="table table-borderless">
                                                                                    @foreach ($options as $sub)
                                                                                        <tr>
                                                                                            <th width="5%" class="p-0 m-0">
                                                                                                <p>{{ $sub->choice }}
                                                                                                </p>
                                                                                            </th>
                                                                                            <td class="p-0 m-0">
                                                                                                @php echo $sub->desc @endphp
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </table>
                                                                            @endif


                                                                            @if ($item->question->file)
                                                                                <b>{{ tr('file') }} :</b><a class="text-info" href="{{ asset(DOC_PATH . $item->question->file) }}" download>
                                                                                    {{ $item->question->file }}
                                                                                </a><br>
                                                                            @endif

                                                                            @switch($item->question->type)
                                                                                @case(0)
                                                                                    <br><span class="badge badge-info">{{ tr('essay') }}</span>
                                                                                @break

                                                                                @case(1)
                                                                                    <br><span class="badge badge-success">{{ tr('pilihan berganda') }}</span>
                                                                                @break

                                                                                @case(2)
                                                                                    <br><span class="badge badge-danger">{{ tr('upload file') }}</span>
                                                                                @break

                                                                                @default
                                                                            @endswitch

                                                                        </td>
                                                                        <td class="align-top text-center">
                                                                            {{ $item->value }}
                                                                        </td>
                                                                        <td class="text-center align-top" width="25%">
                                                                            @php echo $item->question->answer @endphp
                                                                        </td>
                                                                        <td class="text-center  align-top" width="25%" id="answer_{{ $item->id }}">
                                                                        </td>
                                                                        <td width="10%" class="align-top">
                                                                            <input type="hidden" id="id_answer_{{ $item->id }}">
                                                                            <input type="number" class="form-control text-center" id="score_{{ $item->id }}" oninput="scoring({{ $item->id }})">
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>

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
            </div>

        </div>

    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#data-table-1').DataTable({
                createdRow: function(row, data, index) {
                    $(row).addClass('selected')
                },
                searching: false,
                ordering: false,
                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    },
                    info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b> | Records _START_ to _END_ of _MAX_ entries",
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            load_table();
            @if ($class_id !== '')
                load_class();
            @endif
        });

        function load_table() {
            var lecturer_id = "{{ akun('dosen')->id }}";
            var sks_id = "{{ $data->sks_id }}";
            var exam_id = "{{ $data->id }}";

            var table = $('#data-table-bank').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, 'All'],
                ],
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/dosen/ujian/ajax/soal') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        lecturer_id: lecturer_id,
                        sks_id: sks_id,
                        exam_id: exam_id,
                    },
                    async: true,
                    error: function(xhr, error, code) {
                        console.log(xhr);
                        console.log(code);
                    }
                },
                destroy: true,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        class: "text-start",
                        data: 'full_question',
                        name: 'full_question',
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }

                ],
                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    },
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">{{ tr('loading...') }}</span></div></div>',
                    info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
                },

            });
        }
    </script>
    <script>
        function load_class(mode) {

            var loading_ = $('#loadcc');
            var display_ = $('#displaycc');
            var nodata_ = $('#nocc');

            if (mode == 0) {
                loading_.removeClass('d-none');
                display_.addClass('d-none');
                nodata_.addClass('d-none');
            }

            $.ajax({
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    qc_id: {{ $class_id }},
                },
                url: "{{ url('dosen/ujian/ajax/class') }}",
                success: function(data) {
                    console.log(data);
                    var obj = data.result.data;
                    var vl = data.result.total_value;
                    display_.empty();

                    for (let i = 0; i < obj.length; i++) {
                        const item = obj[i];

                        var abs = ``;
                        if (item.passed) {
                            var an = `<small><i class="text-danger">{{ tr('tidak hadir') }}</i></small>`;
                            if (item.absence) {
                                an = ` <small><i class="fa fa-clock"></i> Hadir ${item.absence.time}</small>`;
                            }

                            var btn = `<a href="javascript:void(0)"
                                            onclick="show_correction(${item.colleger.id})">
                                            <span class="badge badge-danger">
                                                <i class="fa fa-pencil"></i>
                                                0/${item.value}
                                            </span>

                                        </a>`;

                            if (item.score) {
                                btn = `<a href="javascript:void(0)"
                                            onclick="show_correction(${item.colleger.id})">
                                            <span class="badge badge-success">
                                                <i class="fa fa-pencil"></i>
                                                ${item.score}/${item.value}
                                            </span>

                                        </a>`;
                            }

                            abs = `<tr>
                                    <td class="p-0 m-0">
                                        ${an}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0 m-0"
                                        id="correction_${item.colleger.id}">
                                        ${btn}
                                    </td>
                                </tr>`;

                        }



                        var content =
                            `<div class="col-xl-12 col-xxl-6">
                            <div class="card" 
                            style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;">
                                <div class="p-1">
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="20%" rowspan="4" class="pe-4">
                                                    <div class="cropcircle"
                                                        style="background-image: url(${item.avatar});">
                                                    </div>
                                                </td>
                                                <td class="p-0 m-0">
                                                    <b>${item.colleger.name}</b>
                                                </td>
                                            </tr>
                                            ${abs}
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                        display_.append(content);

                    }

                    loading_.addClass('d-none');

                    if (obj.length > 0) {
                        display_.removeClass('d-none');
                        nodata_.addClass('d-none');
                    } else {
                        display_.addClass('d-none');
                        nodata_.removeClass('d-none');
                    }

                },
                error: function(request, status, error) {
                    console.error(request.responseText);
                    //alert(request.responseText);
                }
            });
        }
    </script>
    <script>
        function show_correction(id) {
            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    qc_id: "{{ $class_id }}",
                    exam_id: "{{ $data->id }}"
                },
                url: "{{ url('dosen/ujian/ajax/correction') }}",
                success: function(data) {
                    console.log(data);
                    if (data.message == "success") {
                        var el = data.result;
                        $('#name_c').html(data.result.colleger.name);
                        $('#nim_c').html(data.result.colleger.nim);
                        $('#start_c').html(data.result.absence);

                        for (let i = 0; i < data.result.answer.length; i++) {
                            const el = data.result.answer[i];
                            if (el.id == "") {
                                $('#answer_' + el.question_id).html("");
                                $('#score_' + el.question_id).val("0");
                                $('#id_answer_' + el.question_id).val("");
                                $("#score_" + el.question_id).prop("disabled", true);
                            } else {
                                $('#id_answer_' + el.question_id).val(el.id);
                                $('#answer_' + el.question_id).html(el.answer);
                                $('#score_' + el.question_id).val(el.score);
                                $("#score_" + el.question_id).prop("disabled", false)
                            }

                        }

                        $('#correction_modal').modal('show');
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

        let timer;

        function scoring(id) {
            clearTimeout(timer);
            timer = setTimeout(() => {
                upload_score(id);
            }, 500);
        }

        function upload_score(id) {
            var id_ = $('#id_answer_' + id).val();

            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id_,
                    score: $('#score_' + id).val(),
                },
                url: "{{ url('dosen/ujian/scoring') }}",
                success: function(data) {
                    console.log(data);
                    if (data.code == 1) {
                        //$("#btn" + id).attr('disabled', false);
                        load_class(1);
                        show_toast(1, data.message);
                    } else {
                        show_toast(0, data.message);
                    }
                },
                error: function(request, status, error) {
                    console.error(request.responseText);
                    //alert(request.responseText);
                }
            });
        }

        function publish(id) {

            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    value: $('#fs' + id).is(":checked") ? 1 : 0,
                },
                url: "{{ url('dosen/ujian/publish') }}",
                success: function(data) {
                    console.log(data);
                    if (data.code == 1) {
                        //$("#btn" + id).attr('disabled', false);
                        show_toast(1, data.message);
                    } else {
                        show_toast(0, data.message);
                    }
                },
                error: function(request, status, error) {
                    console.error(request.responseText);
                    //alert(request.responseText);
                }
            });
        }
    </script>
@endsection
