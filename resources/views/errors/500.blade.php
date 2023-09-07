@extends('errors::minimal')

@section('content')
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-5">
                    <div class="form-input-content text-center error-page">
                        <h1 class="error-text fw-bold">500</h1>
                        <h4 class="text-nowrap"><i class="fa fa-times-circle text-danger"></i> internal server error</h4>
                        <p>you do not have permission to view this resource</p>
                        <div>
                            <a class="btn btn-primary" href="./index.html">back to home</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
