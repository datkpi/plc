$('#position').change(function () {
    var positionId = $(this).val();
    $.ajax({
        url: '/api/recruitment/request-form/get-annual/' + positionId,
        method: 'GET',
        success: function (resp) {
            $('#annual-employee').empty();
            $.each(resp.data, function (key, value) {
                // $('#check-annual').val(value.employee_number);
                $('#annual-employee').append('<option value="' + value.id + '">Tháng ' +
                    value
                    .month + "/" + value.year + " (" + value
                    .employee_number + ")" +
                    '</option>');
            });
            checkStaffQuantity()
        }
    });
});

$('#staff-quantity,#annual-employee').change(function () {
    checkStaffQuantity();
});

function checkStaffQuantity() {
    if ($('#staff-quantity').val() > $('#annual-employee').val()) {
        $('#staff-quantity-check').show()
        return;
    }
    $('#staff-quantity-check').hide()
}
