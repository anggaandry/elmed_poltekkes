@extends('dosen/master')

@section('title', 'Jadwal mengajar')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">LMS</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Jadwal mengajar</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row w-100">
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Semester</label>
                            <select class="form-select form-select-lg" id="odd" onchange="load_table()">
                                <option value="1" @if (semester_now()->odd == 1) selected @endif>Ganjil </option>
                                <option value="2" @if (semester_now()->odd == 2) selected @endif>Genap</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">Tahun akademik</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="year" oninput="load_table()"
                                    value="{{ semester_now()->year }}" required>
                                <span class="input-group-text border-0" id="next_year">{{ semester_now()->year + 1 }}</span>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table-1" class="display text-center table-striped">
                            <thead class=" bg-primary-light">
                                <tr>
                                    <th class="text-white">Hari</th>
                                    <th class="text-white">Waktu</th>
                                    <th class="text-white">SKS</th>
                                    <th class="text-white">Mata kuliah</th>
                                    <th class="text-white">Kelas</th>
                                    <th class="text-white">Ruangan</th>
                                    <th class="text-white">Dosen</th>
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
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            load_table();
        });

        function load_table() {
            var year_ = $('#year');
            var odd_ = $('#odd option:selected');

            var newval = parseInt(year_.val()) + 1;
            $('#next_year').html(" /" + newval + " ");

            var table = $('#data-table-1').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                searching: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/dosen/jadwal/ajax/table') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _lecturer: "{{ akun('dosen')->id }}",
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
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                    info: "<br> Records _START_ to _END_ of _MAX_ entries",
                },

            });
        }
    </script>
@endsection
