jQuery(function ($) {
    // Set CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    

    $('.assigned_contact').click(function() {
        const site_id = $(this).data('id');
    
        $.post('/ajax/get-assigned-contact', { site_id: site_id }, function (response) {
            let html = '';
    
            if (response.length === 0) {
                html = `
                    <div class="alert alert-warning text-center my-3">
                        <i class="bi bi-exclamation-circle"></i> No records found
                    </div>
                `;
            } else {
                const options = [
                    '<div class="row fw-bold bg-light border py-2"><div class="col-3">Name</div><div class="col-2">Bill</div><div class="col-2">Report</div><div class="col-2">WhatsApp</div><div class="col-2">Primary</div></div>'
                ];
    
                const getIcon = (value) => {
                    return value
                        ? '<i class="bi bi-check-circle-fill text-success"></i>'
                        : '<i class="bi bi-x-circle-fill text-danger"></i>';
                };
    
                $.each(response, function(index, obj) {
                    options.push(`
                        <div class="row border-bottom py-2 align-items-center">
                            <div class="col-3 fw-bold">${obj.full_name}</div>
                            <div class="col-2">${getIcon(obj.send_bill)}</div>
                            <div class="col-2">${getIcon(obj.send_report)}</div>
                            <div class="col-2">${getIcon(obj.whatsapp)}</div>
                            <div class="col-2">${getIcon(obj.is_primary)}</div>
                        </div>
                    `);
                });
    
                html = options.join('');
            }
    
            $('#assigned_contact_div').html(html);
        }).fail(function () {
            $('#assigned_contact_div').html(`
                <div class="alert alert-danger text-center my-3">
                    <i class="bi bi-x-circle"></i> Failed to load contacts. Please try again.
                </div>
            `);
        });
    });

    $('.m3').click(function() {
        const site_id = $(this).data('id');
        const company_id = $(this).data('company_id');
        const customer_id = $(this).data('customer_id');
    
        $('#site_id').val(site_id);
        $('#customer_id').val(customer_id);
        $('.modal-footer').show();
    
        $.post('/ajax/get-contacts', { company_id: company_id }, function (response) {
            let options = [];
    
            if (response.length === 0) {
                options.push(`
                    <div class="alert alert-warning text-center my-3">
                        <i class="bi bi-exclamation-circle"></i> No contacts found for this company.
                    </div>
                `);
            } else {
                options.push(`
                    <div class="row fw-bold bg-light border py-2">
                        <div class="col-3">Name</div>
                        <div class="col-2">Bill</div>
                        <div class="col-2">Report</div>
                        <div class="col-2">WhatsApp</div>
                        <div class="col-2">Primary</div>
                    </div>
                `);
    
                $.each(response, function(index, obj) {
                    options.push(`
                        <div class="row border-bottom py-2 align-items-center">
                            <div class="col-3 fw-bold">
                                ${obj.firstname} ${obj.lastname}
                                <input type="hidden" name="counter[${index}]" value="${obj.id}">
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="send_bill[${index}]" value="${obj.id}">
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="send_report[${index}]" value="${obj.id}">
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="whatsapp[${index}]" value="${obj.id}">
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="is_primary[${index}]" value="${obj.id}">
                            </div>
                        </div>
                    `);
                });
            }
    
            $('#m3_div').html(options.join(''));
        }).fail(function () {
            $('#m3_div').html(`
                <div class="alert alert-danger text-center my-3">
                    <i class="bi bi-x-circle"></i> Failed to load contacts. Please try again.
                </div>
            `);
        });
    });
    


   // $('#contactForm').submit(function (e) {
    $('#assign_submit').click(function(e) {
        e.preventDefault();
    
        let formData = $('#assignContact').serialize();
      
        $.ajax({
            url: URL,
            type: 'POST',
          data: formData,
          success: function (response) {
            $('#formErrors').html('');
            $('#assignContact')[0].reset();
            $('#m3_div').html(`
                <div class="alert alert-success text-center my-3">
                    <i class="bi bi-x-circle"></i>  Contact  successfully Assgined!
                </div>
            `);
            $('.modal-footer').hide();
            
            alert('Contact added successfully!');
            // Optionally reload part of the page or refresh table
          },
          error: function (xhr) {
            if (xhr.status === 422) {
              let errors = xhr.responseJSON.errors;
              let errorHtml = '<ul>';
              $.each(errors, function (key, value) {
                errorHtml += '<li>' + value[0] + '</li>';
              });
              errorHtml += '</ul>';
              $('#formErrors').html(errorHtml);
            } else {
              alert('An error occurred. Please try again.');
            }
          }
        });
        
      });
});
