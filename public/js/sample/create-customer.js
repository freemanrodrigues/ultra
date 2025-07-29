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
    $('#customer_name').on('blur', function () {
       const cus_name =  $(this).val().trim();
        $('#division').val( cus_name);
    });
    $('#site').on('change', function () {
        const cus_name =  $('#customer_name').val().trim();
       // var site_name = $('select[name="site"]').val();
       var site_name = $("#site option:selected").text();
        $('#division').val( cus_name+' '+site_name);
    });
    $('#gst_no').on('blur', function () {
        const gstNo = $(this).val().trim();
        //alert("Works 1");

        const gstinRegex = /^\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}Z[A-Z\d]{1}$/;
        
        //alert("Works");
        if (gstinRegex.test(gstNo)) {
           
          // getState(gstNo.substring(0, 2));
            //alert('Yes');
        
       
            $.ajax({
                url: "/ajax/check-gst",
                method: 'POST',
                data: {
                    'gst_no': gstNo,
                },
                success: function (response) {
                //    alert("Response"+response.exists);
                    if (response.exists) {
                        console.log("THe Pan number Exists");
                     //   $('#gst_result').text('GST matched with company: ' + response.company_name);
                     $('#customer_name').val( response.company_name);
                   //  alert(response.company_id);
                     $('#company_id').val( response.company_id);
                   //  $('#company_id').attr('readonly', true);
                    } else {
                       // alert("GST Not Exus"+response.exists);
                       //$('#gst_result').text('No company found for this GST number');
                       //console.log(" Not Exists");
                    //    $('#company_id').prop('required', true);
                        
                    }
                },
                error: function () {
                    
                    //$('#gst_error').text('Error validating GST number').css('color', 'red');;
                }
            });
        } else {
        //    alert('Else');
            $('#gst_error').text('Please enter a valid 15-digit GST number.').css('color', 'red');;
        }
        
    });
    
    $('#b2c_customer').on('change', function() {
     //   alert("On Checked");
        const isChecked = $(this).prop('checked');
        //alert("On Checked"+isChecked);
         if(isChecked) {
             //  $("#customer_name_div").prop('disabled',true);
               $(".customer_name_div").hide();
         } else {
            // $("#customer_name_div").prop('disabled',false);
             $(".customer_name_div").show();
         }
        
     });
});