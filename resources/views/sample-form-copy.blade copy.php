<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Receipt Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
        }
        .required-field {
            border-color: #dc3545;
        }
        .section-divider {
            border-left: 3px solid #667eea;
            background-color: #f8f9fa;
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: transform 0.2s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .icon-input {
            position: relative;
        }
        .icon-input i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                <div class="card shadow-lg border-0">
                    <div class="form-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1"><i class="bi bi-clipboard-data me-2"></i>Sample Receipt</h3>
                                <p class="mb-0 opacity-75">Laboratory Sample Tracking Form</p>
                            </div>
                            <div class="text-end">
                                <small class="opacity-75">Form ID: SR-2025</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('sample.store') }}">
                        @csrf
                            <!-- Header Information Row -->
                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label for="lotNo" class="form-label fw-semibold">Lot No.</label>
                                    <input type="text" class="form-control" id="lotNo" value="0">
                                </div>
                                <div class="col-md-3">
                                    <label for="courierName" class="form-label fw-semibold">Courier Name</label>
                                    <div class="icon-input">
                                        <input type="text" class="form-control" id="courierName">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="noOfSamples" class="form-label fw-semibold">No of Samples</label>
                                    <input type="number" class="form-control required-field" id="noOfSamples" value="0" min="0">
                                </div>
                                <div class="col-md-3">
                                    <label for="date" class="form-label fw-semibold">Date</label>
                                    <input type="date" class="form-control required-field" id="date" value="2025-06-28">
                                </div>
                                <div class="col-md-2">
                                    <label for="podNo" class="form-label fw-semibold">POD No.</label>
                                    <input type="text" class="form-control" id="podNo">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Customer Information Section -->
                                <div class="col-lg-6">
                                    <div class="section-divider p-3 rounded h-100">
                                        <h5 class="text-primary mb-3"><i class="bi bi-person-circle me-2"></i>Customer Information</h5>
                                        
                                        <div class="mb-3">
                                            <label for="customer" class="form-label fw-semibold">Customer</label>
                                            <div class="icon-input">
                                                <input type="text" class="form-control required-field" id="customer" required>
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="company" class="form-label fw-semibold">Company</label>
                                            <div class="icon-input">
                                                <input type="text" class="form-control" id="company">
                                                <i class="bi bi-building"></i>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="address" class="form-label fw-semibold">Address</label>
                                            <textarea class="form-control" id="address" rows="4" placeholder="Enter full address"></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label for="mobile" class="form-label fw-semibold">Mobile</label>
                                                <div class="icon-input">
                                                    <input type="tel" class="form-control" id="mobile">
                                                    <i class="bi bi-phone"></i>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label for="emailId" class="form-label fw-semibold">Email Id</label>
                                                <div class="icon-input">
                                                    <input type="email" class="form-control" id="emailId">
                                                    <i class="bi bi-envelope"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Site Contact Information Section -->
                                <div class="col-lg-6">
                                    <div class="section-divider p-3 rounded h-100">
                                        <h5 class="text-primary mb-3"><i class="bi bi-geo-alt-fill me-2"></i>Site Contact Information</h5>
                                        
                                        <div class="mb-3">
                                            <label for="siteContactPerson" class="form-label fw-semibold">Site Contact Person</label>
                                            <div class="icon-input">
                                                <input type="text" class="form-control required-field" id="siteContactPerson" required>
                                                <i class="bi bi-person-badge"></i>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="siteCompany" class="form-label fw-semibold">Company</label>
                                            <div class="icon-input">
                                                <input type="text" class="form-control" id="siteCompany">
                                                <i class="bi bi-building"></i>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="siteAddress" class="form-label fw-semibold">Address</label>
                                            <textarea class="form-control" id="siteAddress" rows="4" placeholder="Enter site address"></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label for="siteMobile" class="form-label fw-semibold">Mobile</label>
                                                <div class="icon-input">
                                                    <input type="tel" class="form-control" id="siteMobile">
                                                    <i class="bi bi-phone"></i>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label for="siteEmailId" class="form-label fw-semibold">Email Id</label>
                                                <div class="icon-input">
                                                    <input type="email" class="form-control" id="siteEmailId">
                                                    <i class="bi bi-envelope"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Work Order and Timeline Section -->
                            <div class="row mt-4">
                                <div class="col-lg-6">
                                    <div class="section-divider p-3 rounded">
                                        <h5 class="text-primary mb-3"><i class="bi bi-calendar-check me-2"></i>Work Order & Timeline</h5>
                                        
                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label for="reportExpectedBy" class="form-label fw-semibold">Report Expected by</label>
                                                <input type="date" class="form-control required-field" id="reportExpectedBy">
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label for="workOrderDate" class="form-label fw-semibold">Work Order Date</label>
                                                <input type="date" class="form-control required-field" id="workOrderDate">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="workOrder" class="form-label fw-semibold">Work Order</label>
                                            <input type="text" class="form-control" id="workOrder">
                                        </div>

                                        <div class="mb-3">
                                            <label for="freightCharges" class="form-label fw-semibold">Freight Charges</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" class="form-control" id="freightCharges" value="0" min="0" step="0.01">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="section-divider p-3 rounded">
                                        <h5 class="text-primary mb-3"><i class="bi bi-box-seam me-2"></i>Additional Information</h5>
                                        
                                        <div class="mb-3">
                                            <label for="additionalInfo" class="form-label fw-semibold">Additional Information</label>
                                            <textarea class="form-control" id="additionalInfo" rows="6" placeholder="Enter any additional notes or special instructions"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sample Tracking Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="section-divider p-3 rounded">
                                        <h5 class="text-primary mb-3"><i class="bi bi-arrow-repeat me-2"></i>Sample Tracking Timeline</h5>
                                        
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 mb-3">
                                                <label for="sampleDispatchedFromSite" class="form-label fw-semibold">Sample Dispatched from Site</label>
                                                <input type="datetime-local" class="form-control required-field" id="sampleDispatchedFromSite">
                                            </div>
                                            <div class="col-lg-3 col-md-6 mb-3">
                                                <label for="sampleCollectedFromCenter" class="form-label fw-semibold">Sample Collected from Collection Center</label>
                                                <input type="datetime-local" class="form-control required-field" id="sampleCollectedFromCenter">
                                            </div>
                                            <div class="col-lg-3 col-md-6 mb-3">
                                                <label for="sampleReceivedAtCenter" class="form-label fw-semibold">Sample Received at Collection Center</label>
                                                <input type="datetime-local" class="form-control required-field" id="sampleReceivedAtCenter">
                                            </div>
                                            <div class="col-lg-3 col-md-6 mb-3">
                                                <label for="sampleReceivedAtLab" class="form-label fw-semibold">Sample Received at Lab</label>
                                                <input type="datetime-local" class="form-control required-field" id="sampleReceivedAtLab">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                        </button>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>Preview
                                        </button>
                                        <button type="button" class="btn btn-outline-success">
                                            <i class="bi bi-download me-1"></i>Save Draft
                                        </button>
                                        <button type="submit" class="btn btn-custom text-white">
                                            <i class="bi bi-check-circle me-1"></i>Submit Receipt
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
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
    </script>
</body>
</html>