@extends('mahasiswa/master')

@section('title', 'Materi E-learning')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('mahasiswa/elearning') }}">{{ tr('e-learning') }}</a></li>
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
                            <div class="col-xl-12 col-xxl-12">
                                <h3>{{ $data->name }}</h3>
                            </div>

                        </div>
                        <div class="row mt-2" style="width:110%;">
                            <div class="col-xl-12 col-xxl-9">
                                <ul class="d-flex align-items-center raiting my-0 flex-wrap">
                                    <li class="text-info">
                                        <i class="fa fa-check-circle"></i> {{ $class_first->class->name }}
                                    </li>

                                    <li>{{ title_lecturer($data->lecturer) }}
                                    </li>
                                    @if ($view)
                                        <li>{{ tr('anda lihat pada') }} {{ $view }}
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-xl-12 col-xxl-3">
                                <div class="float-end ">
                                    <i class="fa fa-calendar"></i> {{ date_id($data->created_at, 1) }}
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
                        <div class="col-xl-12 col-xxl-12">
                            <div class="course-details-tab style-2 mt-4">
                                <nav>
                                    <div class="nav nav-tabs tab-auto" id="nav-tab" role="tablist">
                                        <button class="nav-link active " id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about" type="button" role="tab" aria-controls="nav-about" aria-selected="true">{{ tr('penjelasan') }}</button>
                                        <button class="nav-link " id="nav-discussion-tab" data-bs-toggle="tab" data-bs-target="#nav-discussion" type="button" onclick="load_discuss(0);" role="tab" aria-controls="nav-discussion" aria-selected="false">{{ tr('diskusi') }}</button>

                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade active show" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                        <div class="about-content">
                                            @if ($class_first->note)
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6>{{ tr('catatan kelas dosen') }}</h6>
                                                        <p>{{ $class_first->note }}</p>
                                                    </div>
                                                </div>
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

                                            @if ($quiz_data)
                                                <div class="card pt-3" style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;">
                                                    <h5 class="text-center mb-3">{{ tr('kuis e-learning') }}</h5>
                                                    <div class="table-responsive">
                                                        <table class="display table text-center table-borderless">

                                                            <tbody>
                                                                @php $i=1; @endphp
                                                                @foreach ($quiz_data as $item)
                                                                    <tr>
                                                                        <td width="10%" class="align-middle">
                                                                            {{ $i++ }}.</td>
                                                                        <td class="align-middle text-start" width="25%">
                                                                            <img src="{{ url(QUIZ_G . str_replace(' ', '_', $item->name)) }}" alt="" height="75">
                                                                        </td>
                                                                        <td class="align-middle text-start">
                                                                            <h5 class="text-start">
                                                                                {{ $item->quiz->name }}
                                                                            </h5>
                                                                            <small>{{ date_id($item->class->start, 5) }} -
                                                                                {{ date_id($item->class->end, 5) }}</small>
                                                                        </td>
                                                                        <td class="align-middle">
                                                                            <a href="{{ url('mahasiswa/kuis/do/' . $item->class->id) }}" class="btn btn-danger btn-xs float-end me-3"><i class="fa fa-trash"></i> kerjakan
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>

                                                </div>

                                            @endif

                                        </div>
                                    </div>

                                    <div class="tab-pane fade " id="nav-discussion" role="tabpanel" aria-labelledby="nav-discussion-tab">
                                        <div class="about-content">

                                            <div class="d-none" id="displaydiscuss" style="height:500px; overflow-y:auto; width: 100%; overflow-x: hidden">
                                                <div class="row" style="width:100%;">

                                                </div>
                                            </div>
                                            <button class="btn btn-success btn-xs my-5 d-none" id="btndiscuss" onclick="comments()"><i class="fa fa-comment"></i>
                                                {{ tr('kirim komentar') }}</button>

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
                                                <button class="btn btn-success btn-xs mt-3 mb-5" onclick="comments()"><i class="fa fa-comment"></i>
                                                    {{ tr('mulai diskusi') }}</button>
                                            </div>


                                            <div class="modal fade" id="comment">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="titledis">{{ tr('buat komentar elearning') }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                            </button>
                                                        </div>
                                                        <form action="{{ url('/mahasiswa/elearning/discussion/send') }}" method="post" enctype="multipart/form-data" id="form_discuss">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="elearning_class_id" value="{{ $class_id }}">
                                                            <input type="hidden" name="elearning_id" value="{{ $data->id }}">
                                                            <input type="hidden" name="discussion_id" id="id_discuss" value="">
                                                            <input type="hidden" name="colleger_id" value="{{ akun('mahasiswa')->id }}">
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
                url: "{{ url('mahasiswa/elearning/ajax/discussion/list') }}",
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
                                            <button class="btn btn-danger btn-xs" onclick="reply(${item.id},'${item.status==1?"dosen":item.name}')"><i class="fa fa-reply-all"></i> {{ tr('balas') }}</button>
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
    </script>
@endsection
