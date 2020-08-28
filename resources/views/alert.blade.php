@if(session('status'))
    <div class="col-xl-7 col-lg-9 col-md-8 col-sm-12 col-12 mx-auto">
        <div class="alert alert-success alert-dismissible fade show text-center" style="font-size:15px" role="alert">
            <strong>Success!</strong> {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>
    </div>
@endif
@error('auth_code_error')
<div class="col-xl-7 col-lg-9 col-md-8 col-sm-12 col-12 mx-auto">
        <div class="alert alert-danger alert-dismissible fade show text-center" style="font-size:15px" role="alert">
            <strong>Error!</strong> {{ $message }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>
    </div>
@enderror
@error('rate_auth_code_error')
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mx-auto">
        <div class="alert alert-danger alert-dismissible fade show text-center" style="font-size:15px" role="alert">
            <strong>Error!</strong> {{ $message }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>
    </div>
@enderror
@if(session('rate_status'))
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mx-auto">
                <div class="alert alert-success alert-dismissible fade show text-center" style="font-size:14px" role="alert">
                    <strong>Success!</strong> {{ session('rate_status') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            </div>
@endif