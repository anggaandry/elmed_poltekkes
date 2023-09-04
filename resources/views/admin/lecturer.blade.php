@extends('admin/master')

@section('title', 'Dosen')

@section('breadcrumb')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Dosen</a></li>
    </ol>
</div>
@endsection

@section('content')
@php $key_='Dosen' @endphp
<div class="row">
    <div class="col-xl-12 col-xxl-12">
        <div class="card">
            <div class="card-header">
                <div class="row" style="width:110%;">
                    <div class="col-12">
                        <a class="btn  btn-primary float-end" href="{{ url('4dm1n/dosen/form/add') }}"><span class="btn-icon-start text-primary"><i class="fa fa-plus color-primary"></i>
                            </span>Tambah dosen</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table-1" class="display text-center table-striped">
                        <thead class="">
                            <tr>
                                <th>#</th>
                                <th>Avatar</th>
                                <th>Nama</th>
                                <th>NIDN/NUP/NIDK</th>
                                <th>prodi</th>
                                <th>Status</th>
                                @if (can($key_, 'edit') || can($key_, $delete))
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
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
                                <p>Apakah anda ingin menghapus dosen
                                    <b id="name_delete"></b>
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Tutup</button>
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
            ajax: {
                dataType: "JSON",
                type: "POST",
                url: "{{ url('/4dm1n/dosen/ajax/table') }}",
                data: {
                    _token: '{{ csrf_token() }}'

                },
                async: true,
                error: function(xhr, error, code) {
                    console.log(xhr);
                    console.log(code);
                }
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            destroy: true,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'avatar',
                    name: 'avatar',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'name_view',
                    name: 'name_view',
                },
                {
                    data: 'identity_view',
                    name: 'identity_view',
                },
                {
                    data: 'prodi',
                    name: 'prodi',
                },
                {
                    data: 'status_view',
                    name: 'status_view',
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
                processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
            },

        });
    }
</script>

<script>
    function show_delete(id, name) {
        $('#name_delete').html(name);
        $("#button_delete").attr("href", "{{ url('4dm1n/dosen/delete?id=') }}" + id)
        $('#delete').modal('show');
    }
</script>


@endsection