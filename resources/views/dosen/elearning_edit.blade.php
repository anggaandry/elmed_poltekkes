@extends('dosen/master')

@section('title', 'Edit elearning')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dosen/elearning') }}">{{ tr('elearning') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dosen/elearning/detail?id=' . $data->id . '&kelas=' . $kelas) }}">{{ tr('detail elearning') }}</a>
            </li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('edit elearning') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <form action="{{ url('/dosen/elearning/edit') }}" method="post" enctype="multipart/form-data" onsubmit="return check_valid();">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <input type="hidden" name="kelas" value="{{ $kelas }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('grafik key (opsional)') }}</label>
                                <input type="file" class="dropify" name="image" data-default-file="{{ $data->image ? asset(LMS_PATH . $data->image) : '' }}" height="200" />
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('judul elearning') }}</label>
                                <input type="text" class="form-control" name="name" value="{{ $data->name }}" required>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('link video youtube (optional)') }}</label>
                                <input type="hidden" name="video" id="video_" value="{{ $data->video }}">
                                <input type="text" class="form-control" id="video_view" value="{{ $data->video ? 'https://www.youtube.com/watch?v=' : '' }}{{ $data->video }}" oninput="change_video()">
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('mata kuliah') }}</label>
                                <select class="form-select form-select-lg wide" name="sks_id" required>
                                    <option value="">-- {{ tr('pilih mata kuliah') }} --</option>
                                    @foreach ($subject_data as $item)
                                        <option value="{{ $item->id }}" @if ($data->sks_id == $item->id) selected @endif>{{ $item->subject->name }}
                                            ({{ tr('prodi') }}
                                            {{ $item->prodi->program->name }}
                                            {{ $item->prodi->study_program->name }} - {{ $item->prodi->category->name }},
                                            {{ tr('semester') }} {{ $item->semester }}, {{ $item->value }}
                                            {{ tr('sks') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('deskripsi elearning') }}</label>
                                <div class="custom-ekeditor">
                                    <textarea id="editor1" name="description">{{ $data->description }}</textarea>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('lampiran 1 (opsional)') }}</label>
                                <input type="file" class="dropify" data-default-file="{{ $data->file1 ? asset(LMS_PATH . $data->file1) : '' }}" name="file1" height="200" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('lampiran 2 (opsional)') }}</label>
                                <input type="file" class="dropify" data-default-file="{{ $data->file2 ? asset(LMS_PATH . $data->file2) : '' }}" name="file2" height="200" />
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="width:100%;">
                            <a class="btn  btn-secondary" href="{{ url('dosen/elearning/detail?id=' . $data->id . '&kelas=' . $kelas) }}"><span class="btn-icon-start text-secondary"><i class="fa fa-arrow-left-long color-secondary"></i>
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

@section('script')
    <script>
        ClassicEditor
            .create(document.querySelector('#editor1'), {})
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        function check_valid() {
            var view = $('#video_view').val();
            var video = $('#video_').val();
            if (view != "" && video == "") {
                show_toast(0, "{{ tr('link video youtube tidak valid') }}");
                return false;
            }

            return true;
        }

        function change_video() {
            var v = youtube_parser($('#video_view').val());
            $('#video_').val(v);
        }

        function youtube_parser(url) {
            var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
            var match = url.match(regExp);
            return (match && match[7].length == 11) ? match[7] : "";
        }
    </script>

@endsection
