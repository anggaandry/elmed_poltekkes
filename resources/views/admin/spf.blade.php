@extends('admin/master')

@section('title', 'Prodi Lengkap')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('master data') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('prodi lengkap') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    @php $key_='Prodi Lengkap'; @endphp
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                @if (can($key_, 'add'))
                    <div class="card-header">
                        <div style="width:100%;">
                            <a class="btn  btn-primary float-end" data-bs-toggle="modal" href="#add"><span class="btn-icon-start text-primary"><i class="fa fa-plus color-primary"></i>
                                </span>{{ tr('tambah prodi lengkap') }}</a>
                            <div class="modal fade" id="add">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ tr('tambah prodi lengkap') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                            </button>
                                        </div>
                                        <form action="{{ url('/4dm1n/full_prodi/add') }}" method="post">
                                            {{ csrf_field() }}
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">{{ tr('program') }}</label>
                                                        <select class="form-select form-select-lg" name="program_id" required>
                                                            <option value="">-- {{ tr('pilih program') }} --</option>
                                                            @foreach ($program_data as $subitem)
                                                                <option value="{{ $subitem->id }}">{{ $subitem->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">{{ tr('prodi') }}</label>
                                                        <select class="form-select form-select-lg" name="study_program_id" required>
                                                            <option value="">-- {{ tr('pilih prodi') }} --</option>
                                                            @foreach ($prodi_data as $subitem)
                                                                <option value="{{ $subitem->id }}">{{ $subitem->name }}
                                                                    ({{ $subitem->major->name }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">{{ tr('jenis prodi') }}</label>
                                                        <select class="form-select form-select-lg" name="category_id" required>
                                                            <option value="">-- {{ tr('pilih jenis prodi') }} --</option>
                                                            @foreach ($spc_data as $subitem)
                                                                <option value="{{ $subitem->id }}">{{ $subitem->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">{{ tr('bahasa default') }}</label>
                                                        <select class="form-select form-select-lg" name="lang" required>

                                                            @foreach (LANG as $subitem)
                                                                <option>{{ $subitem }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                <button type="submit" class="btn btn-primary">{{ tr('simpan') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table-1" class="display text-center table-striped">
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>{{ tr('program') }}</th>
                                    <th>{{ tr('jurusan') }}</th>
                                    <th>{{ tr('prodi') }}</th>
                                    <th>{{ tr('kategori') }}</th>
                                    <th>{{ tr('bahasa') }}</th>
                                    @if (can($key_, 'edit') || can($key_, 'delete'))
                                        <th>{{ tr('aksi') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @foreach ($spf_data as $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->program->name }}</td>
                                        <td>{{ $item->study_program->major->name }}</td>
                                        <td>{{ $item->study_program->name }}</td>
                                        <td>{{ $item->category->name }}</td>
                                        <td>{{ $item->lang }}</td>
                                        @if (can($key_, 'edit') || can($key_, 'delete'))
                                            <td>
                                                @if (can($key_, 'edit'))
                                                    <a class="btn btn-outline-info btn-xs" data-bs-toggle="modal" href="#edit{{ $item->id }}"><i class="fa fa-edit color-info"></i>
                                                    </a>
                                                    <div class="modal fade" id="edit{{ $item->id }}">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">{{ tr('edit prodi lengkap') }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                    </button>
                                                                </div>
                                                                <form action="{{ url('/4dm1n/full_prodi/edit') }}" method="post">
                                                                    {{ csrf_field() }}
                                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                                    <div class="modal-body text-start">
                                                                        <div class="row">

                                                                            <div class="mb-3 col-md-12">
                                                                                <label class="form-label">{{ tr('program') }}</label>
                                                                                <select class="form-select form-select-lg" name="program_id" required>
                                                                                    <option value="">-- {{ tr('pilih program') }} --</option>


                                                                                    @foreach ($program_data as $subitem)
                                                                                        <option value="{{ $subitem->id }}" @if ($item->program_id == $subitem->id) selected @endif>
                                                                                            {{ $subitem->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-3 col-md-12">
                                                                                <label class="form-label">{{ tr('prodi') }}</label>
                                                                                <select class="form-select form-select-lg" name="study_program_id" required>
                                                                                    <option value="">-- {{ tr('pilih prodi') }} --</option>

                                                                                    @foreach ($prodi_data as $subitem)
                                                                                        <option value="{{ $subitem->id }}" @if ($item->study_program_id == $subitem->id) selected @endif>
                                                                                            {{ $subitem->name }}
                                                                                            ({{ $subitem->major->name }})
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-3 col-md-12">
                                                                                <label class="form-label">{{ tr('jenis prodi') }}</label>

                                                                                <select class="form-select form-select-lg" name="category_id" required>
                                                                                    <option value="">-- {{ tr('pilih jenis prodi') }} --</option>



                                                                                    @foreach ($spc_data as $subitem)
                                                                                        <option value="{{ $subitem->id }}" @if ($item->category_id == $subitem->id) selected @endif>
                                                                                            {{ $subitem->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-3 col-md-12">
                                                                                <label class="form-label">{{ tr('bahasa default') }}</label>
                                                                                <select class="form-select form-select-lg" name="lang" required>

                                                                                    @foreach (LANG as $subitem)
                                                                                        <option @if ($item->lang == $subitem) selected @endif>{{ $subitem }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>


                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                        <button type="submit" class="btn btn-primary">{{ tr('simpan') }}</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

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
                                                                    <p>{{ tr('apakah anda ingin menghapus prodi lengkap') }}<b>{{ $item->study_program->name }} -
                                                                            {{ $item->category->name }}</b>
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                    <a href="{{ url('4dm1n/full_prodi/delete/' . $item->id) }}" class="btn btn-primary">{{ tr('hapus') }}</a>
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

    <script>
        $(document).ready(function() {
            $('#data-table-1').DataTable({
                createdRow: function(row, data, index) {
                    $(row).addClass('selected')
                },
                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    },
                    info: "<br> &nbsp; &nbsp; <b>page _PAGE_ of _PAGES_</b>  | Records _START_ to _END_ of _MAX_ entries",
                }
            });
        });
    </script>


@endsection
