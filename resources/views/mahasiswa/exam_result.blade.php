@extends('mahasiswa/master')

@section('title', 'Ujian')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('mahasiswa/ujian') }}">{{ tr('ujian') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('hasil ujian') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card  course-dedails-bx">

                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-xxl-9">
                            <h2>{{ $qc->exam->name }} </h2>
                            <div class="description">

                                <p>{{ $qc->exam->description }}</p>
                                @if ($qc->note)
                                    <p>
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>{{ tr('catatan kelas') }}</h6>
                                            <p class="card-text">{{ $qc->note }}</p>
                                        </div>
                                    </div>
                                    </p>
                                @endif
                                <ul class="d-flex align-items-center raiting flex-wrap">
                                    <li class="text-info">
                                        <i class="fa fa-check-circle"></i> {{ $qc->class->name }}
                                    </li>
                                    <li><span class="font-weight-bolder ms-3">{{ title_lecturer($qc->exam->lecturer) }}</span>
                                    </li>
                                    <li>{{ $qc->exam->sks->subject->name }}</li>
                                    <li>{{ count($qc->exam->exam_question) }} {{ tr('soal') }}</li>
                                </ul>
                                <ul class="d-flex align-items-center raiting flex-wrap">
                                    <li>
                                        <i class="fa fa-stopwatch"></i> {{ date_id($qc->start, 2) }} -
                                        {{ date_id($qc->end, 2) }}
                                    </li>


                                </ul>


                            </div>
                        </div>
                        <div class="col-xl-12 col-xxl-3">
                            <div
                                style=" background-image: url('{{ asset(EXAM_G . str_replace(' ', '_', $qc->exam->name)) }}');
                                    border: 1px solid #eee;
                                    background-position:center;
                                    background-size:cover;
                                    border-radius: 25px;
                                    width: 100%;
                                    height: 130px;">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card  course-dedails-bx">
                <div class="card-header border-0 pb-0">
                    <h2>{{ tr('soal') }}</h2>
                    <span class="float-end badge badge-danger">{{ tr('skor akhir') }}<h2 class="text-dark">
                            {{ $final_score }}/{{ $total_value }}</h2>
                    </span>
                </div>
                <div class="card-body pt-3">
                    <div class="course-details-tab style-2">
                        <div class="row" style="width:100%">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ tr('soal') }}</th>
                                                <th>{{ tr('skor') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($qc->exam->exam_question as $item)
                                                <tr>
                                                    <th width="10%">
                                                        <div class="mt-2">{{ $item->sort }}.</div>
                                                    </th>
                                                    <td class="align-top">
                                                        <small class="text-info"><b>{{ tr('bobot soal') }} {{ $item->value }}</b>
                                                        </small>
                                                        @php echo $item->question->question @endphp
                                                        @if ($item->question->file)

                                                            <b>{{ tr('file') }} :</b><a class="text-info" href="{{ asset(DOC_PATH . $item->question->file) }}" download>
                                                                {{ $item->question->file }} </a><br>
                                                            <br>
                                                        @endif
                                                        <br>

                                                        @if ($item->question->type == 0)
                                                            <textarea disabled class="form-control">{{ $item->answer ? $item->answer->answer : '' }}</textarea>
                                                        @endif
                                                        @if ($item->question->type == 1)
                                                            @php $options=json_decode($item->question->choice,false); @endphp
                                                            <table class="table table-borderless">
                                                                @foreach ($options as $sub)
                                                                    <tr>
                                                                        <th width="5%" class="p-0 m-0">
                                                                            <div class="form-check-primary">
                                                                                <input class="form-check-input" type="radio" value="{{ $sub->choice }}" @if ($item->answer) @if ($item->answer->answer == $sub->choice) checked @endif @endif
                                                                                disabled>

                                                                            </div>
                                                                        </th>
                                                                        <td class="p-0 m-0" width="5%">
                                                                            <p>{{ $sub->choice }}.</p>
                                                                        </td>
                                                                        <td class="p-0 m-0">
                                                                            @php echo $sub->desc @endphp
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </table>
                                                        @endif

                                                        @if ($item->question->type == 2)
                                                            <a href="@if ($item->answer) {{ $item->answer->file ? asset(LMS_PATH . $item->answer->file) : '' }} @endif" class="btn btn-primary btn-xs" download><i class="fa fa-download"></i>
                                                                @if ($item->answer)
                                                                    {{ $item->answer->file ? $item->answer->file : '' }}
                                                                @endif
                                                            </a>
                                                            <input type="file" class="dropify" name="answer_{{ $item->id }}" onchange="file(this,{{ $item->id }})" data-show-remove="false" name="file" height="200" />
                                                        @endif





                                                        @if ($item->question->answer)
                                                            @if ($item->question->type == 1)
                                                                <b>JAWABAN DOSEN:
                                                                    {{ $item->question->choice_answer }}</b><br>
                                                            @endif
                                                            @php echo $item->question->answer @endphp
                                                        @endif

                                                    </td>
                                                    <td width="5%" class="align-top">

                                                        @if ($item->answer)
                                                            <span class="badge mt-2 {{ $item->answer->score == 0 ? 'badge-danger' : ($item->answer->score == 100 ? 'badge-success' : 'badge-info') }}">
                                                                {{ $item->answer->score }}%</span>
                                                        @else
                                                            <span class="badge mt-2 badge-danger">0%</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
