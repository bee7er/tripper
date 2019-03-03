<script type="text/javascript">

    $('#addConstant').click(function (e) {
        e.preventDefault();

        let target = $(e.target);
        if (target) {
            let action = target.attr('id');
            let clistId = $('#clistId').val();

            openForm(action, clistId);
        }
    });

    $('.row-action').click(function (e) {
        e.preventDefault();

        let target = $(e.target);
        if (target) {
            let idParts = target.attr('id').split('_');
            let id = idParts[1];
            alert('row action ' + idParts[0] + ' for id: ' + id);
        }
    });

    // Execute the form construction and loading using a callback to receive the result
    const openFormCallback = function(response)
    {
        if (response && response.success === true) {
            $("#tripFormData").html(response.data.formHtml).css('display', 'block');

            $('#modalForm').modal('show');
        } else {
            displayMessages(response.success, response.data.messages);
        }

        $(".focus").focus();
    };
    function openForm(action, clistId)
    {
        let url = "{{config('app.base_url')}}admin/api/get-constant-form/";
        ajaxCall(url, JSON.stringify(
            {'clistId': clistId, 'action': action}), openFormCallback
        );
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

        let url = "{{config('app.base_url')}}admin/api/save-clist/";
        if (action === '{{ \App\Model\ContextMenu::CM_ACTION_DELETE }}') {
            url = "{{config('app.base_url')}}admin/api/delete-clist/";
        }

        ajaxCall(url, JSON.stringify(formData), submitFormCallback);
    }

</script>
