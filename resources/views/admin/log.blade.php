@extends('admin/master')

@section('title', 'Log aplikasi')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('log aplikasi') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row w-100">
                        <div class="col-4">
                            <label class="form-label text-left">{{ tr('akses') }}</label>
                            <select class="form-select" id="menu_" onchange="load_table()">
                                <option value="">{{ tr('semua akses') }}</option>
                                @foreach ($menu_data as $obj)
                                    <option value="{{ $obj->id }}">
                                        @if ($obj->category)
                                            {{ $obj->category }} -
                                        @endif {{ $obj->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label text-left">{{ tr('tipe') }}</label>
                            <select class="form-select" id="type_" onchange="load_table()">
                                <option value="">{{ tr('semua tipe') }}</option>
                                <option value="0">{{ tr('admin') }}</option>
                                <option value="1">{{ tr('dosen') }}</option>
                                <option value="2">{{ tr('mahasiswa') }}</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <label class="form-label text-left">{{ tr('dari') }}</label>
                            <input type="date" class="form-control form-control-sm  input-rounded" id="start_" onchange="load_table()" value="{{ date('Y-m-d', strtotime(date('Y-m-d') . '-1 month')) }}">
                        </div>
                        <div class="col-2">
                            <label class="form-label text-left">{{ tr('ke') }}</label>
                            <input type="date" class="form-control form-control-sm  input-rounded" id="end_" onchange="load_table()" value="{{ date('Y-m-d', strtotime(date('Y-m-d') . '+1 day')) }}">
                        </div>

                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table-1" class="display text-center table-striped">
                            <thead class="">
                                <tr>
                                    <th class="border-bottom-0">{{ tr('waktu') }}</th>
                                    <th class="border-bottom-0">{{ tr('jenis') }}</th>
                                    <th class="border-bottom-0">{{ tr('avatar') }}</th>
                                    <th class="border-bottom-0">{{ tr('user') }}</th>
                                    <th class="border-bottom-0">{{ tr('menu') }}</th>
                                    <th class="border-bottom-0">{{ tr('log') }}</th>
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
            var start = $('#start_').val();
            var end = $('#end_').val();
            var menu = $('#menu_ option:selected').val();
            var type = $('#type_ option:selected').val();

            var table = $('#data-table-1').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    dataType: "JSON",
                    type: "POST",
                    url: "{{ url('/4dm1n/log/ajax/table') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        start_date: start,
                        end_date: end,
                        menu_id: menu,
                        type: type
                    },
                    async: true,
                    error: function(xhr, error, code) {
                        console.log(xhr);
                        console.log(code);
                    }
                },
                destroy: true,
                columns: [{
                        data: 'time',
                        name: 'time',
                        orderable: false,
                    },

                    {
                        data: 'user_type',
                        name: 'user_type',
                        orderable: false,
                    },
                    {
                        data: 'avatar',
                        name: 'avatar',
                        orderable: false,
                    },
                    {
                        data: 'user_log',
                        name: 'user_log',
                    },
                    {
                        data: 'menu.name',
                        name: 'menu.name',
                    },
                    {
                        data: 'log',
                        name: 'log'
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
