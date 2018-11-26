@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/actionDiagram.trip") !!}
:: @parent @endsection

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/actionDiagram.title") !!}: {!! $tripTitle !!}
            <div class="pull-right">
                <div class="pull-right">
                    <button class="btn btn-primary btn-xs go_back">
                        <span class="glyphicon glyphicon-backward"></span> {!! trans('admin/admin.back')!!}
                    </button>
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

    <script type="text/javascript">
        $(function () {
//        $('textarea').summernote({height: 250});
//        $('form').submit(function (event) {
//            event.preventDefault();
//            var form = $(this);
//
//            if (form.attr('id') == '' || form.attr('id') != 'fupload') {
//                $.ajax({
//                    type: form.attr('method'),
//                    url: form.attr('action'),
//                    data: form.serialize()
//                }).success(function () {
//                    setTimeout(function () {
//                        parent.$.colorbox.close();
//                    }, 10);
//                }).fail(function (jqXHR, textStatus, errorThrown) {
//                    // Optionally alert the user of an error here...
//                    var textResponse = jqXHR.responseText;
//                    var alertText = "One of the following conditions is not met:\n\n";
//                    var jsonResponse = jQuery.parseJSON(textResponse);
//
//                    $.each(jsonResponse, function (n, elem) {
//                        alertText = alertText + elem + "\n";
//                    });
//                    alert(alertText);
//                });
//            }
//            else {
//                var formData = new FormData(this);
//                $.ajax({
//                    type: form.attr('method'),
//                    url: form.attr('action'),
//                    data: formData,
//                    mimeType: "multipart/form-data",
//                    contentType: false,
//                    cache: false,
//                    processData: false
//                }).success(function () {
//                    setTimeout(function () {
//                        parent.$.colorbox.close();
//                    }, 10);
//
//                }).fail(function (jqXHR, textStatus, errorThrown) {
//                    // Optionally alert the user of an error here...
//                    var textResponse = jqXHR.responseText;
//                    var alertText = "One of the following conditions is not met:\n\n";
//                    var jsonResponse = jQuery.parseJSON(textResponse);
//
//                    $.each(jsonResponse, function (n, elem) {
//                        alertText = alertText + elem + "\n";
//                    });
//
//                    alert(alertText);
//                });
//            }
//            ;
//        });

            $('.go_back').click(function () {
                document.location = "{{config('app.base_url')}}admin/trip";
            });
        });
    </script>
@endsection
