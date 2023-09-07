@extends('admin/master')

@section('title', 'Edit mahasiswa')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ url('4dm1n/mahasiswa') }}">{{ tr('mahasiswa') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('edit mahasiswa') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <form action="{{ url('/4dm1n/mahasiswa/edit') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $colleger_data->id }}">
                    <input type="hidden" name="route" value="{{ $route }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('foto') }}</label>
                                <input type="file" class="dropify" name="avatar" height="200" data-default-file="{{ $colleger_data->avatar ? asset(AVATAR_PATH . $colleger_data->avatar) : '' }}" />
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('nama lengkap mahasiswa') }}</label>
                                <input type="text" class="form-control" name="name" value="{{ $colleger_data->name }}" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('nim') }}</label>
                                <input type="text" class="form-control" name="nim" value="{{ $colleger_data->nim }}" required>
                            </div>


                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('gender') }}</label>
                                <select class="form-select form-select-lg wide" name="gender">
                                    <option value="Laki-laki" @if ($colleger_data->gender == 'Laki-laki') selected @endif>{{ tr('laki-laki') }}</option>
                                    <option value="Perempuan" @if ($colleger_data->gender == 'Perempuan') selected @endif>{{ tr('perempuan') }}</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('prodi') }}</label>
                                @if (can_prodi())
                                    <input type="hidden" name="prodi_id" value="{{ $colleger_data->prodi_id }}">
                                @endif
                                <select class="form-select form-select-lg" name="prodi_id" @if (can_prodi()) disabled @else required @endif>
                                    <option value="">-- {{ tr('pilih prodi') }} --</option>
                                    @foreach ($prodi_data as $item)
                                        <option value="{{ $item->id }}" @if ($colleger_data->prodi_id == $item->id) selected @endif>
                                            {{ $item->program->name }}
                                            {{ $item->study_program->name }} - {{ $item->category->name }}</option>
                                    @endforeach

                                </select>
                            </div>


                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('agama') }}</label>
                                <select class="form-select form-select-lg wide" name="religion_id">

                                    @foreach ($religion_data as $item)
                                        <option value="{{ $item->id }}" @if ($colleger_data->religion_id == $item->id) selected @endif>{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mb-3 col-md-4">
                                <label class="form-label">{{ tr('tanggal lahir') }}</label>
                                <input type="date" class="form-control" name="birthdate" value="{{ $colleger_data->birthdate }}" required>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">{{ tr('status') }}</label>
                                <select class="form-select form-select-lg wide" name="status">
                                    <option value="1" @if ($colleger_data->status == 1) selected @endif>{{ tr('aktif') }}</option>
                                    <option value="2" @if ($colleger_data->status == 2) selected @endif>{{ tr('lulus') }}</option>
                                    <option value="3" @if ($colleger_data->status == 3) selected @endif>{{ tr('d.o') }}</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">{{ tr('tahun masuk') }}</label>
                                <input type="number" class="form-control" name="year" value="{{ $colleger_data->year }}" required>
                            </div>


                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="width:100%;">
                            <a class="btn  btn-secondary" href="{{ url('4dm1n/mahasiswa') }}"><span class="btn-icon-start text-secondary"><i class="fa fa-arrow-left-long color-secondary"></i>
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
