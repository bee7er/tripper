@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/actionDiagram.trip") !!}
:: @parent @endsection

{{-- Content --}}
@section('main')
    <style>
        .menu {
            width: 120px;
            box-shadow: 0 4px 5px 3px rgba(0, 0, 0, 0.2);
            position: relative;
            display: none;
        }

        .menu .menu-options {
            list-style: none;
            padding: 10px 0;
        }

        .menu .menu-options .menu-option {
            font-weight: 500;
            font-size: 14px;
            padding: 10px 40px 10px 20px;
            cursor: pointer;
        }

        .menu .menu-options .menu-option:hover {
            background: rgba(0, 0, 0, 0.2);
        }
    </style>
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
            <div id="dig" class="row-container" style="border: 1px solid #c4c4c4;">
                <div class="row" style="text-align: left;margin: 0;padding: 8px;">
                    @foreach($tree as $twig)
                        <div class="row-selected" id="{!! $twig->id !!}">{!! $twig->line !!}</div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <div class="menu" id="menu" style="font-weigh: bold;z-index: 9999;background-color: white;">
        <ul class="menu-options">
            <li class="menu-option">Back</li>
            <li class="menu-option">Reload</li>
            <li class="menu-option">Save</li>
            <li class="menu-option">Save As</li>
            <li class="menu-option">Inspect</li>
        </ul>
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

        let menuVisible = false;
        let target = null;

        const toggleMenu = function(command) {
            let menu = $("#menu");

            menu.css('display', command === "show" ? "block" : "none");
            menuVisible = (command === "show");
        };

        const setPosition = function({ top, left }) {

            let menu = $("#menu");

            menu.css("top", top + 'px');
            menu.css("left", left + 'px');

//            let position = $("#dig").position();
//            menu.css("left", position.left + 'px');
//            menu.css("top", position.top + 'px');

            toggleMenu('show');
        };

        window.addEventListener("click", function(e) {
            if (menuVisible) toggleMenu("hide");
        });

        window.addEventListener("contextmenu", function(e) {
            e.preventDefault();

//            console.log(target.closest('div').attr('id'));

            if (null !== target) {
                let position = $(e.target).closest('div').parent().position();
                let top = e.pageY - position.top - 300;
                let left = e.pageX - position.left + 40;

                const origin = {
                    top: top,
                    left: left
                };

                setPosition(origin);
            }
            return false;
        });

        $(".row-selected").click(function rowSelected(e) {
            e.preventDefault();

            setTarget(e);
        });

        function setTarget(e) {
            clearTarget();
            target = $(e.target);
            target.css('border','1px solid red');
            console.log(target.closest('div').attr('id'));
        }

        function clearTarget() {
            if (null !== target) {
                target.css('border', '0px solid red');
                target = null;
            }
        }

    </script>
@endsection
