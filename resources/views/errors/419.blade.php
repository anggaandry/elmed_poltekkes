@extends('errors::minimal')

@section('content')
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-5">
                    <div class="form-input-content text-center error-page">
                        <h1 class="error-text fw-bold">419</h1>
                        <h4><i class="fa fa-thumbs-down text-danger"></i> token expired</h4>
                        <p>you do not have valid token to this access</p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
