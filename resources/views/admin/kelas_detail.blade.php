@extends('admin/master')

@section('title', 'Rombel kelas')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('akademik') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('4dm1n/kelas?prodi=' . $class_data->prodi_id . '&tahun=' . $class_data->year . '&odd=' . $class_data->odd) }}">{{ tr('kelas') }}</a>
            </li>
            <li class="breadcrumb-item"><a href="{{ url('4dm1n/kelas') }}">{{ $class_data->name }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    @php $key_='kelas'; @endphp
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                @if (can($key_, 'add'))
                    <div class="card-header">
                        <div class="row" style="width:110%;">
                            <div class="col-8">
                                <div class="table-responsive">
                                    <table class="table table-bordered display table-sm">
                                        <tr>
                                            <th>{{ tr('nama kelas') }}</th>
                                            <td>{{ $class_data->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ tr('tahun akademik') }}</th>
                                            <td>Semester {{ $class_data->semester }} TA {{ $class_data->year }} /
                                                {{ $class_data->year + 1 }} </td>
                                        </tr>
                                        <tr>
                                            <th>{{ tr('prodi') }}</th>
                                            <td>{{ $class_data->prodi->program->name . ' - ' . $class_data->prodi->study_program->name . ' ' . $class_data->prodi->category->name }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-4">

                                @if ($class_data->semester != 1 && count($cc_data) == 0)
                                    <a class="btn  btn-info mb-2 float-end" data-bs-toggle="modal" href="#previous"><span class="btn-icon-start text-info"><i class="fa fa-users color-info"></i>
                                        </span>{{ tr('pindahkan dari kelas lain') }}</a>
                                    <br>
                                @endif
                                <a class="btn  btn-primary float-end" data-bs-toggle="modal" href="#add"><span class="btn-icon-start text-primary"><i class="fa fa-plus color-primary"></i>
                                    </span>{{ tr('tambah mahasiswa') }}</a>


                                <div class="modal fade" id="add">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content ">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ tr('tambah mahasiswa') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <label class="form-label text-left">{{ tr('tahun akademik') }}</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" value="{{ $class_data->year }}" id="year_" oninput="load_table()" required>
                                                            <span class="input-group-text border-0" id="next_year">{{ $class_data->year + 1 }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="table-responsive mt-3">
                                                    <table id="data-table-1" class="display text-center table-striped table-sm">
                                                        <thead class="">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>{{ tr('foto') }}</th>
                                                                <th>{{ tr('nama') }}</th>
                                                                <th>{{ tr('nim') }}</th>
                                                                <th>{{ tr('ta') }}</th>
                                                                <th>{{ tr('aksi') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="previous">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content modal-lg">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ tr('ambil dari kelas lain') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>
                                            </div>
                                            <form action="{{ url('/4dm1n/kelas/colleger/previous/add') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="class_id" value="{{ $class_data->id }}">
                                                <div class="modal-body">
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">{{ tr('kelas yang ingin mahasiswanya disalin ke') }}
                                                            {{ $class_data->name }}</label>
                                                        <select class="form-select form-select-lg" name="previous_id" required>
                                                            <option value="">-- {{ tr('pilih kelas') }} --</option>
                                                            @foreach ($ref_class as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->name }} TA
                                                                    {{ $item->year }}/{{ $item->year + 1 }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                    <button type="submit" class="btn btn-primary">{{ tr('proses') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                @endif
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table-2" class="display text-center table-striped">
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>{{ tr('foto') }}</th>
                                    <th>{{ tr('nama mahasiswa') }}</th>
                                    <th>{{ tr('nim') }}</th>
                                    <th>{{ tr('tahun') }}</th>
                                    @if (can($key_, 'delete'))
                                        <th>{{ tr('aksi') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @foreach ($cc_data as $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            <div class="cropcircle" style="background-image: url({{ $item->colleger->avatar ? asset(AVATAR_PATH . $item->colleger->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $item->colleger->name) }});">
                                            </div>
                                        </td>
                                        <td>{{ $item->colleger->name }}</td>
                                        <td>{{ $item->colleger->nim }}</td>
                                        <td>{{ $item->colleger->year }}/{{ $item->colleger->year + 1 }}</td>
                                        @if (can($key_, 'delete'))
                                            <td>
                                                @if (can($key_, 'delete'))
                                                    <a class="btn btn-outline-danger btn-xs" data-bs-toggle="modal" href="#delete{{ $item->id }}"><i class="fa fa-trash color-danger"></i>
                                                    </a>
                                                    <div class="modal fade" id="delete{{ $item->id }}">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title text-danger">{{ tr('peringatan') }} !!</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <p>{{ tr('apakah anda ingin menghapus mahasiswa dari kelas ini?') }}<b>{{ $item->colleger->name }}</b>
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                    <a href="{{ url('4dm1n/kelas/colleger/delete/' . $item->id) }}" class="btn btn-primary">{{ tr('hapus') }}</a>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        @endif
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
            $('#data-table-2').DataTable({
                createdRow: function(row, data, index) {
                    $(row).addClass('selected')
                },
                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    },
                    info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
                },
                paging: false,
                ordering: false,
            });
        });

        function load_table() {
            var year = $('#year_').val();

            var show_value = parseInt(year) + 1;
            $('#next_year').html(" /" + show_value + " ");
            var table = $('#data-table-1').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, 'All'],
                ],
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/4dm1n/kelas/ajax/colleger') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        class_id: '{{ $class_data->id }}',
                        year: year

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
                    }, {
                        data: 'name',
                        name: 'name',
                    }, {
                        data: 'nim',
                        name: 'nim',
                    }, {
                        data: 'year',
                        name: 'year',
                    }, {
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


@endsection
