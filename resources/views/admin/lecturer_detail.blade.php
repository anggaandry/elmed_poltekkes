@extends('admin/master')

@section('title', 'Profil dosen')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ url('4dm1n/dosen') }}">{{ tr('dosen') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('profil dosen') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="profile card card-body px-3 pt-3 pb-0">
                <div class="profile-head">
                    <div class="photo-content">
                        <div class="cover-photo rounded" style=" background: url({{ asset('images/art/style4.jpg') }});">
                        </div>
                    </div>
                    <div class="profile-info">
                        <div class="profile-photo">
                            <div class="cropcircle-lg" style="background-image: url({{ $lecturer_data->avatar ? asset(AVATAR_PATH . $lecturer_data->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $lecturer_data->name) }});">
                            </div>

                        </div>
                        <div class="profile-details">
                            <div class="profile-name px-3 pt-2">
                                <h4 class="text-primary mb-0">
                                    {{ title_lecturer($lecturer_data) }}

                                </h4>
                                <p>{{ $lecturer_data->identity }}. {{ $lecturer_data->identity_number }}</p>
                            </div>
                            <div class="profile-email pt-2">

                                <p>@php echo $lecturer_data->status ? '<span class="badge bg-success">'.tr('active').'</span>' : '<span class="badge bg-danger">'.tr('unactive').'</span>' @endphp
                                </p>
                            </div>
                            <div class="dropdown ms-auto">
                                <a href="#" class="btn btn-primary light sharp" data-bs-toggle="dropdown" aria-expanded="true"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"></rect>
                                            <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                            <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                            <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                        </g>
                                    </svg></a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li class="dropdown-item"><a href="{{ url('4dm1n/dosen/form/edit?route=1&id=' . $lecturer_data->id) }}"><i class="fa fa-edit text-primary me-2"></i> {{ tr('edit') }}</a></li>
                                    <li class="dropdown-item"><a data-bs-toggle="modal" href="#respass"><i class="fa fa-lock text-primary me-2"></i> {{ tr('reset password') }}</a>
                                    </li>
                                    <li class="dropdown-item "><a data-bs-toggle="modal" href="#delete" class="text-danger"><i class="fa fa-trash text-danger me-2"></i> {{ tr('delete') }}</a></li>
                                </ul>
                            </div>
                        </div>
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
                            <p>{{ tr('apakah anda ingin menghapus dosen') }}<b>{{ $lecturer_data->name }}</b>

                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                            <a href="{{ url('4dm1n/dosen/delete?id=' . $lecturer_data->id . '&route=1') }}" class="btn btn-primary">{{ tr('hapus') }}</a>
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
                            <p>{{ tr('apakah anda ingin mereset password akun') }}<b>{{ $lecturer_data->name }}</b>

                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                            <a href="{{ url('4dm1n/akun/dosen/password/reset?route=1&id=' . $lecturer_data->id) }}" class="btn btn-primary">{{ tr('reset password') }}</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="profile-tab">
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a href="#bio" data-bs-toggle="tab" class="nav-link {{ $tab == 0 ? 'active' : '' }} show">{{ tr('bio') }}</a>
                                </li>
                                <li class="nav-item"><a href="#prodi" data-bs-toggle="tab" class="nav-link {{ $tab == 1 ? 'active' : '' }}">{{ tr('prodi') }}</a>
                                </li>
                                <li class="nav-item"><a href="#matkul" data-bs-toggle="tab" class="nav-link {{ $tab == 2 ? 'active' : '' }}">{{ tr('mata kuliah') }}</a>

                                </li>
                                <li class="nav-item"><a href="#kelas" data-bs-toggle="tab" class="nav-link {{ $tab == 3 ? 'active' : '' }}">{{ tr('kelas') }}</a>
                                </li>
                                <li class="nav-item"><a href="#jadwal" data-bs-toggle="tab" class="nav-link {{ $tab == 4 ? 'active' : '' }}">{{ tr('jadwal') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="bio" class="tab-pane fade {{ $tab == 0 ? 'active show' : '' }}">
                                    <div class="pt-3">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th>{{ tr('nama dosen') }}</th>
                                                    <td>{{ $lecturer_data->front_title }} {{ $lecturer_data->name }}
                                                        {{ $lecturer_data->back_title }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ $lecturer_data->identity }}</th>
                                                    <td>{{ $lecturer_data->identity_number }} </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('jenis kelamin') }}</th>
                                                    <td>{{ $lecturer_data->gender }} </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('agama') }}</th>
                                                    <td>{{ $lecturer_data->religion->name }} </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('tanggal lahir') }}</th>
                                                    <td>{{ date_id($lecturer_data->birthdate, 0) }}

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('usia') }}</th>
                                                    <td>{{ convert_age($lecturer_data->birthdate) }} {{ tr('tahun') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('terakhir online') }}</th>
                                                    <td>{{ $lecturer_data->online ? ago_model($lecturer_data->online) : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('akun dibuat') }}</th>
                                                    <td>{{ date_id($lecturer_data->created_at, 1) }} </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('aktifitas terakhir') }}</th>
                                                    <td>{{ $last_activity ? '[' . $last_activity->menu->name . '] ' . $last_activity->log : '-' }}
                                                    </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="prodi" class="tab-pane fade {{ $tab == 1 ? 'active show' : '' }}">
                                    <div class="pt-3">
                                        @if (!can_prodi())
                                            <form action="{{ url('/4dm1n/dosen/prodi/add') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="lecturer_id" value="{{ $lecturer_data->id }}">
                                                <div class="row">
                                                    <div class="col-8">

                                                        <select class="form-select sel2 mt-2" name="prodi_id" required>
                                                            <option value="">-- {{ tr('pilih prodi') }} --</option>
                                                            @foreach ($prodi_data as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->program->name }}
                                                                    {{ $item->study_program->name }} -
                                                                    {{ $item->category->name }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <button class="btn  btn-outline-success mt-1" type="submit"><i class="fa fa-plus"></i> {{ tr('tambah prodi') }}</button>

                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                        <div class="table-responsive mt-3">
                                            <table class="display table text-center" id="data-table-4">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ tr('nama prodi') }}</th>
                                                        <th>{{ tr('status') }}</th>
                                                        <th>{{ tr('aksi') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $pd=1; @endphp
                                                    @foreach ($prodi_dosen as $item)
                                                        <tr>
                                                            <td>{{ $pd++ }}</td>
                                                            <td>{{ $item->prodi->program->name . ' - ' . $item->prodi->study_program->name . ' ' . $item->prodi->category->name }}
                                                            </td>
                                                            <td>@php echo $item->status==1?'<span class="badge bg-success">'.tr('aktif').'</span>':'<span class="badge bg-danger">'.tr('tidak aktif').'</span>' @endphp</td>
                                                            <td>
                                                                @if ($item->status == 1)
                                                                    <a class="btn btn-outline-danger btn-rounded" data-bs-toggle="modal" href="#disactive{{ $item->id }}"><i class="fa fa-times"></i> {{ tr('nonaktifkan') }}</a>

                                                                    <div class="modal fade" id="disactive{{ $item->id }}">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title text-danger">{{ tr('peringatan') }} !!</h5>

                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                                    </button>
                                                                                </div>

                                                                                <div class="modal-body">
                                                                                    <p>{{ tr('apakah anda ingin menonaktifkan') }}<b>{{ $item->prodi->program->name . ' - ' . $item->prodi->study_program->name . ' ' . $item->prodi->category->name }}</b>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                                    <a href="{{ url('4dm1n/dosen/prodi/status?status=0&id=' . $item->id) }}" class="btn btn-primary">{{ tr('non-aktifkan') }}</a>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <a class="btn btn-outline-success btn-rounded" data-bs-toggle="modal" href="#active{{ $item->id }}"><i class="fa fa-check"></i> {{ tr('mengaktifkan') }}</a>

                                                                    <div class="modal fade" id="active{{ $item->id }}">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title text-danger">{{ tr('peringatan') }} !!</h5>

                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                                    </button>
                                                                                </div>

                                                                                <div class="modal-body">
                                                                                    <p>{{ tr('apakah anda ingin mengaktifkan') }}<b>{{ $item->prodi->program->name . ' - ' . $item->prodi->study_program->name . ' ' . $item->prodi->category->name }}</b>

                                                                                    </p>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                                    <a href="{{ url('4dm1n/dosen/prodi/status?status=1&id=' . $item->id) }}" class="btn btn-primary">{{ tr('aktifkan') }}</a>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <a class="btn btn-outline-danger btn-rounded" data-bs-toggle="modal" href="#delete{{ $item->id }}"><i class="fa fa-trash"></i> {{ tr('hapus') }}</a>

                                                                <div class="modal fade" id="delete{{ $item->id }}">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title text-danger">{{ tr('peringatan') }} !!</h5>

                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <p>{{ tr('apakah anda ingin menghapus') }}<b>{{ $item->prodi->program->name . ' - ' . $item->prodi->study_program->name . ' ' . $item->prodi->category->name }}</b>

                                                                                </p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                                <a href="{{ url('4dm1n/dosen/prodi/delete/' . $item->id) }}" class="btn btn-primary">{{ tr('hapus') }}</a>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="matkul" class="tab-pane fade {{ $tab == 2 ? 'active show' : '' }}">
                                    <div class="pt-3">
                                        <div class="table-responsive">
                                            <table class="table text-center" id="data-table-3">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ tr('matkul') }}</th>
                                                        <th>{{ tr('bobot sks') }}</th>
                                                        <th>{{ tr('semester') }}</th>
                                                        <th>{{ tr('prodi') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $s=1; @endphp
                                                    @foreach ($subject_data as $item)
                                                        <tr>
                                                            <td>{{ $s++ }}</td>
                                                            <td>{{ $item->subject->name }}</td>
                                                            <td>{{ $item->value }}</td>
                                                            <td>{{ $item->semester }}</td>
                                                            <td>{{ $item->prodi->program->name . ' - ' . $item->prodi->study_program->name . ' ' . $item->prodi->category->name }}
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="kelas" class="tab-pane fade {{ $tab == 3 ? 'active show' : '' }}">
                                    <div class="pt-3">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">{{ tr('semester') }}</label>
                                                <select class="form-select form-select-lg" id="odd_" onchange="load_table()">
                                                    <option value="1">{{ tr('ganjil') }}</option>
                                                    <option value="2">{{ tr('genap') }}</option>
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">{{ tr('tahun akademik') }}</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="year_" oninput="load_table()" value="{{ semester_now()->year }}" required>
                                                    <span class="input-group-text border-0" id="next_year">{{ semester_now()->year + 1 }}</span>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="table-responsive">
                                            <table id="data-table-1" class="display text-center table-striped">
                                                <thead class="">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ tr('prodi') }}</th>
                                                        <th>{{ tr('kelas') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="jadwal" class="tab-pane fade {{ $tab == 4 ? 'active show' : '' }}">
                                    <div class="pt-3">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">{{ tr('semester') }}</label>
                                                <select class="form-select form-select-lg" id="odd_j" onchange="load_table_j()">
                                                    <option value="1">{{ tr('ganjil') }}</option>
                                                    <option value="2">{{ tr('genap') }}</option>
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">{{ tr('tahun akademik') }}</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="year_j" oninput="load_table_j()" value="{{ semester_now()->year }}" required>
                                                    <span class="input-group-text border-0" id="next_year_j">{{ semester_now()->year + 1 }}</span>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="table-responsive">
                                            <table id="data-table-2" class="display text-center table-striped">
                                                <thead class="">
                                                    <tr>
                                                        <th>{{ tr('hari') }}</th>
                                                        <th>{{ tr('waktu') }}</th>
                                                        <th>{{ tr('sks') }}</th>
                                                        <th>{{ tr('mata kuliah') }}</th>
                                                        <th>{{ tr('kelas') }}</th>
                                                        <th>{{ tr('ruangan') }}</th>
                                                        <th>{{ tr('dosen') }}</th>
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            load_table();
            $('#data-table-3').DataTable({
                createdRow: function(row, data, index) {
                    $(row).addClass('selected')
                },
                paging: false,
                ordering: false,
                searching: false,
                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    },
                    info: "Records _START_ to _END_ of _MAX_ entries",
                }
            });
            $('#data-table-4').DataTable({
                createdRow: function(row, data, index) {
                    $(row).addClass('selected')
                },
                paging: false,
                ordering: false,
                searching: false,
                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    },
                    info: "Records _START_ to _END_ of _MAX_ entries",
                }
            });
        });

        function load_table() {
            var year_ = $('#year_');
            var odd_ = $('#odd_ option:selected');

            var newval = parseInt(year_.val()) + 1;
            $('#next_year').html(" /" + newval + " ");

            var table = $('#data-table-1').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                paging: false,
                searching: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/4dm1n/dosen/ajax/class') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        _lecturer: '{{ $lecturer_data->id }}',
                        _year: year_.val(),
                        _odd: odd_.val(),

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
                        data: 'name',
                        name: 'name',
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

    <script type="text/javascript">
        $(document).ready(function() {
            load_table_j();
        });

        function load_table_j() {
            var year_ = $('#year_j');
            var odd_ = $('#odd_j option:selected');

            var newval = parseInt(year_.val()) + 1;
            $('#next_year_j').html(" /" + newval + " ");

            var table = $('#data-table-2').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                searching: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/4dm1n/dosen/ajax/schedule') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        _lecturer: '{{ $lecturer_data->id }}',
                        _year: year_.val(),
                        _odd: odd_.val(),

                    },
                    async: true,
                    error: function(xhr, error, code) {
                        console.log(xhr);
                        console.log(code);
                    }
                },
                paging: false,
                ordering: false,
                destroy: true,
                rowsGroup: [0, 4, 5],
                columns: [{
                        data: 'days',
                        name: 'days',
                    }, {
                        data: 'time',
                        name: 'time',
                    }, {
                        data: 'sks.value',
                        name: 'sks.value',
                    }, {
                        data: 'sks.subject.name',
                        name: 'sks.subject.name',
                    }, {
                        data: 'class.name',
                        name: 'class.name',
                    }, {
                        data: 'room.name',
                        name: 'room.name',
                    }, {
                        data: 'lecturer',
                        name: 'lecturer',
                    }

                ],
                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    },
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">{{ tr('loading...') }}</span></div></div>',
                    info: "<br> Records _START_ to _END_ of _MAX_ entries",
                },

            });
        }
    </script>
@endsection
