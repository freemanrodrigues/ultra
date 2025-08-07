<?php

namespace App\Http\Controllers;

use App\Models\{ContactAssignment,CustomerMaster,ContactMaster,CustomerSiteMaster};
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
class ContactAssignmentController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function contactAssignment():view
    {
        //dd($_GET['customer_id']);
        $customer_id = $_GET['customer_id'];
        $customers = CustomerMaster::getCountryId($customer_id);
        $company_id = $customers[0]->company_id;

        $contacts = ContactMaster::where('company_id', $company_id)->get();

        return view('masters.contact-assignments.contact-assignment',compact('contacts'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    /*    contact_id	company_id	customer_id	customer_site_id	equipment_id	department	designation	role	level	send_bill	send_report	whatsapp	is_primary	
*/

        $site_id = $request->site_id;
        $customers = CustomerSiteMaster::where('id', $site_id)->get();
       // dd($customers[0]->site_master_id);
       foreach($request['counter'] as $k => $v) {
       // echo "<br>$k => $v";
        $send_email = '';
        $contact_id = '';
        if(!empty($request['send_email'][$k])) {
             $send_email = TRUE;
             $contact_id = $request['send_email'][$k];
            
        }
        $send_report = '';
        if(!empty($request['send_report'][$k])) {
            $send_report = TRUE;
            $contact_id = $request['send_report'][$k];
        }
        $whatsapp = '';
        if(!empty($request['whatsapp'][$k])) {
            $whatsapp = TRUE;
            $contact_id = $request['whatsapp'][$k];
        }
         //  echo "<br> $contact_id | ".$send_email." --  ".$send_report." --  ".$whatsapp." --  ";
          
           if($contact_id != '') {
            /*
          $ca = new contactAssignment();
          $ca->contact_id = $contact_id;
          $ca->company_id =  $customers[0]->company_id;
          $ca->customer_id =  $customers[0]->customer_id;
          $ca->customer_site_id = $customers[0]->site_master_id; 
        //  $ca->equipment_id = 
        //  $ca->department = 
        // $ca->designation = 
        //  role = 
        //  level = 
        $ca->send_bill = (bool) ($send_email ?? false);
        $ca->send_report = (bool) ($send_report??false);
        $ca->whatsapp = (bool) ($whatsapp??false); 
        //  is_primary
          $ca->save();   */
          contactAssignment::updateOrCreate(
            [
                'contact_id' => $contact_id,
                'customer_site_id' => $customers[0]->site_master_id,
                'equipment_id' => $equipment_id ?? null,
            ],
            [
                'company_id' => $customers[0]->company_id,
                'customer_id' => $customers[0]->customer_id,
                'send_bill' => (bool) ($send_email ?? false),
                'send_report' => (bool) ($send_report ?? false),
                'whatsapp' => (bool) ($whatsapp ?? false),
                'is_primary' => (bool) ($is_primary ?? false),
            ]
        );
          }
          
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactAssignment $contactAssignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactAssignment $contactAssignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactAssignment $contactAssignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactAssignment $contactAssignment)
    {
        //
    }

    public function getAssignedContacts(Request $request)
    {
     /*   $request->validate([
            'company_id' => 'required|integer',
        ]);
       */
        //$data = ContactAssignment::where('customer_site_id', $request->site_id)->get();

        $results = DB::table('contact_assignments as ca')
        ->join('contact_masters as cm', 'ca.contact_id', '=', 'cm.id')
        ->where('ca.customer_site_id', $request->site_id)
        ->select(
            DB::raw("CONCAT(cm.firstname, ' ', cm.lastname) AS full_name"),
            'ca.send_bill',
            'ca.send_report',
            'ca.whatsapp',
            'ca.is_primary'
        )
        ->get();
        if ($results) {
            return response()->json($results);
        }
        return response()->json();
    }
    
}
