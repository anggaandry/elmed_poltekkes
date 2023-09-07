@extends('mahasiswa/master')

@section('title', 'Kuis')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('kuis') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-xxl-4 col-xl-6">
                            <div class="input-group mb-1 input-primary">
                                <input type="text" class="form-control" id="search_" placeholder="Cari disini.." oninput="search_data()">
                                <span class="input-group-text border-0"><i class="fa fa-search"></i></span>
                            </div>
                            <small id="info_finding_">{{ tr('ditemukan 0 kuis') }}</small>

                        </div>
                        <div class="col-4">

                        </div>

                    </div>

                    <div class="p-5 justify-content-center align-items-center text-center w-100 align-middle d-none" id="loading_" style="height: 550px;">
                        <br>
                        <br>
                        <br>
                        <br>
                        <div class="mt-5">
                            <div class="spinner-grow spinner-grow-sm text-primary" role="status">
                                <span class="visually-hidden">{{ tr('loading...') }}</span>
                            </div>
                            <div class="spinner-grow spinner-grow-sm text-primary" role="status">
                                <span class="visually-hidden">{{ tr('loading...') }}</span>
                            </div>
                            <div class="spinner-grow spinner-grow-sm text-primary" role="status">
                                <span class="visually-hidden">{{ tr('loading...') }}</span>
                            </div>
                        </div>

                    </div>

                    <div class="p-5 justify-content-center align-items-center text-center w-100 align-middle d-none" id="nodata_" style="height: 500px;">
                        <br>
                        <br>
                        <br>
                        <br>
                        <div class="mt-5">
                            <img src="{{ asset('images/art/empty1.png') }}" height="100" alt="">
                            <h6 class="mt-3">{{ tr('kuis tidak ditemukan') }}</h6>

                            <br>
                            <br>
                        </div>

                    </div>

                    <div class="my-4  d-none" id="display_" style="height: 550px;">
                        <div class="row">

                        </div>
                    </div>



                    <ul class="pagination pagination-gutter justify-content-center py-2" id="pagination_">


                    </ul>

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
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            load_data(0);
        });

        let timer;
        var page_ = 1;

        function search_data() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                page_ = 1;
                load_data(0);
            }, 500);
        }

        function change_page(p) {
            page_ = p;
            load_data(1);
        }

        function load_data(mode) {
            var search_ = $('#search_').val();
            var loading_ = $('#loading_');
            var display_ = $('#display_');
            var paging_ = $('#pagination_');
            var nodata_ = $('#nodata_');
            var info_ = $('#info_finding_');

            loading_.removeClass('d-none');
            display_.addClass('d-none');
            nodata_.addClass('d-none');
            if (mode == 0) {
                paging_.addClass('d-none');
            }
            info_.addClass('d-none');


            $.ajax({
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    _colleger: "{{ akun('mahasiswa')->id }}",
                    _search: search_,
                    _limit: 8,
                    page: page_,
                },
                url: "{{ url('mahasiswa/kuis/ajax/list') }}",
                success: function(data) {
                    console.log(data);
                    var obj = data.result;
                    info_.html(`Ditemukan ${obj.total} Kuis`);
                    display_.find(".row").empty();

                    obj.data.forEach(function(item, index) {

                        var content = `<div class="items pe-2 py-1 col-xxl-3 col-xl-6">
                                            <a href="javascript:void(0)" onclick="show_quiz(${item.id})">
                                                <div class="card"
                                                    style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;">
                                                    <div class="imgcard"
                                                        style="background-image: url(${item.image});">
                                                        <div class="m-2 float-end">
                                                            ${item.status}
                                                            <span class="badge bg-primary"><i class="fa fa-question-circle"></i>
                                                                ${item.total_question}</span>
                                                        </div>
                                                    </div>
                                                    <div class="px-3 py-2">
                                                        <h4 class="limit-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kuis :${item.name}">
                                                            ${item.name} <br><small>${item.class}</small></h4>
                                                        <p>
                                                            <small><i class="fa fa-book"></i> ${item.subject}</small><br>
                                                            <small><i class="fa fa-stopwatch"></i> ${item.start}</small><br>
                                                            <small><i class="fa fa-flag"></i> ${item.end}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>`;
                        display_.find(".row").append(content);

                    });


                    paging_.empty();

                    if (obj.prev_page_url) {
                        paging_.append(`<li class="page-item page-indicator">
                            <button class="page-link" onclick="change_page(${obj.current_page-1})" >
                                <i class="la la-angle-left"></i></button>
                        </li>`)
                    }

                    for (let i = 1; i < obj.links.length - 1; i++) {
                        var elem = obj.links[i];
                        var disabled = "";
                        if (elem.label == '...') {
                            disabled = 'disabled';
                        }

                        paging_.append(`<li class="page-item ${elem.active?'active':''} ${disabled}">
                            <button class="page-link" onclick="change_page(${elem.label})" ${disabled}>
                                ${elem.label}</button>
                        </li>`)

                    }

                    if (obj.next_page_url) {
                        paging_.append(`<li class="page-item page-indicator">
                            <button class="page-link" onclick="change_page(${obj.current_page+1})" >
                                <i class="la la-angle-right"></i></button>
                        </li>`)
                    }


                    loading_.addClass('d-none');
                    info_.removeClass('d-none');

                    if (obj.total > 0) {



                        display_.removeClass('d-none');
                        nodata_.addClass('d-none');
                        paging_.removeClass('d-none');


                        window.scrollTo(0, 0);

                    } else {
                        display_.addClass('d-none');
                        nodata_.removeClass('d-none');
                        paging_.addClass('d-none');
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
@endsection
