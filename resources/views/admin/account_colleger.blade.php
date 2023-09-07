@extends('admin/master')

@section('title', 'Akun mahasiswa')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('akun') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('mahasiswa') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    @php $key_='Akun mahasiswa' @endphp
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row w-100">
                        <div class="col-3">
                            <label class="form-label text-left">{{ tr('status') }}</label>
                            <select class="form-select form-select-lg" id="status_" onchange="load_table()">
                                <option value="1" @if ($status_id == 1) selected @endif>{{ tr('aktif') }}</option>
                                <option value="2" @if ($status_id == 2) selected @endif>{{ tr('lulus') }}</option>
                                <option value="3" @if ($status_id == 3) selected @endif>{{ tr('d.o') }}</option>
                            </select>
                        </div>
                        <div class="col-5">
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table-1" class="display text-center table-striped">
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>{{ tr('avatar') }}</th>
                                    <th>{{ tr('nama') }}</th>
                                    <th>{{ tr('nim') }}</th>
                                    <th>{{ tr('prodi') }}</th>
                                    <th>{{ tr('online terakhir') }}</th>
                                    <th>{{ tr('status') }}</th>
                                    @if (can($key_, 'edit'))
                                        <th>{{ tr('aksi') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>

                    <div class="modal fade" id="status">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ tr('ganti status') }} <b id="name_status"></b></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <form action="{{ url('/4dm1n/akun/mahasiswa/status') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" id="id_status">
                                    <input type="hidden" name="prodi_id" id="prodi_id_status">
                                    <input type="hidden" name="status_id" id="status_id_status">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label text-left">{{ tr('status') }}</label>
                                                <select class="form-select form-select-lg" name="status" id="status_status">
                                                    <option value="1">{{ tr('aktif') }}</option>
                                                    <option value="2">{{ tr('lulus') }}</option>
                                                    <option value="3">{{ tr('d.o') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                        <button type="submit" class="btn btn-primary">{{ tr('update') }}</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="respass">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger">{{ tr('peringatan') }} !!</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <p>{{ tr('apakah anda ingin mereset password akun') }} <b id="name_respass"></b>

                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                    <a id="button_respass" class="btn btn-primary">{{ tr('reset password') }}</a>
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
            var status_id = $('#status_ option:selected').val();
            //alert(prodi_id);
            var table = $('#data-table-1').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/4dm1n/akun/mahasiswa/ajax/table') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        prodi_id: prodi_id,
                        status_id: status_id
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
                        searchable: false
                    },

                    {
                        data: 'name',
                        name: 'name',
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
                        data: 'online',
                        name: 'online',
                    },
                    {
                        data: 'status_view',
                        name: 'status_view',
                    }
                    @if (can($key_, 'edit'))
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
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">{{ tr('loading...') }}</span></div></div>',
                    info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
                },

            });
        }
    </script>

    <script>
        function show_status(id, status, name) {
            var prodi_id = $('#prodi_ option:selected').val();
            var status_id = $('#status_ option:selected').val();

            $('#name_status').html(name);
            $('#id_status').val(id);
            $('#prodi_id_status').val(prodi_id);
            $('#status_id_status').val(status_id);
            $('#status_status').val(status).change();
            $('#status').modal('show');
        }

        function show_respass(id, name) {
            var prodi_id = $('#prodi_ option:selected').val();
            var status_id = $('#status_ option:selected').val();


            $('#name_respass').html(name);
            $("#button_respass").attr("href", "{{ url('4dm1n/akun/mahasiswa/password/reset?id=') }}" +
                id + "&prodi_id=" +
                prodi_id + "&status_id=" + status_id)
            $('#respass').modal('show');
        }
    </script>


@endsection
