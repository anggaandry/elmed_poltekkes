@extends('admin/master')

@section('title', 'Tambah dosen')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ url('4dm1n/dosen') }}">{{ tr('dosen') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('tambah dosen') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <form action="{{ url('/4dm1n/dosen/add') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('foto') }}</label>
                                <input type="file" class="dropify" name="avatar" height="200" />
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('nama lengkap dosen') }}</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('gelar depan') }}</label>
                                <input type="text" class="form-control" name="front_title">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('gelar belakang') }}</label>
                                <input type="text" class="form-control" name="back_title">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('tipe identitas') }}</label>
                                <select class="form-select form-select-lg wide" name="identity">
                                    <option value="NIDN">{{ tr('nidn') }}</option>
                                    <option value="NUP">{{ tr('nup') }}</option>
                                    <option value="NIDK">{{ tr('nidk') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('no. identitas') }}</label>
                                <input type="text" class="form-control" name="identity_number" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('agama') }}</label>
                                <select class="form-select form-select-lg wide" name="religion_id">

                                    @foreach ($religion_data as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('gender') }}</label>
                                <select class="form-select form-select-lg wide" name="gender">
                                    <option value="Laki-laki">{{ tr('laki-laki') }}</option>
                                    <option value="Perempuan">{{ tr('perempuan') }}</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('tanggal lahir') }}</label>
                                <input type="date" class="form-control" name="birthdate" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('status') }}</label>
                                <select class="form-select form-select-lg wide" name="status">
                                    <option value="1">{{ tr('aktif') }}</option>
                                    <option value="0">{{ tr('tidak aktif') }}</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="width:100%;">
                            <a class="btn  btn-secondary" href="{{ url('4dm1n/dosen') }}"><span class="btn-icon-start text-secondary"><i class="fa fa-arrow-left-long color-secondary"></i>
                                </span>{{ tr('kembali') }}</a>
                            <button class="btn  btn-info float-end mb-3" type="submit"><span class="btn-icon-start text-info"><i class="fa fa-save color-info"></i>
                                </span>{{ tr('simpan') }}</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
