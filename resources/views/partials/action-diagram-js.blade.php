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
    var zoomList = [{tripId: '{!! $trip->id !!}', title: '{!! $trip->title !!}'}];

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

        buildBreadCrumbTrail();
    };
    const loadActionDiagram = function()
    {
        let tripDetails = zoomList[zoomList.length - 1];
        let tripId = tripDetails.tripId;

        $('#actionDiagramTitle').text(tripDetails.title);

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
                        case '{{\App\Model\ContextMenu::CM_ACTION_SELECT_QUESTION}}':
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

    // NB Attaching the context menu just to the action diagram
    $('#actionDiagram').get(0).addEventListener("contextmenu", function(e)
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

    const zoomTo = function(level)
    {
        if (level === 'back') {
            level = zoomList.length - 2;
        }
        // Go to the nominated level
        if (level < zoomList.length) {
            for (let i=(zoomList.length - 1); i > 0; i--) {
                if (level < i) {
                    zoomList.splice(-1, 1);
                }
            }

            loadActionDiagram();
        }
    };

    const buildBreadCrumbTrail = function()
    {
        let bcTrail = 'None';
        if (zoomList && zoomList.length > 0) {
            bcTrail = '<span id="bc_back" class="bc">◀</span>';
            for (let i=0; i < zoomList.length; i++) {
                bcTrail += '<span id="bc_' + i + '" class="bc">' + zoomList[i].title + '</span>/ ';
            }
        }
        $("#bcTrail").html(bcTrail);
        $(".bc").click(function (e) {
            e.preventDefault();

            if ($(e.target)) {
                let selectedId = $(e.target).attr('id').replace('bc_', '');

                zoomTo(selectedId);
            }
        });
    };

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
            $("#tripFormData").html(response.data.formHtml).css('display', 'block');

            $('#modalForm').modal('show');

            // If snippets have been returned listen for events
            $(".snippet").click(function (e) {
                e.preventDefault();

                if ($(e.target).parent()) {
                    let selectedId = $(e.target).parent().attr('id').replace('snippetTrip_', '');
                    selectSnippet(selectedId);
                }
            });

            // If questions have been returned listen for events
            $(".question").click(function (e) {
                e.preventDefault();

                if ($(e.target).parent()) {
                    let selectedId = $(e.target).parent().attr('id').replace('question_', '');
                    selectQuestion(selectedId);
                }
            });
        } else {
            displayMessages(response.success, response.data.messages);
        }
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
        $("#tripFormData").css('display', 'none');
    }

    // Execute the form submission and using a callback to receive the result
    const submitFormCallback = function(response)
    {
        if (response && response.success === true) {

            $("#tripFormData").html(response.formHtml);

            loadActionDiagram();
        }
        displayMessages(response.success, response.data.messages);

        setTimeout(function () {
            closeForm();
        }, 100);
    };
    function submitForm()
    {
        let formData = $("#tripFormData").serializeArray();
        let action = $("#action").val();

        $('#modalForm').modal('hide');

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

            if (response.data.action == 'zoom') {
                // Zoom to next level
                zoomList[zoomList.length] = {tripId: response.data.tripId, title: response.data.title};
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


    // Execute the selection of another instance using a callback to receive the result
    const selectInstanceCallback = function(response)
    {
        if (response && response.success === true) {

            $("#tripFormData").html(response.formHtml);

            loadActionDiagram();
        }

        displayMessages(response.success, response.data.messages);

        $('#modalForm').modal('hide');

        setTimeout(function () {
            closeForm();
        }, 100);
    };
    function selectQuestion(questionId)
    {
        let formData = $("#tripFormData").serializeArray();
        formData[formData.length] = {name: 'questionId', value: questionId};

        let action = $("#action").val();
        let url = "{{config('app.base_url')}}admin/api/selected-question/";

        ajaxCall(url, JSON.stringify(formData), selectInstanceCallback);
    }
    function selectSnippet(snippetId)
    {
        let formData = $("#tripFormData").serializeArray();
        formData[formData.length] = {name: 'snippetTrip_id', value: snippetId};

        let action = $("#action").val();
        let url = "{{config('app.base_url')}}admin/api/selected-snippet/";

        ajaxCall(url, JSON.stringify(formData), selectInstanceCallback);
    }

</script>
