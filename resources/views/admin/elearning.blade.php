@extends('admin/master')

@section('title', 'Materi E-learning')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('materi e-learning') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    @php $key_='Materi' @endphp
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row" style="width:110%;">
                        <div class="col-3">
                            <label class="form-label text-left">{{ tr('tahun') }}</label>
                            <input type="number" class="form-control" id="year_" value="{{ date('Y') }}" oninput="load_table()">
                        </div>
                        <div class="col-5">
                            <label class="form-label text-left">{{ tr('prodi') }}</label>
                            <select class="form-select form-select-lg" id="prodi_" onchange="load_table()" @if (can_prodi()) disabled @endif>
                                <option value="">{{ tr('semua prodi') }}</option>
                                @foreach ($prodi_data as $item)
                                    <option value="{{ $item->id }}" @if (can_prodi() == $item->id) selected @endif>
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
                                    <th>{{ tr('grafik') }}</th>
                                    <th>{{ tr('judul') }}</th>
                                    <th>{{ tr('prodi') }}</th>
                                    <th>{{ tr('matkul') }}</th>
                                    <th>{{ tr('dosen') }}</th>
                                    <th>{{ tr('dibuat') }}</th>
                                    <th>{{ tr('aksi') }}</th>
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
            var prodi_id = $('#prodi_ option:selected').val();
            var year = $('#year_ ').val();
            //alert(prodi_id);
            var table = $('#data-table-1').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/4dm1n/elearning/ajax/table') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        prodi_id: prodi_id,
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
                        data: 'img',
                        name: 'img',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'prodi',
                        name: 'prodi',
                    },
                    {
                        data: 'sks.subject.name',
                        name: 'sks.subject.name',
                    },


                    {
                        data: 'lecturer_view',
                        name: 'lecrurer_view',
                    },
                    {
                        data: 'time',
                        name: 'time',
                    },
                    {
                        data: 'action',
                        name: 'action'
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
