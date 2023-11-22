@extends('admin/master')

@section('title', 'Jadwal')

@section('breadcrumb')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('akademik') }}</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('jadwal') }}</a></li>
    </ol>
</div>
@endsection

@section('content')
@php $key_='Jadwal'; @endphp
<div class="row">
    <div class="col-xl-12 col-xxl-12">
        <div class="card">

            <div class="card-header">
                <div class="row" style="width:110%;">
                    <div class="col-4">
                        <label class="form-label text-left">{{ tr('semester akademik') }}</label>
                        <select class="form-select form-select-lg" id="semester_" onchange="load_semester()">
                            @foreach ($semester_data as $item)
                            <option value="{{ $item->id }}" @if ($semester_id==$item->id) selected @endif>
                                {{ tr('semester') }} {{ $item->odd == 1 ? 'Ganjil' : 'Genap' }} TA
                                {{ $item->year }}/{{ $item->year + 1 }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-5">
                        <label class="form-label text-left">{{ tr('kelas') }}</label>
                        <select class="form-select form-select-lg" id="class_" onchange="load_class()">
                            @foreach ($class_data as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->prodi->program->name .' '.$item->prodi->study_program->name .' '.$item->prodi->category->name .' - '. $item->name }} TA {{ $item->year }}/{{ $item->year + 1 }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        @if (can($key_, 'add'))
                        <a class="btn  btn-primary d-none float-end" data-bs-toggle="modal" href="#add" id="class_btn_add"><span class="btn-icon-start text-primary"><i class="fa fa-plus color-primary"></i>
                            </span>{{ tr('tambah jadwal') }}</a>
                        <div class="modal fade" id="add">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ tr('tambah jadwal') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        </button>
                                    </div>
                                    <form action="{{ url('/4dm1n/jadwal/add') }}" method="post" id="form_add">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="class_id" id="class_add">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">{{ tr('hari') }}</label>
                                                    <select class="form-select form-select-lg" name="day">
                                                        @php $di=0; @endphp
                                                        @foreach (DAY as $subitem)
                                                        @if ($di > 0 && $di < 7) <option value="{{ $di }}">
                                                            {{ $subitem }}</option>
                                                            @endif
                                                            @php $di++; @endphp
                                                            @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">{{ tr('mulai') }}</label>
                                                    <input type="time" class="form-control" name="start" required>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">{{ tr('berakhir') }}</label>
                                                    <input type="time" class="form-control" name="end" required>
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">{{ tr('mata kuliah') }}</label>
                                                    <select class="form-select form-select-lg sel2" name="sks_id" id="sks_add" required>
                                                        <option value="">-- {{ tr('pilih mata kuliah') }} --</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">{{ tr('ruangan') }}</label>
                                                    <select class="form-select form-select-lg" name="room_id">
                                                        <option value="">-- {{ tr('pilih ruangan') }} --</option>


                                                        @foreach ($room_data as $subitem)
                                                        <option value="{{ $subitem->id }}">
                                                            {{ $subitem->name }}
                                                            {{ $subitem->description ? '- ' . $subitem->description : '' }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                            <button type="submit" class="btn btn-primary">
                                                <div class="spinner-border spinner-border-sm d-none" role="status" id="load_add">
                                                    <span class="visually-hidden">{{ tr('loading...') }}</span>
                                                </div>{{ tr('simpan') }}
                                            </button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table id="data-table-1" class="display text-center table-striped">
                        <thead class=" bg-primary-light">
                            <tr>
                                <th class="text-white">{{ tr('hari') }}</th>
                                <th class="text-white">{{ tr('waktu') }}</th>
                                <th class="text-white">{{ tr('sks') }}</th>
                                <th class="text-white">{{ tr('mata kuliah') }}</th>

                                <th class="text-white">{{ tr('ruangan') }}</th>
                                @if (can($key_, 'edit') || can($key_, 'delete'))
                                <th class="text-white" width="30%">{{ tr('dosen') }}</th>
                                <th class="text-white">Jumlah Pertemuan</th>
                                <th class="text-white">{{ tr('aksi') }}</th>
                                @endif
                            </tr>
                        </thead>


                        </tbody>
                    </table>

                    <div class="modal fade" id="edit">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ tr('edit jadwal') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <form action="{{ url('/4dm1n/jadwal/edit') }}" method="post" id="form_edit">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" id="id_edit">
                                    <input type="hidden" name="class_id" id="class_edit">
                                    <div class="modal-body text-start">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">{{ tr('hari') }}</label>
                                                <select class="form-select form-select-lg" name="day" id="day_edit">
                                                    @php $di=-0; @endphp
                                                    @foreach (DAY as $subitem)
                                                    @if ($di > 0 && $di < 7) <option value="{{ $di }}">
                                                        {{ $subitem }}
                                                        </option>
                                                        @endif
                                                        @php $di++; @endphp
                                                        @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">{{ tr('mulai') }}</label>
                                                <input type="time" class="form-control" name="start" id="start_edit" required>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">{{ tr('berakhir') }}</label>
                                                <input type="time" class="form-control" name="end" id="end_edit" required>
                                            </div>
                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">{{ tr('mata kuliah') }}</label>

                                                <select class="form-select form-select-lg sel2" name="sks_id" id="sks_edit">
                                                    <option value="">-- {{ tr('pilih mata kuliah') }} --</option>
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">{{ tr('ruangan') }}</label>
                                                <select class="form-select form-select-lg" name="room_id" id="room_edit">
                                                    <option value="">-- {{ tr('pilih ruangan') }} --</option>


                                                    @foreach ($room_data as $subitem)
                                                    <option value="{{ $subitem->id }}">
                                                        {{ $subitem->name }}
                                                        {{ $subitem->description ? '- ' . $subitem->description : '' }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                        <button type="submit" class="btn btn-primary">
                                            <div class="spinner-border spinner-border-sm d-none" role="status" id="load_edit">
                                                <span class="visually-hidden">{{ tr('loading...') }}</span>
                                            </div>
                                            {{ tr('simpan') }}
                                        </button>
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
                                <form action="{{ url('/4dm1n/jadwal/delete') }}" method="post" id="form_delete">
                                    {{ csrf_field() }}
                                    <div class="modal-body">
                                        <input type="hidden" name="id" id="id_delete">
                                        <p>{{ tr('apakah anda ingin menghapus sks mata kuliah') }} <b id="name_delete"></b>
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                        <button type="submit" class="btn btn-primary">

                                            <div class="spinner-border spinner-border-sm d-none" role="status" id="load_delete">
                                                <span class="visually-hidden">{{ tr('loading...') }}</span>
                                            </div>
                                            {{ tr('hapus') }}
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="lecturer_add">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ tr('tambah dosen untuk jadwal') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <form action="{{ url('/4dm1n/jadwal/lecturer/add') }}" method="post" id="form_lecturer_add">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="schedule_id" id="l_schedule_id_add">
                                    <div class="modal-body text-start">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">{{ tr('dosen') }}</label>
                                                <select class="form-select form-select-lg" name="lecturer_id" id="l_lecturer_add" required>
                                                    <option value="">-- {{ tr('pilih dosen') }} --</option>


                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">{{ tr('status') }}</label>
                                                <select class="form-select form-select-lg" name="sls_id" required>
                                                    <option value="">-- {{ tr('pilih status') }}</option>
                                                    @foreach ($sls_data as $subitem)
                                                    <option value="{{ $subitem->id }}">
                                                        {{ $subitem->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                        <button type="submit" class="btn btn-primary">
                                            <div class="spinner-border spinner-border-sm d-none" role="status" id="load_lecturer_add">
                                                <span class="visually-hidden">{{ tr('loading...') }}</span>
                                            </div>
                                            {{ tr('simpan') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="lecturer_edit">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ tr('edit dosen untuk jadwal') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <form action="{{ url('/4dm1n/jadwal/lecturer/edit') }}" method="post" id="form_lecturer_edit">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" id="l_id_edit">
                                    <div class="modal-body text-start">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">{{ tr('dosen') }}</label>
                                                <select class="form-select form-select-lg" name="lecturer_id" id="l_lecturer_edit" disabled>
                                                    <option value="">-- {{ tr('pilih dosen') }} --</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">{{ tr('status') }}</label>
                                                <select class="form-select form-select-lg" name="sls_id" id="l_sls_edit" required>
                                                    <option value="">-- {{ tr('pilih status') }} --</option>
                                                    @foreach ($sls_data as $subitem)
                                                    <option value="{{ $subitem->id }}">
                                                        {{ $subitem->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                        <button type="submit" class="btn btn-primary">
                                            <div class="spinner-border spinner-border-sm d-none" role="status" id="load_lecturer_edit">
                                                <span class="visually-hidden">{{ tr('loading...') }}</span>
                                            </div>
                                            {{ tr('simpan') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="lecturer_delete">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger">{{ tr('peringatan') }} !!</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <form action="{{ url('/4dm1n/jadwal/lecturer/delete') }}" method="post" id="form_lecturer_delete">
                                    {{ csrf_field() }}
                                    <div class="modal-body">
                                        <input type="hidden" name="id" id="l_id_delete">
                                        <p>{{ tr('apakah anda ingin menghapus dosen') }} <b id="l_name_delete"></b> {{ tr('pada jadwal ini') }}?

                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                        <button type="submit" class="btn btn-primary">

                                            <div class="spinner-border spinner-border-sm d-none" role="status" id="load_lecture_delete">
                                                <span class="visually-hidden">{{ tr('loading...') }}</span>
                                            </div>
                                            {{ tr('hapus') }}
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
@endsection

@section('script')

<script>
    $(document).ready(function() {
        load_class();
    });

    function load_class() {
        var class_id = $('#class_ option:selected');
        $('#class_add').val(class_id.val());

        <?php if (can($key_, 'add')) { ?>
            if (class_id.val()) {
                $('#class_btn_add').removeClass('d-none');
            } else {
                $('#class_btn_add').addClass('d-none');
            }
        <?php } ?>

        if (class_id.val()) {
            sks_list();
            lecturer_list();
        }


        var table = $('#data-table-1').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            searching: false,
            paging: false,
            ordering: false,
            rowsGroup: [0, 3, 4],
            ajax: {
                dataType: "JSON",
                type: "POST",
                url: "{{ url('/4dm1n/jadwal/ajax/table') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    class_id: class_id.val(),

                },
                async: true,
                error: function(xhr, error, code) {
                    console.log(xhr);
                    console.log(code);
                }
            },
            destroy: true,
            columns: [{
                    data: 'days',
                    name: 'days',
                },
                {
                    data: 'time',
                    name: 'time',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).css('background-color', 'red')
                    }
                },
                {
                    data: 'sks.value',
                    name: 'sks.value',
                },
                {
                    data: 'sks.subject.name',
                    name: 'sks.subject.name',
                },
                {
                    data: 'room.name',
                    name: 'room.name',
                }
                <?php if (can($key_, 'edit') || can($key_, 'delete')) { ?>, {
                        class: 'align-middle',
                        data: 'lecturer',
                        name: 'lecturer',
                    }, {
                        class: 'align-middle',
                        data: 'total_meeting',
                        name: 'total_meeting',
                    }, {
                        class: 'align-middle',
                        data: 'action',
                        name: 'action',
                    }
                <?php } ?>

            ],

            language: {
                paginate: {
                    next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                },
                processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">' +
                    '<?php echo tr('loading...') ?>' + '</span></div></div>',
                info: "<br> Records _START_ to _END_ of _MAX_ entries",
            },

        });
    }

    function load_semester() {
        var semester_ = $('#semester_ option:selected').val();
        window.location.href = "{{ url('4dm1n/jadwal?semester=') }}" + semester_;
    }
</script>

<script>
    $(document).on('submit', '#form_add', function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.

        var formData = new FormData(this);
        var actionUrl = $(this).attr('action');
        $('#load_add').removeClass('d-none');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData, // serializes the form's elements.
            success: function(data) {
                if (data.code == 1) {
                    show_toast(1, data.message);
                    load_class();
                    $('#add').modal('hide');
                    $('#form_add')[0].reset();
                } else {
                    show_toast(0, data.message);
                }
                $('#load_add').addClass('d-none');
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

    $(document).on('submit', '#form_edit', function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.

        var formData = new FormData(this);
        var actionUrl = $(this).attr('action');
        $('#load_edit').removeClass('d-none');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData, // serializes the form's elements.
            success: function(data) {
                if (data.code == 1) {
                    show_toast(1, data.message);
                    load_class();
                    $('#edit').modal('hide');
                } else {
                    show_toast(0, data.message);
                }
                $('#load_edit').addClass('d-none');
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
                    load_class();
                    $('#delete').modal('hide');
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

    $(document).on('submit', '#form_lecturer_add', function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.

        var formData = new FormData(this);
        var actionUrl = $(this).attr('action');
        $('#load_lecturer_add').removeClass('d-none');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData, // serializes the form's elements.
            success: function(data) {
                if (data.code == 1) {
                    show_toast(1, data.message);
                    load_class();
                    $('#lecturer_add').modal('hide');
                    $('#form_lecturer_add')[0].reset();
                } else {
                    show_toast(0, data.message);
                }
                $('#load_lecturer_add').addClass('d-none');
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

    $(document).on('submit', '#form_lecturer_edit', function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.

        var formData = new FormData(this);
        var actionUrl = $(this).attr('action');
        $('#load_lecturer_edit').removeClass('d-none');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData, // serializes the form's elements.
            success: function(data) {
                if (data.code == 1) {
                    show_toast(1, data.message);
                    load_class();
                    $('#lecturer_edit').modal('hide');
                } else {
                    show_toast(0, data.message);
                }
                $('#load_lecturer_edit').addClass('d-none');
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

    $(document).on('submit', '#form_lecturer_delete', function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.

        var formData = new FormData(this);
        var actionUrl = $(this).attr('action');
        $('#load_lecturer_delete').removeClass('d-none');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData, // serializes the form's elements.
            success: function(data) {
                if (data.code == 1) {
                    show_toast(1, data.message);
                    load_class();
                    $('#lecturer_delete').modal('hide');
                } else {
                    show_toast(0, data.message);
                }
                $('#load_lecturer_delete').addClass('d-none');
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
    function show_delete(id, name) {
        $('#name_delete').html(name);
        $('#id_delete').val(id);
        $('#delete').modal('show');
    }

    function show_lecture_delete(id, name) {

        $('#l_name_delete').html(name);
        $('#l_id_delete').val(id);
        $('#lecturer_delete').modal('show');
    }

    function show_lecture_edit(id, sls_id, lecturer_id) {
        $('#l_id_edit').val(id);
        $('#l_sls_edit').val(sls_id).change();

        $('#l_lecturer_edit').val(lecturer_id);
        $('#lecturer_edit').modal('show');
    }

    function show_lecture_add(id) {

        $('#l_schedule_id_add').val(id);
        $('#lecturer_add').modal('show');
    }

    function show_edit(id) {

        $.ajax({
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id
            },
            url: "{{ url('4dm1n/jadwal/ajax/id') }}",
            success: function(data) {
                console.log(data);
                if (data.message == "success") {
                    var el = data.result;
                    $('#id_edit').val(el.id);
                    $('#class_edit').val(el.class_id);

                    $('#start_edit').val(el.start);
                    $('#end_edit').val(el.end);

                    $('#day_edit').val(el.day).change();
                    $('#sks_edit').val(el.sks_id).change();
                    $('#room_edit').val(el.room_id).change();

                    $('#edit').modal('show');
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

<script>
    function sks_list() {

        var class_ = $('#class_ option:selected');
        var sks_add = $('#sks_add');
        var sks_edit = $('#sks_edit');
        $.ajax({
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                class_id: class_.val(),
            },
            url: "{{ url('4dm1n/jadwal/ajax/sks') }}",
            success: function(data) {
                console.log(data);
                if (data.message == "success") {
                    var el = data.result;
                    sks_add.empty();
                    sks_edit.empty();
                    sks_add.append(`<option value="">-- {{ tr('pilih mata kuliah') }} --</option>`);
                    sks_edit.append(`<option value="">-- {{ tr('pilih mata kuliah') }} --</option>`);
                    for (var i = 0; i < el.length; i++) {
                        var row = el[i];
                        sks_add.append(`<option value="${row.id}">${row.name}</option>`);
                        sks_edit.append(`<option value="${row.id}">${row.name}</option>`);

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

    function lecturer_list() {

        var class_ = $('#class_ option:selected');
        var lecturer_add = $('#l_lecturer_add');
        var lecturer_edit = $('#l_lecturer_edit');
        $.ajax({
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                class_id: class_.val(),
            },
            url: "{{ url('4dm1n/jadwal/ajax/lecturer') }}",
            success: function(data) {
                console.log(data);
                if (data.message == "success") {
                    var el = data.result;
                    lecturer_add.empty();
                    lecturer_edit.empty();
                    lecturer_add.append(`<option value="">-- {{ tr('pilih dosen') }} --</option>`);
                    lecturer_edit.append(`<option value="">-- {{ tr('pilih dosen') }} --</option>`);
                    for (var i = 0; i < el.length; i++) {
                        var row = el[i];
                        lecturer_add.append(`<option value="${row.id}">${row.name}</option>`);
                        lecturer_edit.append(`<option value="${row.id}">${row.name}</option>`);
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
</script>


@endsection