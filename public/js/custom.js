
// Select with search
if ($('.select-search').length) {
    $('.select-search').select2();
}


// Initialize
if ($('.touchspin').length) {
    $('.touchspin').TouchSpin({
        min: 0,
        max: 1000000000,
    });

// Trigger value change when +/- buttons are clicked
    $('.touchspin').on('touchspin.on.startspin', function () {
        $(this).trigger('blur');
    });
}
if ($('.tokenfield').length) {
    // Basic initialization
    $('.tokenfield').tokenfield();
}
if ($('.pickadate').length) {
    // Basic options
    $('.pickadate').pickadate({
        format: 'dd/mm/yyyy',
        formatSubmit: 'yyyy-mm-dd'
    });
}
if ($('.ckeditor').length) {
    if (typeof (CKEDITOR) !== 'undefined' && $('#content').length)
        CKEDITOR.replace('content');
    if (typeof (CKEDITOR) !== 'undefined' && $('#description').length)
        CKEDITOR.replace('description');
}
// Default initialization
if ($('.form-check-input-styled').length) {
    $('.form-check-input-styled').uniform();
// Initialize
    var validator = $('.form-validate-jquery').validate({
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        errorClass: 'validation-invalid-label',
        successClass: 'validation-valid-label',
        validClass: 'validation-valid-label',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        success: function (label) {
            label.addClass('validation-valid-label').text('Success.'); // remove to hide Success message
        },

        // Different components require proper error label placement
        errorPlacement: function (error, element) {

            // Unstyled checkboxes, radios
            if (element.parents().hasClass('form-check')) {
                error.appendTo(element.parents('.form-check').parent());
            }

            // Input with icons and Select2
            else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                error.appendTo(element.parent());
            }

            // Input group, styled file input
            else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                error.appendTo(element.parent().parent());
            }

            // Other elements
            else {
                error.insertAfter(element);
            }
        },
        rules: {
            password: {
                minlength: 5
            },
            repeat_password: {
                equalTo: '#password'
            },
            email: {
                email: true
            },
            repeat_email: {
                equalTo: '#email'
            },
            minimum_characters: {
                minlength: 10
            },
            maximum_characters: {
                maxlength: 10
            },
            minimum_number: {
                min: 10
            },
            maximum_number: {
                max: 10
            },
            number_range: {
                range: [10, 20]
            },
            url: {
                url: true
            },
            date: {
                date: true
            },
            date_iso: {
                dateISO: true
            },
            numbers: {
                number: true
            },
            digits: {
                digits: true
            },
            creditcard: {
                creditcard: true
            },
            basic_checkbox: {
                minlength: 2
            },
            styled_checkbox: {
                minlength: 2
            },
            switchery_group: {
                minlength: 2
            },
            switch_group: {
                minlength: 2
            }
        },
        messages: {
            custom: {
                required: 'This is a custom error message'
            },
            basic_checkbox: {
                minlength: 'Please select at least {0} checkboxes'
            },
            styled_checkbox: {
                minlength: 'Please select at least {0} checkboxes'
            },
            switchery_group: {
                minlength: 'Please select at least {0} switches'
            },
            switch_group: {
                minlength: 'Please select at least {0} switches'
            },
            agree: 'Please accept our policy'
        }
    });
}
