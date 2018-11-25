@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/actionDiagram.trip") !!}
:: @parent @endsection

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/actionDiagram.title") !!}
            <div class="pull-right">
                <div class="pull-right">
                    xxx
                </div>
            </div>
        </h3>
    </div>

    <div style="margin: 10px;">

        @if(count($tree)>0)
            <div class="row-container">
                <div class="row" style="text-align: left;">
                    @foreach($tree as $twig)
                        <div>{{ $twig->line }}</div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection

{{-- Scripts --}}
@section('scripts')
@endsection
