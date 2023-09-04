@extends('admin/master')

@section('title', 'Edit dosen')

@section('breadcrumb')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{ url('4dm1n/dosen') }}">Dosen</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Edit dosen</a></li>
    </ol>
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-xl-12 col-xxl-12">
        <div class="card">
            <form action="{{ url('/4dm1n/dosen/edit') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $lecturer_data->id }}">
                <input type="hidden" name="route" value="{{ $route }}">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Foto</label>
                            <input type="file" class="dropify" name="avatar" height="200" data-default-file="{{ $lecturer_data->avatar ? asset(AVATAR_PATH . $lecturer_data->avatar) : '' }}" />
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Nama lengkap dosen</label>
                            <input type="text" class="form-control" name="name" value="{{ $lecturer_data->name }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Gelar depan</label>
                            <input type="text" class="form-control" name="front_title" value="{{ $lecturer_data->front_title }}">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Gelar belakang</label>
                            <input type="text" class="form-control" name="back_title" value="{{ $lecturer_data->back_title }}">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Tipe identitas</label>
                            <select class="form-select form-select-lg wide" name="identity">
                                <option value="NIDN" @if ($lecturer_data->identity == 'NIDN') selected @endif>NIDN</option>
                                <option value="NUP" @if ($lecturer_data->identity == 'NUP') selected @endif>NUP</option>
                                <option value="NIDK" @if ($lecturer_data->identity == 'NIDK') selected @endif>NIDK</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">No. identitas</label>
                            <input type="text" class="form-control" name="identity_number" value="{{ $lecturer_data->identity_number }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Agama</label>
                            <select class="form-select form-select-lg wide" name="religion_id">

                                @foreach ($religion_data as $item)
                                <option value="{{ $item->id }}" @if ($lecturer_data->religion_id == $item->id) selected @endif>{{ $item->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Gender</label>
                            <select class="form-select form-select-lg wide" name="gender">
                                <option value="Laki-laki" @if ($lecturer_data->gender == 'Laki-laki') selected @endif>Laki-laki
                                </option>
                                <option value="Perempuan" @if ($lecturer_data->gender == 'Perempuan') selected @endif>Perempuan
                                </option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Tanggal lahir</label>
                            <input type="date" class="form-control" name="birthdate" value="{{ $lecturer_data->birthdate }}" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select form-select-lg wide" name="status">
                                <option value="1" @if ($lecturer_data->status == '1') selected @endif>Aktif</option>
                                <option value="0" @if ($lecturer_data->status == '0') selected @endif>Tidak aktif
                                </option>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="card-footer">
                    <div style="width:100%;">
                        <a class="btn  btn-secondary" href="{{ url('4dm1n/dosen') }}"><span class="btn-icon-start text-secondary"><i class="fa fa-arrow-left-long color-secondary"></i>
                            </span>Kembali</a>
                        <button class="btn  btn-info float-end mb-3" type="submit"><span class="btn-icon-start text-info"><i class="fa fa-save color-info"></i>
                            </span>Simpan</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection