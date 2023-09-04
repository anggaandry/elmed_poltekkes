@extends('dosen/master')

@section('title', 'Dashboard')


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
                            <div class="cropcircle-lg"
                                style="background-image: url({{ $lecturer_data->avatar ? asset(AVATAR_PATH . $lecturer_data->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $lecturer_data->name) }});">
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

                                <p>@php echo $lecturer_data->status ? '<span class="badge bg-success">active</span>' : '<span class="badge bg-danger">unactive</span>' @endphp
                                </p>
                            </div>
                            <div class="dropdown ms-auto">
                                <a ata-bs-toggle="modal" href="#password_header" class="btn btn-danger btn-xs"><i
                                        class="fa fa-lock"></i> ganti password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="row">

        <div class="col-xl-7">
            <div class="card">
                <div class="card-body">
                    <div class="profile-tab">
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a href="#bio" data-bs-toggle="tab"
                                        class="nav-link {{ $tab == 0 ? 'active' : '' }} show">Bio</a>
                                </li>

                                <li class="nav-item"><a href="#kelas" data-bs-toggle="tab"
                                        class="nav-link {{ $tab == 1 ? 'active' : '' }}">Kelas yang diampu</a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div id="bio" class="tab-pane fade {{ $tab == 0 ? 'active show' : '' }}">
                                    <div class="pt-3">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th>Nama dosen</th>
                                                    <td>{{ $lecturer_data->front_title }} {{ $lecturer_data->name }}
                                                        {{ $lecturer_data->back_title }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ $lecturer_data->identity }}</th>
                                                    <td>{{ $lecturer_data->identity_number }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Jenis kelamin</th>
                                                    <td>{{ $lecturer_data->gender }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Agama</th>
                                                    <td>{{ $lecturer_data->religion->name }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal lahir</th>
                                                    <td>{{ date_id($lecturer_data->birthdate, 0) }}

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Usia</th>
                                                    <td>{{ convert_age($lecturer_data->birthdate) }} tahun</td>
                                                </tr>
                                                <tr>
                                                    <th>Terakhir online</th>
                                                    <td>{{ $lecturer_data->online ? ago_model($lecturer_data->online) : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Akun dibuat</th>
                                                    <td>{{ date_id($lecturer_data->created_at, 1) }} </td>
                                                </tr>
                                                <tr>
                                                    <th>Aktifitas terakhir</th>
                                                    <td>{{ $last_activity ? '[' . $last_activity->menu->name . '] ' . $last_activity->log : '-' }}
                                                    </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div id="kelas" class="tab-pane fade {{ $tab == 1 ? 'active show' : '' }}">
                                    <div class="pt-3">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Semester</label>
                                                <select class="form-select" id="odd_" onchange="load_table()">
                                                    <option value="1">Ganjil </option>
                                                    <option value="2">Genap</option>
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Tahun akademik</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control form-control-sm"
                                                        id="year_" oninput="load_table()"
                                                        value="{{ semester_now()->year }}" required>
                                                    <span class="input-group-text border-0"
                                                        id="next_year">{{ semester_now()->year + 1 }}</span>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="table-responsive">
                                            <table id="data-table-1" class="text-center">
                                                <thead class=" bg-primary-light">
                                                    <tr>
                                                        <th class="text-white">#</th>

                                                        <th class="text-white text-left">Kelas</th>
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

        <div class="col-xl-5">
            <div class="card">
                <div class="card-body">
                    <h6>Prodi dosen</h6>
                    <div class="table-responsive mt-3">
                        <table class="table text-start table-bordered">

                            <tbody>
                                @php $pd=1; @endphp
                                @foreach ($prodi_dosen as $item)
                                    <tr>
                                        <td>{{ $pd++ }}</td>
                                        <td>{{ $item->prodi->program->name . ' - ' . $item->prodi->study_program->name . ' ' . $item->prodi->category->name }}
                                        </td>
                                        <td class="text-center">@php echo $item->status==1?'<span class="badge bg-success">aktif</span>':'<span class="badge bg-danger">tidak aktif</span>' @endphp</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <h6>Matkul dosen</h6>
                    <div class="table-responsive">
                        <table class="table text-start table-bordered">

                            <tbody>
                                @php $s=1; @endphp
                                @foreach ($subject_data as $item)
                                    <tr>
                                        <td>{{ $s++ }}</td>
                                        <td>{{ $item->subject->name }} - {{ $item->value }}SKS <br> <small>semester
                                                {{ $item->semester }}</small></td>

                                        <td class="text-center">PRODI
                                            @php echo $item->prodi->program->name . ' <br> ' . $item->prodi->study_program->name . ' ' . $item->prodi->category->name @endphp
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
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
                ordering: false,
                info: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/dosen/ajax/class') }}",
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
                        class: "text-start",
                        data: 'kelas',
                        name: 'kelas',
                    }

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


@endsection
