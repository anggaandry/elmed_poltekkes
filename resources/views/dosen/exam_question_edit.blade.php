@extends('dosen/master')

@section('title', 'Edit soal untuk ujian')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dosen/ujian') }}">{{ tr('ujian') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dosen/ujian/detail?id=' . $data->exam->id . '&kelas=' . $kelas) }}">Detail
                    Ujian</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('edit soal untuk ujian') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <form action="{{ url('/dosen/ujian/soal/edit') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $data->id }}" name="id">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('ujian') }}</label>
                                <input type="hidden" name="exam_id" value="{{ $data->exam->id }}">
                                <input type="text" class="form-control" value="{{ $data->exam->name }}" disabled>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('mata kuliah') }}</label>
                                <input type="hidden" name="sks_id" value="{{ $data->exam->sks_id }}">
                                <input type="text" class="form-control" value="{{ $data->exam->sks->subject->name }} (prodi {{ $data->exam->sks->prodi->program->name }} {{ $data->exam->sks->prodi->study_program->name }} - {{ $data->exam->sks->prodi->category->name }}, semester {{ $data->exam->sks->semester }}, {{ $data->exam->sks->value }} SKS)" disabled>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('nomor urut soal') }}</label>
                                <input type="text" class="form-control" value="{{ $data->sort }}" name="sort" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('bobot soal') }}</label>
                                <input type="text" class="form-control" value="{{ $data->value }}" name="value" required>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('tipe') }}</label>
                                <input type="hidden" name="type" value="{{ $data->question->type }}">
                                <select class="form-select form-select-lg wide" id="type" onchange="change_type()" disabled>
                                    <option value="0" @if ($data->question->type == 0) selected @endif>{{ tr('essay') }}</option>
                                    <option value="1" @if ($data->question->type == 1) selected @endif>{{ tr('pilihan berganda') }}</option>

                                    <option value="2" @if ($data->question->type == 2) selected @endif>{{ tr('upload file') }}</option>

                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('soal') }}</label>
                                <div class="custom-ekeditor">
                                    <textarea id="editor1" name="question">{{ $data->question->question }}</textarea>
                                </div>
                                @if ($data->question->type == 1)
                                    @php $cq= json_decode($data->question->choice,false) @endphp
                                    <div class="table-responsive" id="multiple">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <th width="10%">{{ tr('a.') }}</th>
                                                    <td>
                                                        <div class="custom-ekeditor">
                                                            <textarea id="editorA" name="choice_a">{{ $cq[0]->desc }}</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="10%">{{ tr('b.') }}</th>
                                                    <td>
                                                        <div class="custom-ekeditor">
                                                            <textarea id="editorB" name="choice_b">{{ $cq[1]->desc }}</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="10%">{{ tr('c.') }}</th>
                                                    <td>
                                                        <div class="custom-ekeditor">
                                                            <textarea id="editorC" name="choice_c">{{ $cq[2]->desc }}</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="10%">{{ tr('d.') }}</th>
                                                    <td>
                                                        <div class="custom-ekeditor">
                                                            <textarea id="editorD" name="choice_d">{{ $cq[3]->desc }}</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="10%">{{ tr('e.') }}</th>
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
                                        <label class="form-label">{{ tr('jawaban') }}</label>
                                        <select class="form-select form-select-lg wide" id="answer_multiple" name="choice_answer">
                                            <option value="A" {{ $data->question->choice_answer == 'A' ? 'selected' : '' }}>
                                                A</option>
                                            <option value="B" {{ $data->question->choice_answer == 'B' ? 'selected' : '' }}>
                                                B</option>
                                            <option value="C" {{ $data->question->choice_answer == 'C' ? 'selected' : '' }}>
                                                C</option>
                                            <option value="D" {{ $data->question->choice_answer == 'D' ? 'selected' : '' }}>
                                                D</option>
                                            <option value="E" {{ $data->question->choice_answer == 'E' ? 'selected' : '' }}>
                                                E</option>
                                        </select>
                                    </div>
                                @endif

                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('referensi Jawaban (optional)') }}</label>
                                <div class="custom-ekeditor">
                                    <textarea id="editor2" name="answer">{{ $data->question->answer }}</textarea>
                                </div>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('lampiran soal (opsional)') }}</label>
                                <input type="file" class="dropify" name="file" height="200" data-default-file="{{ $data->question->file ? asset(DOC_PATH . $data->question->file) : '' }}" />
                            </div>


                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="width:100%;">
                            <a class="btn  btn-secondary" href="{{ url('dosen/ujian/detail?id=' . $data->exam->id . '&kelas=' . $kelas) }}"><span class="btn-icon-start text-secondary"><i class="fa fa-arrow-left-long color-secondary"></i>
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
