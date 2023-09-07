@extends('admin/master')

@section('title', 'Akun admin')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('akun') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('admin') }}</a></li>
        </ol>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css" integrity="" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    @php $key_='Admin'; @endphp
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                @if (can($key_, 'add'))
                    <div class="card-header">
                        <div style="width:100%;">
                            <a class="btn  btn-primary float-end" data-bs-toggle="modal" href="#add"><span class="btn-icon-start text-primary"><i class="fa fa-plus color-primary"></i>
                                </span>{{ tr('tambah akun') }}</a>
                            <div class="modal fade" id="add">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ tr('tambah akun') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                            </button>
                                        </div>
                                        <form action="{{ url('/4dm1n/akun/admin/add') }}" method="post" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">{{ tr('foto profil') }}</label>
                                                        <input type="file" class="dropify" name="avatar" height="200" />
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ tr('nama lengkap') }}</label>
                                                        <input type="text" class="form-control" name="name" required>
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ tr('username') }}</label>
                                                        <input type="text" class="form-control" name="nip" required>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ tr('role') }}</label>
                                                        <select class="form-select form-select-lg" name="role_id">
                                                            @foreach ($role_data as $obj)
                                                                <option value="{{ $obj->id }}">
                                                                    {{ $obj->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ tr('email') }}</label>
                                                        <input type="text" class="form-control" name="email">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ tr('no hp.') }}</label>
                                                        <input type="text" class="form-control" name="phone">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ tr('tanggal lahir') }}</label>
                                                        <input type="date" class="form-control" name="birthdate" required>
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
                        </div>
                    </div>
                @endif

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table-1" class="display text-center table-striped">
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>{{ tr('avatar') }}</th>
                                    <th>{{ tr('nama') }}</th>
                                    <th>{{ tr('role') }}</th>
                                    <th>{{ tr('username') }}</th>
                                    <th>{{ tr('tanggal lahir') }}</th>
                                    <th>{{ tr('online terakhir') }}</th>
                                    <th>{{ tr('status') }}</th>
                                    @if (can($key_, 'edit') || can($key_, 'delete'))
                                        <th>{{ tr('aksi') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>

                    <div class="modal fade" id="edit">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ tr('edit akun') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <form action="{{ url('/4dm1n/akun/admin/edit') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" id="id_edit">
                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">{{ tr('nama lengkap') }}</label>
                                                <input type="text" class="form-control" name="name" id="name_edit" required>
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">{{ tr('username') }}</label>
                                                <input type="text" class="form-control" name="nip" id="nip_edit" required>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">{{ tr('role') }}</label>
                                                <select class="form-select form-select-lg" name="role_id" id="role_edit">
                                                    @foreach ($role_data as $obj)
                                                        <option value="{{ $obj->id }}">
                                                            {{ $obj->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">{{ tr('email') }}</label>
                                                <input type="text" class="form-control" name="email" id="email_edit">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">{{ tr('no hp.') }}</label>
                                                <input type="text" class="form-control" name="phone" id="phone_edit">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">{{ tr('tanggal lahir') }}</label>
                                                <input type="date" class="form-control" name="birthdate" id="birthdate_edit" required>
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

                    <div class="modal fade" id="avatar">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ tr('edit avatar') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <form action="{{ url('/4dm1n/akun/admin/avatar/update') }}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" id="id_avatar">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">{{ tr('foto profil') }}</label>
                                                <input type="file" class="dropify" id="avatar_avatar" name="avatar" height="200" required />
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
                                    <p>{{ tr('apakah anda ingin menghapus akun') }} <b id="name_delete"></b>

                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                    <a id="button_delete" class="btn btn-primary">{{ tr('hapus') }}</a>
                                </div>

                            </div>
                        </div>
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
            var table = $('#data-table-1').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/4dm1n/akun/admin/ajax/table') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    async: true,
                    error: function(xhr, error, code) {
                        console.log(xhr);
                        console.log(code);
                    }
                },
                destroy: true,
                "buttons": [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                // dom: 'Bfrtip',
                dom: 'lBfrtip',
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
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
                        data: 'role_view',
                        name: 'role_view',
                    },
                    {
                        data: 'nip',
                        name: 'nip',
                    },
                    {
                        data: 'birthdate',
                        name: 'birthdate',
                    },
                    {
                        data: 'online',
                        name: 'online',
                    },
                    {
                        data: 'status_view',
                        name: 'status_view',
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
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">{{ tr('loading...') }}</span></div></div>',
                    info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
                },

            });
        }
    </script>

    <script>
        function show_delete(id, name) {
            $('#name_delete').html(name);
            $("#button_delete").attr("href", "{{ url('4dm1n/akun/admin/delete') }}/" + id)
            $('#delete').modal('show');
        }

        function show_active(id, name) {
            $('#name_active').html(name);
            $("#button_active").attr("href", "{{ url('4dm1n/akun/admin/status?status=1') }}&id=" + id)
            $('#active').modal('show');
        }

        function show_disactive(id, name) {
            $('#name_disactive').html(name);
            $("#button_disactive").attr("href", "{{ url('4dm1n/akun/admin/status?status=0') }}&id=" + id)
            $('#disactive').modal('show');
        }

        function show_respass(id, name) {
            $('#name_respass').html(name);
            $("#button_respass").attr("href", "{{ url('4dm1n/akun/admin/password/reset?id=') }}" + id)
            $('#respass').modal('show');
        }

        function show_avatar(id) {
            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                url: "{{ url('4dm1n/akun/admin/ajax/id') }}",
                success: function(data) {
                    console.log(data);
                    if (data.message == "success") {
                        var el = data.result;
                        $('#id_avatar').val(el.id);


                        var drEvent = $('#avatar_avatar').dropify({
                            defaultFile: el.avatar
                        });
                        drEvent = drEvent.data('dropify');
                        drEvent.resetPreview();
                        drEvent.clearElement();
                        drEvent.settings.defaultFile = el.avatar;
                        drEvent.destroy();
                        drEvent.init();


                        $('#avatar').modal('show');
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

        function show_edit(id) {
            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                url: "{{ url('4dm1n/akun/admin/ajax/id') }}",
                success: function(data) {
                    console.log(data);
                    if (data.message == "success") {
                        var el = data.result;
                        $('#id_edit').val(el.id);
                        $('#name_edit').val(el.name);
                        $('#nip_edit').val(el.nip);
                        $("#role_edit").val(el.role_id).change();
                        $('#email_edit').val(el.email);
                        $('#phone_edit').val(el.phone);
                        $('#birthdate_edit').val(el.birthdate);

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
