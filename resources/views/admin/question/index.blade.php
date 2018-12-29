@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/question.question") !!}
:: @parent @endsection

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/question.question") !!}
            <div class="pull-right">
                <div class="pull-right">
                    <a href="{!! url('admin/question/create') !!}"
                       class="btn btn-sm  btn-primary iframe"><span
                                class="glyphicon glyphicon-plus-sign"></span> {{ trans("admin/modal.new") }}</a>
                </div>
            </div>
        </h3>
    </div>

    <table id="table" class="table table-squestioned table-hover">
        <thead>
        <tr>
            <th>{!! trans("admin/question.label") !!}</th>
            <th>{!! trans("admin/question.question") !!}</th>
            <th>{!! trans("admin/admin.created_at") !!}</th>
            <th>{!! trans("admin/admin.action") !!}</th>
        </tr>
        </thead>
        <tbody>
        @if(count($questions)>0)
            <div class="row">
                @foreach ($questions as $question)
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-8">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        </tbody>
    </table>
@endsection

{{-- Scripts --}}
@section('scripts')
@endsection
