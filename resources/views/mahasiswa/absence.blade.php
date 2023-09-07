@extends('mahasiswa/master')

@section('title', 'Absensi mahasiswa')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('absensi mahasiswa') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row w-100">
                        <div class="mb-3 col-md-3">
                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}" id="date_" oninput="load_table()">
                        </div>
                    </div>
                </div>

                <div class="card-body">
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
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            load_table();
        });

        function load_table() {
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
                    _colleger: {{ akun('mahasiswa')->id }}
                },
                url: "{{ url('mahasiswa/absensi/table') }}",
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
