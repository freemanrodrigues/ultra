<?php

namespace App\Http\Controllers;

use App\Models\{CompanyMaster,CourierMaster,CustomerMaster,Sample,SiteMaster,User};
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;
use Pdf; // Use the Pdf Facade
use Carbon\Carbon; // For date handling
class SampleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():View
    {
        $samples = Sample::all();
        $courier_mst = CourierMaster::getCourierArray();
        $company_mst = CompanyMaster::getCompanyArray();
        $customer_mst = CustomerMaster::getCustomerArray();
        $sitemaster = SiteMaster::getSiteMasterArray();
        $users = User::getUserArray();
        
        return view('sample-list',compact('samples','courier_mst','company_mst','customer_mst','users','sitemaster'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
        $courier_mst = CourierMaster::getCourierArray();
        $company_mst = CompanyMaster::getCompanyArray();
        $customer_mst = CustomerMaster::getCustomerArray();
        
        return view('sample-form',compact('courier_mst','company_mst','customer_mst'));
    }
    public function sampleCopy():View
    {
        //die('copy');
        return view('sample2');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //dd($request->all());
        $validated = $request->validate([
            'lot_no' => 'required|string|max:15',
            'courier_id' => 'required|integer',
            'no_of_samples' => 'required|integer|max:255',
            'sample_date' => 'required|date',
            "pod_no" => 'required|string|max:24',
            "site_master_id" => 'nullable|integer',    
          "customer_id" => 'nullable|integer',
        "company_id" => 'nullable|integer',
        "cus_mobile" => 'required|numeric',
        "cus_email" => 'required|email',
        "cus_site_contact_person_id" => 'nullable|integer',
        "cus_site_contact_mobile" => 'nullable|numeric',
        "cus_site_contact_email" => 'nullable|email',
        "expected_report_date" => 'nullable|date',
        "work_order_date" => 'nullable|date',
        "work_order" => 'nullable|string|max:24',
        "freight_charges" => ['nullable', 'regex:/^\d{1,4}(\.\d{1,2})?$/'],
        "additional_info" => 'nullable|string|max:255',
        "site_sample_dispacted_date" => 'nullable|date',
        "collection_center_sample_received_date" => 'date',
        "collection_center_sample_collected_date" => 'nullable|date',
        "lab_sample_received_date" => 'nullable|date',
        ]);
       
       
        try {
            //  dd($validated);
            $customer = CustomerMaster::getCountryId($request['customer_id']);
            $company_id = $customer[0]->company_id;
            
            $sam = new Sample();
            $sam->lot_no = $request->lot_no;
            $sam->courier_id = $request->courier_id;
            $sam->no_of_samples = $request->no_of_samples;
            $sam->sample_date = $request->sample_date;
            $sam->pod_no = $request->pod_no;
            $sam->site_master_id = $request->site_master_id;
            $sam->customer_id = $request->customer_id;
            $sam->company_id = $company_id;
            $sam->cus_mobile= $request->cus_mobile;
            $sam->cus_email	= $request->cus_email;
            $sam->cus_site_contact_person_id = $request->cus_site_contact_person_id;
            $sam->cus_site_contact_mobile = $request->cus_site_contact_mobile;
            $sam->cus_site_contact_email = $request->cus_site_contact_email;
            $sam->expected_report_date = $request->expected_report_date;
            $sam->work_order_date = $request->work_order_date;
            $sam->work_order = $request->work_order;
            $sam->freight_charges = $request->freight_charges;
            $sam->additional_info = $request->additional_info;
            $sam->site_sample_dispacted_date = $request->site_sample_dispacted_date;
            $sam->collection_center_sample_received_date = $request->collection_center_sample_received_date;
            $sam->collection_center_sample_collected_date = $request->collection_center_sample_received_date;
            $sam->lab_sample_received_date = $request->lab_sample_received_date;
            $sam->save();

              return redirect()->route('sample.index')
                             ->with('success', 'SampleNature created successfully!');
          } catch (\Exception $e) {
              dd("Fail". $e->getMessage());
              return redirect()->back()
                             ->withInput()
                             ->with('error', 'Error creating SampleNature: ' . $e->getMessage());
          }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sample $sample)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sample $sample)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sample $sample)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sample $sample)
    {
        //
    }

    public function generateSalesReport()
    {
        // 1. Fetch Data (Replace with your actual data retrieval logic)
        $reportDate = Carbon::now()->format('F j, Y');
        $generatedBy = 'Admin User';
        $startDate = 'July 1, 2025';
        $endDate = 'July 25, 2025'; // Current date

        $totalSales = 12500.75;
        $totalOrders = 150;
        $averageOrderValue = $totalSales / $totalOrders;

        // Sample sales data (from a database query, for example)
        $salesData = [
            ['order_id' => 'ORD001', 'product_name' => 'Laptop Pro', 'quantity' => 1, 'price' => 1200.00, 'order_date' => '2025-07-01'],
            ['order_id' => 'ORD002', 'product_name' => 'Wireless Mouse', 'quantity' => 2, 'price' => 25.50, 'order_date' => '2025-07-02'],
            ['order_id' => 'ORD003', 'product_name' => 'Mechanical Keyboard', 'quantity' => 1, 'price' => 75.00, 'order_date' => '2025-07-03'],
            ['order_id' => 'ORD004', 'product_name' => 'External Hard Drive', 'quantity' => 1, 'price' => 150.00, 'order_date' => '2025-07-05'],
            ['order_id' => 'ORD005', 'product_name' => 'Monitor 27 inch', 'quantity' => 1, 'price' => 300.00, 'order_date' => '2025-07-08'],
            // Add more data as needed for testing pagination etc.
            ['order_id' => 'ORD006', 'product_name' => 'Webcam HD', 'quantity' => 1, 'price' => 45.00, 'order_date' => '2025-07-10'],
            ['order_id' => 'ORD007', 'product_name' => 'USB Hub', 'quantity' => 3, 'price' => 15.00, 'order_date' => '2025-07-12'],
            ['order_id' => 'ORD008', 'product_name' => 'Gaming Headset', 'quantity' => 1, 'price' => 99.99, 'order_date' => '2025-07-15'],
            ['order_id' => 'ORD009', 'product_name' => 'Ergonomic Chair', 'quantity' => 1, 'price' => 400.00, 'order_date' => '2025-07-18'],
            ['order_id' => 'ORD010', 'product_name' => 'Laptop Stand', 'quantity' => 1, 'price' => 30.00, 'order_date' => '2025-07-20'],
        ];

        $userName = 'Customer'; // Or retrieve dynamically

        $data = [
            'reportDate' => $reportDate,
            'generatedBy' => $generatedBy,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'averageOrderValue' => $averageOrderValue,
            'salesData' => $salesData,
            'userName' => $userName,
        ];

        // 2. Load the Blade view with data
        $pdf = Pdf::loadView('reports.my_report_pdf', $data);

        // Optional: Set paper size and orientation (e.g., A4, landscape)
        // $pdf->setPaper('A4', 'landscape');

        // Optional: Set custom options (e.g., for enabling remote images)
        // $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);


        // 3. Return the PDF
        // Option A: Stream the PDF to the browser (opens in new tab or viewer)
        return $pdf->stream('sales_report_' . Carbon::now()->format('YmdHis') . '.pdf');

        // Option B: Download the PDF to the user's computer
        // return $pdf->download('sales_report_' . Carbon::now()->format('YmdHis') . '.pdf');

        // Option C: Save the PDF to a storage disk (e.g., storage/app/public/reports)
        // This requires the 'dompdf.php' config file to be published and 'DOMPDF_ENABLE_REMOTE' to be true if images are remote
        // $path = 'public/reports/sales_report_' . Carbon::now()->format('YmdHis') . '.pdf';
        // Storage::put($path, $pdf->output());
        // return redirect()->back()->with('success', 'Report saved to storage!');
    }

    public function generateSalesReport1()
    {
        // 1. Fetch Data (Replace with your actual data retrieval logic)
       $reportDate = Carbon::now()->format('F j, Y');
   $report =  (object) [
        // Header Information
        'severity' => 'CRITICAL',
        'report_id' => 'ULR-TC586525000031042F',
        'report_date' => '11-Jul-2025',
        
        // Sample Information
        'sample_id' => 'USN459561',
        'equipment_id' => 'THEJAHL0C00000901',
        'received_date' => '09-Jul-2025',
        'tested_date' => '11-Jul-2025',
        'sample_type' => 'USED TRAVEL DEVICE OIL(RHS)',
        'sampling_point' => 'TOP UP POINT',
        
        // Customer Information
        'company_name' => 'TATA HITACHI CONSTRUCTION MACHINERY COMPANY PRIVATE LIMITED',
        'site_name' => 'PACHHWARA COAL MINE - PAKUR',
        'contact_person' => 'Mr. Rahul Singh',
        'address' => 'JUBILEE BUILDING, 45 MUSEUM ROAD, BANGLORE 560025',
        'vendor_code' => 'U81630',
        
        // Equipment Information
        'equipment_make' => 'TATA HITACHI',
        'equipment_model' => 'ZX470',
        'equipment_serial' => 'TC-5865',
        'oil_grade' => 'TG-90',
        'oil_brand' => 'Tata Hitachi',
        'fluid_hours' => '2000.9',
        'sump_capacity' => '11.00 Ltrs',
        'sub_asy_no' => 'Not Specified',
        'total_hmr' => '9997.7',
        'sub_asy_hrs' => 'Not Specified',
        'top_up_volume' => 'Not Specified',
        
        // Physico Chemical Analysis
        'water_content' => '>2100',
        'water_previous' => '6058',
        'water_limits' => '750/2000',
        'water_status' => 'CRITICAL',
        
        'appearance' => 'DARK',
        'appearance_previous' => 'DARK',
        'appearance_status' => 'CAUTION',
        
        'kv_40c' => '192.1',
        'kv_40c_previous' => '170.8',
        'kv_40c_units' => 'mm²/s',
        'kv_40c_method' => 'ASTM D445',
        
        'kv_100c' => 'N.P',
        'kv_100c_previous' => 'N.P',
        'kv_100c_limits' => '14.75-18.02',
        
        'viscosity_index' => 'N.P',
        'viscosity_index_previous' => 'N.P',
        
        'acid_number' => 'N.P',
        'acid_number_previous' => 'N.P',
        'acid_number_limits' => '2.42/3.42',
        
        'flash_point' => 'N.P',
        'flash_point_previous' => '220',
        'flash_point_limits' => '>190',
        
        'pq_index' => '2617',
        'pq_index_previous' => '132',
        'pq_index_limits' => '300/900',
        'pq_index_status' => 'CRITICAL',
        
        'oxidation' => '3.40',
        'oxidation_previous' => '3.00',
        'oxidation_limits' => '15/30',
        'oxidation_status' => 'OK',
        
        // Wear Metals Analysis
        'iron' => '2346.1',
        'iron_previous' => '1739.6',
        'iron_limits' => '1700/3500',
        'iron_status' => 'CRITICAL',
        
        'chromium' => '16.2',
        'chromium_previous' => '12.6',
        'chromium_limits' => '10/25',
        'chromium_status' => 'CAUTION',
        
        'tin' => '1.6',
        'tin_previous' => '3.0',
        'tin_limits' => '5/10',
        'tin_status' => 'OK',
        
        'aluminium' => '253.9',
        'aluminium_previous' => '150.8',
        'aluminium_limits' => '70/180',
        'aluminium_status' => 'CRITICAL',
        
        'nickel' => '<0.5',
        'nickel_previous' => '<0.5',
        'nickel_limits' => '5/10',
        'nickel_status' => 'OK',
        
        'manganese' => '15.0',
        'manganese_previous' => '13.4',
        'manganese_status' => 'OK',
        
        'copper' => '1.8',
        'copper_previous' => '2.3',
        'copper_limits' => '10/15',
        'copper_status' => 'OK',
        
        'lead' => '<0.5',
        'lead_previous' => '<0.5',
        'lead_limits' => '5/10',
        'lead_status' => 'OK',
        
        'silver' => '<0.5',
        'silver_previous' => '<0.5',
        'silver_status' => 'OK',
        
        'vanadium' => '<0.5',
        'vanadium_previous' => '<0.5',
        'vanadium_status' => 'OK',
        
        'titanium' => '18.9',
        'titanium_previous' => '10.2',
        'titanium_status' => 'OK',
        
        // Contaminants
        'silicon' => '549.5',
        'silicon_previous' => '328.5',
        'silicon_limits' => '290/600',
        'silicon_status' => 'CAUTION',
        
        'potassium' => '<0.5',
        'potassium_previous' => '1.6',
        'potassium_status' => 'OK',
        
        'sodium' => '3.3',
        'sodium_previous' => '<0.5',
        'sodium_status' => 'OK',
        
        // Additive Elements
        'calcium' => '22.6',
        'calcium_previous' => '15.8',
        'calcium_status' => 'OK',
        
        'magnesium' => '4.6',
        'magnesium_previous' => '3.9',
        'magnesium_status' => 'OK',
        
        'cadmium' => '<0.5',
        'cadmium_previous' => '<0.5',
        'cadmium_status' => 'OK',
        
        'boron' => '2.4',
        'boron_previous' => '1.6',
        'boron_status' => 'OK',
        
        'zinc' => '9.2',
        'zinc_previous' => '8.4',
        'zinc_status' => 'OK',
        
        'phosphorus' => '463.5',
        'phosphorus_previous' => '492.1',
        'phosphorus_status' => 'OK',
        
        'barium' => '1.5',
        'barium_previous' => '<0.5',
        'barium_status' => 'OK',
        
        'molybdenum' => '1.5',
        'molybdenum_previous' => '<0.5',
        'molybdenum_status' => 'OK',
        
        // Problem Analysis
        'problem_summary' => 'Water, PQ INDEX, Iron, Chromium, Aluminium, Silicon',
        'problems' => [
            'Silicon content is found high, which indicates dust ingress problem.',
            'Increase contamination leads to wearing of internal components like gears, shafts, bearings & washers as result PQ Index, Iron, Chromium and Aluminium content are found high.',
            'Low Iron and High PQ Index is a result of large Iron particles and few small particles which indicates fatigue wear.',
            'As per limit water content is found very high, i.e. 18101 ppm.'
        ],
        
        // Comments
        'comments' => [
            'Due to high water content present in the oil, rest of the tests were not possible.',
            'Last sample was found in CRITICAL stage. Hence risk is high running the equipment without corrections.',
            'Kindly provide the reason for sending the sample in sample Datasheet.'
        ],
        
        // Recommendations
        'recommendations' => [
            'It is recommended to change the oil.',
            'Please check the seal-ability of floating seal for the source of dust & water ingress.',
            'It is strongly recommended to check / inspect the internal components like gears, shafts, bearings & washers very closely with a magnifying glass to understand the type of wear of surfaces, diameters and gear teeth flanks.',
            'From high water and Silicon content it seems the machine was used in water/mud/slurry. Hence water and mud might have seeped thru Floating Seal of Travel devices. As per Tata Hitachi recommendations/guidelines if machine used in water/mud/slurry, Floating seal need to be replaced.',
            'After corrective action resend the sample after 100 hrs. of operation to understand the wear and contamination trend.'
        ],
        
        // Action Taken
        'action_taken' => 'Abnormalities were founded in third stage carrier and its outer splines And also abnormalities were founded in third stage Sun & Planetry gear, Rubbing marks were founded in housing.',
        
        // Report Authorization
        'authorized_by' => 'Mrs. Radhika Bhojane-Technical Manager',
        'company_details' => [
            'name' => 'Ultra Plus Lubes Pvt. Ltd.',
            'address' => 'Plot 17, JCIE, Kamothe,Panvel-410 209, Maharashtra, India',
            'website' => 'www.ultralabs.co.in',
            'report_version' => 'QR48 Rev. No.3 dt. 24.05.2021'
        ],
        
        // Additional Report Data
        'sampling_dates' => [
            'current' => '01-Jul-2025',
            'previous' => '14-Mar-2025'
        ],
        
        'report_ids' => [
            'current' => 'ULR-TC5865250/ 13407',
            'previous' => 'ULR-TC586525031042'
        ],
        
        // Test Methods
        'test_methods' => [
            'water' => 'ASTM D6304',
            'appearance' => 'Visual',
            'kv_40c' => 'ASTM D445',
            'kv_100c' => 'ASTM D445',
            'viscosity_index' => 'ASTM D2270',
            'acid_number' => 'ASTM D974',
            'flash_point' => 'ASTM D92',
            'pq_index' => 'ASTM D8184',
            'oxidation' => 'ASTM E2412-10',
            'elemental_analysis' => 'ASTM D5185',
            'ftir' => 'ASTM E2412'
        ],
        
        // Disclaimer
        'disclaimer' => 'The test report shall not be reproduced fully or partially without the written approval of Ultra Plus Lubes Pvt. Ltd. Sample will be retained for 30 working days from the date of testing. Sampling is solely done by the customer/party and the reported result is related to the sample tested only. The reported results relates exclusively and purely to the tested sample for the use of the customer/party mentioned in the report. Ultra Plus Lubes Pvt. Ltd. will not be responsible for any liabilities for damage caused due to the use of information contained in this report by a third party.',
        
        // Abbreviations
        'abbreviations' => [
            'N.S.' => 'Not Specified',
            'N.P.' => 'Not Possible',
            'S.I.' => 'Sample Insufficient',
            'U.P.' => 'Under Process'
        ]
    ];

   // $pdf = Pdf::loadView('reports.my_report2_pdf', compact('report'));
    $pdf = Pdf::loadView('reports.my_report_pdf_3', compact('report'));


    return $pdf->stream('sales_report_' . Carbon::now()->format('YmdHis') . '.pdf');
    }
}
