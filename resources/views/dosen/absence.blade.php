@extends('dosen/master')

@section('title', 'Absensi')

@section('breadcrumb')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
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

                    <div class="col-9" id="schedule_view">
                        <label class="form-label text-left">{{ tr('jadwal') }}</label>
                        <select class="form-select form-select-lg" id="schedule_" onchange="check()">
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="perintah" class="d-none text-center p-5" style="width:100%; height:300px;">
                    <div class="align-middle">
                        <h6 class="text-danger mt-5" id="unstart_txt"></h6>
                        <button class="btn btn-primary " id="move_btn" onclick="show_move()"><i class="fa fa-calendar"></i>{{ tr('pindah jadwal') }}</button>

                        <button class="btn btn-success " id="start_btn" onclick="show_absence()"><i class="fa fa-pencil"></i>{{ tr('mulai absensi') }}</button>

                        <button class="btn btn-danger " id="cancel_move_btn" onclick="show_cancel_move()"><i class="fa fa-calendar-times"></i> {{ tr('batalkan pindah jadwal') }}</button>
                    </div>

                </div>

                <div class="text-center p-5 d-none" width="100%" style="height:300px;" id="libur">
                    <div class="align-middle">
                        <img src="{{ asset('images/art/holiday.png') }}" class="mt-5" height="100" alt="">
                        <h5 class="text-danger mt-3" id="libur_txt"></h5>
                    </div>
                </div>

                <div class="d-none" id="tabel">

                    <table class="table">
                        <tr>
                            <th width="15%">{{ tr('dimulai oleh') }}</th>
                            <td width="35%" id="t_start_by"></td>
                            <th width="15%">{{ tr('matkul') }}</th>
                            <td width="35%" id="t_sks_info"></td>
                        </tr>
                        <tr>
                            <th width="15%">{{ tr('pertemuan ke') }}</th>
                            <td width="35%" id="t_session"></td>
                            <th width="15%">{{ tr('jadwal') }}</th>
                            <td width="35%" id="t_schedule_info"></td>
                        </tr>
                        <tr>
                            <th width="15%">{{ tr('aktivitas') }}</th>
                            <td width="35%" id="t_activity"></td>
                            <th width="15%">{{ tr('dosen pengampu') }}</th>
                            <td width="35%" id="t_lecturer"></td>
                        </tr>
                        <tr>
                            <th width="15%">{{ tr('status') }}</th>
                            <td width="35%" id="t_status">

                                <div class="spinner-border spinner-border-sm"></div>

                            </td>
                            <th width="15%">{{ tr('aksi') }}</th>
                            <td width="35%">
                                <h6 id="submit_txt"></h6>
                                <a href="#delete_modal" data-bs-toggle="modal" class="btn btn-danger btn-xs" id="delete_btn">{{ tr('hapus absen') }} >></a>
                                <a href="#submit_modal" data-bs-toggle="modal" class="btn btn-success btn-xs d-none" id="submit_btn">{{ tr('submit absen ') }} >>></a>
                                <div class="modal fade" id="submit_modal">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">{{ tr('submit absensi') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>
                                            </div>
                                            <form action="{{ url('/dosen/absensi/submit') }}" method="post" id="form_submit">
                                                {{ csrf_field() }}

                                                <input type="hidden" name="start_id" id="submit_start_id">
                                                <input type="hidden" name="schedule_id" id="submit_schedule_id">
                                                <input type="hidden" name="lecturer_id" value="{{ akun('dosen')->id }}">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="mb-3 col-md-12">
                                                            <p class="text-danger">{{ tr('apakah anda ingin mensubmit sesi belajar ini? absensi tidak akan bisa anda edit lagi setelah submit') }}</p>


                                                        </div>
                                                        <div class="mb-3 col-md-12">
                                                            <label class="form-label">{{ tr('status') }}</label>
                                                            <select name="status" class="form-select">
                                                                <option value="1"><span class="text-success">{{ tr('hadir') }}</span></option>
                                                                <option value="2"><span class="text-info">{{ tr('izin') }}</span></option>
                                                                <option value="0"><span class="text-danger">{{ tr('absen') }}</span></option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3 col-md-12">
                                                            <label class="form-label">{{ tr('catatan absensi') }}</label>
                                                            <textarea name="activity" class="form-control"></textarea>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <div class="spinner-border spinner-border-sm d-none" role="status" id="load_submit">
                                                            <span class="visually-hidden">{{ tr('loading...') }}</span>
                                                        </div> {{ tr('simpan') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="delete_modal">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">{{ tr('hapus absensi') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>
                                            </div>
                                            <form action="{{ url('/dosen/absensi/delete') }}" method="post" id="form_delete">
                                                {{ csrf_field() }}

                                                <input type="hidden" name="start_id" id="delete_start_id">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="mb-3 col-md-12">
                                                            <p class="text-danger">{{ tr('apakah anda ingin menghapus sesi belajar ini?') }}</p>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <div class="spinner-border spinner-border-sm d-none" role="status" id="load_delete">
                                                            <span class="visually-hidden">{{ tr('loading...') }}</span>
                                                        </div> {{ tr('simpan') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="table-responsive mt-5">
                        <table id="data-table-1" class="dislay text-center">
                            <thead class=" bg-primary-light text-white">
                                <tr>
                                    <th class="border-bottom-0 text-white">#</th>
                                    <th class="border-bottom-0 text-white text-center">{{ tr('mahasiswa') }}</th>
                                    <th class="border-bottom-0 text-white">{{ tr('alfa') }}</th>
                                    <th class="border-bottom-0 text-white">{{ tr('hadir') }}</th>
                                    <th class="border-bottom-0 text-white">{{ tr('izin') }}</th>

                                    <th class="border-bottom-0 text-white">{{ tr('catatan') }}</th>
                                    <th class="border-bottom-0 text-white">{{ tr('aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal fade" id="move_absence_modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ tr('pindahkan jadwal') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <form action="{{ url('/dosen/absensi/move') }}" method="post" id="form_move">
                                {{ csrf_field() }}

                                <input type="hidden" name="schedule_id" id="v_schedule_move">
                                <input type="hidden" name="lecturer_id" value="{{ akun('dosen')->id }}">
                                <input type="hidden" name="session" id="v_session_move">
                                <input type="hidden" name="moved_from" id="v_date_move">


                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">{{ tr('matkul') }}</label>
                                            <input type="text" class="form-control" id="sks_move" disabled>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">{{ tr('kelas') }}</label>
                                            <input type="text" class="form-control" id="class_move" disabled>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ tr('pertemuan ke') }}</label>
                                            <input type="text" class="form-control" name="session" id="session_move" disabled>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ tr('tanggal') }}</label>
                                            <input type="date" class="form-control" name="date" id="date_move" required>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ tr('mulai') }}</label>
                                            <input type="time" class="form-control" name="start" id="start_move" required>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ tr('berakhir') }}</label>
                                            <input type="time" class="form-control" name="end" id="end_move" required>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">{{ tr('alasan pemindahan jadwal') }}</label>
                                            <textarea name="move_reason" class="form-control" id="reason_move" required></textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                    <button type="submit" class="btn btn-primary">
                                        <div class="spinner-border spinner-border-sm d-none" role="status" id="load_start">
                                            <span class="visually-hidden">{{ tr('loading...') }}</span>
                                        </div> {{ tr('simpan') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="cancel_move_modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger">{{ tr('batalkan pemindahan jadwal') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <form action="{{ url('/dosen/absensi/move_cancel') }}" method="post" id="form_cancel_move">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="cancel_move_id">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <p>{{ tr('apakah anda ingin membatalkan pemindahan jadwal ini?') }}
                                            </p>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tidak') }}</button>
                                    <button type="submit" class="btn btn-primary">
                                        <div class="spinner-border spinner-border-sm d-none" role="status" id="load_cancel_move">
                                            <span class="visually-hidden">{{ tr('loading...') }}</span>
                                        </div>
                                        {{ tr('ya') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="start_absence_modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ tr('mulai absensi') }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <form action="{{ url('/dosen/absensi/start') }}" method="post" id="form_start">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="v_id_start">
                                <input type="hidden" name="schedule_id" id="v_schedule_start">
                                <input type="hidden" name="lecturer_id" value="{{ akun('dosen')->id }}">
                                <input type="hidden" name="session" id="v_session_start">
                                <input type="hidden" name="date" id="v_date_start">
                                <input type="hidden" name="start" id="v_start_start">
                                <input type="hidden" name="end" id="v_end_start">

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">{{ tr('pertemuan ke') }}</label>
                                            <input type="text" class="form-control" name="session" id="session_start" disabled>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">{{ tr('matkul') }}</label>
                                            <input type="text" class="form-control" id="sks_start" disabled>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">{{ tr('kelas') }}</label>
                                            <input type="text" class="form-control" id="class_start" disabled>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ tr('hari') }}</label>
                                            <input type="text" class="form-control" id="day_start" disabled>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ tr('tanggal') }}</label>
                                            <input type="date" class="form-control" id="date_start" disabled>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ tr('mulai') }}</label>
                                            <input type="time" class="form-control" id="start_start" disabled>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">{{ tr('berakhir') }}</label>
                                            <input type="time" class="form-control" id="end_start" disabled>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">{{ tr('aktivitas pembelajaran hari ini') }}</label>
                                            <textarea name="activity" class="form-control" required></textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                    <button type="submit" class="btn btn-primary">
                                        <div class="spinner-border spinner-border-sm d-none" role="status" id="load_start">
                                            <span class="visually-hidden">{{ tr('loading...') }}</span>
                                        </div> {{ tr('simpan') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="activity_edit_modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ tr('edit aktivitas pembelajaran dosen') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <form action="{{ url('/dosen/absensi/activity') }}" method="post" id="form_activity">
                                {{ csrf_field() }}

                                <input type="hidden" name="id" id="am_start_id">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">{{ tr('aktivitas pembelajaran hari ini') }}</label>
                                            <textarea name="activity" id="am_activity" class="form-control" required></textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                    <button type="submit" class="btn btn-primary">
                                        <div class="spinner-border spinner-border-sm d-none" role="status" id="load_am">
                                            <span class="visually-hidden">{{ tr('loading...') }}</span>
                                        </div> {{ tr('simpan') }}
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
@endsection

@section('script')
<script type="text/javascript">
    var start_id = null;
    $(document).ready(function() {

        change_date();

    });

    function load_table() {
        var schedule = $('#schedule_').val();
        var date = $('#date_').val();
        var schedule_id = schedule;

        var table = $('#data-table-1').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            paging: false,
            searching: false,
            ordering: false,
            ajax: {
                dataType: "JSON",
                type: "POST",
                url: "{{ url('/dosen/absensi/ajax/table') }}",
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
            columnDefs: [{
                width: 250,
                targets: 5
            }],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    class: "text-start",
                    data: 'avatar',
                    name: 'avatar',
                    orderable: false,
                },
                {
                    data: 'absent',
                    name: 'absent',
                    orderable: false,
                },
                {
                    data: 'present',
                    name: 'present',
                    orderable: false,
                },
                {
                    data: 'permit',
                    name: 'permit',
                    orderable: false,
                },

                {
                    data: 'note',
                    name: 'note',
                    orderable: false,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                }
            ],
            language: {
                paginate: {
                    next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                },
                emptyTable: `<div class="text-center text-danger p-5" width="100%" height="200">
                                {{ tr('tidak ada siswa disini') }}
                                </div>`,
                processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden"><?php echo tr('loading') ?></span><?php echo tr(' </div>') ?></div > ',
                info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
            },

        });


    }
