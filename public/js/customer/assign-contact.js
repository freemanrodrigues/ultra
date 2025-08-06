jQuery(function ($) {
    // Set CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    
 
  
      $('.assign_contact').click(function() {
       

        const company_id =  $(this).data('id');
        //alert(company_id);
            $.post('/ajax/get-contacts', { company_id: company_id }, function (response) {
                //alert(response);
                var options = ["<option value=''>Select</option>"];

                $.each(response, function(index, obj) {
                    options.push("<option value='" + obj.id + "'>" + obj.firstname + " " + obj.lastname + "</option>");
                });

                var html = options.join('');
                $('#assign_contact').html(html);
                            }).fail(function () {
                                
                            });

      
    });

    $('.assigned_contact').click(function() {
        const site_id =  $(this).data('id');
            $.post('/ajax/get-assigned-contact', { site_id: site_id }, function (response) {
                var options = ['<div class="row fw-bold bg-light border py-2"><div class="col-3">Name</div><div class="col-2">Bill</div><div class="col-2">Report</div><div class="col-2">WhatsApp</div><div class="col-2">Primary</div></div>'];

                $.each(response, function(index, obj) {
                    options.push('<div class="row border-bottom py-2"><div class="col-3">'+obj.full_name+'</div><div class="col-2">'+obj.send_bill+'</div><div class="col-2">'+obj.send_report+'</div><div class="col-2">'+obj.whatsapp+'</div><div class="col-2">'+obj.is_primary+'</div></div>');
                });
                options.push('</div>');
            var html = options.join('');
            $('#assigned_contact_div').html(html);
            }).fail(function () {
                
            });
    });
    
});
