@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create</div>
                    <div class="panel-body">
                        <form class="form-horizontal" data-toggle="validator" role="form" method="POST" action="{{ url('/users/store') }}">
                            {!! csrf_field() !!}

                            <div class="form-group has-feedback">
                                <label for="inputName" class="col-md-4 control-label">Name</label>
                                <div class="col-md-6">
                                    <input type="text" id="inputName" class="form-control" name="name" value="{{ old('name') }}" minlength="5" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="inputEmail" class="col-md-4 control-label">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input type="email" id="inputEmail"  class="form-control" name="email" value="{{ old('email') }}" data-error="{{trans('js_validation.email')}}" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="form-group has-feedback">
                                <label  for="inputPassword" class="col-md-4 control-label">Password</label>
                                <div class="col-md-6">
                                    <input  id="inputPassword" type="password" class="form-control" name="password"data-minlength="8" pattern="^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$" data-error="{{trans('js_validation.password')}}" required>
                                    <div class="help-block with-errors">{{trans('js_validation.password')}}</div>
                                </div>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="inputPasswordConfirm" class="col-md-4 control-label">Confirm Password</label>
                                <div class="col-md-6">
                                    <input type="password" id="inputPasswordConfirm" data-match="#inputPassword" data-match-error="{{trans('js_validation.password_confirm')}}" class="form-control" name="password_confirmation" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="type" class="control-label col-md-4">Roles</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="role_id" required>
                                        <option value=""></option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" @if(old('role_id') == $role->id) selected @endif>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-user"></i> Create user
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page_js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.10.2/validator.min.js"></script>
@endpush