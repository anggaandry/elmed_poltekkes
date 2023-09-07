@extends('admin/master')

@section('title', 'Semester')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('akademik') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('semester') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    @php $key_='Semester'; @endphp
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                @if (can($key_, 'add'))
                    <div class="card-header">
                        <div style="width:100%;">
                            <a class="btn  btn-primary float-end" data-bs-toggle="modal" href="#add"><span class="btn-icon-start text-primary"><i class="fa fa-plus color-primary"></i>
                                </span>{{ tr('tambah semester') }}</a>
                            <div class="modal fade" id="add">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ tr('tambah semester') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                            </button>
                                        </div>
                                        <form action="{{ url('/4dm1n/semester/add') }}" method="post">
                                            {{ csrf_field() }}
                                            <div class="modal-body">
                                                <div class="row">

                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ tr('semester') }}</label>
                                                        <select class="form-select form-select-lg" name="odd">
                                                            <option value="1">{{ tr('ganjil') }}</option>
                                                            <option value="2">{{ tr('genap') }}</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ tr('tahun akademik') }}</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" name="year" oninput="next(this.value)" required>
                                                            <span class="input-group-text border-0" id="next_year"></span>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ tr('mulai semester') }}</label>
                                                        <input type="date" name="start" class="form-control" required>
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ tr('berakhir semester') }}</label>
                                                        <input type="date" name="end" class="form-control" required>
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
                                    <th>{{ tr('mulai') }}</th>
                                    <th>{{ tr('berakhir') }}</th>
                                    <th>{{ tr('semester') }}</th>
                                    <th>{{ tr('t.a') }}</th>

                                    <th>{{ tr('status') }}</th>
                                    @if (can($key_, 'edit') || can($key_, 'delete'))
                                        <th>{{ tr('aksi') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @foreach ($semester_data as $item)
                                    <tr>
                                        <td>{{ date_id($item->start, 0) }}</td>
                                        <td>{{ date_id($item->end, 0) }}</td>
                                        <td>{{ $item->odd == 1 ? 'Ganjil' : 'Genap' }}</td>
                                        <td>{{ $item->year }}/{{ $item->year + 1 }}</td>

                                        <td>
                                            @if (strtotime($item->start) <= strtotime(date('Y-m-d')) && strtotime($item->end) >= strtotime(date('Y-m-d')))
                                                <span class='badge bg-success'>{{ tr('aktif') }}</span>
                                            @elseif(strtotime($item->start) >= strtotime(date('Y-m-d')))
                                                <span class='badge bg-primary'>{{ tr('coming soon') }}</span>
                                            @elseif(strtotime($item->end) <= strtotime(date('Y-m-d')))
                                                <span class='badge bg-secondary'>{{ tr('passed') }}</span>
                                            @endif
                                        </td>
                                        @if (can($key_, 'edit') || can($key_, 'delete'))
                                            <td>
                                                @if (can($key_, 'edit'))
                                                    <a class="btn btn-outline-info btn-xs" data-bs-toggle="modal" href="#edit{{ $item->id }}"><i class="fa fa-edit color-info"></i>
                                                    </a>
                                                    <div class="modal fade" id="edit{{ $item->id }}">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">{{ tr('edit semester') }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                    </button>
                                                                </div>
                                                                <form action="{{ url('/4dm1n/semester/edit') }}" method="post">
                                                                    {{ csrf_field() }}
                                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                                    <div class="modal-body text-start">
                                                                        <div class="row">

                                                                            <div class="mb-3 col-md-6">
                                                                                <label class="form-label">{{ tr('semester') }}</label>
                                                                                <select class="form-select form-select-lg" name="odd" disabled>
                                                                                    <option value="1" @if ($item->odd == 1) selected @endif>
                                                                                        {{ tr('ganjil') }} </option>
                                                                                    <option value="2" @if ($item->odd == 2) selected @endif>
                                                                                        {{ tr('genap') }}</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-3 col-md-6">
                                                                                <label class="form-label">{{ tr('tahun akademik') }}</label>

                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control" name="year" value="{{ $item->year }}" disabled>
                                                                                    <span class="input-group-text border-0">/{{ $item->year + 1 }}</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="mb-3 col-md-6">
                                                                                <label class="form-label">{{ tr('mulai semester') }}</label>

                                                                                <input type="date" name="start" value="{{ $item->start }}" class="form-control" required>
                                                                            </div>

                                                                            <div class="mb-3 col-md-6">
                                                                                <label class="form-label">{{ tr('berakhir semester') }}</label>

                                                                                <input type="date" name="end" value="{{ $item->end }}" class="form-control" required>
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
                                                                    <p>{{ tr('apakah anda ingin menghapus semester') }}<b>{{ $item->odd == 1 ? tr('ganjil') : tr('genap') }}

                                                                            {{ $item->year }}</b>
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">{{ tr('tutup') }}</button>
                                                                    <a href="{{ url('4dm1n/semester/delete/' . $item->id) }}" class="btn btn-primary">{{ tr('hapus') }}</a>
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
                paging: false,
                ordering: false,

                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    },
                    info: "Records _START_ to _END_ of _MAX_ entries",
                }
            });
        });
    </script>

    <script>
        function next(value) {
            value++;
            $('#next_year').html(" /" + value + " ");
        }
    </script>


@endsection
