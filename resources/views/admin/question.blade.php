@extends('admin/master')

@section('title', 'Bank soal')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('bank soal') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row" style="width:110%;">
                        <div class="col-5">
                            <label class="form-label text-left">{{ tr('prodi') }}</label>
                            <select class="form-select form-select-lg" id="prodi_" onchange="load_table()" @if (can_prodi()) disabled @endif>
                                <option value="">{{ tr('semua prodi') }}</option>
                                @foreach ($prodi_data as $item)
                                    <option value="{{ $item->id }}" @if ($prodi_id == $item->id) selected @endif>
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
                                    <th>{{ tr('jenis') }}</th>
                                    <th>{{ tr('matkul') }}</th>
                                    <th width="50%" class="text-start">{{ tr('soal') }}</th>
                                    <th>{{ tr('dibuat') }}</th>
                                    <th>{{ tr('aksi') }}</th>
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
                                    <h5 class="modal-title">{{ tr('detail soal') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>{{ tr('jenis soal') }}</th>
                                                </tr>
                                                <tr>
                                                    <td id="type_detail"></td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('soal') }}</th>
                                                </tr>
                                                <tr>
                                                    <td id="question_detail"></td>
                                                </tr>
                                                <tr>
                                                    <th>{{ tr('referensi jawaban') }}</td>
                                                </tr>
                                                <tr>
                                                    <td id="answer_detail"></td>
                                                </tr>
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
                    url: "{{ url('/4dm1n/soal/ajax/table') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        prodi_id: prodi_id
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
                    processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">{{ tr('loading...') }}</span></div></div>',
                    info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
                },

            });
        }
    </script>

    <script>
        function show_detail(id) {

            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                url: "{{ url('4dm1n/soal/ajax/id') }}",
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
