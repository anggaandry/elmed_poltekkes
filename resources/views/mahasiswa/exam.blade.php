@extends('mahasiswa/master')

@section('title', 'Ujian')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">LMS</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Ujian</a></li>
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
                                <input type="text" class="form-control" id="search_" placeholder="Cari disini.."
                                    oninput="search_data()">
                                <span class="input-group-text border-0"><i class="fa fa-search"></i></span>
                            </div>
                            <small id="info_finding_">Ditemukan 0 Ujian</small>

                        </div>
                        <div class="col-4">

                        </div>

                    </div>

                    <div class="p-5 justify-content-center align-items-center text-center w-100 align-middle d-none"
                        id="loading_" style="height: 550px;">
                        <br>
                        <br>
                        <br>
                        <br>
                        <div class="mt-5">
                            <div class="spinner-grow spinner-grow-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow spinner-grow-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow spinner-grow-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                    </div>

                    <div class="p-5 justify-content-center align-items-center text-center w-100 align-middle d-none"
                        id="nodata_" style="height: 500px;">
                        <br>
                        <br>
                        <br>
                        <br>
                        <div class="mt-5">
                            <img src="{{ asset('images/art/empty1.png') }}" height="100" alt="">
                            <h6 class="mt-3">Ujian tidak ditemukan</h6>

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

                    <div class="modal fade" id="exam">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail ujian</h5>
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
                                                    <th>Judul ujian</th>
                                                    <td id="name_exam"></td>
                                                    <th>Dosen pengampu</th>
                                                    <td id="lecturer_exam"></td>
                                                </tr>

                                                <tr>
                                                    <th>Mata kuliah</th>
                                                    <td id="subject_exam"> </td>
                                                    <th>Skor</th>
                                                    <td id="score_exam"></td>
                                                </tr>

                                                <tr>
                                                    <th>Mulai</th>
                                                    <td id="start_exam"></td>
                                                    <th>Berakhir</th>
                                                    <td id="end_exam"></td>
                                                </tr>

                                                <tr>
                                                    <th>Deskripsi</th>
                                                    <td id="description_exam" colspan="3"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <a href="" class="btn btn-primary" id="btn_exam">

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
                url: "{{ url('mahasiswa/ujian/ajax/list') }}",
                success: function(data) {
                    console.log(data);
                    var obj = data.result;
                    info_.html(`Ditemukan ${obj.total} Ujian`);
                    display_.find(".row").empty();

                    obj.data.forEach(function(item, index) {

                        var content = `<div class="items pe-2 py-1 col-xxl-3 col-xl-6">
                                            <a href="javascript:void(0)" onclick="show_exam(${item.id})">
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
                                                        <h4 class="limit-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ujian :${item.name}">
                                                            ${item.name} <br> <small>${item.class}</small></h4>
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
@endsection
