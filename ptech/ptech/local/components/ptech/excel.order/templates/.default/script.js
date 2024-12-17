$(document).ready(function () {
    $(document).on('click', 'button.excel-load-button', function(e) {
        e.preventDefault();
        var form        = document.forms.excel_form,
            formData    = new FormData(form);
        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(result) {
                $('.ptech-ecxcel-order.messages').html(result);
            }
        })
    })
})