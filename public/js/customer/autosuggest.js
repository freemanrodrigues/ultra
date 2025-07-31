
        $(document).ready(function() {
            var $ = jQuery.noConflict();
            let timeout = null; // Variable to hold the debounce timeout

            // Event listener for keyup on the search input
            $('#customer_name').on('keyup', function() {
                const query = $(this).val(); // Get the current input value

                // Clear any existing timeout to debounce the input
                clearTimeout(timeout);

                // If the query is empty, hide suggestions and loading indicator
                if (query.length === 0) {
                    $('#suggestions').empty().addClass('d-none'); // Use d-none for Bootstrap hidden
                    $('#loading').hide();
                    return;
                }

                // Show loading indicator
                $('#loading').show();
                $('#suggestions').empty().addClass('d-none'); // Clear previous suggestions and hide

                // Set a new timeout to delay the AJAX request (debounce)
                timeout = setTimeout(function() {
                    // Make an AJAX GET request to your Laravel backend
                    $.ajax({
                        url: '/ajax/autosuggest-customer', // Replace with your actual Laravel API endpoint
                        method: 'GET',
                        data: { query: query }, // Send the search query
                        dataType: 'json', // Expect JSON response
                        success: function(response) {
                            $('#loading').hide(); // Hide loading indicator
                            $('#suggestions').empty(); // Clear previous suggestions

                            if (response.length > 0) {
                                // Iterate over the response data and append to suggestions list
                                $.each(response, function(index, item) {
                                    // Assuming each item has an 'name' and 'id' property
                                    const suggestionHtml = `
                                        <div class="suggestion-item" data-id="${item.id}" data-name="${item.company_name}">
                                            ${item.company_name}
                                        </div>
                                    `;
                                    $('#suggestions').append(suggestionHtml);
                                });
                                $('#suggestions').removeClass('d-none'); // Show suggestions
                            } else {
                                // No results found
                                $('#suggestions').append('<div class="p-3 text-muted">No results found.</div>').removeClass('d-none');
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#loading').hide(); // Hide loading indicator
                            console.error("AJAX Error: ", status, error);
                            $('#suggestions').empty().append('<div class="p-3 text-danger">Error fetching suggestions.</div>').removeClass('d-none');
                        }
                    });
                }, 300); // Debounce time: 300 milliseconds
            });

            // Event listener for clicking on a suggestion item
            $('#suggestions').on('click', '.suggestion-item', function() {
                const selectedName = $(this).data('name');
                const selectedId = $(this).data('id');

                // Set the input field's value to the selected suggestion's name
                $('#customer_name').val(selectedName);

                // Display the selected item (you might want to store the ID in a hidden field)
                $('#selectedItem').text(`${selectedName} (ID: ${selectedId})`);

                // Hide the suggestions list
                $('#suggestions').empty().addClass('d-none');
            });

            // Hide suggestions when clicking outside the input and suggestion list
            $(document).on('click', function(event) {
                if (!$(event.target).closest('#customer_name, #suggestions').length) {
                    $('#suggestions').empty().addClass('d-none');
                }
            });
        });
