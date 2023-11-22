@extends('admin/master')

@section('title', 'Absensi')

@section('breadcrumb')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('akademik') }}</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('absensi') }}</a></li>
    </ol>
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-xl-12 col-xxl-12">
        <div class="card">
            <div class="card-header">
                <div class="row" style="width:110%;">
                    <div class="col-3">
                        <label class="form-label text-left">{{ tr('tanggal') }}</label>
                        <input type="date" class="form-control" value="{{ date('Y-m-d') }}" id="date_" oninput="change_date()">
                    </div>
                    <div class="col-4" id="class_view">
                        <label class="form-label text-left">{{ tr('kelas') }}</label>
                        <select class="form-select sel2" id="class_" onchange="change_class()">

                        </select>
                    </div>
                    <div class="col-5" id="schedule_view">
                        <label class="form-label text-left">{{ tr('jadwal') }}</label>
                        <select class="form-select form-select-lg" id="schedule_" onchange="check()">

                        </select>
                    </div>



                </div>

            </div>
            <div class="card-body">
                <div class="w-100 text-center p-5  d-none" id="loading" style="height:300px;">
                    <br>
                    <br>


                    <div class="mt-5">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">{{ tr('loading...') }}</span>
                        </div>
                        <br>
                        <small>{{ tr('loading absensi..') }}</small>
                    </div>
                </div>

                <div class="text-center p-5 d-none" width="100%" style="height:300px;" id="libur">
                    <div class="align-middle">
                        <img src="{{ asset('images/art/holiday.png') }}" class="mt-5" height="100" alt="">
                        <h5 class="text-danger mt-3" id="libur_txt"></h5>
                    </div>
                </div>

                <div class="text-center p-5 d-none" width="100%" style="height:300px;" id="nostart">
                    <div class="align-middle">
                        <br><br><br>
                        <h5 class="text-danger mt-5" id="nostart_txt"></h5>
                    </div>
                </div>

                <div class="d-none" id="tabel">
                    <table class="table mb-3">
                        <tr>
                            <th width="10%" class="align-top">{{ tr('dimulai oleh') }}</th>
                            <td width="30%" class="align-top" id="t_start_by"></td>
                            <th width="10%" class="align-top">{{ tr('matkul') }}</th>
                            <td width="50%" class="align-top" id="t_sks_info"></td>
                        </tr>
                        <tr>
                            <th width="10%" class="align-top">{{ tr('pertemuan ke') }}</th>
                            <td width="30%" class="align-top" id="t_session"></td>
                            <th width="10%" class="align-top">{{ tr('jadwal') }}</th>
                            <td width="50%" class="align-top" id="t_schedule_info"></td>
                        </tr>
                        <tr>
                            <th width="10%" class="align-top">{{ tr('aktivitas') }}</th>
                            <td width="30%" class="align-top" id="t_activity"></td>
                            <th width="10%" class="align-top">{{ tr('dosen pengampu') }}</th>
                            <td width="50%" class="align-top" id="t_lecturer"></td>
                        </tr>

                    </table>

                    <div class="table-responsive">
                        <table id="data-table-1" class="display text-center table-striped">
                            <thead class="">
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">{{ tr('foto') }}</th>
                                    <th class="border-bottom-0">{{ tr('nama') }}</th>
                                    <th class="border-bottom-0">{{ tr('nim') }}</th>
                                    <th class="border-bottom-0">{{ tr('status') }}</th>
                                    <th class="border-bottom-0">{{ tr('catatan') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    var start_id = null;

    $(document).ready(function() {
        check();
        change_date();
    });

    function check() {
        var date_ = $('#date_').val();

        var schedule_ = $('#schedule_');

        var typeData = 0;
        var schedule_id = schedule_.val();
        if (schedule_.val()) {
            if (schedule_id.includes('.')) {
                typeData = 1;
            }
        }


        $('#t_status').html('  <div class="spinner-border spinner-border-sm"></div>');

        if (typeData == 1) {
            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _date: date_,
                    _schedule: schedule_id,
                },
                url: "{{ url('4dm1n/absensi/check_move') }}",
                success: function(data) {
                    console.warn(data);

                    if (data.message == "success") {
                        var res = data.result;
                        start_id = null;

                        if (res.active == 0) {
                            $('#tabel').addClass('d-none');
                            $('#nostart').removeClass('d-none');
                            $('#nostart_txt').html('{{ tr("sesi kelas pindahan belum dimulai ") }}');
                        }

                        if (res.active == 1) {
                            $('#nostart_txt').html('{{ tr("kelas sedang dimulai ") }}');
                            if (res.check_start.active == 1) {
                                $('#tabel').removeClass('d-none');
                                $('#nostart').addClass('d-none');


                            } else {
                                $('#tabel').addClass('d-none');
                                $('#nostart').removeClass('d-none');


                            }
                        }

                        if (res.active == 2) {


                            if (res.check_start.active == 1) {
                                $('#tabel').removeClass('d-none');
                                $('#nostart').addClass('d-none');
                            } else {
                                $('#tabel').addClass('d-none');
                                $('#nostart').removeClass('d-none');
                                $('#nostart_txt').html('{{ tr("sesi kelas pindahan berakhir tanpa ada absensi ") }}');
                            }

                        }

                        if (res.check_start.active == 0) {

                        } else {

                            $('#t_start_by').html(res.check_start.dosen);
                            $('#t_sks_info').html(res.schedule.sks_name);
                            $('#t_session').html(res.check_start.session);
                            $('#t_schedule_info').html(res.check_start.schedule_info);

                            var editbut = "";



                            $('#t_activity').html(res.check_start.activity + editbut);





                            var content = "";
                            for (let i = 0; i < res.lecturer.length; i++) {
                                const element = res.lecturer[i];
                                content += `<tr>
                                                    <th class="p-0 align-middle">${i+1}.</th>
                                                    <td class="p-0 align-middle">${element['lecturer']}</td>
                                                   
                                                    <td class="p-0 align-middle float-end">
                                                        <span class="badge badge-xs badge-${element['position_color']}">{{ tr('dosen') }} ${element['position']}</span>
                                                        <span class="badge badge-xs badge-${element['status_color']}">${element['status']}</span>
                                                    </td>
                                              </tr>`;

                            }

                            $('#t_lecturer').html(`
                                    <table class="table table-borderless">
                                        ${content}
                                    </table>
                                `);


                            start_id = res.check_start.id;
                            load_table();
                        }

                    } else {
                        alert(data.message);
                    }


                },
                error: function(request, status, error) {
                    console.error(request.responseText);
                    //alert(request.responseText);
                }
            });
        } else {
            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _date: date_,
                    _schedule: schedule_id,
                },
                url: "{{ url('4dm1n/absensi/check_status') }}",
                success: function(data) {
                    console.warn(data);

                    if (data.message == "success") {
                        var res = data.result;
                        start_id = null;

                        if (res.schedule) {
                            $('#libur').addClass('d-none');
                            if (res.active == 0) {
                                $('#tabel').addClass('d-none');
                                $('#nostart').removeClass('d-none');
                                $('#nostart_txt').html('{{ tr("sesi kelas belum dimulai ") }}');
                            }

                            if (res.active == 1) {
                                $('#nostart_txt').html('{{ tr("kelas sedang berlangsung ") }}');
                                if (res.check_start) {
                                    $('#tabel').removeClass('d-none');
                                    $('#nostart').addClass('d-none');
                                } else {
                                    $('#tabel').addClass('d-none');
                                    $('#nostart').removeClass('d-none');
                                }
                            }

                            if (res.active == 2) {
                                if (res.check_start) {
                                    $('#tabel').removeClass('d-none');
                                    $('#nostart').addClass('d-none');
                                } else {
                                    $('#tabel').addClass('d-none');
                                    $('#nostart').removeClass('d-none');
                                    $('#nostart_txt').html('{{ tr("sesi kelas berakhir tanpa ada absensi ") }}');
                                }

                                if (res.check_move) {
                                    $('#tabel').addClass('d-none');
                                    $('#nostart').removeClass('d-none');
                                    $('#nostart_txt').html('{{ tr("sesi kelas dipindahkan ke ") }} ' + res.check_move.time_info);

                                }
                            }



                            if (res.check_start) {
                                $('#t_start_by').html(res.check_start.dosen);
                                $('#t_sks_info').html(res.schedule.sks_name);
                                $('#t_session').html(res.check_start.session);
                                $('#t_schedule_info').html(res.check_start.schedule_info);

                                var editbut = "";


                                $('#t_activity').html(res.check_start.activity + editbut);
                                var content = "";
                                for (let i = 0; i < res.lecturer.length; i++) {
                                    const element = res.lecturer[i];
                                    content += `<tr>
                                                    <th class="p-0 align-middle">${i+1}.</th>
                                                    <td class="p-0 align-middle">${element['lecturer']}</td>
                                                   
                                                    <td class="p-0 align-middle float-end">
                                                        <span class="badge badge-xs badge-${element['position_color']}">Dosen ${element['position']}</span>
                                                        <span class="badge badge-xs badge-${element['status_color']}">${element['status']}</span>
                                                    </td>
                                              </tr>`;

                                }
                                $('#t_lecturer').html(`
                                        <table class="table table-borderless">
                                            ${content}
                                        </table>
                                    `);


                                start_id = res.check_start.id;
                                load_table();
                            }


                        } else {
                            $('#libur').removeClass('d-none');
                            $('#tabel').addClass('d-none');
                            $('#nostart').addClass('d-none');
                        }
                    } else {
                        alert(data.message);
                    }


                },
                error: function(request, status, error) {
                    console.error(request.responseText);
                    //alert(request.responseText);
                }
            });
        }



    }

    function load_table() {
        var schedule = $('#schedule_').val();
        var date = $('#date_').val();
        var type = 0;
        var schedule_id = schedule;
        if (schedule) {
            if (schedule.includes(".")) {
                type = 1;
                const arr_id = schedule.split(".");
                schedule_id = arr_id[0]
            }
        }

        var table = $('#data-table-1').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                dataType: "JSON",
                type: "POST",
                url: "{{ url('/4dm1n/absensi/ajax/table') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    schedule_id: schedule_id,
                    start_id: start_id,
                    date: date,
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
                    data: 'avatar',
                    name: 'avatar',
                    orderable: false,
                },

                {
                    data: 'colleger.name',
                    name: 'colleger.name',
                    orderable: false,
                },
                {
                    data: 'colleger.nim',
                    name: 'colleger.nim',
                    orderable: false,
                },
                {
                    data: 'status_view',
                    name: 'status_view',
                },
                {
                    data: 'note',
                    name: 'note',
                }
            ],
            language: {
                paginate: {
                    next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                },
                processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">{{ tr("loading...") }}</span></div></div>',
                info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
            },

        });


    }
