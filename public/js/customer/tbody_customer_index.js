$(document).ready(function() {
    var $ = jQuery.noConflict();
    $('.search').on('keyup', function() {
        var query = $(this).val();
        let clickedId = $(this).attr("id");
       // alert("You clicked: " + clickedId);
        let txtbx = $(this).data("txt_id"); 
     //   alert(txtbx);
        let URL = '';
        const path = window.location.pathname;    
        
        const urlMap = {
            site_master_id: '/ajax/autosuggest-sitename',
            company_id: '/ajax/autosuggest-companyname',
            customer_id: '/ajax/autosuggest-customer',
            company_site_id: '/ajax/autosuggest-customer1'
        };
  

        // Handle a default case, if necessary
        URL = urlMap[txtbx] || '/ajax/autosuggest-customer';
      
 
        if (query.length > 2) {
            $.ajax({
                url: URL, // The URL to your server-side script
                method: 'GET',
                data: {
                    query: query
                },
                success: function(data) {
                     let resultsHtml = '';
                    // alert(txtbx);
                    if (data.length > 0) {
                      
                        $.each(data, function(index, record) {
                           // alert(record)
                            // Option 1: Clickable to fill a textbox and a hidden input
                            resultsHtml += '<tr><td></td><td>' +record.cus_mas_id;
                           
                            resultsHtml += '</td><td> ' + record.customer_name; 
                            resultsHtml += '</td><td> ' + record.statename; 
                            resultsHtml += '</td><td> ' + record.division; 
                            resultsHtml += '</td><td> ' + record.group; 
                            resultsHtml += '</td><td> ' + record.status; 
                            resultsHtml += '</td><td><div class="btn-group" role="group"><a href="/master/customer-site-masters/create?customer_id='+record.cus_mas_id+'" class="btn btn-sm btn-outline-info" title="List Sites"> <i class="bi bi-house-add"></i></a><a href="/master/customer-site-masters/?customer_id='+record.cus_mas_id+'" class="btn btn-sm btn-outline-info" title="List Sites"><i class="bi bi-list"></i></a><a href="/masters/customer/'+record.cus_mas_id+'" class="btn btn-sm btn-outline-info" title="View"><i class="bi bi-eye"></i></a><a href="/masters/customer/'+record.cus_mas_id+'/edit" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a> </td><tr>';

                          
                        });
                    } else {
                        if(txtbx == 'site_master_id') {
                            resultsHtml = '<p><a href="/master/site-masters/create">Create New Site</a>.</p>';     
                        } else {
                        resultsHtml = '<tr><td colspan="7">No results found.</td><tr>';
                        }
                    }
                    $('#tbody_customer_index').html(resultsHtml);
                    

                }
            });
        } else {

            $('#tbody_customer_index').html('');
        }
    });

    $('.delete_customer').on('click', function() {
       
        alert("Delete");
   });

});
