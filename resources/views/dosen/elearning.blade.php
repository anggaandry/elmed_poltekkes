@extends('dosen/master')

@section('title', 'E-learning')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">LMS</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">E-learning</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-body">

                    @if (count($active_elearning) > 0)
                        <h3 class="mb-3">E-Learning Aktif <small class="float-end text-primary"
                                style="font-size: 15px;!important">{{ count($active_elearning) }} elearning</small></h3>
                        <div class="owl-carousel owl-carousel owl-loaded front-view-slider mb-4">
                            @foreach ($active_elearning as $item)
                                <div class="items p-1">
                                    <a
                                        href="{{ url('dosen/elearning/detail?id=' . $item->elearning_id . '&kelas=' . $item->id) }}">
                                        <div class="card"
                                            style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;">
                                            <div class="imgcard"
                                                style="background-image: url({{ $item->elearning->image ? asset(LMS_PATH . $item->elearning->image) : url(ELEARNING_G) . str_replace(' ', '_', $item->elearning->name) }});">
                                                <div class="m-2 float-end">
                                                    <span class="badge bg-success">active</span>
                                                    <span class="badge bg-primary"><i class="fa fa-comment"></i>
                                                        {{ count($item->elearning_discussion) }}</span>
                                                </div>

                                            </div>

                                            <div class="px-3 py-2">
                                                <h4 class="limit-text" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="Elearning :{{ $item->elearning->name }}">
                                                    {{ $item->elearning->name }}</h4>
                                                <p>{{ $item->class->name }}</p>
                                                <p>
                                                    <small><i class="fa fa-stopwatch"></i> {{ date_id($item->start, 1) }}
                                                        </i></small>
                                                    <br>
                                                    <small><i class="fa fa-flag"></i> {{ date_id($item->end, 1) }}</small>
                                                </p>

                                            </div>

                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <br>

                    @endif


                    <div class="row">
                        <div class="col-xxl-4 col-xl-6">
                            <div class="input-group mb-1 input-primary">
                                <input type="text" class="form-control" id="search_" placeholder="Cari disini.."
                                    oninput="search_data()">
                                <span class="input-group-text border-0"><i class="fa fa-search"></i></span>
                            </div>
                            <small id="info_finding_">Ditemukan 0 Elearning</small>

                        </div>
                        <div class="col-4">

                        </div>
                        <div class="col-4">
                            <a class="btn btn-primary  mb-3  float-end" href="{{ url('dosen/elearning/form/add') }}"> <i
                                    class="fa fa-plus-circle"></i> Buat
                                elearning</a>
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
                            <h6 class="mt-3">Elearning tidak ditemukan</h6>
                            <!-- <a class="btn btn-primary btn-xs mb-3 mt-3" href="{{ url('dosen/elearning/form/add') }}"> <i
                                                                                                                                                                                                                                                                                                                                                                                        class="fa fa-plus-circle"></i> Buat elearning</a> -->
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
        var addition = {{ count($active_elearning) }};

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
                    _lecturer: "{{ akun('dosen')->id }}",
                    _search: search_,
                    _limit: 8,
                    page: page_,
                },
                url: "{{ url('dosen/elearning/ajax/list') }}",
                success: function(data) {
                    console.log(data);
                    var obj = data.result;
                    info_.html(`Ditemukan ${obj.total} E-learning`);
                    display_.find(".row").empty();

                    obj.data.forEach(function(item, index) {

                        var content = `<div class="items pe-2 py-1 col-xxl-3 col-xl-6">
                                            <a href="${item.link}">
                                                <div class="card"
                                                    style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;">
                                                    <div class="imgcard"
                                                        style="background-image: url(${item.image});">
                                                        <div class="m-2 float-end">
                                                            <span class="badge bg-info"><i class="fa fa-chalkboard-teacher"></i>
                                                                ${item.total_class}</span>
                                                            <span class="badge bg-primary"><i class="fa fa-comment"></i>
                                                                ${item.total_discussion}</span>
                                                        </div>
                                                    </div>
                                                    <div class="px-3 py-2">
                                                        <h4 class="limit-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Elearning :${item.name}">
                                                            ${item.name}</h4>
                                                        <p>
                                                            <small><i class="fa fa-book"></i> ${item.subject}</small><br>
                                                            <small><i class="fa fa-pencil"></i> ${item.prodi}</small><br>
                                                            <small><i class="fa fa-clock"></i> ${item.time}</small>
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

                        if (addition == 0) {
                            window.scrollTo(0, 0);
                        } else {
                            window.scrollTo(0, 500);
                        }


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
@endsection
