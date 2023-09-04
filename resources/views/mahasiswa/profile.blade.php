@extends('mahasiswa/master')

@section('title', 'Dashboard')


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
                            <div class="cropcircle-lg"
                                style="background-image: url({{ $colleger_data->avatar ? asset(AVATAR_PATH . $colleger_data->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $colleger_data->name) }});">
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
                                            <span class="badge bg-success">active</span>
                                        @break

                                        @case(2)
                                            <span class="badge bg-info">graduated</span>
                                        @break

                                        @case(3)
                                            <span class="badge bg-danger">D.O</span>
                                        @break
                                    @endswitch
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
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4">Kelas mahasiswa</h5>
                    <div class="row">
                        @php $i=1; @endphp
                        @foreach ($colleger_data->colleger_class as $item)
                            <div class="col-xxl-12">
                                <div class="card"
                                    style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;">
                                    <div class="p-1 pt-3">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <th rowspan="2" class='p-1 m-1 align-middle text-center'
                                                        width="15%">{{ $i++ }}.
                                                    </th>
                                                    <td class='p-0 m-0'>{{ $item->class->name }}</td>
                                                    <td rowspan="2" width="15%">
                                                        @if (semester_now()->year == $item->class->year && semester_now()->odd == $item->class->odd)
                                                            <span class="badge badge-primary badge-sm">Aktif</span>
                                                        @endif
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td class='p-0 m-0'>
                                                        <small>Semester {{ $item->class->semester }} TA
                                                            {{ $item->class->year }}/{{ $item->class->year + 1 }}</small>
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>
            </div>

        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4">Biodata mahasiswa</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>Nama mahasiswa</th>
                                <td>{{ $colleger_data->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>NIM</th>
                                <td>{{ $colleger_data->nim }} </td>
                            </tr>
                            <tr>
                                <th>Tahun angkatan</th>
                                <td>{{ $colleger_data->year }}
                                </td>
                            </tr>
                            <tr>
                                <th>Prodi</th>
                                <td>{{ $colleger_data->prodi->program->name . ' - ' . $colleger_data->prodi->study_program->name . ' ' . $colleger_data->prodi->category->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>Jenis kelamin</th>
                                <td>{{ $colleger_data->gender }} </td>
                            </tr>
                            <tr>
                                <th>Agama</th>
                                <td>{{ $colleger_data->religion->name }} </td>
                            </tr>
                            <tr>
                                <th>Tanggal lahir</th>
                                <td>{{ date_id($colleger_data->birthdate, 0) }}

                                </td>
                            </tr>
                            <tr>
                                <th>Usia</th>
                                <td>{{ convert_age($colleger_data->birthdate) }} tahun</td>
                            </tr>
                            <tr>
                                <th>Terakhir online</th>
                                <td>{{ $colleger_data->online ? ago_model($colleger_data->online) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Akun dibuat</th>
                                <td>{{ date_id($colleger_data->created_at, 1) }} </td>
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
        </div>


    </div>
@endsection

@section('script')



@endsection
