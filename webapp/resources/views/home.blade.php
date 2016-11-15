@extends('layouts.app')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-header medium">Dashboard</div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="col-sm-12">
            <div class="row">
                @if(Auth::user()->hasRole(['administrator','user']))
                    <div class="col-lg-3 col-md-6">
                        <a href="{{url('/relays')}}">
                            <div class="panel panel-red">

                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="visual col-xs-3">
                                            <i class="fa fa-fw fa-check fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="visual-box medium text-right">Check Token</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <!-- /#page-wrapper -->
    </div>

@endsection
