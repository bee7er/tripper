@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/actionDiagram.trip") !!}
:: @parent @endsection

{{-- Content --}}
@section('main')

    <div class="page-header">
        <h3>
            {!! trans("admin/actionDiagram.title") !!}: <span id="actionDiagramTitle">&nbsp;</span>
            <div class="pull-right">
                <div class="pull-right">
                    <button class="btn btn-primary btn-xs go_back">
                        <span class="glyphicon glyphicon-backward"></span> {!! trans('admin/admin.back')!!}
                    </button>
                </div>
            </div>
        </h3>
        <h4>
            <div id="bcTrail">&nbsp;</div>
        </h4>
    </div>

    @include('partials.action-diagram')
    @include('partials.context-menu')
    @include('partials.forms')
    @include('partials.messages')
    @include('partials.action-diagram-js')

@endsection
{{-- Scripts --}}
@section('scripts')

@endsection
