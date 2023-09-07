@extends('admin/master')

@section('title', 'Akun dosen')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('akun') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('dosen') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    @php $key_='Akun dosen' @endphp
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row w-100">
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
                                    <th>{{ tr('nidn') }}</th>
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



                    <div class="modal fade" id="active">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger">{{ tr('peringatan') }} !!</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <p>{{ tr('apakah anda ingin mengaktifkan akun') }} <b id="name_active"></b>

                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                    <a id="button_active" class="btn btn-primary">{{ tr('aktifkan') }}</a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="disactive">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger">{{ tr('peringatan') }} !!</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <p>{{ tr('apakah anda ingin menonaktifkan akun') }} <b id="name_disactive"></b>

                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                    <a id="button_disactive" class="btn btn-primary">{{ tr('non-aktifkan') }}</a>
                                </div>

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
            var table = $('#data-table-1').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/4dm1n/akun/dosen/ajax/table') }}",
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
                        data: 'avatar',
                        name: 'avatar',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'name_view',
                        name: 'name_view',
                    },
                    {
                        data: 'identity_number',
                        name: 'iden',
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
        function show_active(id, name) {
            var prodi_id = $('#prodi_ option:selected').val();
            $('#name_active').html(name);
            $("#button_active").attr("href", "{{ url('4dm1n/akun/dosen/status?id=') }}" +
                id + "&status=1" + "&prodi_id=" + prodi_id)
            $('#active').modal('show');
        }

        function show_disactive(id, name) {
            var prodi_id = $('#prodi_ option:selected').val();
            $('#name_disactive').html(name);
            $("#button_disactive").attr("href", "{{ url('4dm1n/akun/dosen/status?id=') }}" +
                id + "&status=0" + "&prodi_id=" + prodi_id)
            $('#disactive').modal('show');
        }

        function show_respass(id, name) {
            var prodi_id = $('#prodi_ option:selected').val();
            $('#name_respass').html(name);
            $("#button_respass").attr("href",
                "{{ url('4dm1n/akun/dosen/password/reset?') }}id=" +
                id + "&prodi_id=" + prodi_id)
            $('#respass').modal('show');
        }
    </script>


@endsection
