$(document).ready(function () {
    var $ = jQuery.noConflict();
    // Auto-fill display name based on customer name
    // $('#customer_name').on('input', function () {
    //     const displayNameField = $('#display_name');
    //     if (!displayNameField.val()) {
    //         displayNameField.val($(this).val());
    //     }
    // });

    // Reset form
    $('#resetBtn').on('click', function () {
        if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            $('#customerForm')[0].reset();
        }
    });

    // Preview functionality
    $('#previewBtn').on('click', function () {
        const form = $('#customerForm')[0];
        const formData = new FormData(form);
        let previewHTML = '<div class="row">';

        // Basic Information
        previewHTML += '<div class="col-md-6"><h6 class="text-primary">Basic Information</h6>';
        previewHTML += '<p><strong>Customer Name:</strong> ' + (formData.get('customer_name') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>Display Name:</strong> ' + (formData.get('display_name') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>Company:</strong> ' + ($('#company_id option:selected').text() || 'Not selected') + '</p>';
        previewHTML += '<p><strong>GST Number:</strong> ' + (formData.get('gst_no') || 'Not provided') + '</p>';
        previewHTML += '</div>';

        // Address Information
        previewHTML += '<div class="col-md-6"><h6 class="text-success">Address Information</h6>';
        previewHTML += '<p><strong>Address:</strong> ' + (formData.get('address') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>City:</strong> ' + (formData.get('city') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>State:</strong> ' + (formData.get('state') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>Country:</strong> ' + (formData.get('country') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>Pincode:</strong> ' + (formData.get('pincode') || 'Not provided') + '</p>';
        previewHTML += '</div>';

        // Business Information
        previewHTML += '<div class="col-12 mt-3"><h6 class="text-warning">Business Information</h6>';
        previewHTML += '<div class="row">';
        previewHTML += '<div class="col-md-4"><p><strong>Billing Cycle:</strong> ' + (formData.get('billing_cycle') || 'Not selected') + '</p></div>';
        previewHTML += '<div class="col-md-4"><p><strong>Credit Cycle:</strong> ' + (formData.get('credit_cycle') || 'Not provided') + ' days</p></div>';
        previewHTML += '<div class="col-md-4"><p><strong>Group:</strong> ' + (formData.get('group') || 'Not selected') + '</p></div>';
        previewHTML += '<div class="col-md-6"><p><strong>Sales Person:</strong> ' + ($('#sales_person_id option:selected').text() || 'Not selected') + '</p></div>';
        previewHTML += '<div class="col-md-6"><p><strong>Status:</strong> ' + (formData.get('status') || 'Not selected') + '</p></div>';
        previewHTML += '</div></div>';

        previewHTML += '</div>';

        $('#previewContent').html(previewHTML);
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    });

    // Form validation
    $('#customerForm').on('submit', function (e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // GST number validation
    $('#gst_no').on('input', function () {
        
        const gstPattern = /^[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}[1-9A-Za-z]{1}[Z]{1}[0-9A-Za-z]{1}$/;
        const value = $(this).val().toUpperCase();
        $(this).val(value);
        // Ajax Check

        if (value && !gstPattern.test(value)) {
            this.setCustomValidity('Please enter a valid GST number');
        } else {
            this.setCustomValidity('');
        }
    });

    // Pincode validation
    $('#pincode').on('input', function () {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#gst_no').on('blur', function () {
        const gstNo = $(this).val().trim();
        //alert("Works");
        if (gstNo.length >= 15) {
            $.ajax({
                url: "/check-gst",
                method: 'POST',
                data: {
                    'gst_no': gstNo,
                },
                success: function (response) {
                    if (response.exists) {
                        console.log("THe Pan number Exists");
                     //   $('#gst_result').text('GST matched with company: ' + response.company_name);
                     $('#company_id').val( response.company_name);
                     alert(response.company_id);
                     $('#companyid_val').val( response.company_id);
                     $('#company_id').attr('readonly', true);
                    } else {
                       //$('#gst_result').text('No company found for this GST number');
                       console.log(" Not Exists");
                        $('#company_id').prop('required', true);
                        
                    }
                },
                error: function () {
                    $('#gst_result').text('Error validating GST number');
                }
            });
        } else {
            $('#gst_result').text('Please enter a valid 15-digit GST number.');
        }
    });
    
    $('#customer_id').on('change', function () {
        alert("Customer Selected");
    });
    
});