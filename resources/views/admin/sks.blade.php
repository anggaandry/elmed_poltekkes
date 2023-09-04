@extends('admin/master')

@section('title', 'SKS')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Akademik</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Matkul prodi</a></li>
        </ol>
    </div>
@endsection

@section('content')
    @php $key_='SKS'; @endphp
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row" style="width:110%;">
                        <div class="col-6">
                            <label class="form-label text-left">Prodi</label>
                            <select class="form-select form-select-lg" id="prodi_" onchange="load_table()"
                                @if (can_prodi()) disabled @endif>
                                <option value="">Semua prodi </option>
                                @foreach ($prodi_data as $item)
                                    <option value="{{ $item->id }}" @if ($prodi_id == $item->id) selected @endif>
                                        {{ $item->program->name }}
                                        {{ $item->study_program->name }} - {{ $item->category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6">
                            @if (can($key_, 'add'))
                                <a class="btn  btn-primary float-end" data-bs-toggle="modal" href="#add"><span
                                        class="btn-icon-start text-primary"><i class="fa fa-plus color-primary"></i>
                                    </span>Tambah Matkul Prodi</a>
                                <div class="modal fade" id="add">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tambah Matkul Prodi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>
                                            </div>
                                            <form action="{{ url('/4dm1n/sks/add') }}" method="post">
                                                {{ csrf_field() }}
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="mb-3 col-md-12">
                                                            <label class="form-label">Prodi</label>
                                                            @if (can_prodi())
                                                                <input type="hidden" name="prodi_id"
                                                                    value="{{ can_prodi() }}">
                                                            @endif
                                                            <select class="form-select form-select-lg" name="prodi_id"
                                                                @if (can_prodi()) disabled @else required @endif>
                                                                <option value="">-- Pilih prodi-- </option>
                                                                @foreach ($prodi_data as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if (can_prodi()) @if (can_prodi() == $item->id) 
                                                                                selected @endif
                                                                        @endif>
                                                                        {{ $item->program->name }}
                                                                        {{ $item->study_program->name }} -
                                                                        {{ $item->category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3 col-md-12">
                                                            <label class="form-label">Mata kuliah</label>
                                                            <select class="form-select form-select-lg sel2"
                                                                name="subject_id" required>
                                                                <option value="">-- Pilih Mata kuliah -- </option>
                                                                @foreach ($subject_data as $item)
                                                                    <option value="{{ $item->id }}">
                                                                        {{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3 col-md-6">
                                                            <label class="form-label">Kode MK</label>
                                                            <input type="text" name="code" class="form-control"
                                                                required>
                                                        </div>

                                                        <div class="mb-3 col-md-6">
                                                            <label class="form-label">Semester</label>
                                                            <select class="form-select form-select-lg" name="semester">
                                                                @for ($i = 1; $i < 9; $i++)
                                                                    <option value="{{ $i }}">
                                                                        Semester {{ $i }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>

                                                        <div class="mb-3 col-md-6">
                                                            <label class="form-label">Bobot SKS</label>
                                                            <input type="number" name="value" class="form-control"
                                                                required>
                                                        </div>

                                                        <div class="mb-3 col-md-6">
                                                            <label class="form-label">Status</label>
                                                            <select class="form-select form-select-lg" name="status">
                                                                <option value="1"> Aktif</option>
                                                                <option value="0"> Tidak Aktif</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger light"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>Prodi</th>
                                    <th>Kode</th>
                                    <th>Matkul</th>
                                    <th>Semester</th>
                                    <th>Bobot SKS</th>
                                    <th>Status</th>
                                    @if (can($key_, 'edit') || can($key_, 'delete'))
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>

                    <div class="modal fade" id="edit">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Matkul Prodi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <form action="{{ url('/4dm1n/sks/edit') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" id="id_edit">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">Prodi</label>
                                                @if (can_prodi())
                                                    <input type="hidden" name="prodi_id" value="{{ can_prodi() }}">
                                                @endif
                                                <select class="form-select form-select-lg" name="prodi_id"
                                                    id="prodi_id_edit"
                                                    @if (can_prodi()) disabled @else required @endif>
                                                    <option value="">-- Pilih prodi-- </option>
                                                    @foreach ($prodi_data as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->program->name }}
                                                            {{ $item->study_program->name }} -
                                                            {{ $item->category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">Mata kuliah</label>
                                                <select class="form-select form-select-lg sel2" name="subject_id"
                                                    id="subject_id_edit" required>
                                                    <option value="">-- Pilih Mata kuliah -- </option>
                                                    @foreach ($subject_data as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Kode MK</label>
                                                <input type="text" name="code" id="code_edit" class="form-control"
                                                    required>
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Semester</label>
                                                <select class="form-select form-select-lg" name="semester"
                                                    id="semester_edit">
                                                    @for ($i = 1; $i < 9; $i++)
                                                        <option value="{{ $i }}">
                                                            Semester {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Bobot SKS</label>
                                                <input type="number" name="value" class="form-control"
                                                    id="value_edit" required>
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Status</label>
                                                <select class="form-select form-select-lg" name="status"
                                                    id="status_edit">
                                                    <option value="1"> Aktif</option>
                                                    <option value="0"> Tidak Aktif</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger light"
                                            data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="delete">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger">Peringatan !!</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <p>Apakah anda ingin menghapus Matkul Prodi
                                        <b id="name_delete"></b>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <a id="button_delete" class="btn btn-primary">Hapus</a>
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

    <script type="text/javascript">
        $(document).ready(function() {
            load_table();
        });

        function load_table() {
            var prodi_id = $('#prodi_ option:selected').val();

            var table = $('#data-table-1').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/4dm1n/sks/ajax/table') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        prodi_id: prodi_id
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
                        data: 'prodi',
                        name: 'prodi',
                    }, {
                        data: 'code',
                        name: 'code',
                    }, {
                        data: 'subject.name',
                        name: 'subject.name',
                    }, {
                        data: 'semester',
                        name: 'semester',
                    }, {
                        data: 'value',
                        name: 'value',
                    }, {
                        data: 'status_view',
                        name: 'status_view',
                    }
                    @if (can($key_, 'edit') || can($key_, 'delete'))
                        , {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    @endif
                ],
                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    },
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                    info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
                },

            });
        }
    </script>

    <script>
        function show_delete(id, name) {
            $('#name_delete').html(name);
            $("#button_delete").attr("href", "{{ url('4dm1n/sks/delete') }}/" + id)
            $('#delete').modal('show');
        }

        function show_edit(id) {

            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                url: "{{ url('4dm1n/sks/ajax/id') }}",
                success: function(data) {
                    console.log(data);
                    if (data.message == "success") {
                        var el = data.result;
                        $('#id_edit').val(el.id);
                        $('#code_edit').val(el.code);
                        $('#value_edit').val(el.value);

                        $('#status_edit').val(el.status).change();
                        $('#semester_edit').val(el.semester).change();
                        $('#prodi_id_edit').val(el.prodi_id).change();
                        $('#subject_id_edit').val(el.subject_id).change();

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


@endsection
