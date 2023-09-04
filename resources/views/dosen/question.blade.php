@extends('dosen/master')

@section('title', 'Bank soal')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">LMS</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Bank soal</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row" style="width:110%;">
                        <div class="col-6">

                        </div>

                        <div class="col-6">
                            <a href="{{ url('dosen/soal/form/add') }}" class="btn btn-success float-end"><i
                                    class="fa fa-plus"></i>
                                Tambah soal </a>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table-1" class="display text-center table-striped">
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>Jenis</th>
                                    <th>Matkul</th>
                                    <th width="50%" class="text-start">Soal</th>
                                    <th>dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>

                    <div class="modal fade" id="detail">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail soal</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>JENIS SOAL</th>
                                                </tr>
                                                <tr>
                                                    <td id="type_detail"></td>
                                                </tr>
                                                <tr>
                                                    <th>SOAL</th>
                                                </tr>
                                                <tr>
                                                    <td id="question_detail"></td>
                                                </tr>
                                                <tr>
                                                    <th>REFERENSI JAWABAN</th>
                                                </tr>
                                                <tr>
                                                    <td id="answer_detail"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light"
                                        data-bs-dismiss="modal">Tutup</button>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="delete">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger">Peringatan !!</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <p>Apakah anda ingin menghapus soal
                                        ini?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <a id="button_delete" class="btn btn-primary">Hapus</a>
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
        });

        function load_table() {

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
                    url: "{{ url('/dosen/soal/ajax/table') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
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
                        data: 'type_name',
                        name: 'type_name',
                    }, {
                        data: 'subject',
                        name: 'subject',
                    }, {
                        class: "text-start",
                        data: 'full_question',
                        name: 'full_question',
                    }, {
                        data: 'time',
                        name: 'time',

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
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                    info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
                },

            });
        }
    </script>

    <script>
        function show_delete(id) {
            $("#button_delete").attr("href", "{{ url('dosen/soal/delete') }}/" + id)
            $('#delete').modal('show');
        }

        function show_detail(id) {

            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                url: "{{ url('dosen/soal/ajax/id') }}",
                success: function(data) {
                    console.log(data);
                    if (data.message == "success") {
                        var el = data.result;

                        $('#type_detail').html(el.type_name);
                        $('#question_detail').html(el.fq);

                        var addition = ``;
                        if (el.type == 1) {
                            addition = `<b>Jawaban: ${el.choice_answer}</b><br>`;
                        }

                        $('#answer_detail').html(addition + el.answer);

                        $('#detail').modal('show');
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
