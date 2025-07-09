<script data-file="script.js">
document.addEventListener('DOMContentLoaded', function() {
    // Auto-set current date
    document.getElementById('date').valueAsDate = new Date();
    
    // Form submission handler
    document.getElementById('sampleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const requiredFields = document.querySelectorAll('.required-field');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (isValid) {
            alert('Form submitted successfully!');
        } else {
            alert('Please fill in all required fields marked in red.');
        }
    });
    
    // Remove validation styling on input
    document.querySelectorAll('.required-field').forEach(field => {
        field.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
    
    // Reset button handler
    document.getElementById('resetBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the form? All data will be lost.')) {
            document.getElementById('sampleForm').reset();
            document.getElementById('date').valueAsDate = new Date();
            document.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
        }
    });
    
    // Preview button handler
    document.getElementById('previewBtn').addEventListener('click', function() {
        alert('Preview functionality would open a modal or new window with form data preview.');
    });
    
    // Save draft button handler
    document.getElementById('saveDraftBtn').addEventListener('click', function() {
        alert('Draft saved successfully! (This would typically save to localStorage or server)');
    });
});
</script>