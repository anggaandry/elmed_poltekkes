@extends('admin/master')

@section('title', 'Mahasiswa')

@section('breadcrumb')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('mahasiswa') }}</a></li>
    </ol>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css" integrity="" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
@php $key_='Mahasiswa' @endphp
<div class="row">
    <div class="col-xl-12 col-xxl-12">
        <div class="card">
            <div class="card-header">
                <div class="row" style="width:110%;">
                    <div class="col-2">
                        <label class="form-label text-left">{{ tr('status') }}</label>
                        <select class="form-select form-select-lg" id="status_" onchange="load_table()">
                            <option value="1" @if ($status_id==1) selected @endif>{{ tr('aktif') }}</option>
                            <option value="2" @if ($status_id==2) selected @endif>{{ tr('lulus') }}</option>
                            <option value="3" @if ($status_id==3) selected @endif>{{ tr('d.o') }}</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label text-left">{{ tr('prodi') }}</label>
                        <select class="form-select form-select-lg" id="prodi_" onchange="load_table()" @if (can_prodi()) disabled @endif>
                            <option value="">{{ tr('semua prodi') }}</option>
                            @foreach ($prodi_data as $item)
                            <option value="{{ $item->id }}" @if ($prodi_id==$item->id) selected @endif>
                                {{ $item->program->name }}
                                {{ $item->study_program->name }} - {{ $item->category->name }}
                            </option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-3">
                        <label class="form-label text-left">{{ tr('tahun akademik') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control" value="{{ semester_now()->year }}" id="year_" oninput="load_table()" required>
                            <span class="input-group-text border-0" id="next_year">{{ semester_now()->year + 1 }}</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <button class="btn  btn-primary float-end" onclick="add();"><span class="btn-icon-start text-primary"><i class="fa fa-plus color-primary"></i>
                            </span>{{ tr('tambah mahasiswa') }}</button>
                        <button class="btn  btn-success float-end" onclick="show_import();" style="margin-top: 10px;"><span class="btn-icon-start text-primary"><i class="fa fa-plus color-primary"></i>
                            </span>{{ tr('import data mahasiswa') }}</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table-1" class="display text-center table-striped">
                        <thead class="">
                            <tr>
                                <th>{{ tr('#') }}</th>
                                <th>{{ tr('avatar') }}</th>
                                <th>{{ tr('nama') }}</th>
                                <th>{{ tr('gender') }}</th>
                                <th>{{ tr('nim') }}</th>
                                <th>{{ tr('prodi') }}</th>
                                <th>{{ tr('status') }}</th>
                                <th>{{ tr('aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
                                <p>{{ tr('apakah anda ingin menghapus mahasiswa') }} <b id="name_delete"></b></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                <a id="button_delete" class="btn btn-primary">{{ tr('hapus') }}</a>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal fade" id="import_modal">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ tr('import mahasiswa') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <form action="{{ url('4dm1n/mahasiswa/import') }}" method="post" enctype="multipart/form-data">

                                <div class="modal-body">
                                    @csrf
                                    <div class="form-group">
                                        <label for="">{{ tr('format excel') }}</label>
                                        <br>
                                        <a href="{{ asset('import/contoh_format_import.xlsx') }}" class="btn btn-xs btn-success">{{ tr('download format excel') }}</a>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="">{{ tr('file') }}</label>
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{ tr('prodi') }}</label>
                                        <select class="form-select form-select-lg" name="prodi_id" @if (can_prodi()) disabled @endif>
                                            <option value="">{{ tr('semua prodi') }}</option>
                                            @foreach ($prodi_data as $item)
                                            <option value="{{ $item->id }}" @if ($prodi_id==$item->id) selected @endif>
                                                {{ $item->program->name }}
                                                {{ $item->study_program->name }} - {{ $item->category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{ tr('tahun ajaran') }}</label>
                                        <input type="number" class="form-control" value="{{ semester_now()->year }}" name="year" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ tr('submit') }}</button>
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

<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
    $(document).ready(function() {
        load_table();
    });

    function load_table() {
        var prodi_id = $('#prodi_ option:selected').val();
        var status_id = $('#status_ option:selected').val();

        var year = $('#year_').val();

        var show_value = parseInt(year) + 1;
        $('#next_year').html(" /" + show_value + " ");

        //alert(prodi_id);
        var table = $('#data-table-1').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                dataType: "JSON",
                type: "POST",
                url: "{{ url('/4dm1n/mahasiswa/ajax/table') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    prodi_id: prodi_id,
                    year: year,
                    status_id: status_id
                },
                async: true,
                error: function(xhr, error, code) {
                    console.log(xhr);
                    console.log(code);
                }
            },
            destroy: true,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            // dom: 'Bfrtip',
            dom: 'lBfrtip',
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'avatar',
                    name: 'avatar',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'gender',
                    name: 'gender',
                },
                {
                    data: 'nim',
                    name: 'nim',
                },
                {
                    data: 'prodi',
                    name: 'prodi',
                },

                {
                    data: 'status_view',
                    name: 'status_view',
                },
                {
                    data: 'action',
                    name: 'action'
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
    function show_delete(id, name) {
        $('#name_delete').html(name);
        $("#button_delete").attr("href", "{{ url('4dm1n/mahasiswa/delete') }}/" + id)
        $('#delete').modal('show');
    }

    function show_import() {
        // $('#name_delete').html(name);
        // $("#button_delete").attr("href", "{{ url('4dm1n/mahasiswa/delete') }}/" + id)
        $('#import_modal').modal('show');
    }

    function add() {
        var prodi_id = $('#prodi_ option:selected').val();
        window.location.href = "{{ url('4dm1n/mahasiswa/form/add?prodi_id=') }}" + prodi_id;
    }
</script>


@endsection