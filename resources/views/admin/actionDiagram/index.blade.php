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

    <script type="text/javascript">

        $(function () {
            $('.go_back').click(function () {
                document.location = "{{config('app.base_url')}}admin/trip";
            });
        });

        let menuVisible = false;
        let target = null;
        let toggleCommand = null;

        const contextMenuCallback = function(response) {
            let menu = $("#menu");
            if (response && response.success === true) {
                if (response.success === true) {
                    menu.html(response.formHtml);
                }

                $(".menu-option").click(function (e) {
                    e.preventDefault();

                    if (null !== target) {
                        openForm();
                    }
                });

                menu.css('display', toggleCommand === "show" ? "block" : "none");
                menuVisible = (toggleCommand === "show");
            }
        };
        const toggleMenu = function(command) {
            let instanceId = target.closest('div').attr('id');
            let url = "{{config('app.base_url')}}admin/api/get-instance-context-menu/";
            // Must use global for callback function
            toggleCommand = command;
            ajaxCall(url, JSON.stringify({instanceId}), contextMenuCallback);
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
            target.parent().addClass('instance-selected');
        }

        function clearTarget() {
            if (null !== target) {
                target.parent().removeClass('instance-selected');
                target = null;
            }
        }

        const openFormCallback = function(response) {
            if (response && response.success === true) {
                $("#instanceFormData").html(response.formHtml);
                $("#instanceForm").css('display', 'block');
            }
        };
        function openForm() {
            let instanceId = target.closest('div').attr('id');
            let url = "{{config('app.base_url')}}admin/api/get-instance-form/";
            ajaxCall(url, JSON.stringify({instanceId}), openFormCallback);
        }

        function closeForm() {
            $("#instanceForm").css('display', 'none');
        }

        const submitFormCallback = function(response) {
            if (response && response.success === true) {
                $("#instanceFormData").html(response.formHtml);
                setTimeout(function () {
                    closeForm();
                }, 100);
            }
        };
        function submitForm() {
            let formData = $("#instanceForm").serializeArray();
            let instanceId = target.closest('div').attr('id');
            let url = "{{config('app.base_url')}}admin/api/update-instance/";
            ajaxCall(url, JSON.stringify(formData), submitFormCallback);
        }
    </script>
@endsection
