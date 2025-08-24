$(document).ready(function() {
    var $ = jQuery.noConflict();
    $('.search').on('keyup', function() {
        var query = $(this).val();
        let clickedId = $(this).attr("id");
       // alert("You clicked: " + clickedId);
        let txtbx = $(this).data("txt_id"); 
        
        let URL = '';
        const path = window.location.pathname;    
        
        const urlMap = {
            site_master_id: '/ajax/autosuggest-sitename',
            company_id: '/ajax/autosuggest-companyname',
            customer_id: '/ajax/autosuggest-customer'
        };
  
    
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
                            // Option 1: Clickable to fill a textbox and a hidden input
                           resultsHtml += '<p class="dropdown-item" data-id="' + record.id + '" data-clickid="' + clickedId + '" data-name="' + record.name + '" data-txtbx="' + txtbx + '"  >';
                            
                            resultsHtml += ' ' + record.name; // Checkmark icon
                            resultsHtml += '</p>';

                         	 $('.myDropdown_'+ txtbx).html(resultsHtml);
 				             $('#myDropdown_'+ txtbx).show();

                        });
			            $('#myDropdown_'+ txtbx).show();
                    } else {
                        if(txtbx == 'site_master_id') {
                            resultsHtml = '<p class="dropdown-item"><a href="/master/site-masters/create">Create New Site</a>.</p>';     
                        } else {
                        resultsHtml = '<p class="dropdown-item">No results found.</p>';
                        }
                    }
                    $('#myDropdown_'+ txtbx).html(resultsHtml);
                }
            });
        } else {
            // Hide the modal and clear its content if the query is too short
            $('#searchModal').css('display', 'none');
            $('#myDropdown_'+ txtbx).html('');
        }
    });

    // Event listener to close the modal
    $('.close-btn').on('click', function() {
        $('#searchModal').css('display', 'none');
    });

    // Also close the modal if the user clicks anywhere outside of it
    $(window).on('click', function(event) {
        if (event.target.id == 'searchModal') {
            $('#searchModal').css('display', 'none');
        }
    });

    $('.myDropdown').on('click', '.dropdown-item', function() {
    // 'this' now refers to the specific <p> element that was clicked
    const id = $(this).data('id');
    const name = $(this).data('name');
    const txtbx = $(this).data('txtbx');
    const clickid = $(this).data('clickid');
  //  alert("Final"+txtbx);
    // Assuming your input box is '#search' and your hidden input is '#record-id'
    $('#' + clickid).val(name);
    $('#' + txtbx).val(id);
    $('#' + txtbx).trigger('change');

    // Hide the modal and clear its content
    $('#searchModal').css('display', 'none');
    $('#myDropdown_'+ txtbx).html('');
});
   
});
