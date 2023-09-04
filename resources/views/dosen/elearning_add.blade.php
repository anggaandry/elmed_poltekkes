@extends('dosen/master')

@section('title', 'Tambah elearning')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">LMS</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dosen/elearning') }}">Elearning</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Tambah elearning</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <form action="{{ url('/dosen/elearning/add') }}" method="post" enctype="multipart/form-data"
                    onsubmit="return check_valid();">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Grafik key (opsional)</label>
                                <input type="file" class="dropify" name="image" height="200" />
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Judul Elearning</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Link video youtube (optional)</label>
                                <input type="hidden" name="video" id="video_">
                                <input type="text" class="form-control" id="video_view" oninput="change_video()">
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Mata kuliah</label>
                                <select class="form-select form-select-lg wide" name="sks_id" required>
                                    <option value="">-- pilih mata kuliah --</option>
                                    @foreach ($subject_data as $item)
                                        <option value="{{ $item->id }}">{{ $item->subject->name }} (prodi
                                            {{ $item->prodi->program->name }}
                                            {{ $item->prodi->study_program->name }} - {{ $item->prodi->category->name }},
                                            semester {{ $item->semester }}, {{ $item->value }}
                                            SKS)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Deskripsi elearning</label>
                                <div class="custom-ekeditor">
                                    <textarea id="editor1" name="description"></textarea>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Lampiran 1 (opsional)</label>
                                <input type="file" class="dropify" name="file1" height="200" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Lampiran 2 (opsional)</label>
                                <input type="file" class="dropify" name="file2" height="200" />
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="width:100%;">
                            <a class="btn  btn-secondary" href="{{ url('dosen/elearning') }}"><span
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
                show_toast(0, "Link video youtube tidak valid");
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
