<?php

namespace App\Http\Controllers;

use App\Models\{EquipmentMaster, EquipmentAssignment, EquipmentComponent,MakeModelMaster};
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
class EquipmentMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = EquipmentMaster::query();
       
        // Filter by status if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('equipment_name', 'like', "%{$search}%");
            });
        }
        
        // Order by created_at desc by default
        $equipmentMasters = $query->orderBy('created_at', 'desc')->paginate(15);
       

       // dd($equipmentMasters);
        return view('masters.equipment-masters.index', compact('equipmentMasters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $make_models = MakeModelMaster::all();
        return view('masters.equipment-masters.create',compact('make_models'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // dd($request->all());
    /*   "equipment_name" => "BullDozer"
        "serial_number" => "BF23"
        "make_model_id" => "1"
        "status" => "1" */
        $validated = $request->validate([
            'equipment_name' => 'required|string',
            'serial_number' => 'required|string|unique:equipment_masters,serial_number',
           "make_model_id" => 'integer',
           'status' => 'required|in:1,0'
         ]);

         try {
             // $data = $request->validate();
             EquipmentMaster::create($validated);
             
              return redirect()->route('equipment-masters.index')
                             ->with('success', 'Make EquipmentMaster created successfully!');
          } catch (\Exception $e) {
              
              return redirect()->back()
                             ->withInput()
                             ->with('error', 'Error creating EquipmentMaster: ' . $e->getMessage());
          }    
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentMaster $equipmentMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentMaster $equipmentMaster)
    {
        $make_models = MakeModelMaster::all();
        return view('masters.equipment-masters.edit',compact('make_models','equipmentMaster'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentMaster $equipmentMaster)
    {
        $validated = $request->validate([
            'equipment_name' => 'required|string',
            'serial_number' => 'required|string|unique:equipment_masters,serial_number,'.$equipmentMaster->id,
           "make_model_id" => 'integer',
           'status' => 'required|in:1,0'
         ]);

         try {
             // $data = $request->validate();
            // EquipmentMaster::create($validated);
             $equipmentMaster->update($validated);
              return redirect()->route('equipment-masters.index')
                             ->with('success', 'Make EquipmentMaster updated successfully!');
          } catch (\Exception $e) {
              
              return redirect()->back()
                             ->withInput()
                             ->with('error', 'Error updating EquipmentMaster: ' . $e->getMessage());
          }   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentMaster $equipmentMaster)
    {
        //
    }

    public function ajaxSaveEquipmentAndMore(Request $request)
    {
        /*




make_model_id - New
new_make_name - KIA Motors
new_model_name - K1000
equipment_name - VAN
serial_number - MH12-4523
customer_site_equipment_name - KG Van
customer_id - 235
customer_site_id - 8
*/
//print_r($request->all());
       $userid = Auth::user()->id;
        if($request->make_model_id == 'New') {
            if($request->make_id == 'New') {
                $make = $request->new_make_name;
            }else {
                $make = $request->make_id;
            }     

            $array = array('model' => $request->new_model_name,'status'=> 1,'make' => $make);
            $mmm = MakeModelMaster::create($array);
            $make_model_id =$mmm->id;
        } else {
            $make_model_id = $request->make_model_id;
        }
       // 
       // createEquipment()
       $em = array('equipment_name' => $request->equipment_name , 'make_model_id' => $make_model_id, 'serial_number' => ($request->serial_number??NULL), 'status'=>1);
       $equipmentMaster = EquipmentMaster::create($em);

       $ea = array('equipment_id' => $equipmentMaster->id , 'company_id', 'customer_id' => $request->customer_id, 'customer_site_id' => $request->customer_site_id, 'customer_site_equiment_name' => $request->customer_site_equipment_name ,  'status' => 1 , 'createdby_id' =>$userid );
       $equip = EquipmentAssignment::create($ea);
$data = array('id' => $equip->id, 'name'=>$request->customer_site_equipment_name, 'customer_site_equiment_name'=>$request->customer_site_equipment_name );
       return response()->json([
                'success' => true,
                'equipment' =>$data,
                
            ]);
    }   
}
