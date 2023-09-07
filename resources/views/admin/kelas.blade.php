@extends('admin/master')

@section('title', 'Kelas')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('akademik') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('kelas') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    @php $key_='Kelas'; @endphp
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row" style="width:110%;">
                        <div class="col-4">
                            <label class="form-label text-left">{{ tr('prodi') }}</label>
                            <select class="form-select form-select-lg" id="prodi_" onchange="load_table()" @if (can_prodi()) disabled @endif>
                                <option value="">{{ tr('semua prodi') }}</option>
                                @foreach ($prodi_data as $item)
                                    <option value="{{ $item->id }}" @if ($prodi_id == $item->id) selected @endif>
                                        {{ $item->program->name }}
                                        {{ $item->study_program->name }} - {{ $item->category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <label class="form-label text-left">{{ tr('semester') }}</label>
                            <select class="form-select form-select-lg" id="odd_" onchange="load_table()">
                                <option value="1" @if ($odd == 1) selected @endif>{{ tr('ganjil') }}</option>
                                <option value="2" @if ($odd == 2) selected @endif>{{ tr('genap') }}</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label text-left">{{ tr('tahun akademik') }}</label>
                            <div class="input-group">
                                <input type="number" class="form-control" value="{{ $year }}" id="year_" oninput="load_table()" required>
                                <span class="input-group-text border-0" id="next_year">{{ $year + 1 }}</span>
                            </div>
                        </div>

                        <div class="col-3">
                            @if (can($key_, 'add'))
                                <a class="btn  btn-primary float-end" data-bs-toggle="modal" href="#add"><span class="btn-icon-start text-primary"><i class="fa fa-plus color-primary"></i>
                                    </span>{{ tr('tambah kelas') }}</a>
                                <div class="modal fade" id="add">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ tr('tambah kelas') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>
                                            </div>
                                            <form action="{{ url('/4dm1n/kelas/add') }}" method="post">
                                                {{ csrf_field() }}
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="mb-3 col-md-12">
                                                            <label class="form-label">{{ tr('prodi') }}</label>
                                                            @if (can_prodi())
                                                                <input type="hidden" name="prodi_id" value="{{ can_prodi() }}">
                                                            @endif
                                                            <select class="form-select form-select-lg" name="prodi_id" @if (can_prodi()) disabled @else required @endif>
                                                                <option value="">-- {{ tr('pilih prodi') }} --</option>
                                                                @foreach ($prodi_data as $item)
                                                                <option value="{{ $item->id }}" @if (can_prodi()) @if (can_prodi() == $item->id) 
                                                                                selected @endif @else @if ($prodi_id == $item->id) selected @endif @endif>
                                                                        {{ $item->program->name }}
                                                                        {{ $item->study_program->name }} -
                                                                        {{ $item->category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3 col-md-12">
                                                            <label class="form-label">{{ tr('nama kelas') }}</label>
                                                            <input type="text" name="name" class="form-control" required>
                                                        </div>


                                                        <div class="mb-3 col-md-4">
                                                            <label class="form-label">{{ tr('tahun akademik') }}</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="year" value="{{ $year }}" oninput="next_add(this.value)" required>
                                                                <span class="input-group-text border-0" id="next_year_add">{{ $year + 1 }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3 col-md-4">
                                                            <label class="form-label">{{ tr('ganjil') }}/{{ tr('genap') }}</label>
                                                            <select class="form-select form-select-lg" name="odd">
                                                                <option value="1" @if ($odd == 1) selected @endif>{{ tr('ganjil') }}</option>
                                                                <option value="2" @if ($odd == 2) selected @endif>{{ tr('genap') }}</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3 col-md-4">
                                                            <label class="form-label">{{ tr('semester') }}</label>
                                                            <input type="number" name="semester" class="form-control" required>
                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                    <button type="submit" class="btn btn-primary">{{ tr('simpan') }}</button>
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
                                    <th>{{ tr('nama kelas') }}</th>
                                    <th>{{ tr('prodi') }}</th>
                                    <th>{{ tr('semester') }}</th>
                                    <th>{{ tr('jumlah') }}<br>{{ tr('mahasiswa') }}</th>
                                    <th>{{ tr('ta') }}</th>

                                    <th>{{ tr('aksi') }}</th>

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
                                    <h5 class="modal-title">{{ tr('edit kelas') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <form action="{{ url('/4dm1n/kelas/edit') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" id="id_edit">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">{{ tr('prodi') }}</label>
                                                @if (can_prodi())
                                                    <input type="hidden" name="prodi_id" value="{{ can_prodi() }}">
                                                @endif
                                                <select class="form-select form-select-lg" name="prodi_id" id="prodi_id_edit" @if (can_prodi()) disabled @else required @endif>
                                                    <option value="">-- {{ tr('pilih prodi') }} --</option>
                                                    @foreach ($prodi_data as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->program->name }}
                                                            {{ $item->study_program->name }} -
                                                            {{ $item->category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">{{ tr('nama kelas') }}</label>
                                                <input type="text" name="name" id="name_edit" class="form-control" required>
                                            </div>


                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">{{ tr('tahun akademik') }}</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="year" id="year_edit" oninput="next_edit(this.value)" required>
                                                    <span class="input-group-text border-0" id="next_year_edit"></span>
                                                </div>
                                            </div>

                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">{{ tr('ganjil') }}/{{ tr('genap') }}</label>
                                                <select class="form-select form-select-lg" name="odd" id="odd_edit">
                                                    <option value="1">{{ tr('ganjil') }}</option>
                                                    <option value="2">{{ tr('genap') }}</option>
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">{{ tr('semester') }}</label>
                                                <input type="number" name="semester" id="semester_edit" class="form-control" required>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                        <button type="submit" class="btn btn-primary">{{ tr('simpan') }}</button>
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

                                <div class="modal-body">
                                    <p>{{ tr('apakah anda ingin menghapus kelas') }} <b id="name_delete"></b>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                    <a id="button_delete" class="btn btn-primary">{{ tr('hapus') }}</a>
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
            var year = $('#year_').val();
            var odd = $('#odd_ option:selected').val();

            var show_value = parseInt(year) + 1;
            $('#next_year').html(" /" + show_value + " ");

            var table = $('#data-table-1').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/4dm1n/kelas/ajax/table') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        prodi_id: prodi_id,
                        year: year,
                        odd: odd
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
                        data: 'name',
                        name: 'name',
                    }, {
                        data: 'prodi',
                        name: 'prodi',
                    }, {
                        data: 'semester',
                        name: 'semester',
                    }, {
                        data: 'colleger',
                        name: 'colleger',
                    }, {
                        data: 'year_view',
                        name: 'year_view',
                    }

                    , {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }

                ],
                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    },
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">{{ tr('loading...') }}</span></div></div>',
                    info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
                },

            });
        }
    </script>

    <script>
        function show_delete(id, name) {
            $('#name_delete').html(name);
            $("#button_delete").attr("href", "{{ url('4dm1n/kelas/delete') }}/" + id)
            $('#delete').modal('show');
        }

        function show_edit(id) {

            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                url: "{{ url('4dm1n/kelas/ajax/id') }}",
                success: function(data) {
                    console.log(data);
                    if (data.message == "success") {
                        var el = data.result;
                        $('#id_edit').val(el.id);
                        $('#name_edit').val(el.name);
                        $('#odd_edit').val(el.odd);
                        $('#semester_edit').val(el.semester);
                        $('#year_edit').val(el.year);
                        $('#prodi_id_edit').val(el.prodi_id).change();

                        $('#next_year_edit').html(" /" + (el.year + 1) + " ");

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
        function next_add(value) {
            value++;
            $('#next_year_add').html(" /" + value + " ");
        }

        function next_edit(value) {
            value++;
            $('#next_year_edit').html(" /" + value + " ");
        }
    </script>


@endsection
