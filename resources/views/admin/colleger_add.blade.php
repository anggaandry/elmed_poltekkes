@extends('admin/master')

@section('title', 'Tambah mahasiswa')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ url('4dm1n/mahasiswa') }}">Mahasiswa</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Tambah mahasiswa</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <form action="{{ url('/4dm1n/mahasiswa/add') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Foto</label>
                                <input type="file" class="dropify" name="avatar" height="200" />
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Nama lengkap mahasiswa</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">NIM</label>
                                <input type="text" class="form-control" name="nim" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Gender</label>
                                <select class="form-select form-select-lg wide" name="gender">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Prodi</label>
                                @if (can_prodi())
                                    <input type="hidden" name="prodi_id" value="{{ $prodi_id }}">
                                @endif
                                <select class="form-select form-select-lg" name="prodi_id"
                                    @if (can_prodi()) disabled @else required @endif>
                                    <option value="">-- pilih prodi -- </option>
                                    @foreach ($prodi_data as $item)
                                        <option value="{{ $item->id }}"
                                            @if ($prodi_id == $item->id) selected @endif>
                                            {{ $item->program->name }}
                                            {{ $item->study_program->name }} - {{ $item->category->name }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Agama</label>
                                <select class="form-select form-select-lg wide" name="religion_id">

                                    @foreach ($religion_data as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="mb-3 col-md-4">
                                <label class="form-label">Tanggal lahir</label>
                                <input type="date" class="form-control" name="birthdate" required>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Status</label>
                                <select class="form-select form-select-lg wide" name="status">
                                    <option value="1">Aktif </option>
                                    <option value="2">Lulus </option>
                                    <option value="3">D.O </option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Tahun masuk</label>
                                <input type="number" class="form-control" name="year" required>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="width:100%;">
                            <a class="btn  btn-secondary" href="{{ url('4dm1n/mahasiswa') }}"><span
                                    class="btn-icon-start text-secondary"><i
                                        class="fa fa-arrow-left-long color-secondary"></i>
                                </span>Kembali</a>
                            <button class="btn  btn-info float-end mb-3" type="submit"><span
                                    class="btn-icon-start text-info"><i class="fa fa-save color-info"></i>
                                </span>Simpan</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
