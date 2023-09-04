@extends('admin/master')

@section('title', 'Mata kuliah')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Master data</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Mata kuliah</a></li>
        </ol>
    </div>
@endsection

@section('content')
    @php $key_='Mata kuliah'; @endphp
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row" style="width:110%;">

                        <div class="col-12">
                            @if (can($key_, 'add'))
                                <a class="btn  btn-primary float-end" data-bs-toggle="modal" href="#add"><span
                                        class="btn-icon-start text-primary"><i class="fa fa-plus color-primary"></i>
                                    </span>Tambah mata kuliah</a>
                                <div class="modal fade" id="add">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tambah mata kuliah</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>
                                            </div>
                                            <form action="{{ url('/4dm1n/matkul/add') }}" method="post">
                                                {{ csrf_field() }}
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="mb-3 col-md-12">
                                                            <label class="form-label">Nama mata kuliah</label>
                                                            <input type="text" class="form-control" name="name"
                                                                required>
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
                                    <th>Nama</th>
                                    <th>Dibuat</th>
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
                                    <h5 class="modal-title">Edit mata kuliah</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <form action="{{ url('/4dm1n/matkul/edit') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" id="id_edit">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">Nama mata kuliah</label>
                                                <input type="text" class="form-control" name="name" id="name_edit"
                                                    required>
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
                                    <p>Apakah anda ingin menghapus mata kuliah
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


            var table = $('#data-table-1').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/4dm1n/matkul/ajax/table') }}",
                    data: {
                        _token: '{{ csrf_token() }}'

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
                        data: 'time',
                        name: 'time',
                    }
                    @if (can($key_, 'edit') || can($key_, 'delete'))
                        , {
                            data: 'action',
                            name: 'action'
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
            $("#button_delete").attr("href", "{{ url('4dm1n/matkul/delete') }}/" + id)
            $('#delete').modal('show');
        }

        function show_edit(id) {

            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                url: "{{ url('4dm1n/matkul/ajax/id') }}",
                success: function(data) {
                    console.log(data);
                    if (data.message == "success") {
                        var el = data.result;
                        $('#id_edit').val(el.id);
                        $('#name_edit').val(el.name);


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