</script>

<script>
    function change_date() {
        var date_ = $('#date_').val();
        var class_ = $('#class_');
        $.ajax({
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _date: date_,
                prodi_id: "{{ can_prodi() }}"
            },
            url: "{{ url('4dm1n/absensi/ajax/class') }}",
            success: function(data) {
                console.log(data);
                if (data.message == "success") {
                    var el = data.result;
                    class_.empty();
                    for (var i = 0; i < el.length; i++) {
                        var row = el[i];
                        class_.append(`<option value="${row.id}">${row.name}</option>`);

                    }

                    if (el.length > 0) {
                        $('#class_view').removeClass('d-none');
                        $('#schedule_view').removeClass('d-none');
                        change_class();
                    } else {
                        $('#class_view').addClass('d-none');
                        $('#schedule_view').addClass('d-none');
                        check();
                    }

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

    function change_class() {
        var date_ = $('#date_').val();
        var class_ = $('#class_').val();
        var schedule_ = $('#schedule_');
        $.ajax({
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _class: class_,
                _date: date_,
            },
            url: "{{ url('4dm1n/absensi/ajax/jadwal') }}",
            success: function(data) {
                console.log(data);
                if (data.message == "success") {
                    var el = data.result;
                    schedule_.empty();
                    for (var i = 0; i < el.length; i++) {
                        var row = el[i];
                        schedule_.append(`<option value="${row.id}" ${row.selected}>${row.name}</option>`)
                    }

                    if (el.length > 0) {
                        $('#schedule_view').removeClass('d-none');
                    } else {
                        $('#schedule_view').addClass('d-none');
                        $('#libur_txt').html(data.holiday);
                    }

                    check();
                } else {
                    alert(data.message);
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