@extends('admin/master')

@section('title', 'Konfigurasi')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Konfigurasi</a></li>

        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <form action="{{ url('/4dm1n/konfigurasi/update') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ UNIVERSITY_ID }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Logo kampus</label>
                                <input type="file" class="dropify" name="logo" height="200"
                                    data-default-file="{{ $config_data->logo ? asset(LOGO_PATH . $config_data->logo) : '' }}" />
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Kode kampus</label>
                                <input type="text" class="form-control" name="code" value="{{ $config_data->code }}"
                                    disabled="true">
                            </div>
                            <div class="mb-3 col-md-9">
                                <label class="form-label">Nama kampus</label>
                                <input type="text" class="form-control" name="name" value="{{ $config_data->name }}"
                                    required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Tipe kampus</label>
                                <select class="form-select form-select-lg" name="type">
                                    <option {{ $config_data->type == 'Universitas' ? 'selected' : '' }}>Universitas</option>
                                    <option {{ $config_data->type == 'Politeknik' ? 'selected' : '' }}>Politeknik</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" value="{{ $config_data->email }}"
                                    required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">No telp</label>
                                <input type="text" class="form-control" name="phone" value="{{ $config_data->phone }}"
                                    required>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">Alamat lengkap</label>
                                <textarea class="form-control" name="address">{{ $config_data->address }}</textarea>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Longitude kampus</label>
                                <input type="text" class="form-control" name="lon" value="{{ $config_data->lon }}"
                                    required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Latitude kampus</label>
                                <input type="text" class="form-control" name="lat" value="{{ $config_data->lat }}"
                                    required>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="width:100%;">

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
