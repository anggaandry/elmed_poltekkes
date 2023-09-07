@extends('admin/master')

@section('title', 'Materi E-learning')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('4dm1n/elearning') }}">{{ tr('materi e-learning') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('detail materi') }}</a></li>
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
                            <div class="col-8">
                                <h3>{{ $data->name }}</h3>
                            </div>
                            <div class="col-4">
                                <div class="float-end">
                                    <i class="fa fa-calendar"></i> {{ date_id($data->created_at, 2) }}
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2" style="width:110%;">
                            <div class="col-12">
                                <ul class="d-flex align-items-center raiting my-0 flex-wrap">

                                    <li>{{ $data->sks->prodi->program->name . ' - ' . $data->sks->prodi->study_program->name . ' ' . $data->sks->prodi->category->name }}
                                    </li>
                                    <li>{{ title_lecturer($data->lecturer) }}
                                    </li>
                                    <li>{{ count($class) }} {{ tr('kelas') }}</li>
                                    <li>{{ count($viewer_data) }} {{ tr('mahasiswa') }}</li>
                                </ul>
                            </div>

                        </div>

                    </div>
                    <div class="video-img style-1">

                        @if ($data->video)
                            <iframe src="https://www.youtube.com/embed/{{ $data->video }}" height="400" width="100%">
                            </iframe>
                        @else
                            <div class="video-img style-1">


                                <div
                                    style=" background-image: url('{{ $data->image ? asset(LMS_PATH . $data->image) : url(ELEARNING_G) . str_replace(' ', '_', $data->name) }}');
                        border: 1px solid #eee;
                        border-radius: 25px;
                        background-position: center center;
                        width: 100%;
                        height: 400px;">

                                </div>



                            </div>
                        @endif


                    </div>
                    <div class="course-details-tab style-2 mt-4">
                        <nav>
                            <div class="nav nav-tabs tab-auto" id="nav-tab" role="tablist">
                                <button class="nav-link {{ $tab == 1 ? 'active' : '' }}" id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about" type="button" role="tab" aria-controls="nav-about" aria-selected="true">{{ tr('penjelasan') }}</button>
                                <button class="nav-link {{ $tab == 2 ? 'active' : '' }}" id="nav-class-tab" data-bs-toggle="tab" data-bs-target="#nav-class" type="button" role="tab" aria-controls="nav-class" aria-selected="false">{{ tr('kelas') }}</button>
                                <button class="nav-link {{ $tab == 3 ? 'active' : '' }}" id="nav-views-tab" data-bs-toggle="tab" data-bs-target="#nav-views" type="button" role="tab" aria-controls="nav-views" aria-selected="false">{{ tr('viewer') }}</button>
                                <button class="nav-link {{ $tab == 4 ? 'active' : '' }}" id="nav-discussion-tab" data-bs-toggle="tab" data-bs-target="#nav-discussion" type="button" role="tab" aria-controls="nav-discussion" aria-selected="false">{{ tr('diskusi') }}</button>
                                <button class="nav-link {{ $tab == 5 ? 'active' : '' }}" id="nav-quiz-tab" data-bs-toggle="tab" data-bs-target="#nav-quiz" type="button" role="tab" aria-controls="nav-quiz" aria-selected="false">{{ tr('kuis') }}</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade {{ $tab == 1 ? 'show active' : '' }}" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                <div class="about-content mb-5">
                                    @php echo $data->description @endphp

                                    <div class="text-center w-100">
                                        @if ($data->file1 || $data->file1)
                                            <br>
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
                            <div class="tab-pane fade {{ $tab == 2 ? 'show active' : '' }}" id="nav-class" role="tabpanel" aria-labelledby="nav-class-tab">
                                <div class="about-content">
                                    @if (count($class) > 0)
                                        <div class="table-responsive">
                                            <table class="display table text-center">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ tr('kelas') }}</th>
                                                        <th>{{ tr('waktu') }}</th>
                                                        <th>{{ tr('mengikuti') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $i=1; @endphp
                                                    @foreach ($class as $item)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $item->class->name }}</td>
                                                            <td>{{ date_id($item->start, 1) }} -
                                                                {{ date_id($item->end, 1) }}
                                                            </td>
                                                            <td>{{ $item->total_colleger }}/{{ $item->total_colleger_view }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    @else
                                        <div class="w-100 text-center p-5">
                                            <i class="text-center">{{ tr('belum ada kelas disini') }}</i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade {{ $tab == 3 ? 'show active' : '' }}" id="nav-views" role="tabpanel" aria-labelledby="nav-views-tab">
                                <div class="about-content">
                                    <div class="table-responsive">
                                        <table id="data-table-1" class="display table text-center">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ tr('mahasiswa') }}</th>
                                                    <th>{{ tr('waktu') }}</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i=1; @endphp
                                                @foreach ($viewer_data as $item)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>
                                                            <div class="media d-flex align-items-center">
                                                                <div class="avatar avatar-xl me-2">
                                                                    <div class="cropcircle" style="background-image: url({{ $item->colleger->avatar ? asset(AVATAR_PATH . $item->colleger->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $item->colleger->name) }});">
                                                                    </div>

                                                                </div>
                                                                <div class="media-body">
                                                                    <h5 class="mb-0 fs--1">{{ $item->colleger->name }}
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ date_id($item->created_at, 1) }}
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>


                            </div>
                            <div class="tab-pane fade {{ $tab == 4 ? 'show active' : '' }}" id="nav-discussion" role="tabpanel" aria-labelledby="nav-discussion-tab">
                                <div class="about-content">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <select class="form-select form-select-lg" id="class_" onchange="change_class()">
                                                <option value="">-- {{ tr('pilih kelas') }} --</option>
                                                @foreach ($class as $item)
                                                    <option value="{{ $item->id }}" @if ($class_id == $item->id) selected @endif>
                                                        {{ $item->class->name }}
                                                        {{ $item->class->year }}/{{ $item->class->year + 1 }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="d-none mb-4" id="displaydiscuss" style="height:700px; overflow-y:auto; width: 100%; overflow-x: hidden">
                                        <div class="row" style="width:100%;">

                                        </div>

                                    </div>

                                    <div class="w-100 text-center p-5 d-none" id="loaddiscuss" style="height:700px;">
                                        <br>
                                        <br>
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

                                    <div class="w-100 text-center p-5 d-none" id="nodiscuss" style="height:700px;">
                                        <br>
                                        <br>
                                        <br>
                                        <img src="{{ asset('images/art/empty1.png') }}" height="100" alt="">
                                        <br>
                                        <i class="text-center">{{ tr('belum ada diskusi di kelas sini') }}</i>
                                        <br>
                                        <button class="btn btn-success btn-xs mt-3 mb-5" onclick="comments()"><i class="fa fa-comment"></i>
                                            mulai diskusi</button>
                                    </div>

                                    <div class="w-100 text-center p-5" style="height:700px;" id="noclass">
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <img src="{{ asset('images/art/empty3.png') }}" height="100" alt="">
                                        <br>
                                        <i class="text-center">{{ tr('pilih kelas terlebih dahulu') }}</i>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade {{ $tab == 5 ? 'show active' : '' }}" id="nav-quiz" role="tabpanel" aria-labelledby="nav-quiz-tab">
                                <div class="about-content">
                                    @if (count($quiz) > 0)
                                        <div class="table-responsive">
                                            <table class="display table text-center">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ tr('kuis') }}</th>
                                                        <th>{{ tr('aksi') }}</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $i=1; @endphp
                                                    @foreach ($quiz as $item)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $item->quiz->name }}</td>

                                                            <td><a href="{{ url('adm1n/kuis/detail?id=' . $item->id) }}" class="btn btn-primary"><i class="fa fa-eye"></i>
                                                                    {{ tr('detail') }}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    @else
                                        <div class="w-100 text-center p-5">
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
@endsection
@section('script')
    <script>
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
        });

        function change_class() {
            var class_id = $('#class_').val();
            $('#noclass').addClass('d-none');

            if (class_id == "") {
                $('#noclass').removeClass('d-none');
                $('#loaddiscuss').addClass('d-none');
                $('#displaydiscuss').addClass('d-none');
                $('#nodiscuss').addClass('d-none');

            } else {

                load_discuss(0);
            }

        }

        function load_discuss(mode) {

            var loading_ = $('#loaddiscuss');
            var display_ = $('#displaydiscuss');
            var nodata_ = $('#nodiscuss');

            var class_id = $('#class_').val();

            if (mode == 0) {
                loading_.removeClass('d-none');
                display_.addClass('d-none');

                nodata_.addClass('d-none');
            }

            $.ajax({
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    _class_id: class_id,
                },
                url: "{{ url('4dm1n/elearning/ajax/discussion/list') }}",
                success: function(data) {
                    console.log(data);
                    var obj = data.result;
                    display_.find(".row").empty();
                    obj = obj.reverse();
                    for (let i = 0; i < obj.length; i++) {
                        const item = obj[i];

                        var ava = "";
                        if (item.status == 1) {
                            ava = `<div class="cropcircle text-center pt-1"
                        style="background-image: url(${item.avatar});">
                        <span
                            class="badge badge-primary badge-xs mt-5">dosen
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
                                    <span
                                        class="badge badge-primary badge-xs mt-5">dosen
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

                        nodata_.addClass('d-none');
                        //show_toast(0, last + " - " + obj.length);
                        if (mode == 1 || mode == 2 || (mode == 3 && last != obj.length)) {
                            var audio = new Audio("{{ asset('audio/notif.mp3') }}");
                            audio.play();
                        }
                        if (mode < 2) {
                            /*
                            setTimeout(function() {
                                display_.scrollTop(display_[0].scrollHeight);
                            }, 1000);
                            */
                        }

                        last = obj.length;
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
@endsection
