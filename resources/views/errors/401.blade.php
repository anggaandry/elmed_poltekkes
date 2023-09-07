@extends('errors::minimal')

@section('content')
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-5">
                    <div class="form-input-content text-center error-page">
                        <h1 class="error-text fw-bold">401</h1>
                        <h4><i class="fa fa-thumbs-down text-danger"></i>bad request</h4>
                        <p>your request resulted in an error</p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
