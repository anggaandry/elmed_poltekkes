@extends('admin/master')

@section('title', 'Ujian')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">LMS</a></li>
            <li class="breadcrumb-item"><a href="{{ url('4dm1n/ujian') }}">Ujian</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Detail Ujian</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div
                            style=" background-image: url('{{ asset(EXAM_G . str_replace(' ', '_', $data->name)) }}');
                                    border: 1px solid #eee;
                                    background-position: center;
                                    background-size:cover;
                                    border-radius: 25px;
                                    width: 100%;
                                    height: 200px;">
                        </div>

                        <div class="mt-3">
                            <table class="table">
                                <tr>
                                    <th>Mata kuliah</th>
                                    <td>{{ $data->sks->subject->name }}</td>
                                </tr>
                            </table>

                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2">Kelas peserta</th>
                                </tr>
                                @php $i=1;@endphp
                                @foreach ($data->exam_class as $item)
                                    <tr>
                                        <th rowspan="2">{{ $i++ }}</th>
                                        <td>{{ $item->class->name }} - {{ $item->class->class_colleger }} peserta<br>
                                            <small>{{ date_id($item->start, 5) }} -
                                                {{ date_id($item->end, 5) }}</small>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td>
                                            @if (strtotime($item->start) <= strtotime(date('Y-m-d h:i')) &&
                                                strtotime($item->end) >= strtotime(date('Y-m-d h:i')))
                                                <span class="badge badge-success"> sedang aktif</span>
                                            @elseif(strtotime($item->start) >= strtotime(date('Y-m-d h:i')))
                                                <span class="badge badge-default">belum mulai</span>
                                            @elseif(strtotime($item->end) <= strtotime(date('Y-m-d h:i')))
                                                @if ($item->publish == 1)
                                                    <span class="badge badge-info">nilai publish</span>
                                                @else
                                                    <span class="badge badge-danger">sedang dikoreksi</span>
                                                @endif
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </table>


                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card  course-dedails-bx">
                <div class="card-header border-0 pb-0">
                    <h2>{{ $data->name }}</h2>
                </div>
                <div class="card-body pt-0">
                    <div class="description">
                        <p>{{ $data->description }}</p>
                        <ul class="d-flex align-items-center raiting flex-wrap">
                            <li>{{ count($data->exam_question) }} soal</li>
                            <li>{{ count($data->exam_class) }} kelas</li>
                        </ul>
                        <div class="mb-3">
                            <table>
                                <tr>
                                    <td>
                                        <div class="cropcircle"
                                            style="background-image: url({{ $data->lecturer->avatar ? asset(AVATAR_PATH . $data->lecturer->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $data->lecturer->name) }});">
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
                                <button class="nav-link active" id="nav-question-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-question" type="button" role="tab"
                                    aria-controls="nav-question" aria-selected="true">Soal</button>
                                <button class="nav-link " id="nav-participant-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-participant" type="button" role="tab"
                                    aria-controls="nav-participant" aria-selected="false">Peserta</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-question" role="tabpanel"
                                aria-labelledby="nav-question-tab">
                                <div class="about-content">

                                    <div class="row" style="width:100%">

                                        <div class="col-12">
                                            @if (count($data->exam_question) > 0)

                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
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
                                                                            <b>File :</b> <a class="text-info"
                                                                                href="{{ asset(DOC_PATH . $item->question->file) }}"
                                                                                download>
                                                                                {{ $item->question->file }} </a><br>
                                                                        @endif

                                                                        @switch($item->question->type)
                                                                            @case(0)
                                                                                <br> <span class="badge badge-info">Essay</span>
                                                                            @break

                                                                            @case(1)
                                                                                <br> <span class="badge badge-success">Pilihan
                                                                                    berganda</span>
                                                                            @break

                                                                            @case(2)
                                                                                <br> <span class="badge badge-danger">Upload
                                                                                    file</span>
                                                                            @break

                                                                            @default
                                                                        @endswitch

                                                                    </td>
                                                                    <td width="10%" class="align-top">
                                                                        <button class="btn btn-warning btn-xs"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#detail-question-{{ $item->id }}"><i
                                                                                class="fa fa-eye"></i></button>

                                                                        <div class="modal fade"
                                                                            id="detail-question-{{ $item->id }}">
                                                                            <div class="modal-dialog modal-dialog-centered"
                                                                                role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title">Detail
                                                                                            soal
                                                                                        </h5>
                                                                                        <button type="button"
                                                                                            class="btn-close"
                                                                                            data-bs-dismiss="modal">
                                                                                        </button>
                                                                                    </div>

                                                                                    <div class="modal-body">
                                                                                        <div class="table-responsive">
                                                                                            <table class="table">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <th>JENIS SOAL
                                                                                                        </th>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            @switch($item->question->type)
                                                                                                                @case(0)
                                                                                                                    ESSAY
                                                                                                                @break

                                                                                                                @case(1)
                                                                                                                    PILIHAN BERGANDA
                                                                                                                @break

                                                                                                                @case(1)
                                                                                                                    UPLOAD FILE
                                                                                                                @break

                                                                                                                @default
                                                                                                            @endswitch
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <th>SOAL
                                                                                                        </th>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            @php echo $item->question->question @endphp
                                                                                                            @if ($item->question->choice)
                                                                                                                @php $options=json_decode($item->question->choice,false); @endphp
                                                                                                                <table
                                                                                                                    class="table table-borderless">
                                                                                                                    @foreach ($options as $sub)
                                                                                                                        <tr>
                                                                                                                            <th width="5%"
                                                                                                                                class="p-0 m-0">
                                                                                                                                <p>{{ $sub->choice }}
                                                                                                                                </p>
                                                                                                                            </th>
                                                                                                                            <td
                                                                                                                                class="p-0 m-0">
                                                                                                                                @php echo $sub->desc @endphp
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    @endforeach
                                                                                                                </table>
                                                                                                            @endif


                                                                                                            @if ($item->question->file)
                                                                                                                <b>File
                                                                                                                    :</b>
                                                                                                                <a class="text-info"
                                                                                                                    href="{{ asset(DOC_PATH . $item->question->file) }}"
                                                                                                                    download>
                                                                                                                    {{ $item->question->file }}
                                                                                                                </a><br>
                                                                                                            @endif


                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <th>REFERENSI
                                                                                                            JAWABAN
                                                                                                        </th>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            @php echo $item->question->answer @endphp
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>


                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button"
                                                                                            class="btn btn-danger light"
                                                                                            data-bs-dismiss="modal">Tutup</button>

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
                                                    <img src="{{ asset('images/art/empty3.png') }}" height="50"
                                                        class="mt-5" alt="">
                                                    <br>
                                                    <br>
                                                    <i class="mt-3">Soal untuk ujian ini belum ada</i>

                                                    <br>
                                                    <br>
                                                </div>
                                            @endif
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="tab-pane fade " id="nav-participant" role="tabpanel"
                                aria-labelledby="nav-participant-tab">
                                <div class="about-content">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <select class="form-select form-select-lg" id="class_"
                                                onchange="change_class()">
                                                <option value="">-- pilih kelas --</option>
                                                @foreach ($data->exam_class as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->class->name }}
                                                        {{ $item->class->year }}/{{ $item->class->year + 1 }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row" class="d-none" id="displaycc" style="width:100%;">

                                    </div>

                                    <div class="w-100 text-center d-none p-5" id="loadcc" style="height:300px;">
                                        <br>
                                        <br>
                                        <br>

                                        <div class="mt-5">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <br>
                                            <small>Loading peserta..</small>
                                        </div>
                                    </div>

                                    <div class="w-100 text-center p-5 d-none" id="nocc" style="height:300px;">
                                        <br>
                                        <img src="{{ asset('images/art/empty1.png') }}" height="100" alt="">
                                        <br>
                                        <i class="text-center">tidak ada siswa di kelas ini</i>
                                        <br>

                                    </div>

                                    <div class="w-100 text-center p-5" id="noclass" style="height:300px;">
                                        <br>
                                        <img src="{{ asset('images/art/empty3.png') }}" height="100" alt="">
                                        <br>
                                        <i class="text-center">tidak ada kelas yang dipilih</i>
                                    </div>

                                    <div class="modal fade" id="correction_modal">
                                        <div class="modal-dialog modal-fullscreen" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">koreksi jawaban Siswa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <th>nama mahasiswa</th>
                                                                    <td id="name_c"></td>
                                                                    <th>NIM mahasiswa</th>
                                                                    <td id="nim_c"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>mulai mengerjakan</th>
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
                                                                    <th class="text-start">Soal</th>
                                                                    <th class="text-center" width="5%">Bobot</th>
                                                                    <th class="text-center" width="25%">
                                                                        Referensi Jawaban
                                                                    </th>
                                                                    <th class="text-center" width="25%">Jawaban
                                                                        mahasiswa
                                                                    </th>
                                                                    <th width="10%" class="text-center">% Skor
                                                                    </th>
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
                                                                                            <th width="5%"
                                                                                                class="p-0 m-0">
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
                                                                                <b>File :</b> <a class="text-info"
                                                                                    href="{{ asset(DOC_PATH . $item->question->file) }}"
                                                                                    download>
                                                                                    {{ $item->question->file }}
                                                                                </a><br>
                                                                            @endif

                                                                            @switch($item->question->type)
                                                                                @case(0)
                                                                                    <br> <span
                                                                                        class="badge badge-info">Essay</span>
                                                                                @break

                                                                                @case(1)
                                                                                    <br> <span class="badge badge-success">Pilihan
                                                                                        berganda</span>
                                                                                @break

                                                                                @case(2)
                                                                                    <br> <span class="badge badge-danger">Upload
                                                                                        file</span>
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
                                                                        <td class="text-center  align-top" width="25%"
                                                                            id="answer_{{ $item->id }}">
                                                                        </td>
                                                                        <td width="10%" class="align-top text-center"
                                                                            id="score_{{ $item->id }}">

                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger light"
                                                            data-bs-dismiss="modal">Tutup</button>

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
    <script>
        function change_class() {
            var class_id = $('#class_').val();
            $('#noclass').addClass('d-none');

            if (class_id == "") {
                $('#noclass').removeClass('d-none');
                $('#loadcc').addClass('d-none');
                $('#displaycc').addClass('d-none');
                $('#nocc').addClass('d-none');

            } else {

                load_class(0);
            }

        }

        function load_class(mode) {
            var class_ = $('#class_ option:selected').val();
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
                    qc_id: class_,
                },
                url: "{{ url('4dm1n/ujian/ajax/class') }}",
                success: function(data) {
                    console.log(data);
                    var obj = data.result.data;
                    var vl = data.result.total_value;
                    display_.empty();

                    for (let i = 0; i < obj.length; i++) {
                        const item = obj[i];

                        var abs = ``;
                        if (item.passed) {
                            var an = `<small><i class="text-danger">tidak hadir </i></small>`;
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
                url: "{{ url('4dm1n/ujian/ajax/correction') }}",
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
                                $('#score_' + el.question_id).html("<span class='badge badge-danger'>0</span>");

                            } else {

                                $('#answer_' + el.question_id).html(el.answer);
                                var sc_view = `<span class='badge badge-danger'>${el.score}</span>`;
                                if (el.score > 0) {
                                    sc_view = `<span class='badge badge-info'>${el.score}</span>`;
                                }
                                $('#score_' + el.question_id).html(sc_view);

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
    </script>
@endsection
