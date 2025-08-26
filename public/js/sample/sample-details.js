$(document).ready(function() {
   
    $(".equipment").on('change', function() {
      // Check if the selected value is 'New'
      var dataIdValue = $(this).attr('data-id');
      alert(dataIdValue);
      if ($(this).val() === 'New') {
        // Define the new row to be added
        var newRow = '<tr><td></td><td><input type="text" placeholder="New Data 1"></td><td><input type="text" placeholder="New Data 2"></td><td><input type="text" placeholder="New Data 2"></td><td><input type="text" placeholder="New Data 2"></td></tr>';
  
        // Append the new row to the table's tbody
        $('#addTr'+dataIdValue).append(newRow);
      }
    });
  });

  