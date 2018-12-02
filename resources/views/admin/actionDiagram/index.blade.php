@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/actionDiagram.trip") !!}
:: @parent @endsection

{{-- Content --}}
@section('main')
    <style>
        .menu {
            width: 140px;
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
            padding: 5px 20px 5px 10px;
            cursor: pointer;
        }

        .menu .menu-options .menu-option:hover {
            background: rgba(0, 128, 128, 0.25);
        }
    </style>
    <div class="page-header">
        <h3>
            {!! trans("admin/actionDiagram.title") !!}: {!! $tripTitle !!} / Action: <span id="actionId">-</span>
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


    @include('partials.context-menu')
    @include('partials.forms')

@endsection
{{-- Scripts --}}
@section('scripts')
    <style>
        body {font-family: Arial, Helvetica, sans-serif;}
        * {box-sizing: border-box;}

        /* Button used to open the contact form - fixed at the bottom of the page */
        .open-button {
            background-color: #555;
            color: white;
            padding: 16px 20px;
            border: none;
            cursor: pointer;
            opacity: 0.8;
            position: fixed;
            bottom: 23px;
            right: 28px;
            width: 280px;
        }

        /* The popup form - hidden by default */
        .form-popup {
            display: none;
            position: fixed;
            bottom: 0;
            right: 15px;
            border: 3px solid #f1f1f1;
            z-index: 9;
        }

        /* Add styles to the form container */
        .form-container {
            max-width: 300px;
            padding: 10px;
            background-color: white;
        }

        /* Full-width input fields */
        .form-container input[type=text], .form-container input[type=password] {
            width: 100%;
            padding: 15px;
            margin: 5px 0 22px 0;
            border: none;
            background: #f1f1f1;
        }

        /* When the inputs get focus, do something */
        .form-container input[type=text]:focus, .form-container input[type=password]:focus {
            background-color: #ddd;
            outline: none;
        }

        /* Set a style for the submit/login button */
        .form-container .btn {
            background-color: #4CAF50;
            color: white;
            padding: 16px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-bottom:10px;
            opacity: 0.8;
        }

        /* Add a red background color to the cancel button */
        .form-container .cancel {
            background-color: red;
        }

        /* Add some hover effects to buttons */
        .form-container .btn:hover, .open-button:hover {
            opacity: 1;
        }
    </style>

    <script type="text/javascript">

        const color = "px solid #c40000";

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

            let instanceId = target.closest('div').attr('id');
            $.ajax({
                type: 'post',
                url: "{{config('app.base_url')}}admin/api/get-instance-context-menu/",
                data: JSON.stringify({instanceId}),
                contentType: 'application/json',
                cache: false,
                processData: false
            }).success(function (response) {
                if (response.success === true) {
                    $("#menu").html(response.formHtml);
                }


                $(".menu-option").click(function (e) {
                    e.preventDefault();

                    if (null !== target) {
                        openForm();
                    }
                });

            }).fail(function (jqXHR, textStatus, errorThrown) {
                // Optionally alert the user of an error here...
                let textResponse = jqXHR.responseText;
                let alertText = "One of the following conditions is not met:\n\n";
                let jsonResponse = jQuery.parseJSON(textResponse);

                $.each(jsonResponse, function (n, elem) {
                    alertText = alertText + elem + "\n";
                });

                alert(alertText);

                return false;
            });

            menu.css('display', command === "show" ? "block" : "none");
            menuVisible = (command === "show");
        };

        const setPosition = function({ top, left }) {
            let menu = $("#menu");

            menu.css("top", top + 'px');
            menu.css("left", left + 'px');
            toggleMenu('show');
        };

        window.addEventListener("click", function(e) {
            if (menuVisible) toggleMenu("hide");
        });

        window.addEventListener("keyup", function(e) {
            if (e.which == 27) {
                if (menuVisible) toggleMenu("hide");
                else if (target) clearTarget();
            }
        });

        window.addEventListener("contextmenu", function(e) {
            e.preventDefault();

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
            target.css('border','1' + color);

            let actionId = target.closest('div').attr('id');
            // Show which entry will be processed
            $("#actionId").text(actionId);
        }

        function clearTarget() {
            if (null !== target) {
                target.css('border', '0' + color);
                target = null;

                $("#actionId").text('-');
            }
        }
        function openForm() {

            let instanceId = target.closest('div').attr('id');
            $.ajax({
                type: 'post',
                url: "{{config('app.base_url')}}admin/api/get-instance-form/",
                data: JSON.stringify({instanceId}),
                contentType: 'application/json',
                cache: false,
                processData: false
            }).success(function (response) {
                if (response.success === true) {
                    $("#instanceFormData").html(response.formHtml);
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                // Optionally alert the user of an error here...
                let textResponse = jqXHR.responseText;
                let alertText = "One of the following conditions is not met:\n\n";
                let jsonResponse = jQuery.parseJSON(textResponse);

                $.each(jsonResponse, function (n, elem) {
                    alertText = alertText + elem + "\n";
                });

                alert(alertText);

                return false;
            });

            $("#instanceForm").css('display', 'block');
        }

        function closeForm() {
            $("#instanceForm").css('display', 'none');
        }

        function submitForm() {
            let formData = $("#instanceForm").serializeArray(),
                    actionId = target.closest('div').attr('id');

             $.ajax({
                type: 'post',
                url: "{{config('app.base_url')}}admin/api/update-instance/",
                data: JSON.stringify(formData),
                contentType: 'application/json',
                cache: false,
                processData: false
            }).success(function () {
                setTimeout(function () {
                    closeForm();
                }, 300);

            }).fail(function (jqXHR, textStatus, errorThrown) {
                // Optionally alert the user of an error here...
                let textResponse = jqXHR.responseText;
                let alertText = "One of the following conditions is not met:\n\n";
                let jsonResponse = jQuery.parseJSON(textResponse);

                $.each(jsonResponse, function (n, elem) {
                    alertText = alertText + elem + "\n";
                });

                alert(alertText);
            });
        }
    </script>
@endsection
