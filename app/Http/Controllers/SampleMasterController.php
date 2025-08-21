<?php

namespace App\Http\Controllers;

use App\Models\{CourierMaster,CompanyMaster,CustomerMaster,SampleMaster};
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;
use Pdf; // Use the Pdf Facade
use Carbon\Carbon; // For date handling

class SampleMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $samples = SampleMaster::all();
        $courier_mst = CourierMaster::getCourierArray();
        $company_mst = CompanyMaster::getCompanyArray();
        $customer_mst = CustomerMaster::getCustomerArray();
      //  $sitemaster = SiteMaster::getSiteMasterArray();
       // $users = User::getUserArray();
       $users = array(1 =>'Ram');
       $sitemaster = array(1 =>'Solapur');
        return view('sample-list',compact('samples','courier_mst','company_mst','customer_mst','users','sitemaster'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courier_mst = CourierMaster::getCourierArray();
     //$courier_mst = array(1=>'Blue Dart',2=>'India Post',3=>'Hand Delivery');
        
        $customer_mst = CustomerMaster::getCustomerArray();
        
        return view('sample-form',compact('courier_mst','customer_mst'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SampleMaster $sampleMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SampleMaster $sampleMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SampleMaster $sampleMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SampleMaster $sampleMaster)
    {
        //
    }
}
