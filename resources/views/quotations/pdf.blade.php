<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quotation - {{ $quotation->quotation_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .container {
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #667eef 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .company-info {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .info-section {
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-col {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
        }
        
        .info-col.left {
            border-right: 1px solid #dee2e6;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 3px;
        }
        
        .info-value {
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        table th {
            background-color: #667eef;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }
        
        table td {
            padding: 6px 8px;
            border-bottom: 1px solid #dee2e6;
        }
        
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .totals-table {
            width: 300px;
            float: right;
            margin-top: 10px;
        }
        
        .totals-table td {
            padding: 5px 10px;
        }
        
        .totals-table .total-row {
            background-color: #667eef;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #667eef;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #667eef;
        }
        
        .terms {
            background: #f8f9fa;
            padding: 10px;
            margin-top: 20px;
            border-left: 3px solid #667eef;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #666;
            padding: 10px;
            border-top: 1px solid #dee2e6;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-draft { background-color: #6c757d; color: white; }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-approved { background-color: #28a745; color: white; }
        .status-converted { background-color: #17a2b8; color: white; }
        .status-closed { background-color: #343a40; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- Header -->
        <div class="header">
            <h1>QUOTATION</h1>
            <p>{{ $quotation->formatted_quotation_number }}</p>
        </div>
        
        <!-- Company Info -->
        <div class="company-info">
            <strong style="font-size: 16px;">{{ $quotation->company->company_name ?? 'Company Name' }}</strong>
            @if($quotation->company->pancard)
                <br>PAN: {{ $quotation->company->pancard }}
            @endif
        </div>
        
        <!-- Quotation & Customer Info -->
        <div class="info-row">
            <div class="info-col left">
                <div class="section-title">QUOTATION DETAILS</div>
                <div class="info-label">Quotation Number:</div>
                <div class="info-value">{{ $quotation->quotation_number }}</div>
                <br>
                @if($quotation->reference_number)
                    <div class="info-label">Reference Number:</div>
                    <div class="info-value">{{ $quotation->reference_number }}</div>
                    <br>
                @endif
                <div class="info-label">Date:</div>
                <div class="info-value">{{ $quotation->quotation_date->format('d-M-Y') }}</div>
                <br>
                <div class="info-label">Valid From:</div>
                <div class="info-value">{{ $quotation->valid_from->format('d-M-Y') }}</div>
                <br>
                <div class="info-label">Valid Until:</div>
                <div class="info-value">{{ $quotation->valid_until->format('d-M-Y') }}</div>
                <br>
                <div class="info-label">Status:</div>
                <div class="info-value">
                    @php
                        $statusClasses = [1 => 'status-draft', 2 => 'status-pending', 3 => 'status-approved', 
                                        4 => 'status-converted', 5 => 'status-closed', 6 => 'status-cancelled'];
                    @endphp
                    <span class="status-badge {{ $statusClasses[$quotation->status] ?? 'status-draft' }}">
                        {{ $quotation->status_name }}
                    </span>
                </div>
            </div>
            
            <div class="info-col">
                <div class="section-title">CUSTOMER DETAILS</div>
                @if($quotation->customer)
                    <div class="info-label">Customer Name:</div>
                    <div class="info-value">{{ $quotation->customer->customer_name }}</div>
                    <br>
                    @if($quotation->customer->gst_no)
                        <div class="info-label">GST Number:</div>
                        <div class="info-value">{{ $quotation->customer->gst_no }}</div>
                        <br>
                    @endif
                @endif
                @if($quotation->site)
                    <div class="info-label">Site:</div>
                    <div class="info-value">{{ $quotation->site->site_name }}</div>
                    <br>
                @endif
                @if($quotation->billing_address)
                    <div class="info-label">Billing Address:</div>
                    <div class="info-value">{{ $quotation->billing_address }}</div>
                @endif
            </div>
        </div>
        
        <!-- Items Table -->
        <div class="section-title">QUOTATION ITEMS</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 25%;">Sample Type</th>
                    <th style="width: 30%;">Tests</th>
                    <th style="width: 10%;" class="text-center">Sample Qty</th>
                    <th style="width: 10%;" class="text-right">Sample Rate</th>
                    <th style="width: 10%;" class="text-right">Test Total</th>
                    <th style="width: 10%;" class="text-right">Item Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->sampleType->sample_type_name ?? 'N/A' }}</td>
                        <td>
                            @if($item->tests && $item->tests->count() > 0)
                                @foreach($item->tests as $test)
                                    • {{ $test->test->test_name ?? 'N/A' }} (₹{{ number_format($test->standard_rate, 2) }})<br>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">{{ $item->sample_count }}</td>
                        <td class="text-right">₹{{ number_format($item->sample_rate, 2) }}</td>
                        <td class="text-right">₹{{ number_format($item->test_total, 2) }}</td>
                        <td class="text-right">₹{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="clearfix">
            <table class="totals-table">
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td class="text-right">₹{{ number_format($quotation->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Discount ({{ $quotation->discount_percent }}%):</strong></td>
                    <td class="text-right">₹{{ number_format($quotation->discount_amount, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Tax ({{ $quotation->tax_percent }}%):</strong></td>
                    <td class="text-right">₹{{ number_format($quotation->tax_amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Total Amount:</strong></td>
                    <td class="text-right"><strong>₹{{ number_format($quotation->total_amount, 2) }}</strong></td>
                </tr>
            </table>
        </div>
        
        <div style="clear: both; margin-top: 20px;"></div>
        
        <!-- Terms and Conditions -->
        @if($quotation->terms_and_conditions)
            <div class="section-title">TERMS & CONDITIONS</div>
            <div class="terms">
                {!! nl2br(e($quotation->terms_and_conditions)) !!}
            </div>
        @endif
        
        <!-- Notes -->
        @if($quotation->notes)
            <div class="section-title">NOTES</div>
            <div class="terms">
                {!! nl2br(e($quotation->notes)) !!}
            </div>
        @endif
        
        <!-- Special Instructions -->
        @if($quotation->special_instructions)
            <div class="section-title">SPECIAL INSTRUCTIONS</div>
            <div class="terms">
                {!! nl2br(e($quotation->special_instructions)) !!}
            </div>
        @endif
        
        <!-- Signatures -->
        <div style="margin-top: 40px;">
            <div class="info-row">
                <div class="info-col left">
                    <div class="info-label">Created By:</div>
                    <div class="info-value">{{ $quotation->creator->firstname ?? 'N/A' }} {{ $quotation->creator->lastname ?? '' }}</div>
                    <div class="info-value">Date: {{ $quotation->created_at->format('d-M-Y') }}</div>
                </div>
                <div class="info-col">
                    @if($quotation->approved_by)
                        <div class="info-label">Approved By:</div>
                        <div class="info-value">{{ $quotation->approver->firstname ?? 'N/A' }} {{ $quotation->approver->lastname ?? '' }}</div>
                        <div class="info-value">Date: {{ $quotation->approved_at ? $quotation->approved_at->format('d-M-Y') : 'N/A' }}</div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>This is a computer-generated quotation and does not require a signature.</p>
        <p>Generated on {{ now()->format('d-M-Y H:i:s') }}</p>
    </div>
    
</body>
</html>

