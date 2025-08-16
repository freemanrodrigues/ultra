jQuery(function ($) {
    // Set CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const $form = $('#customerForm');
    const $gstNo = $('#gst_no');
    const $pincode = $('#pincode');
    const $state = $('#state');
    const $customerName = $('#customer_name');
    const $b2cCheckbox = $('#b2c_customer');
    const $createCustomer = $('#create_customer');

    // Reset form confirmation
    $('#resetBtn').on('click', function () {
        if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            $form[0].reset();
        }
    });

    // HTML5 Form validation
    $form.on('submit', function (e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // GST input formatting and validation
    $gstNo.on('input', function () {
        const gstPattern = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/;
        const value = $(this).val().toUpperCase();
        $(this).val(value);

        this.setCustomValidity(gstPattern.test(value) ? '' : 'Please enter a valid GST number');
    });

    // GST on blur - Extract PAN & auto-fill company info
    $gstNo.on('blur', function () {
        const gstNoVal = $gstNo.val().trim();
        const gstRegex = /^\d{2}[A-Z]{5}\d{4}[A-Z][A-Z\d]Z[A-Z\d]$/;

        if (gstRegex.test(gstNoVal)) {
            $('#pan_no').val(gstNoVal.substring(2, 12));

            $.post('/ajax/check-gst', { gst_no: gstNoVal }, function (response) {
                if (response.exists) {
                    if (response.company_name && response.company_name.trim() !== '') {
                        $('#gst_success').text('GST number already added').css('color', 'green');
                        $('#customer_name').val(response.company_name);
                        $("#customer_name").prop("disabled", true);
                       
                    }
                    $("#customer_name").prop("disabled", false);
                    $('#company_id').val(response.company_id);
                    $('#state_code').val(response.state_code);
                    $('#state').val(response.state_id).change();
                    $('#gst_error').empty();
                   
                }
            }).fail(function () {
                $('#gst_error').text('Error validating GST number').css('color', 'red');
            });

        } else {
            $('#gst_error').text('Please enter a valid 15-digit GST number.').css('color', 'red');
        }
    });

    // Allow only digits in pincode
    $pincode.on('input', function () {
        $(this).val($(this).val().replace(/\D/g, ''));
    });

    // Auto-fill state_code when B2C is checked and state changes
    $state.on('change', function () {
        if ($b2cCheckbox.is(':checked')) {
            $.post('/ajax/get-state', { state: $state.val() }, function (response) {
                if (response.exists) {
                    $('#state_code').val(response.state_code);
                }
            });
        }
    });

    // Disable cut/copy/paste on customer name
    $customerName.on('cut copy paste', function (e) {
        e.preventDefault();
        alert('Copying and pasting is disabled in this field.');
    });

    // Optional trimming on blur (for future use)
    $customerName.on('blur', function () {
        $(this).val($(this).val().trim());
    });

    // Optional logic on site change (disabled currently)
    $('#site').on('change', function () {
        // Placeholder for future use if needed
    });

    // B2C checkbox toggle logic
    $b2cCheckbox.on('change', function () {
        const isChecked = $(this).is(':checked');
        var tokenValue = $('input[name="_token"]').val();
        // Reset all inputs except the B2C checkbox itself
        $createCustomer.find(':input').not(this).val('');
        $createCustomer.find('input:radio, input:checkbox').not(this).prop('checked', false);
        $createCustomer.find('select').not(this).prop('selectedIndex', 0);
        $('#gst_error').empty();
        $('#gst_success').empty();
        $('input[name="_token"]').val(tokenValue);
      //  $('.customer_name_div').toggle(!isChecked);
      if ($(this).is(':checked')) {
        // Enable inputs
        $('#gst_no, #pan_no').prop('disabled', true);
    } else {
        // Disable inputs
        $('#gst_no, #pan_no').prop('disabled', false);
    }
    });
});
