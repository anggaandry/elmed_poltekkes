@extends('mahasiswa/master')

@section('title', 'Kuis')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ tr('lms') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url('mahasiswa/kuis') }}">{{ tr('kuis') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ tr('kerjakan kuis') }}</a></li>
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
                            <h2>{{ $qc->quiz->name }} </h2>
                            <div class="description">

                                <p>{{ $qc->quiz->description }}</p>
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
                                    <li><span class="font-weight-bolder ms-3">{{ title_lecturer($qc->quiz->lecturer) }}</span>
                                    </li>
                                    <li>{{ $qc->quiz->sks->subject->name }}</li>
                                    <li>{{ count($qc->quiz->quiz_question) }} {{ tr('soal') }}</li>
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
                                style=" background-image: url('{{ asset(QUIZ_G . str_replace(' ', '_', $qc->quiz->name)) }}');
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
                    <span id="cd" class="float-end badge badge-danger"></span>
                </div>
                <div class="card-body pt-3">
                    <div class="course-details-tab style-2">
                        <div class="row" style="width:100%">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @foreach ($qc->quiz->quiz_question as $item)
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


                                                        @if ($item->question->type == 0)
                                                            <textarea name="answer_{{ $item->id }}" oninput="essay(this,{{ $item->id }})" rows="3" class="form-control">{{ $item->answer ? $item->answer->answer : '' }}</textarea>
                                                        @endif
                                                        @if ($item->question->type == 1)
                                                            @php $options=json_decode($item->question->choice,false); @endphp
                                                            <table class="table table-borderless">
                                                                @foreach ($options as $sub)
                                                                    <tr>
                                                                        <th width="5%" class="p-0 m-0">
                                                                            <div class="form-check-primary">
                                                                                <input class="form-check-input" type="radio" name="answer_{{ $item->id }}" value="{{ $sub->choice }}" @if ($item->answer) @if ($item->answer->answer == $sub->choice) checked @endif @endif
                                                                                onchange="answer(this,{{ $item->id }})">

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
                                                            <input type="file" class="dropify" name="answer_{{ $item->id }}" onchange="file(this,{{ $item->id }})" data-show-remove="false" @if ($item->answer) data-default-file="{{ $item->answer->file ? asset(LMS_PATH . $item->answer->file) : '' }}" @endif name="file" height="200" />
                                                        @endif



                                                    </td>
                                                    <td width="5%" class="align-top">
                                                        <button class="btn btn-danger btn-xs mt-2" id="btn-{{ $item->id }}" @if (!$item->answer) disabled @endif onclick="delete_({{ $item->id }},{{ $item->question->type }})"><i class="fa fa-trash"></i></button>

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

@section('script')
    <script>
        function answer(el, id) {
            var answer_ = el.value;
            var quiz_ = "{{ $qc->quiz->id }}";
            var quiz_class_ = "{{ $qc->id }}";
            var question_ = id;
            var colleger_ = "{{ $colleger->id }}";

            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    colleger_id: colleger_,
                    quiz_id: quiz_,
                    quiz_question_id: question_,
                    quiz_class_id: quiz_class_,
                    answer: answer_,
                },
                url: "{{ url('mahasiswa/kuis/ajax/answer') }}",
                success: function(data) {
                    console.log(data);
                    if (data.code == 1) {
                        $("#btn-" + id).attr('disabled', false);
                        show_toast(1, data.message);
                    } else {
                        show_toast(0, data.message);
                    }
                },
                error: function(request, status, error) {
                    console.error(request.responseText);
                    //alert(request.responseText);
                }
            });
        }

        function file(el, id) {
            var formData = new FormData();
            formData.append('file', $('input[name="answer_' + id + '"]')[0].files[0]);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('colleger_id', "{{ $colleger->id }}");
            formData.append('quiz_id', "{{ $qc->quiz->id }}");
            formData.append('quiz_class_id', "{{ $qc->id }}");
            formData.append('quiz_question_id', id);

            $.ajax({
                type: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: formData,
                enctype: 'multipart/form-data',
                url: "{{ url('mahasiswa/kuis/ajax/file') }}",
                success: function(data) {
                    console.log(data);
                    if (data.code == 1) {
                        $("#btn-" + id).attr('disabled', false);
                        show_toast(1, data.message);
                    } else {
                        show_toast(0, data.message);
                    }
                },
                error: function(request, status, error) {
                    console.error(request.responseText);
                    //alert(request.responseText);
                }
            });
        }

        let timer;

        function essay(el, id) {
            clearTimeout(timer);
            timer = setTimeout(() => {
                answer(el, id);
            }, 1000);
        }

        function delete_(id, type) {
            var quiz_ = "{{ $qc->quiz->id }}";
            var quiz_class_ = "{{ $qc->id }}";
            var question_ = id;
            var colleger_ = "{{ $colleger->id }}";

            $.ajax({
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    colleger_id: colleger_,
                    quiz_id: quiz_,
                    quiz_question_id: question_,
                    quiz_class_id: quiz_class_,
                },
                url: "{{ url('mahasiswa/kuis/ajax/reset') }}",
                success: function(data) {
                    console.log(data);
                    if (data.code == 1) {
                        show_toast(1, data.message);
                        $("#btn-" + id).attr('disabled', true);
                        if (type == 0) {
                            $('textarea[name="answer_' + id + '"]').val("");
                        }

                        if (type == 1) {
                            $('input[name="answer_' + id + '"]').prop('checked', false);
                        }

                        if (type == 2) {
                            $('input[name="answer_' + id + '"]').parent().find(".dropify-clear").trigger(
                                'click');
                        }


                    } else {
                        show_toast(0, data.message);
                    }
                },
                error: function(request, status, error) {
                    console.error(request.responseText);
                    //alert(request.responseText);
                }
            });
        }
    </script>

    <script>
        // Set the date we're counting down to

        var countDownDate = new Date("{{ date('M d, Y H:i:s', strtotime($qc->end)) }}").getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            var cont = "{{ tr('berakhir dalam') }} ";
            if (days > 0) {
                cont += (days + " Hari ");
            }

            if (hours > 0) {
                cont += (hours + " jam ");
            }
            document.getElementById("cd").innerHTML = cont +
                minutes + " {{ tr('menit') }} " + seconds + " {{ tr('detik') }} ";

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("cd").innerHTML = "{{ tr('waktu habis') }}";
                setTimeout(function() {
                    window.location.reload();
                }, 1000);

            }
        }, 1000);
    </script>
@endsection
