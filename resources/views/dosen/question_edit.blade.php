@extends('dosen/master')

@section('title', 'Edit soal')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">LMS</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dosen/elearning') }}">Soal</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Edit soal</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <form action="{{ url('/dosen/soal/edit') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $question_data->id }}" name="id">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Mata kuliah</label>
                                <select class="form-select form-select-lg wide" name="sks_id" required>
                                    <option value="">-- pilih mata kuliah --</option>
                                    @foreach ($subject_data as $item)
                                        <option value="{{ $item->id }}"
                                            @if ($question_data->sks_id == $item->id) selected @endif>{{ $item->subject->name }}
                                            (prodi
                                            {{ $item->prodi->program->name }}
                                            {{ $item->prodi->study_program->name }} - {{ $item->prodi->category->name }},
                                            semester {{ $item->semester }}, {{ $item->value }}
                                            SKS)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Tipe</label>
                                <input type="hidden" name="type" value="{{ $question_data->type }}">
                                <select class="form-select form-select-lg wide" id="type" onchange="change_type()"
                                    disabled>
                                    <option value="0" @if ($question_data->type == 0) selected @endif>Essay</option>
                                    <option value="1" @if ($question_data->type == 1) selected @endif>Pilihan
                                        berganda</option>
                                    <option value="2" @if ($question_data->type == 2) selected @endif>Upload file
                                    </option>

                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Soal</label>
                                <div class="custom-ekeditor">
                                    <textarea id="editor1" name="question">{{ $question_data->question }}</textarea>
                                </div>
                                @if ($question_data->type == 1)
                                    @php $cq= json_decode($question_data->choice,false) @endphp
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
                                                {{ $question_data->choice_answer == 'A' ? 'selected' : '' }}>
                                                A</option>
                                            <option value="B"
                                                {{ $question_data->choice_answer == 'B' ? 'selected' : '' }}>
                                                B</option>
                                            <option value="C"
                                                {{ $question_data->choice_answer == 'C' ? 'selected' : '' }}>
                                                C</option>
                                            <option value="D"
                                                {{ $question_data->choice_answer == 'D' ? 'selected' : '' }}>
                                                D</option>
                                            <option value="E"
                                                {{ $question_data->choice_answer == 'E' ? 'selected' : '' }}>
                                                E</option>
                                        </select>
                                    </div>
                                @endif

                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">Referensi Jawaban (optional)</label>
                                <div class="custom-ekeditor">
                                    <textarea id="editor2" name="answer">{{ $question_data->answer }}</textarea>
                                </div>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Lampiran soal (opsional)</label>
                                <input type="file" class="dropify" name="file" height="200"
                                    data-default-file="{{ $question_data->file ? asset(DOC_PATH . $question_data->file) : '' }}" />
                            </div>


                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="width:100%;">
                            <a class="btn  btn-secondary" href="{{ url('dosen/soal') }}"><span
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

        @if ($question_data->type == 1)
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
