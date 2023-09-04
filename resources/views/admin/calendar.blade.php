@extends('admin/master')

@section('title', 'Kalender')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Akademik</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Kalender akademik</a></li>
        </ol>
    </div>
@endsection

@section('content')
    @php $key_='Kalender'; @endphp
    <div class="row">

        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                @if (can($key_, 'add'))
                    <div class="card-header">
                        <div style="width:100%;">
                            <div class="row">
                                <div class="col-6">

                                    <select class="form-select form-select-lg" id="semester_" onchange="reload()">
                                        @foreach ($semester_data as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($semester_id == $item->id) selected @endif>
                                                Semester {{ $item->odd == 1 ? 'Ganjil' : 'Genap' }} TA
                                                {{ $item->year }}/{{ $item->year + 1 }} ({{ date_id($item->start, 0) }} -
                                                {{ date_id($item->end, 0) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <a class="btn  btn-primary float-end" data-bs-toggle="modal" href="#add"><span
                                            class="btn-icon-start text-primary"><i class="fa fa-plus color-primary"></i>
                                        </span>Tambah kalender akademik</a>
                                    <div class="modal fade" id="add">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tambah kalender akademik</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                                                </div>
                                                <form action="{{ url('/4dm1n/kalender/add') }}" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="mb-3 col-md-12">
                                                                <label class="form-label">Tanggal</label>
                                                                <input type="date" class="form-control" name="date"
                                                                    required>
                                                            </div>
                                                            <div class="mb-3 col-md-12">
                                                                <label class="form-label">Libur</label>
                                                                <select name="off" class="form-select">
                                                                    <option value="1">
                                                                        YA
                                                                    </option>
                                                                    <option value="0">
                                                                        TIDAK
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3 col-md-12">
                                                                <label class="form-label">Event</label>
                                                                <textarea name="name" rows="3" class="form-control" required></textarea>
                                                            </div>
                                                            <div class="mb-3 col-md-12">
                                                                <small class="text-danger">note: semua
                                                                    absensi di tanggal ini akan di
                                                                    tiadakan</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger light"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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
                                    <th>Tanggal</th>
                                    <th>Event</th>
                                    <th>Libur</th>
                                    @if (can($key_, 'edit') || can($key_, 'delete'))
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @foreach ($calendar_data as $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ date_id($item->date, 3) }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>@php echo $item->off == 1 ? "<span class='badge badge-danger'>Libur</span>" : '-' @endphp
                                        </td>
                                        @if (can($key_, 'edit') || can($key_, 'delete'))
                                            <td>
                                                @if (can($key_, 'edit'))
                                                    <a class="btn btn-outline-info btn-xs" data-bs-toggle="modal"
                                                        href="#edit{{ $item->id }}"><i
                                                            class="fa fa-edit color-info"></i>
                                                    </a>
                                                    <div class="modal fade" id="edit{{ $item->id }}">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Edit kalender akademik</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal">
                                                                    </button>
                                                                </div>
                                                                <form action="{{ url('/4dm1n/kalender/edit') }}"
                                                                    method="post">
                                                                    {{ csrf_field() }}
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $item->id }}">
                                                                    <div class="modal-body text-start">
                                                                        <div class="row">
                                                                            <div class="mb-3 col-md-12">
                                                                                <label class="form-label">Tanggal</label>
                                                                                <input type="date" class="form-control"
                                                                                    name="date"
                                                                                    value="{{ $item->date }}" required>
                                                                            </div>
                                                                            <div class="mb-3 col-md-12">
                                                                                <label class="form-label">Libur</label>
                                                                                <select name="off" class="form-select">
                                                                                    <option value="1"
                                                                                        @if ($item->off == 1) selected @endif>
                                                                                        YA
                                                                                    </option>
                                                                                    <option value="0"
                                                                                        @if ($item->off == 0) selected @endif>
                                                                                        TIDAK
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-3 col-md-12">
                                                                                <label class="form-label">Event</label>
                                                                                <textarea name="name" rows="3" class="form-control" required>{{ $item->name }}</textarea>

                                                                            </div>
                                                                            <div class="mb-3 col-md-12">
                                                                                <small class="text-danger">note: semua
                                                                                    absensi di tanggal ini akan di
                                                                                    tiadakan</small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-danger light"
                                                                            data-bs-dismiss="modal">Tutup</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Simpan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if (can($key_, 'delete'))
                                                    <a class="btn btn-outline-danger btn-xs" data-bs-toggle="modal"
                                                        href="#delete{{ $item->id }}"><i
                                                            class="fa fa-trash color-danger"></i>
                                                    </a>
                                                    <div class="modal fade" id="delete{{ $item->id }}">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title text-danger">Peringatan !!</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal">
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <p>Apakah anda ingin menghapus kalender akademik
                                                                        <b>{{ $item->name }}</b> ?
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger light"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                    <a href="{{ url('4dm1n/kalender/delete/' . $item->id) }}"
                                                                        class="btn btn-primary">Hapus</a>
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
                paging: false,
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

        function reload() {
            var semester_id = $('#semester_ option:selected').val();
            window.location.href = "{{ url('4dm1n/kalender?semester=') }}" + semester_id;
        }
    </script>


@endsection
