@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/actionDiagram.trip") !!}
:: @parent @endsection

{{-- Content --}}
@section('main')

    <div class="page-header">
        <h3>
            {!! trans("admin/actionDiagram.title") !!}: {!! $trip->title !!}
            <div class="pull-right">
                <div class="pull-right">
                    <button class="btn btn-primary btn-xs go_back">
                        <span class="glyphicon glyphicon-backward"></span> {!! trans('admin/admin.back')!!}
                    </button>
                </div>
            </div>
        </h3>
    </div>

    @include('partials.action-diagram')
    @include('partials.context-menu')
    @include('partials.forms')
    @include('partials.messages')

@endsection
{{-- Scripts --}}
@section('scripts')

    <script type="text/javascript">

        $(function () {
            $('.go_back').click(function () {
                document.location = "{{config('app.base_url')}}admin/trip";
            });
        });

        // The selected action diagram instance entry
        var targetInstance = null;

        // Execute the context menu construction and using a callback to receive the result
        const actionDiagramCallback = function(response, command) {

            let diagram = $("#actionDiagram");
            if (response && response.success === true) {
                if (response.success === true) {
                    diagram.html(response.formHtml);

                    // Now that the diagram has been constructed we set up the event handlers
                    // Execute the context menu construction and using a callback to receive the result
                    const contextMenuCallback = function(response, command) {

                        let menu = $("#menu");
                        if (response && response.success === true) {
                            if (response.success === true) {
                                menu.html(response.formHtml);
                            }

                            $(".menu-option").click(function (e) {
                                e.preventDefault();

                                if (null === targetInstance) {
                                    alert('Please select an action diagram entry');
                                } else {
                                    // Which option was selected?
                                    let action = $(e.target).attr('id');
                                    switch (action) {
                                        case '{{\App\Model\ContextMenu::CM_ACTION_COLLAPSE}}':
                                            sendAction(action);
                                            break;
                                        case '{{\App\Model\ContextMenu::CM_ACTION_DELETE}}':
                                        case '{{\App\Model\ContextMenu::CM_ACTION_EDIT}}':
                                            openForm(action);
                                            break;
                                    }
                                }
                            });

                            let newClass = (command === "show" ? "menu-show" : "menu-hide");
                            // Clear current classes and then set the new one
                            menu.removeClass("menu-show menu-hide");
                            menu.addClass(newClass);
                        }
                    };
                    const toggleMenu = function(command) {
                        let instanceId = targetInstance.attr('id');
                        let url = "{{config('app.base_url')}}admin/api/get-instance-context-menu/";
                        ajaxCall(url, JSON.stringify({'instanceId': instanceId}), contextMenuCallback, command);
                    };

                    const setMenuPosition = function({ top, left }) {
                        let menu = $("#menu");
                        menu.css("top", top + 'px');
                        menu.css("left", left + 'px');
                        toggleMenu('show');
                    };

                    window.addEventListener("click", function(e) {
                        let menu = $("#menu");
                        if (menu.hasClass('menu-show')) toggleMenu("hide");
                    });

                    window.addEventListener("keyup", function(e) {
                        if (e.which == 27) {
                            let menu = $("#menu");
                            if (menu.hasClass('menu-show')) toggleMenu("hide");
                            else if (targetInstance) clearTarget();

                            closeForm();
                        }
                    });

                    window.addEventListener("contextmenu", function(e) {
                        e.preventDefault();

                        checkEventForTarget(e);

                        if (null !== targetInstance) {
                            let position = $(e.target).position();
                            let top = e.pageY - position.top - 300;
                            let left = e.pageX - position.left + 40;

                            const origin = {
                                top: top,
                                left: left
                            };

                            setMenuPosition(origin);
                        }
                        return false;
                    });

                    $(".row-selected").click(function rowSelected(e) {
                        e.preventDefault();

                        setTarget(e);
                    });

                    function setTarget(e) {
                        clearTarget();
                        closeForm();
                        checkEventForTarget(e);
                    }
                }
            } else {
                // DIsplay error
                alert(response.data.message);
            }
        };
        const loadActionDiagram = function(command) {
            let tripId = '{!! $trip->id !!}';
            let url = "{{config('app.base_url')}}admin/api/get-action-diagram/";
            ajaxCall(url, JSON.stringify({'tripId': tripId}), actionDiagramCallback);
        };
        // *****************
        loadActionDiagram();
        // *****************

        // Checks the event target to see if it is a usable entry
        function checkEventForTarget(e) {
            let target = $(e.target).parent();
            // Only accept the target if it is one of our entries, i.e. it has an id
            if (target && target.attr('id')) {
                clearTarget();
                targetInstance = target;
                targetInstance.addClass('instance-selected');
            }
        }

        function clearTarget() {
            if (null !== targetInstance) {
                targetInstance.removeClass('instance-selected');
                targetInstance = null;
            }
        }

        // Execute the form construction and loading using a callback to receive the result
        const openFormCallback = function(response) {
            if (response && response.success === true) {
                $("#instanceFormData").html(response.formHtml);
                $("#instanceForm").css('display', 'block');
            } else {
                // DIsplay error
                alert(response.data.message);
            }
        };
        function openForm(action) {
            let targetInstanceId = targetInstance.attr('id'),
                    instanceIdParts = targetInstanceId.split('_'),
                    instanceId = instanceIdParts[0],
                    insertAction = instanceIdParts[1];

            let url = "{{config('app.base_url')}}admin/api/get-instance-form/";
            ajaxCall(url, JSON.stringify({'instanceId': instanceId, 'action': action, 'insertAction': insertAction}), openFormCallback);
        }

        function closeForm() {
            $("#instanceForm").css('display', 'none');
        }

        // Execute the form submission and using a callback to receive the result
        const submitFormCallback = function(response) {
            if (response && response.success === true) {

                $("#instanceFormData").html(response.formHtml);

                setTimeout(function () {
                    closeForm();
                }, 100);

                loadActionDiagram();

                $("#messages").text(response.data.message).fadeIn(800).delay(3000).fadeOut(800);
            } else {
                // DIsplay error
                alert(response.data.message);
            }
        };
        function submitForm() {
            let formData = $("#instanceFormData").serializeArray();
            let action = $("#action").val();

            // Defaulting to edit / insert
            let url = "{{config('app.base_url')}}admin/api/save-instance/";
            if (action === '{{ \App\Model\ContextMenu::CM_ACTION_DELETE }}') {
                url = "{{config('app.base_url')}}admin/api/delete-instance/";
            }

            ajaxCall(url, JSON.stringify(formData), submitFormCallback);
        }

        const sendActionCallback = function(response) {
            if (response && response.success === true) {

                loadActionDiagram();
            } else {
                // DIsplay error
                alert(response.data.message);
            }
        };
        function sendAction(action) {

            let targetInstanceId = targetInstance.attr('id'),
                    instanceIdParts = targetInstanceId.split('_'),
                    instanceId = instanceIdParts[0],
                    insertAction = instanceIdParts[1];

            let url = "{{config('app.base_url')}}admin/api/send-action/";
            ajaxCall(url, JSON.stringify({'instanceId': instanceId, 'action': action, 'insertAction': insertAction}), sendActionCallback);
        }

    </script>
@endsection