</script>

<script>
    function change_date() {
        var date_ = $('#date_').val();
        var lecturer_ = "{{ akun('dosen')->id }}";
        var schedule_ = $('#schedule_');
        $.ajax({
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _lecturer: lecturer_,
                _date: date_,
            },
            url: "{{ url('dosen/absensi/ajax/schedule') }}",
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

    function show_absence() {
        $("#start_absence_modal").modal('show');
    }

    function show_move() {
        $("#move_absence_modal").modal('show');
    }

    function show_cancel_move() {
        $("#cancel_move_modal").modal('show');
    }

    function check() {
        var date_ = $('#date_').val();
        var lecturer_ = "{{ akun('dosen')->id }}";
        var schedule_ = $('#schedule_');

        var typeData = 0;
        var schedule_id = schedule_.val();
        if (schedule_.val()) {
            if (schedule_id.includes('.')) {
                typeData = 1;
            }
        }

        countDownDate = null;
        $('#t_status').html('<div class="spinner-border spinner-border-sm"></div>');
        $('#v_id_start').val('');
        $('#start_btn').html('<i class = "fa fa-pencil"></i> <?php echo tr('mulai absensi ') ?> ');
        if (typeData == 1) {
            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _lecturer: lecturer_,
                    _date: date_,
                    _schedule: schedule_id,
                },
                url: "{{ url('dosen/absensi/check_move') }}",
                success: function(data) {
                    console.warn(data);

                    if (data.message == "success") {
                        var res = data.result;
                        start_id = null;
                        // res.active=1;
                        if (res.active == 0) {
                            $('#tabel').addClass('d-none');
                            $('#perintah').removeClass('d-none');
                            $('#move_btn').removeClass('d-none');
                            $('#cancel_move_btn').removeClass('d-none');
                            $('#start_btn').addClass('d-none');
                            $('#submit_btn').addClass('d-none');
                            $('#submit_txt').addClass('d-none');
                            $('#unstart_txt').html('<?php echo tr('jadwal kelas pindahan belum dimulai ') ?>');
                            $('#move_btn').html('<i class="fa fa-calendar"></i> <?php echo tr('edit pindah jadwal ') ?>');

                        }

                        if (res.active == 1) {
                            if (res.check_start.active == 1) {
                                $('#tabel').removeClass('d-none');
                                $('#perintah').addClass('d-none');


                            } else {
                                $('#tabel').addClass('d-none');
                                $('#perintah').removeClass('d-none');
                                $('#move_btn').addClass('d-none');
                                $('#start_btn').removeClass('d-none');
                                $('#unstart_txt').html('');
                            }

                            $('#submit_btn').addClass('d-none');
                            $('#submit_txt').removeClass('d-none');
                            $('#submit_txt').html('-');
                            $('#cancel_move_btn').addClass('d-none');

                            $('#v_schedule_start').val(res.schedule.id);
                            $('#v_session_start').val(res.session);
                            $('#v_date_start').val(date_);
                            $('#v_start_start').val(res.check_start.start);
                            $('#v_end_start').val(res.check_start.end);

                            $('#session_start').val(res.session);
                            $('#sks_start').val(res.schedule.sks_name);
                            $('#class_start').val(res.schedule.class_name);
                            $('#day_start').val(res.check_start.day_name);
                            $('#date_start').val(date_);
                            $('#start_start').val(res.check_start.start);
                            $('#end_start').val(res.check_start.end);
                            $('#v_id_start').val(res.check_start.id);

                        }

                        if (res.active == 2) {
                            $('#submit_btn').removeClass('d-none');

                            $('#submit_txt').addClass('d-none');
                            if (res.check_start.active == 1) {
                                $('#tabel').removeClass('d-none');
                                $('#perintah').addClass('d-none');
                            } else {
                                $('#tabel').addClass('d-none');
                                $('#perintah').removeClass('d-none');
                                $('#move_btn').addClass('d-none');
                                $('#start_btn').addClass('d-none');
                                $('#unstart_txt').html('{{ tr("sesi kelas pindahan sudah habis tanpa kegiatan pembelajaran ") }}');

                                // if (res.same_day) {
                                //     $('#unstart_txt').html("Jam sesi kelas sudah berakhir");
                                //     $('#start_btn').removeClass('d-none');
                                //     $('#start_btn').html('<i class = "fa fa-edit"></i> Absen manual');
                                // }
                                $('#unstart_txt').html("{{ tr('jam sesi kelas sudah berakhir') }}");
                                $('#start_btn').removeClass('d-none');
                                $('#start_btn').html('<i class = "fa fa-edit"></i> {{ tr("absen manual ") }} ');
                            }

                            $('#cancel_move_btn').addClass('d-none');
                            if (res.check_submit) {
                                $('#submit_btn').addClass('d-none');
                                $('#submit_txt').removeClass('d-none');
                                $('#submit_txt').html(`<small><i class="text-info"> {{ tr('anda sudah submit di') }} ${res.check_submit.time}</i></small>`);


                            }
                        }

                        if (res.check_start.active == 0) {
                            $('textarea#reason_move').val(res.check_start.move_reason);
                            $('#date_move').val(res.check_start.date);
                            $('#start_move').val(res.check_start.start);
                            $('#end_move').val(res.check_start.end);
                            $('#cancel_move_id').val(res.check_start.id);
                            $("textarea#am_activity").val("");
                        } else {
                            $('textarea#reason_move').val("");
                            $('#date_move').val("");
                            $('#start_move').val("");
                            $('#end_move').val("");

                            $('#t_start_by').html(res.check_start.dosen);
                            $('#t_sks_info').html(res.schedule.sks_name);
                            $('#t_session').html(res.check_start.session);
                            $('#t_schedule_info').html(res.check_start.schedule_info);

                            var editbut = "";

                            if (res.active == 1) {
                                editbut =
                                    ` <a href="#activity_edit_modal" data-bs-toggle="modal" ><i class="fa fa-edit"></i></a>`;
                            }

                            $('#t_activity').html(res.check_start.activity + editbut);


                            countDownDate = new Date(res.check_start.countdown).getTime();


                            var content = "";
                            for (let i = 0; i < res.lecturer.length; i++) {
                                const element = res.lecturer[i];
                                content += `<tr>
                                                    <th class="p-0 align-middle">${i+1}.</th>
                                                    <td class="p-0 align-middle">${element['lecturer']}</td>
                                                    
                                                    <td class="p-0 align-middle float-end">
                                                        <span class="badge badge-xs badge-${element['position_color']}">${element['position']}</span>
                                                        <span class="badge badge-xs badge-${element['status_color']}">${element['status']}</span>
                                                    </td>
                                                </tr>`;

                            }
                            $('#t_lecturer').html(`
                                                        <table class="table table-borderless">
                                                            ${content}
                                                        </table>
                                                    `);

                            $("#am_start_id").val(res.check_start.id);
                            $("textarea#am_activity").val(res.check_start.activity);
                            $("#submit_start_id").val(res.check_start.id);
                            $("#delete_start_id").val(res.check_start.id);
                            $("#submit_schedule_id").val(res.schedule.id);
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
                    _lecturer: lecturer_,
                    _date: date_,
                    _schedule: schedule_id,
                },
                url: "{{ url('dosen/absensi/check') }}",
                success: function(data) {
                    console.warn(data);

                    if (data.message == "success") {
                        var res = data.result;
                        start_id = null;

                        $('#move_btn').html('<i class="fa fa-calendar"></i> pindah jadwal');
                        if (res.schedule) {
                            $('#libur').addClass('d-none');
                            // res.active=1;
                            if (res.active == 0) {
                                $('#tabel').addClass('d-none');
                                $('#perintah').removeClass('d-none');
                                $('#move_btn').removeClass('d-none');
                                $('#start_btn').addClass('d-none');
                                $('#submit_btn').addClass('d-none');
                                $('#cancel_move_btn').addClass('d-none');
                                $('#submit_txt').addClass('d-none');
                                $('#unstart_txt').html('{{ tr("jadwal kelas belum dimulai ") }}');
                                if (res.check_move) {
                                    $('#unstart_txt').html('{{ tr("jadwal kelas sudah dipindah ke ") }} <br>' + res.check_move.time_info);

                                    $('#move_btn').html('<i class="fa fa-calendar"></i>{{ tr("edit pindah jadwal ") }} ');


                                    $('#cancel_move_btn').removeClass('d-none');
                                }
                            }

                            if (res.active == 1) {
                                if (res.check_start) {
                                    $('#tabel').removeClass('d-none');
                                    $('#perintah').addClass('d-none');


                                } else {
                                    $('#tabel').addClass('d-none');
                                    $('#perintah').removeClass('d-none');
                                    $('#move_btn').removeClass('d-none');
                                    $('#start_btn').removeClass('d-none');
                                    $('#unstart_txt').html('');
                                }

                                $('#submit_btn').addClass('d-none');
                                $('#submit_txt').removeClass('d-none');
                                $('#submit_txt').html('-');
                                $('#cancel_move_btn').addClass('d-none');
                                if (res.check_move) {
                                    $('#cancel_move_btn').removeClass('d-none');
                                    $('#unstart_txt').removeClass('d-none');
                                    $('#start_btn').addClass('d-none');
                                    $('#unstart_txt').html('{{ tr("jadwal kelas sudah dipindah ke ") }} < br > ' + res.check_move.time_info);
                                    $('#move_btn').html('<i class="fa fa-calendar"></i> {{ tr("Edit pindah jadwal ") }}');
                                }
                            }

                            if (res.active == 2) {
                                $('#submit_btn').removeClass('d-none');

                                $('#submit_txt').addClass('d-none');
                                if (res.check_start) {
                                    $('#tabel').removeClass('d-none');
                                    $('#perintah').addClass('d-none');
                                } else {
                                    $('#tabel').addClass('d-none');
                                    $('#perintah').removeClass('d-none');
                                    $('#move_btn').addClass('d-none');
                                    $('#start_btn').addClass('d-none');
                                    $('#unstart_txt').html('{{ tr("sesi kelas sudah habis tanpa kegiatan pembelajaran ") }}');

                                    // if (res.same_day) {
                                    //     $('#unstart_txt').html("Jam sesi kelas sudah berakhir");
                                    //     $('#start_btn').removeClass('d-none');
                                    //     $('#start_btn').html('<i class = "fa fa-edit"></i> Absen manual');
                                    // }
                                    $('#unstart_txt').html("{{ tr('jam sesi kelas sudah berakhir') }}");
                                    $('#start_btn').removeClass('d-none');
                                    $('#start_btn').html('<i class = "fa fa-edit"></i> {{ tr("absen manual ") }}');

                                }

                                $('#cancel_move_btn').addClass('d-none');
                                if (res.check_submit) {
                                    $('#submit_btn').addClass('d-none');
                                    $('#submit_txt').removeClass('d-none');
                                    $('#submit_txt').html(`<small><i class="text-info"> {{ tr('anda sudah submit di') }} ${res.check_submit.time}</i></small>`);




                                }

                                if (res.check_move) {
                                    $('#tabel').addClass('d-none');
                                    $('#perintah').removeClass('d-none');
                                    $('#move_btn').addClass('d-none');
                                    $('#start_btn').addClass('d-none');
                                    $('#unstart_txt').html('{{ tr("sesi kelas sudah dipindahkan ke ") }} <br>' + res.check_move.time_info);


                                }
                            }

                            if (res.check_move) {
                                $('textarea#reason_move').val(res.check_move.move_reason);
                                $('#date_move').val(res.check_move.date);
                                $('#start_move').val(res.check_move.start);
                                $('#end_move').val(res.check_move.end);
                                $('#cancel_move_id').val(res.check_move.id);
                            } else {
                                $('textarea#reason_move').val("");
                                $('#date_move').val("");
                                $('#start_move').val("");
                                $('#end_move').val("");
                            }

                            if (res.check_start) {
                                $('#t_start_by').html(res.check_start.dosen);
                                $('#t_sks_info').html(res.schedule.sks_name);
                                $('#t_session').html(res.check_start.session);
                                $('#t_schedule_info').html(res.check_start.schedule_info);

                                var editbut = "";

                                if (res.active == 1) {
                                    editbut =
                                        ` <a href="#activity_edit_modal" data-bs-toggle="modal" ><i class="fa fa-edit"></i></a>`;
                                }

                                $('#t_activity').html(res.check_start.activity + editbut);


                                countDownDate = new Date(res.check_start.countdown).getTime();


                                var content = "";
                                for (let i = 0; i < res.lecturer.length; i++) {
                                    const element = res.lecturer[i];
                                    content += `<tr>
                                                    <th class="p-0 align-middle">${i+1}.</th>
                                                    <td class="p-0 align-middle">${element['lecturer']}</td>
                                                   
                                                    <td class="p-0 align-middle float-end">
                                                        <span class="badge badge-xs badge-${element['position_color']}">${element['position']}</span>
                                                        <span class="badge badge-xs badge-${element['status_color']}">${element['status']}</span>
                                                    </td>
                                              </tr>`;

                                }
                                $('#t_lecturer').html(`
                                    <table class="table table-borderless">
                                        ${content}
                                    </table>
                                `);

                                $("#am_start_id").val(res.check_start.id);
                                $("textarea#am_activity").val(res.check_start.activity);
                                $("#submit_start_id").val(res.check_start.id);
                                $("#delete_start_id").val(res.check_start.id);
                                $("#submit_schedule_id").val(res.schedule.id);
                                start_id = res.check_start.id;
                                load_table();
                            } else {
                                $("textarea#am_activity").val("");
                            }

                            if (res.schedule) {
                                $('#v_schedule_start').val(res.schedule.id);
                                $('#v_session_start').val(res.session);
                                $('#v_date_start').val(date_);
                                $('#v_start_start').val(res.schedule.start);
                                $('#v_end_start').val(res.schedule.end);

                                $('#session_start').val(res.session);
                                $('#sks_start').val(res.schedule.sks_name);
                                $('#class_start').val(res.schedule.class_name);
                                $('#day_start').val(res.schedule.day_name);
                                $('#date_start').val(date_);
                                $('#start_start').val(res.schedule.start);
                                $('#end_start').val(res.schedule.end);

                                $('#v_schedule_move').val(res.schedule.id);
                                $('#v_session_move').val(res.session);
                                $('#v_date_move').val(date_);
                                $('#session_move').val(res.session);
                                $('#sks_move').val(res.schedule.sks_name);
                                $('#class_move').val(res.schedule.class_name);


                            }
                        } else {
                            $('#libur').removeClass('d-none');
                            $('#tabel').addClass('d-none');
                            $('#perintah').addClass('d-none');
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

    function change_status(status, id) {
        var date_ = $('#date_').val();
        var schedule_ = $('#schedule_').val();

        $.ajax({
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _colleger: id,
                _date: date_,
                _schedule: schedule_,
                _status: status,
                _start: start_id,
            },
            url: "{{ url('dosen/absensi/status') }}",
            success: function(data) {
                console.log(data);
                if (data.code == 1) {
                    $("#btn" + id).attr('disabled', false);
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

    let timer;

    function input_note(id) {
        clearTimeout(timer);
        timer = setTimeout(() => {
            change_note(id);
        }, 1000);
    }



    function change_note(id) {
        var date_ = $('#date_').val();
        var schedule_ = $('#schedule_').val();

        var note_ = $('textarea#note' + id).val();

        $.ajax({
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _colleger: id,
                _date: date_,
                _schedule: schedule_,
                _note: note_,
                _start: start_id,
            },
            url: "{{ url('dosen/absensi/note') }}",
            success: function(data) {
                console.log(data);
                if (data.code == 1) {
                    $("#btn" + id).attr('disabled', false);
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

    function delete_absence(id) {
        var date_ = $('#date_').val();
        var schedule_ = $('#schedule_').val();


        $.ajax({
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _colleger: id,
                _date: date_,
                _schedule: schedule_,
                _start: start_id,
            },
            url: "{{ url('dosen/absensi/reset') }}",
            success: function(data) {
                console.log(data);
                if (data.code == 1) {
                    show_toast(1, data.message);
                    $("#btn" + id).attr('disabled', true);
                    $('textarea#note' + id).val("");
                    $('input[name=radioAb' + id + ']').prop('checked', false);
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

<script>
    $(document).on('submit', '#form_start', function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.

        var formData = new FormData(this);
        var actionUrl = $(this).attr('action');
        $('#load_start').removeClass('d-none');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData, // serializes the form's elements.
            success: function(data) {
                if (data.code == 1) {
                    show_toast(1, data.message);
                    check();
                    $('#start_absence_modal').modal('hide');
                    $('#form_start')[0].reset();
                } else {
                    show_toast(0, data.message);
                }
                $('#load_start').addClass('d-none');
            },
            error: function(request, status, error) {
                console.error(request.responseText);
                //alert(request.responseText);
            },
            cache: false,
            contentType: false,
            processData: false
        });

    });

    $(document).on('submit', '#form_move', function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.

        var formData = new FormData(this);
        var actionUrl = $(this).attr('action');
        $('#load_move').removeClass('d-none');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData, // serializes the form's elements.
            success: function(data) {
                if (data.code == 1) {
                    show_toast(1, data.message);
                    check();
                    $('#move_absence_modal').modal('hide');
                    $('#form_move')[0].reset();
                } else {
                    show_toast(0, data.message);
                }
                $('#load_move').addClass('d-none');
                var schedule_ = $('#schedule_').val();

                if (schedule_.includes(".")) {
                    change_date();
                }

                if (formData.get('moved_from') == $('#date_').val()) {
                    change_date();
                }


            },
            error: function(request, status, error) {
                console.error(request.responseText);
                //alert(request.responseText);
            },
            cache: false,
            contentType: false,
            processData: false
        });

    });

    $(document).on('submit', '#form_cancel_move', function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.

        var formData = new FormData(this);
        var actionUrl = $(this).attr('action');
        $('#load_move').removeClass('d-none');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData, // serializes the form's elements.
            success: function(data) {
                if (data.code == 1) {
                    show_toast(1, data.message);
                    check();
                    $('#cancel_move_modal').modal('hide');
                    $('#form_cancel_move')[0].reset();
                } else {
                    show_toast(0, data.message);
                }
                $('#load_cancel_move').addClass('d-none');
                var schedule_ = $('#schedule_').val();
                if (schedule_.includes(".")) {

                    change_date();
                }
            },
            error: function(request, status, error) {
                console.error(request.responseText);
                //alert(request.responseText);
            },
            cache: false,
            contentType: false,
            processData: false
        });

    });

    $(document).on('submit', '#form_activity', function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.

        var formData = new FormData(this);
        var actionUrl = $(this).attr('action');
        $('#load_am').removeClass('d-none');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData, // serializes the form's elements.
            success: function(data) {
                if (data.code == 1) {
                    show_toast(1, data.message);
                    check();
                    $('#activity_edit_modal').modal('hide');
                    $('#form_activity')[0].reset();
                } else {
                    show_toast(0, data.message);
                }
                $('#load_am').addClass('d-none');
            },
            error: function(request, status, error) {
                console.error(request.responseText);
                //alert(request.responseText);
            },
            cache: false,
            contentType: false,
            processData: false
        });

    });

    $(document).on('submit', '#form_submit', function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.

        var formData = new FormData(this);
        var actionUrl = $(this).attr('action');
        $('#load_submit').removeClass('d-none');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData, // serializes the form's elements.
            success: function(data) {
                if (data.code == 1) {
                    show_toast(1, data.message);
                    check();
                    $('#submit_modal').modal('hide');
                    $('#form_submit')[0].reset();
                } else {
                    show_toast(0, data.message);
                }
                $('#load_submit').addClass('d-none');
            },
            error: function(request, status, error) {
                console.error(request.responseText);
                //alert(request.responseText);
            },
            cache: false,
            contentType: false,
            processData: false
        });

    });

    $(document).on('submit', '#form_delete', function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.

        var formData = new FormData(this);
        var actionUrl = $(this).attr('action');
        $('#load_delete').removeClass('d-none');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData, // serializes the form's elements.
            success: function(data) {
                if (data.code == 1) {
                    show_toast(1, data.message);
                    check();
                    $('#delete_modal').modal('hide');
                    $('#form_delete')[0].reset();
                } else {
                    show_toast(0, data.message);
                }
                $('#load_delete').addClass('d-none');
            },
            error: function(request, status, error) {
                console.error(request.responseText);
                //alert(request.responseText);
            },
            cache: false,
            contentType: false,
            processData: false
        });

    });
</script>

<script>
    // Set the date we're counting down to

    var countDownDate = null;
    var rel = true;

    // Update the count down every 1 second
    var x = setInterval(function() {
        if (countDownDate != null) {
            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            var cont = "<span class='badge badge-danger'>{{ tr('kelas berakhir dalam') }} ";
            if (days > 0) {
                cont += (days + " Hari ");
            }

            if (hours > 0) {
                cont += (hours + " jam ");
            }
            document.getElementById("t_status").innerHTML = cont +
                minutes + " {{ tr('menit') }} " + seconds + " {{ tr('detik') }} </span> ";

            // If the count down is finished, write some text
            if (distance < 0) {
                // /clearInterval(x);
                if (rel == false) {

                    setTimeout(function() {
                        check();

                    }, 2000);
                    rel = true;
                }

                document.getElementById("t_status").innerHTML =
                    "<span class='badge badge-info'>{{ tr('sesi sudah selesai') }}</span>";


            } else {
                rel = false;
            }
        }

    }, 1000, true);
</script>

@endsection