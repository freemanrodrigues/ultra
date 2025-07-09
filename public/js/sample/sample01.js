<script data-file="sample01.js">
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill display name based on customer name
    document.getElementById('customer_name').addEventListener('input', function() {
        const displayNameField = document.getElementById('display_name');
        if (!displayNameField.value) {
            displayNameField.value = this.value;
        }
    });
    
    // Reset form
    document.getElementById('resetBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            document.getElementById('customerForm').reset();
        }
    });
    
    // Preview functionality
    document.getElementById('previewBtn').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('customerForm'));
        let previewHTML = '<div class="row">';
        
        // Basic Information
        previewHTML += '<div class="col-md-6"><h6 class="text-primary">Basic Information</h6>';
        previewHTML += '<p><strong>Customer Name:</strong> ' + (formData.get('customer_name') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>Display Name:</strong> ' + (formData.get('display_name') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>Company:</strong> ' + (document.getElementById('company_id').selectedOptions[0]?.text || 'Not selected') + '</p>';
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
        previewHTML += '<div class="col-md-6"><p><strong>Sales Person:</strong> ' + (document.getElementById('sales_person_id').selectedOptions[0]?.text || 'Not selected') + '</p></div>';
        previewHTML += '<div class="col-md-6"><p><strong>Status:</strong> ' + (formData.get('status') || 'Not selected') + '</p></div>';
        previewHTML += '</div></div>';
        
        previewHTML += '</div>';
        
        document.getElementById('previewContent').innerHTML = previewHTML;
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    });
    
    // Form validation
    document.getElementById('customerForm').addEventListener('submit', function(e) {
        const form = this;
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
    
    // GST number validation
    document.getElementById('gst_no').addEventListener('input', function() {
        const gstPattern = /^[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}[1-9A-Za-z]{1}[Z]{1}[0-9A-Za-z]{1}$/;
        const value = this.value.toUpperCase();
        this.value = value;
        
        if (value && !gstPattern.test(value)) {
            this.setCustomValidity('Please enter a valid GST number');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Pincode validation
    document.getElementById('pincode').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>