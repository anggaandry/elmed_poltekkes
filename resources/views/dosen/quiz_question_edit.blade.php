@extends('dosen/master')

@section('title', 'Edit soal untuk kuis')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">LMS</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dosen/kuis') }}">Kuis</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ url('dosen/kuis/detail?id=' . $data->quiz->id . '&kelas=' . $kelas) }}">Detail
                    Kuis</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Edit soal untuk kuis</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <form action="{{ url('/dosen/kuis/soal/edit') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $data->id }}" name="id">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Kuis</label>
                                <input type="hidden" name="quiz_id" value="{{ $data->quiz->id }}">
                                <input type="text" class="form-control" value="{{ $data->quiz->name }}" disabled>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Mata kuliah</label>
                                <input type="hidden" name="sks_id" value="{{ $data->quiz->sks_id }}">
                                <input type="text" class="form-control"
                                    value="{{ $data->quiz->sks->subject->name }} (prodi {{ $data->quiz->sks->prodi->program->name }} {{ $data->quiz->sks->prodi->study_program->name }} - {{ $data->quiz->sks->prodi->category->name }}, semester {{ $data->quiz->sks->semester }}, {{ $data->quiz->sks->value }} SKS)"
                                    disabled>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Nomor urut soal</label>
                                <input type="text" class="form-control" value="{{ $data->sort }}" name="sort"
                                    required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Bobot soal</label>
                                <input type="text" class="form-control" value="{{ $data->value }}" name="value"
                                    required>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Tipe</label>
                                <input type="hidden" name="type" value="{{ $data->question->type }}">
                                <select class="form-select form-select-lg wide" id="type" onchange="change_type()"
                                    disabled>
                                    <option value="0" @if ($data->question->type == 0) selected @endif>Essay</option>
                                    <option value="1" @if ($data->question->type == 1) selected @endif>Pilihan
                                        berganda</option>
                                    <option value="2" @if ($data->question->type == 2) selected @endif>Upload file
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Soal</label>
                                <div class="custom-ekeditor">
                                    <textarea id="editor1" name="question">{{ $data->question->question }}</textarea>
                                </div>
                                @if ($data->question->type == 1)
                                    @php $cq= json_decode($data->question->choice,false) @endphp
                                    <div class="table-responsive" id="multiple">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <th width="10%">A.</th>
                                                    <td>
                                                        <div class="custom-ekeditor">
                                                            <textarea id="editorA" name="choice_a">{{ $cq[0]->desc }}</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="10%">B.</th>
                                                    <td>
                                                        <div class="custom-ekeditor">
                                                            <textarea id="editorB" name="choice_b">{{ $cq[1]->desc }}</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="10%">C.</th>
                                                    <td>
                                                        <div class="custom-ekeditor">
                                                            <textarea id="editorC" name="choice_c">{{ $cq[2]->desc }}</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="10%">D.</th>
                                                    <td>
                                                        <div class="custom-ekeditor">
                                                            <textarea id="editorD" name="choice_d">{{ $cq[3]->desc }}</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="10%">E.</th>
                                                    <td>
                                                        <div class="custom-ekeditor">
                                                            <textarea id="editorE" name="choice_e">{{ $cq[4]->desc }}</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Jawaban</label>
                                        <select class="form-select form-select-lg wide" name="choice_answer">
                                            <option value="A"
                                                {{ $data->question->choice_answer == 'A' ? 'selected' : '' }}>
                                                A</option>
                                            <option value="B"
                                                {{ $data->question->choice_answer == 'B' ? 'selected' : '' }}>
                                                B</option>
                                            <option value="C"
                                                {{ $data->question->choice_answer == 'C' ? 'selected' : '' }}>
                                                C</option>
                                            <option value="D"
                                                {{ $data->question->choice_answer == 'D' ? 'selected' : '' }}>
                                                D</option>
                                            <option value="E"
                                                {{ $data->question->choice_answer == 'E' ? 'selected' : '' }}>
                                                E</option>
                                        </select>
                                    </div>
                                @endif

                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">Referensi Jawaban (optional)</label>
                                <div class="custom-ekeditor">
                                    <textarea id="editor2" name="answer">{{ $data->question->answer }}</textarea>
                                </div>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Lampiran soal (opsional)</label>
                                <input type="file" class="dropify" name="file" height="200"
                                    data-default-file="{{ $data->question->file ? asset(DOC_PATH . $data->question->file) : '' }}" />
                            </div>


                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="width:100%;">
                            <a class="btn  btn-secondary"
                                href="{{ url('dosen/kuis/detail?id=' . $data->quiz->id . '&kelas=' . $kelas) }}"><span
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
        let EditorA;
        let EditorB;
        let EditorC;
        let EditorD;
        let EditorE;

        ClassicEditor
            .create(document.querySelector('#editor1'), {})
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('#editor2'), {})
            .catch(error => {
                console.error(error);
            });

        @if ($data->question->type == 1)
            ClassicEditor
                .create(document.querySelector('#editorA'), {})
                .then(editor => {
                    //window.editor = editor;
                    EditorA = editor;
                })
                .catch(error => {
                    console.error(error);
                });

            ClassicEditor
                .create(document.querySelector('#editorB'), {})
                .then(editor => {
                    //window.editor = editor;
                    EditorB = editor;
                })
                .catch(error => {
                    console.error(error);
                });

            ClassicEditor
                .create(document.querySelector('#editorC'), {})
                .then(editor => {
                    //window.editor = editor;
                    EditorC = editor;
                })
                .catch(error => {
                    console.error(error);
                });

            ClassicEditor
                .create(document.querySelector('#editorD'), {})
                .then(editor => {
                    //window.editor = editor;
                    EditorD = editor;
                })
                .catch(error => {
                    console.error(error);
                });

            ClassicEditor
                .create(document.querySelector('#editorE'), {})
                .then(editor => {
                    //window.editor = editor;
                    EditorE = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        @endif
    </script>



@endsection
