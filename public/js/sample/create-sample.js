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

    $('#id_customer').blur(function() {
       // alert("Get Customer");
      //const customerid = $(this).val().trim();
      const customerid = $('#customer_id').val().trim();
      

        $.ajax({
            url: "/ajax-get-customer-address", // {{ route('ajax-get-customer-address') }}
            method: 'POST',
            data: {
                'customerid': customerid,
            },
            success: function (response) {
              //  alert(response.customer.id);
               // if (response.exists) {
                   // console.log("THe Pan number Exists");
                  //  alert(response.customer.id);
                    
                //    $('#address_place_holder').append("<h5>address</h5>");  
                  var address =  response.customer.address+"<br>"+response.customer.city+", "+response.customer.state+"<br>"+response.customer.pincode;
                
                var optHtml = '<option value="">Select Contact</option>';
                 $.each(response.sitemaster, function(index, site) {
                    //console.log('Site ' + index + ':', site.site_name);
                    // Access properties: site.id, site.site_code, etc.
                    optHtml+= '<option value="'+site.id+'"  data-address="' + address + '">'+site.site_name+'</option>';
                });
                //alert("OP :"+optHtml);
                 $('#site_master_id').html(optHtml);
                 $('#address_place_holder').html( address);
                
            },
            error: function () {
                $('#gst_result').text('Error validating GST number');
            }
        });
    });
    
    $('#site_master_id').change(function() {
          
          const site_master_id = $(this).val().trim();
          $.ajax({
              url: "/ajax-get-site-contact-details", // {{ route('ajax-get-customer-address') }}
              method: 'POST',
              data: {
                  'site_master_id': site_master_id,
              },
              success: function (response) {
               // alert(response.firstname);
                  var site_address = '';
                  $.each(response.site_address, function(index, sitemasters) { 
                    site_address = sitemasters.address+'<br>'+sitemasters.city;
                });
                
              //  firstname: "Vinod", lastname: "Patil", phone: "9845645654", email
                  var optHtml = '';
                   $.each(response.contacts, function(index, user) {
                      optHtml+= '<div>'+ user.firstname +' '+ user.lastname+'<br> Phone: '+ user.phone +' <br> Email: '+ user.email +' </div>';
                  });
                   $('#contact_place_holder').html(optHtml);
                   $('#siteAddress').html(site_address);
              },
              error: function () {
                  $('#siteAddress').text('Address Not Available');
              }
          });
      });

      $('#customer_id').on('change', function() {
       // handleHiddenFieldValueChange();
       const customerid = $('#customer_id').val().trim();
        $.ajax({
        url: "/ajax-get-customer-sites", // {{ route('ajax-get-customer-address') }}
        method: 'POST',
        data: {
            'customerid': customerid,
        },
        success: function (response) {
            var optHtml = '<option value="">Select Site</option>';
             $.each(response.customer, function(index, site) {
              //  console.log('Site ' + index + ':', site.site_customer_name);
                optHtml+= '<option value="'+site.id+'">'+site.site_customer_name+'</option>';
            });
            //alert("OP :"+optHtml);
             $('#site_master_id').html(optHtml);
        //     $('#address_place_holder').html( address);
        var optHtml = '';
        $.each(response.contacts, function(index, user) {
            console.log('Site ' + index + ':', user.firstname);
           optHtml+= '<option value="'+user.id+'">'+user.firstname+' '+user.lastname+'</option>';
       });

        var optHtml = '<option value="">Select Work Order</option>';
         $.each(response.pos, function(index, po) {
              //  console.log('Site ' + index + ':', site.site_customer_name);
                optHtml+= '<option value="'+po.id+'">'+po.po_number+'</option>';
            });
            //alert("OP :"+optHtml);
             $('#workOrder_desc').html(optHtml);
 
        $('#contact_id').html(optHtml);
      //  $('#siteAddress').html(site_address);
        },
        error: function () {
            $('#gst_result').text('Error validating GST number');
        }
        });
    });

    // 
    $('#workOrder_desc').on('change', function() {
          const po_id = $('#workOrder_desc').val().trim();
            $('#workOrder').val(po_id);
     });
});