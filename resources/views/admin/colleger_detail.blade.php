@extends('admin/master')

@section('title', 'Profil mahasiswa')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ url('4dm1n/mahasiswa') }}">{{ tr('mahasiswa') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('profil mahasiswa') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="profile card card-body px-3 pt-3 pb-0">
                <div class="profile-head">
                    <div class="photo-content">
                        <div class="cover-photo rounded" style=" background: url({{ asset('images/art/style5.jpg') }});">
                        </div>
                    </div>
                    <div class="profile-info">
                        <div class="profile-photo">
                            <div class="cropcircle-lg" style="background-image: url({{ $colleger_data->avatar ? asset(AVATAR_PATH . $colleger_data->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $colleger_data->name) }});">
                            </div>

                        </div>
                        <div class="profile-details">
                            <div class="profile-name px-3 pt-2">
                                <h4 class="text-primary mb-0">
                                    {{ $colleger_data->name }}

                                </h4>
                                <p>NIM. {{ $colleger_data->nim }}</p>
                            </div>
                            <div class="profile-email pt-2">

                                <p> @switch ($colleger_data->status)
                                        @case(1)
                                            <span class="badge bg-success">{{ tr('active') }}</span>
                                        @break

                                        @case(2)
                                            <span class="badge bg-info">{{ tr('graduated') }}</span>
                                        @break

                                        @case(3)
                                            <span class="badge bg-danger">{{ tr('d.o') }}</span>
                                        @break
                                    @endswitch
                                </p>
                            </div>
                            <div class="dropdown ms-auto">
                                <a href="#" class="btn btn-primary light sharp" data-bs-toggle="dropdown" aria-expanded="true"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"></rect>
                                            <circle fill="#000000" cx="5" cy="12" r="2">
                                            </circle>
                                            <circle fill="#000000" cx="12" cy="12" r="2">
                                            </circle>
                                            <circle fill="#000000" cx="19" cy="12" r="2">
                                            </circle>
                                        </g>
                                    </svg></a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li class="dropdown-item"><a href="{{ url('4dm1n/mahasiswa/form/edit?route=1&id=' . $colleger_data->id) }}"><i class="fa fa-edit text-primary me-2"></i> {{ tr('edit') }}</a></li>
                                    <li class="dropdown-item"><a data-bs-toggle="modal" href="#respass"><i class="fa fa-lock text-primary me-2"></i> {{ tr('reset password') }}</a>
                                    </li>
                                    <li class="dropdown-item "><a data-bs-toggle="modal" href="#delete" class="text-danger"><i class="fa fa-trash text-danger me-2"></i> {{ tr('delete') }}</a>
                                    </li>
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
                            <p>{{ tr('apakah anda ingin menghapus mahasiswa') }}<b>{{ $colleger_data->name }}</b></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                            <a href="{{ url('4dm1n/mahasiswa/delete/' . $colleger_data->id . '?route=1') }}" class="btn btn-primary">{{ tr('hapus') }}</a>
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
                            <p>{{ tr('apakah anda ingin mereset password akun') }}<b>{{ $colleger_data->name }}</b></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                            <a href="{{ url('4dm1n/akun/mahasiswa/password/reset?route=1&id=' . $colleger_data->id) }}" class="btn btn-primary">{{ tr('reset password') }}</a>
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
                                <li class="nav-item"><a href="#kelas" data-bs-toggle="tab" class="nav-link {{ $tab == 1 ? 'active' : '' }}">{{ tr('kelas') }}</a>
                                </li>
                                <li class="nav-item"><a href="#jadwal" data-bs-toggle="tab" class="nav-link {{ $tab == 2 ? 'active' : '' }}">Jadwal kuliah</a>

                                </li>
                                <li class="nav-item"><a href="#absensi" data-bs-toggle="tab" class="nav-link {{ $tab == 3 ? 'active' : '' }}">{{ tr('absensi') }}</a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div id="bio" class="tab-pane fade {{ $tab == 0 ? 'active show' : '' }}">
                                    <div class="pt-3">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th>{{ tr('nama mahasiswa') }}</th>
                                                    <td>{{ $colleger_data->name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('nim') }}</th>
                                                    <td>{{ $colleger_data->nim }} </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('tahun angkatan') }}</th>
                                                    <td>{{ $colleger_data->year }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('prodi') }}</th>
                                                    <td>{{ $colleger_data->prodi->program->name . ' - ' . $colleger_data->prodi->study_program->name . ' ' . $colleger_data->prodi->category->name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('jenis kelamin') }}</th>
                                                    <td>{{ $colleger_data->gender }} </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('agama') }}</th>
                                                    <td>{{ $colleger_data->religion->name }} </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('tanggal lahir') }}</th>
                                                    <td>{{ date_id($colleger_data->birthdate, 0) }}

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('usia') }}</th>
                                                    <td>{{ convert_age($colleger_data->birthdate) }} {{ tr('tahun') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('terakhir online') }}</th>
                                                    <td>{{ $colleger_data->online ? ago_model($colleger_data->online) : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('akun dibuat') }}</th>
                                                    <td>{{ date_id($colleger_data->created_at, 1) }} </td>
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
                                <div id="kelas" class="tab-pane fade {{ $tab == 1 ? 'active show' : '' }}">
                                    <div class="pt-3">
                                        <table id="data-table-1" class="display text-center table-striped">
                                            <thead class="">
                                                <tr>
                                                    <th>{{ tr('semester') }}</th>
                                                    <th>{{ tr('kelas') }}</th>
                                                    <th>{{ tr('t.a') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($class_data as $item)
                                                    <tr>
                                                        <td>{{ $item->class->semester }}</td>
                                                        <td>{{ $item->class->name }}</td>
                                                        <td>{{ $item->class->year }}/{{ $item->class->year + 1 }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="jadwal" class="tab-pane fade {{ $tab == 2 ? 'active show' : '' }}">
                                    <div class="pt-3">
                                        <div class="row">
                                            <div class="mb-3 col-md-6">

                                                <select class="form-select form-select-lg" id="class_" onchange="load_table_schedule()">
                                                    @foreach ($class_data as $item)
                                                        <option value="{{ $item->id }}">{{ $item->class->name }}
                                                            {{ $item->class->year }}/{{ $item->class->year + 1 }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <div class="table-responsive">
                                            <table id="data-table-2" class="display text-center">
                                                <thead class="">
                                                    <tr>
                                                        <th>{{ tr('hari') }}</th>
                                                        <th>{{ tr('waktu') }}</th>
                                                        <th>{{ tr('sks') }}</th>
                                                        <th>{{ tr('mata kuliah') }}</th>
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
                                <div id="absensi" class="tab-pane fade {{ $tab == 3 ? 'active show' : '' }}">
                                    <div class="pt-3">
                                        <div class="pt-3">
                                            <div class="row">
                                                <div class="mb-3 col-md-3">
                                                    <input type="date" class="form-control" value="{{ date('Y-m-d') }}" id="date_" oninput="load_table_absence()">

                                                </div>

                                            </div>

                                            <div class="w-100 text-center p-5  d-none" id="loading" style="height:300px;">
                                                <br>
                                                <br>
                                                <br>

                                                <div class="mt-5">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">{{ tr('loading...') }}</span>
                                                    </div>
                                                    <br>
                                                    <small>{{ tr('loading absensi..') }}</small>
                                                </div>
                                            </div>

                                            <div class="text-center p-5 d-none" width="100%" id="nodata" style="height:300px;">
                                                <br>
                                                <br>
                                                <img src="{{ asset('images/art/holiday.png') }}" height="100" alt="">
                                                <h5 class="text-danger mt-3" id="nodata_name"></h5>
                                            </div>

                                            <div id="display" class="d-none">
                                                <div class="table-responsive">
                                                    <table id="table_1" class="table text-center">
                                                        <thead class=" bg-primary-light text-white">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>{{ tr('waktu') }}</th>
                                                                <th>{{ tr('kelas') }}</th>
                                                                <th>{{ tr('mata kuliah') }}</th>
                                                                <th>{{ tr('ruangan') }}</th>
                                                                <th>{{ tr('pertemuan ke') }}</th>
                                                                <th>{{ tr('status') }}</th>
                                                                <th>{{ tr('catatan') }}</th>
                                                                <th>{{ tr('detail') }}</th>
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
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#data-table-1').DataTable({
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
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">{{ tr('loading...') }}</span></div></div>',
                    info: "Records _START_ to _END_ of _MAX_ entries",
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            load_table_j();
        });

        function load_table_j() {
            var class_ = $('#class_').val();

            var table = $('#data-table-2').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                searching: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/4dm1n/mahasiswa/ajax/schedule') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        _class: class_,
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
                rowsGroup: [0, 2, 3, 4, 5],
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

    <script type="text/javascript">
        $(document).ready(function() {
            load_table_absence();
        });

        function load_table_absence() {
            var date = $('#date_').val();
            var display_ = $('#display');
            var nodata_ = $('#nodata');
            var loading_ = $('#loading');
            var table_ = $('#table_1');

            display_.addClass('d-none');
            nodata_.addClass('d-none');
            loading_.removeClass('d-none');

            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _date: date,
                    _colleger: {{ $colleger_data->id }}
                },
                url: "{{ url('4dm1n/mahasiswa/ajax/absence') }}",
                success: function(data) {
                    console.log(data);
                    if (data.message == "success") {
                        if (data.result.length == 0) {
                            display_.addClass('d-none');
                            nodata_.removeClass('d-none');
                            loading_.addClass('d-none');
                            $('#nodata_name').html(data.holiday);
                        } else {
                            display_.removeClass('d-none');
                            nodata_.addClass('d-none');
                            loading_.addClass('d-none');

                            table_.children('tbody').empty();
                            for (let i = 0; i < data.result.length; i++) {
                                const el = data.result[i];
                                console.log(el);
                                var absence = `  
                                        <td class="align-middle">${el.status}</td>
                                        <td class="align-middle">${el.note}</td>`;
                                if (el.nosession) {
                                    absence = `<td class="align-middle" colspan="2">${el.nosession}</td>`;
                                }
                                table_.children('tbody').append(`
                                    <tr>
                                        <td class="align-middle">${i+1}.</td>
                                        <td class="text-start align-middle">${el.time}</td>
                                        <td class="align-middle">${el.class_name}</td>
                                        <td class="align-middle">${el.sks_name}</td>
                                        <td class="align-middle">${el.room_name}</td>
                                        <td class="align-middle">${el.session}</td>
                                        ${absence}
                                        <td class="align-middle">
                                            <button class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#detail${i}"><i class="fa fa-eye"></i></button>
                                            <div class="modal fade" id="detail${i}">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ tr('detail pertemuan') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="table-responsive">
                                                                <table class="table text-start">
                                                                    
                                                                    <tbody>
                                                                     
                                                                        <tr>
                                                                            <th>{{ tr('pertemuan ke') }}</th>
                                                                            <td>${el.session}</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <th>{{ tr('aktivitas pembelajaran') }}</th>
                                                                            <td>${el.activity}</td>
                                                                        
                                                                        </tr>
                                                                        <tr>
                                                                            <th>{{ tr('dosen') }}</th>
                                                                            <td>${el.dosen}</td>
                                                                        </tr>

                                                                        
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger light"
                                                                data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                            <a href="" class="btn btn-primary" id="btn_quiz">

                                                            </a>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                `);
                            }
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
