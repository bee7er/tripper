@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! $title !!} :: @parent @endsection

{{-- Content --}}
@section('main')
    <h3>
        {{$title}}
    </h3>
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-screenshot fa-3x teal"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge teal">{{$trips}}</div>
                            <div class="teal">{{ trans("admin/trip.trip") }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{url('admin/trip')}}">
                    <div class="panel-footer">
                        <span class="pull-left">{{ trans("admin/admin.view_detail") }}</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-eye-open fa-3x teal"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge teal">{{$questions}}</div>
                            <div class="teal">{{ trans("admin/question.questions") }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{url('admin/question')}}">
                    <div class="panel-footer">
                        <span class="pull-left">{{ trans("admin/admin.view_detail") }}</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-eye-open fa-3x teal"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge teal">{{$clists}}</div>
                            <div class="teal">{{ trans("admin/clist.clists") }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{url('admin/clist')}}">
                    <div class="panel-footer">
                        <span class="pull-left">{{ trans("admin/admin.view_detail") }}</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-language fa-3x teal"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge teal">{{$languages}}</div>
                            <div class="teal">{{ trans("admin/admin.languages") }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{url('admin/language')}}">
                    <div class="panel-footer">
                        <span class="pull-left">{{ trans("admin/admin.view_detail") }}</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-user fa-3x teal"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge teal">{{$users}}</div>
                            <div class="teal">{{ trans("admin/admin.users") }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{url('admin/user')}}">
                    <div class="panel-footer">
                        <span class="pull-left">{{ trans("admin/admin.view_detail") }}</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
