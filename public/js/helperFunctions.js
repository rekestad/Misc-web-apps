
// only allow float number input
$(".inputAllowOnlyFloat").on("keypress keyup blur", function (event) {
    $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
    if ((event.which !== 46 || $(this).val().indexOf('.') !== -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
});

// Make an ajax call
function ajaxCall(type, url, parameters, successResponse) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: type,
        url: url,
        data: JSON.stringify(parameters),
        contentType: 'application/json;',
        dataType: 'json',
        success: successResponse,
        error: function (xhr, textStatus, errorThrown) {
            console.log('error');
        }
    });
}

function ajaxResponse_showErrorIfFailed(result) {
    const isDebug = true;

    if(!result.isSuccess) {
        showErrorModal(result.message);
    }

    if(isDebug) {
        console.log(result)
    }
}

function showErrorModal(message) {
    const errorModal = new bootstrap.Modal($('#errorModal')[0]);
    const errorMessage = $("#errorModalBody");

    errorMessage.html(message);
    errorModal.show();
}

function showInfoModal(res) {
    const modal = new bootstrap.Modal($('#infoModal')[0]);
    const header = $("#infoModalHeader");
    const body = $("#infoModalBody");

    header.html(res.modalHeader);
    body.html(res.modalBody);
    modal.show();
}

function selectRandomOptionInSelectInput($selectInputId, $noOfOptions) {
    const randomNumber = Math.floor(Math.random() * $noOfOptions);
    $('#' + $selectInputId).prop('selectedIndex', randomNumber).trigger('change');
}

