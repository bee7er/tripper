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

        $(function ()
        {
            $('.go_back').click(function () {
                document.location = "{{config('app.base_url')}}admin/trip";
            });
        });

        // The selected action diagram instance entry
        var targetInstance = null;
        // A list of action diagrams we have zoomed into
        var zoomlist = ['{!! $trip->id !!}'];

        // Execute the context menu construction and using a callback to receive the result
        const actionDiagramCallback = function(response)
        {

            let diagram = $("#actionDiagram");

            if (response && response.success === true) {
                if (response.success === true) {
                    diagram.html(response.formHtml);

                    $(".row-selected").click(function rowSelected(e) {
                        e.preventDefault();
                        setTarget(e);
                    });

                    $(".row-selected").dblclick(function rowSelected(e) {
                        e.preventDefault();

                        // Go ahead and edit
                        setTarget(e);
                        openForm('{{\App\Model\ContextMenu::CM_ACTION_EDIT}}');
                    });

                    function setTarget(e) {
                        clearTarget();
                        closeForm();
                        checkEventForTarget(e);
                    }

                    $(".in-complete").fadeOut(20).fadeIn(20).delay(50).fadeOut(20).fadeIn(20);
                }
            } else {
                displayMessages(response.success, response.data.messages);
            }
        };
        const loadActionDiagram = function()
        {
            let tripId = zoomlist[zoomlist.length - 1];
            let url = "{{config('app.base_url')}}admin/api/get-action-diagram/";
            ajaxCall(url, JSON.stringify({'tripId': tripId}), actionDiagramCallback);
        };
        // *****************
        loadActionDiagram();
        // *****************
        // Now that the diagram has been constructed we set up the event handlers
        // Execute the context menu construction and using a callback to receive the result
        const contextMenuCallback = function (response, params)
        {
            let command = params[0];
            let event = params[1];
            let menu = $("#menu");
            if (response && response.success === true) {

                menu.html(response.data.formHtml);
                if (response.data.messages) {
                    displayMessages(response.success, response.data.messages);
                }

                $(".menu-option").click(function (e) {
                    e.preventDefault();

                    if (null === targetInstance) {
                        alert('Please select an action diagram entry');
                    } else {
                        // Which option was selected?
                        let action = $(e.target).attr('id');
                        switch (action) {
                            case '{{\App\Model\ContextMenu::CM_ACTION_ZOOM}}':
                            case '{{\App\Model\ContextMenu::CM_ACTION_COLLAPSE}}':
                                sendAction(action);
                                break;
                            case '{{\App\Model\ContextMenu::CM_ACTION_DELETE}}':
                            case '{{\App\Model\ContextMenu::CM_ACTION_EDIT}}':
                            case '{{\App\Model\ContextMenu::CM_ACTION_INSERT_ACTION}}':
                            case '{{\App\Model\ContextMenu::CM_ACTION_INSERT_COMMENT}}':
                            case '{{\App\Model\ContextMenu::CM_ACTION_INSERT_CONDITION}}':
                            case '{{\App\Model\ContextMenu::CM_ACTION_INSERT_ITERATION}}':
                            case '{{\App\Model\ContextMenu::CM_ACTION_INSERT_SEQUENCE}}':
                            case '{{\App\Model\ContextMenu::CM_ACTION_SELECT_SNIPPET}}':
                                openForm(action);
                                break;
                            default:
                                alert('Not yet supported');
                        }
                    }
                });

                let newClass = (command === "show" ? "menu-show" : "menu-hide");
                // Clear current classes and then set the new one
                menu.removeClass("menu-show menu-hide");
                menu.addClass(newClass);
                if (event) {
                    menu.offset({left: event.pageX, top: event.pageY});
                }
            } else {
                displayMessages(response.success, response.data.messages);
            }
        };
        const toggleMenu = function(command, event)
        {
            if ('hide' === command) {
                let menu = $("#menu");
                // Clear current classes and then set the new one
                menu.removeClass("menu-show menu-hide");
                menu.addClass("menu-hide");
            } else if (targetInstance) {
                let instanceId = targetInstance.attr('id');
                let url = "{{config('app.base_url')}}admin/api/get-instance-context-menu/";
                ajaxCall(url, JSON.stringify({'instanceId': instanceId}), contextMenuCallback, [command, event]);
            }
        };

        window.addEventListener("contextmenu", function(e)
        {
            e.preventDefault();

            checkEventForTarget(e);

            $("#menu").offset({left: e.pageX, top: e.pageY});
            toggleMenu('show', e);
        });

        window.addEventListener("click", function(e)
        {
            e.preventDefault();

            if ($("#menu").hasClass('menu-show')) toggleMenu("hide");
        });

        window.addEventListener("keyup", function(e)
        {
            e.preventDefault();

            if (e.which == 27) {
                if ($("#menu").hasClass('menu-show')) toggleMenu("hide");
                else if (targetInstance) clearTarget();

                closeForm();
            }
        });

        // Checks the event target to see if it is a usable entry
        function checkEventForTarget(e)
        {
            let target = $(e.target).parent();
            // Only accept the target if it is one of our entries, i.e. it has an id
            if (target && target.attr('id')) {
                clearTarget();
                targetInstance = target;
                targetInstance.addClass('instance-selected');
            }
        }

        function clearTarget()
        {
            if (null !== targetInstance) {
                targetInstance.removeClass('instance-selected');
                targetInstance = null;
                toggleMenu("hide");
            }
        }

        // Execute the form construction and loading using a callback to receive the result
        const openFormCallback = function(response)
        {
            if (response && response.success === true) {
                $("#instanceFormData").html(response.data.formHtml);
                $("#instanceForm").css('display', 'block');

                // If snippets have been returned listen for events
                $(".snippet").click(function (e) {
                    e.preventDefault();

                    if ($(e.target).parent()) {
                        let selectedId = $(e.target).parent().attr('id').replace('snippet_', '');
                        selectSnippet(selectedId);
                    }
                });
            } else {
                displayMessages(response.success, response.data.messages);
            }

            $(".focus").focus();
        };
        function openForm(action)
        {
            if (targetInstance) {
                let targetInstanceId = targetInstance.attr('id'),
                        instanceIdParts = targetInstanceId.split('_'),
                        instanceId = instanceIdParts[0],
                        insertAction = instanceIdParts[1];

                let url = "{{config('app.base_url')}}admin/api/get-instance-form/";
                ajaxCall(url, JSON.stringify(
                        {'instanceId': instanceId, 'action': action, 'insertAction': insertAction}), openFormCallback
                );
            }
        }

        function closeForm() {
            $("#instanceForm").css('display', 'none');
        }

        // Execute the form submission and using a callback to receive the result
        const submitFormCallback = function(response)
        {
            if (response && response.success === true) {

                $("#instanceFormData").html(response.formHtml);

                loadActionDiagram();
            }
            displayMessages(response.success, response.data.messages);

            setTimeout(function () {
                closeForm();
            }, 100);
        };
        function submitForm()
        {
            let formData = $("#instanceFormData").serializeArray();
            let action = $("#action").val();

            // Defaulting to edit / insert
            let url = "{{config('app.base_url')}}admin/api/save-instance/";
            if (action === '{{ \App\Model\ContextMenu::CM_ACTION_DELETE }}') {
                url = "{{config('app.base_url')}}admin/api/delete-instance/";
            }

            ajaxCall(url, JSON.stringify(formData), submitFormCallback);
        }

        const sendActionCallback = function(response)
        {
            if (response && response.success === true) {

                console.log(response);
                if (response.data.action == 'zoom') {
                    // Zoom to next level
                    zoomlist[zoomlist.length] = response.data.tripId;
                }

                loadActionDiagram();
            }
            displayMessages(response.success, response.data.messages);
        };
        function sendAction(action)
        {
            let targetInstanceId = targetInstance.attr('id'),
                    instanceIdParts = targetInstanceId.split('_'),
                    instanceId = instanceIdParts[0],
                    insertAction = instanceIdParts[1];

            let url = "{{config('app.base_url')}}admin/api/send-action/";
            ajaxCall(url,
                    JSON.stringify({'instanceId': instanceId, 'action': action, 'insertAction': insertAction}),
                    sendActionCallback
            );
        }


        // Execute the selection of a snippet using a callback to receive the result
        const selectSnippetCallback = function(response)
        {
            if (response && response.success === true) {

                $("#instanceFormData").html(response.formHtml);

                loadActionDiagram();

                displayMessages(response.success, response.data.messages);
            } else {
                displayMessages(response.success, response.data.messages);
            }

            setTimeout(function () {
                closeForm();
            }, 100);
        };
        function selectSnippet(snippetId)
        {
            let formData = $("#instanceFormData").serializeArray();
            formData[formData.length] = {name: 'snippetId', value: snippetId};

            console.log(formData);

            let action = $("#action").val();
            let url = "{{config('app.base_url')}}admin/api/selected-snippet/";

            ajaxCall(url, JSON.stringify(formData), selectSnippetCallback);
        }

        function displayMessages(success, messages)
        {
            if (messages) {
                let messageStatus = success ? "#info-messages" : "#error-messages";
                for (let i=0; i<messages.length; i++) {
                    if (messages[i]) {
                        $(messageStatus).text(messages[i]).fadeIn(800).delay(3000).fadeOut(800);
                    }
                }
            }
        }

    </script>
@endsection
