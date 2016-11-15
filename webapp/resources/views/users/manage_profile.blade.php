@extends('layouts.app')

@section('content')
    <div class="container">
        @if (!$errors->has('two_factor_enabled'))
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Manage Profile</div>
                        <div class="panel-body">
                         @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                         @endif
                            <form class="form-horizontal" data-toggle="validator" role="form" method="POST" action="{{ url('/manage_profile') }}">
                                {!! csrf_field() !!}

                                <div class="form-group has-feedback">
                                    <label for="inputName" class="col-md-4 control-label">Name</label>
                                    <div class="col-md-6">
                                        <input type="text" id="inputName" class="form-control" name="name" value="{{ $user->name }}" minlength="5">
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">E-Mail Address</label>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" name="email" value="{{ $user->email  }}" disabled>
                                    </div>
                                </div>

                                <div class="form-group has-feedback">
                                    <label for="inputPassword" class="col-md-4 control-label">Password</label>
                                    <div class="col-md-6">
                                        <input id="inputPassword" type="password" class="form-control" name="password" data-minlength="8" pattern="^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$" data-error="{{trans('js_validation.password')}}">
                                        <div class="help-block with-errors">{{trans('js_validation.password')}}</div>
                                    </div>
                                </div>

                                <div class="form-group has-feedback">
                                    <label for="inputPasswordConfirm" class="col-md-4 control-label">Confirm Password</label>
                                    <div class="col-md-6">
                                        <input id="inputPasswordConfirm" type="password" class="form-control" name="password_confirmation" data-match="#inputPassword" data-match-error="{{trans('js_validation.password_confirm')}}">
                                        <div class="help-block with-errors"></div>
                                       
                                        
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-btn fa-user"></i> Update user
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
@push('page_js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.10.2/validator.min.js"></script>
    <script src="/js/modal.js"></script>
@endpush