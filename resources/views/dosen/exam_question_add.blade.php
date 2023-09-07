@extends('dosen/master')

@section('title', 'Tambah soal untuk ujian')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dosen/ujian') }}">{{ tr('ujian') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('dosen/ujian/detail?id=' . $exam_data->id . '&kelas=' . $kelas) }}">{{ tr('detail ujian') }}</a></li>

            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('tambah soal untuk ujian') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <form action="{{ url('/dosen/ujian/soal/add') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ akun('dosen')->id }}" name="lecturer_id">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('ujian') }}</label>
                                <input type="hidden" name="exam_id" value="{{ $exam_data->id }}">
                                <input type="text" class="form-control" value="{{ $exam_data->name }}" disabled>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('mata kuliah') }}</label>
                                <input type="hidden" name="sks_id" value="{{ $exam_data->sks_id }}">
                                <input type="text" class="form-control" value="{{ $exam_data->sks->subject->name }} (prodi {{ $exam_data->sks->prodi->program->name }} {{ $exam_data->sks->prodi->study_program->name }} - {{ $exam_data->sks->prodi->category->name }}, semester {{ $exam_data->sks->semester }}, {{ $exam_data->sks->value }} SKS)" disabled>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('nomor urut soal') }}</label>
                                <input type="text" class="form-control" name="sort" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ tr('bobot soal') }}</label>
                                <input type="text" class="form-control" name="value" required>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('tipe') }}</label>
                                <select class="form-select form-select-lg wide" name="type" id="type" onchange="change_type()">
                                    <option value="0">{{ tr('essay') }}</option>
                                    <option value="1">{{ tr('pilihan berganda') }}</option>
                                    <option value="2">{{ tr('upload file') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('soal') }}</label>
                                <div class="custom-ekeditor">
                                    <textarea id="editor1" name="question"></textarea>
                                </div>
                                <div class="table-responsive d-none" id="multiple">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th width="10%">{{ tr('a.') }}</th>
                                                <td>
                                                    <div class="custom-ekeditor">
                                                        <textarea id="editorA" name="choice_a"></textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="10%">{{ tr('b.') }}</th>
                                                <td>
                                                    <div class="custom-ekeditor">
                                                        <textarea id="editorB" name="choice_b"></textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="10%">{{ tr('c.') }}</th>
                                                <td>
                                                    <div class="custom-ekeditor">
                                                        <textarea id="editorC" name="choice_c"></textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="10%">{{ tr('d.') }}</th>
                                                <td>
                                                    <div class="custom-ekeditor">
                                                        <textarea id="editorD" name="choice_d"></textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="10%">{{ tr('e.') }}</th>
                                                <td>
                                                    <div class="custom-ekeditor">
                                                        <textarea id="editorE" name="choice_e"></textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mb-3 col-md-12 d-none" id="answer_multiple">
                                    <label class="form-label">{{ tr('jawaban') }}</label>
                                    <select class="form-select form-select-lg wide" name="choice_answer" disabled>
                                        <option value="A">{{ tr('a') }}</option>
                                        <option value="B">{{ tr('b') }}</option>
                                        <option value="C">{{ tr('c') }}</option>
                                        <option value="D">{{ tr('d') }}</option>
                                        <option value="E">{{ tr('e') }}</option>
                                    </select>
                                </div>

                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('referensi Jawaban (optional)') }}</label>
                                <div class="custom-ekeditor">
                                    <textarea id="editor2" name="answer"></textarea>
                                </div>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ tr('lampiran soal (opsional)') }}</label>
                                <input type="file" class="dropify" name="file" height="200" />
                            </div>


                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="width:100%;">
                            <a class="btn  btn-secondary" href="{{ url('dosen/ujian/detail?id=' . $exam_data->id . '&kelas=' . $kelas) }}"><span class="btn-icon-start text-secondary"><i class="fa fa-arrow-left-long color-secondary"></i>
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
    </script>

    <script>
        function change_type() {
            var type = $('#type option:selected').val();
            var multi = $('#multiple');
            var ansmulti = $('#answer_multiple');
            if (type == 1) {
                EditorA.setData('');
                EditorB.setData('');
                EditorC.setData('');
                EditorD.setData('');
                EditorE.setData('');

                multi.removeClass('d-none');
                ansmulti.removeClass('d-none');
                ansmulti.children("select").prop("disabled", false);
            } else {
                multi.addClass('d-none');
                ansmulti.addClass('d-none');
                ansmulti.children("select").prop("disabled", true);
            }

        }
    </script>

@endsection
