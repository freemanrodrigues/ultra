$(document).ready(function() {

    $('#search').on('keyup', function() {
        var query = $(this).val();
        var table = 'users';
        const path = window.location.pathname;    
        let URL = '';

  
    switch (path) {
        case '/master/contacts-masters':
            URL = '/ajax/autosuggest-companyname';
            break;
        case '/masters/customer':
            URL = '/ajax/autosuggest-customer';
            break;
        case '/master/site-masters':
            URL = '/ajax/autosuggest-sitename';
            break;
                
        default:
            // Handle a default case, if necessary
            URL = '/ajax/autosuggest-customer';
            break;
    }
        if (query.length > 2) {
            $.ajax({
                url: URL, // The URL to your server-side script
                method: 'GET',
                data: {
                    query: query,
                    table: table
                },
                success: function(data) {
                     let resultsHtml = '';
                     
                    if (data.length > 0) {
                      
                        $.each(data, function(index, record) {
                            // Option 1: Clickable to fill a textbox and a hidden input
                            resultsHtml += '<p class="select-option" data-id="' + record.id + '" data-name="' + record.name + '">';
                            
                            resultsHtml += ' ' + record.name; // Checkmark icon
                            resultsHtml += '</p>';

                            // Option 2: Clickable with an href
                           // resultsHtml += '<p class="link-option">';
                          //  resultsHtml += '<a href="/view-record?id=' + record.id + '"><span>&#x1F517;</span> View ' + record.name + '</a>'; // Link icon
                         //   resultsHtml += '</p>';
                        });
                    } else {
                        resultsHtml = '<p>No results found.</p>';
                    }
                    $('#modal-search-results').html(resultsHtml);
                    
                    // Display the modal
                    $('#searchModal').css('display', 'block');
                }
            });
        } else {
            // Hide the modal and clear its content if the query is too short
            $('#searchModal').css('display', 'none');
            $('#modal-search-results').html('');
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

    $('#modal-search-results').on('click', '.select-option', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
   
        // Assuming your input box is '#search' and your hidden input is '#record-id'
        $('#search').val(name);
        $('#record-id').val(id); 
    
        // Hide the modal and clear its content
        $('#searchModal').css('display', 'none');
        $('#modal-search-results').html('');
    });
});
