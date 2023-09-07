@extends('mahasiswa/master')

@section('title', 'Jadwal kuliah')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('jadwal kuliah') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row w-100">
                        <div class="mb-3 col-md-8">
                            <label class="form-label">{{ tr('pilih kelas') }}</label>
                            <select class="form-select form-select-lg" id="class_" onchange="load_table()">
                                @foreach ($colleger_data->colleger_class as $item)
                                    <option value="{{ $item->class_id }}" @if ($item->class_id == active_class()->id) selected @endif>
                                        {{ $item->class->name }} -
                                        Semester {{ $item->class->semester }} TA
                                        {{ $item->class->year }}/{{ $item->class->year + 1 }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table-1" class="display text-center table-striped">
                            <thead class=" bg-primary-light">
                                <tr>
                                    <th class="text-white">{{ tr('hari') }}</th>
                                    <th class="text-white">{{ tr('waktu') }}</th>
                                    <th class="text-white">{{ tr('sks') }}</th>
                                    <th class="text-white">{{ tr('mata kuliah') }}</th>
                                    <th class="text-white">{{ tr('ruangan') }}</th>
                                    <th class="text-white">{{ tr('dosen') }}</th>
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
            var class_ = $('#class_');


            var table = $('#data-table-1').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                searching: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/mahasiswa/jadwal/ajax/table') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        class_id: class_.val(),
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
