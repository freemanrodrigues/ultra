    // Auto-set current date
    
    document.getElementById('date').valueAsDate = new Date();
        
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
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
  