<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BottleTypeController,BrandMasterController, CompanyMasterController, ContactMasterController, ContactAssignmentController,CourierMasterController,CustomerMasterController,CustomerSiteMasterController,EquipmentMasterController,EquipmentAssignmentController,EquipmentComponentController, FerrographyMasterController,GradeMasterController, ItemMasterController,MakeMasterController,MakeModelMasterController,ModelMasterController, SampleMasterController, SampleDetailController, SampleNatureController,SampleTypeController, StateController,SiteMasterController,SiteMachineDetailController,SubAssemblyController,UnitMasterController,UserController};


Route::get('/', function () {
    return view('welcome');
});

// Original file
Route::get('/original', function () {  
    return view('original');
});
Route::get('/test', function () {
    return view('admin');
});
Route::get('/first', function () {
    return view('first');
});
Route::get('/blank', function () {
    return view('blank');
});
Route::get('/sample', function () {
    return view('sample-form');
});

Route::any('/delete', function () {
   return view('blank1');
});
Route::get('testing', [UserController::class,'testing'])->name('testing');
Route::any('/ajax/autosuggest-country', [UserController::class,'getCountryAddress'])->name('autosuggest-country');
/*
Route::get('/blank1', function () {
    return view('blank1');
});
*/
   
Route::get('/dashboard', function () {  return view('dashboard');})->name('dashboard');;
Route::get('login', [UserController::class,'loginHtml'])->name('login');;
Route::get('logout', [UserController::class,'logout'])->name('logout');;
Route::get('register', [UserController::class,'registerHtml'])->name('register');;
Route::post('register-user', [UserController::class,'registerUser'])->name('register-user');
Route::get('registersuccess', [UserController::class,'registerSuccess'])->name('register-success');
Route::post('verify', [UserController::class,'verifyUser'])->name('verify-login');
Route::get('forgot-password', [UserController::class,'forgotPasswordView'])->name('forgot-password');
Route::post('forgot-password-update', [UserController::class,'updateForgotPassword'])->name('forgot-password.update');

Route::group(['middleware' => ['loginAuth']], function () {

 


// Ajax
Route::any('/ajax/check-gst', [CompanyMasterController::class,'checkGST'])->name('check.gst');
Route::post('/ajax/get-state', [StateController::class,'getState'])->name('get-state');
Route::any('/ajax/autosuggest-customer', [CustomerMasterController::class,'autoSuggestCustomer'])->name('autosuggest-customer');

Route::any('/ajax/autosuggest-customer1', [CustomerMasterController::class,'autoSuggestCustomer1'])->name('autosuggest-customer1');

Route::any('/ajax/autosuggest-sitename', [SiteMasterController::class,'autoSuggestSiteName'])->name('autosuggest-sitename');
Route::any('/ajax/autosuggest-companyname', [CompanyMasterController::class,'autoSuggestCompanyName'])->name('autosuggest-companyname');

Route::any('/ajax/autosuggest-make', [MakeMasterController::class,'autoSuggestMakeName'])->name('autosuggest-make');

Route::any('/ajax/list-sitemaster', [SiteMasterController::class,'ajaxListSitemaster'])->name('list-sitemaster');
Route::any('/ajax/list-courier', [CourierMasterController::class,'ajaxListCourier'])->name('list-courier');

//Route::resource('/master/state', SiteMasterController::class);
Route::get('profile', [UserController::class,'userProfile'])->name('profile');
Route::get('reset-password', [UserController::class,'resetPasswordView'])->name('reset-password');
Route::post('reset-password-update', [UserController::class,'updateResetPassword'])->name('reset-password.update');
Route::resource('/master/users', UserController::class);


Route::any('/master/site-masters/assign-users/{id}', [SiteMasterController::class,'assignUsers'])->name('site-masters.assign-users');  
Route::any('/master/site-masters/save-assign-users', [SiteMasterController::class, 'saveAssignUsers'])->name('site-masters.save-assign-users');     
Route::resource('/master/site-masters', SiteMasterController::class);
Route::patch('site-masters/{siteMaster}/toggle-status', [SiteMasterController::class, 'toggleStatus'])
         ->name('site-masters.toggle-status');

Route::any('/master/customer-site-masters/assign-contact/{id}', [CustomerSiteMasterController::class,'assignContact'])->name('customer-site-masters.assign-contact');  
Route::any('/master/customer-site-masters/save-assign-contact', [CustomerSiteMasterController::class, 'saveAssignContact'])->name('customer-site-masters.save-assign-contact'); 
         
Route::resource('/master/customer-site-masters', CustomerSiteMasterController::class);

Route::patch('/masters/make-model-masters/toggle-status', [MakeModelMasterController::class, 'toggleStatus'])->name('make-model-masters.toggle-status');
Route::resource('/master/make-model-masters', MakeModelMasterController::class);

Route::resource('/master/equipment-masters', EquipmentMasterController::class);




Route::resource('/master/equipment-assignments', EquipmentAssignmentController::class);
Route::resource('/master/equipment-components', EquipmentComponentController::class);


Route::get('/master/site-device-list/{id}', [SiteMachineDetailController::class,'deviceBySiteMaster'])->name('site-device-list');

Route::resource('/master/site-master-device', SiteMachineDetailController::class);

Route::any('/master/assign-contact-assignments', [ContactAssignmentController::class,'contactAssignment'])->name('assign-contact-assignments');

Route::resource('/master/contacts-masters', ContactMasterController::class);
Route::resource('/master/contact-assignments', ContactAssignmentController::class);     
Route::resource('/master/brand', BrandMasterController::class);
Route::any('/master/brand/bulk_delete', [BrandMasterController::class,'bulkDelete'])->name('brand.bulk_delete');


Route::resource('/masters/unit-masters', UnitMasterController::class);
Route::patch('/masters/unit-masters/toggle-status', [UnitMasterController::class, 'toggleStatus'])
         ->name('unit-masters.toggle-status');
Route::resource('/masters/ferrography', FerrographyMasterController::class);
Route::any('/master/ferrography/bulk_delete', [FerrographyMasterController::class,'bulkDelete'])->name('ferrography.bulk_delete');
Route::resource('/masters/sample-nature', SampleNatureController::class);
Route::any('/master/ferrography/bulk_delete', [SampleNatureController::class,'bulkDelete'])->name('sample-nature.bulk_delete');

Route::any('/master/bottle-type/bulk_delete', [BottleTypeController::class,'bulkDelete'])->name('bottle-type.bulk_delete');
Route::resource('/masters/bottle-type', BottleTypeController::class);
Route::resource('/masters/grade', GradeMasterController::class);
Route::any('/master/grade/bulk_delete', [GradeMasterController::class,'bulkDelete'])->name('grade.bulk_delete');

Route::get('/generate-sales-report', [SampleController::class, 'generateSalesReport'])->name('reports.sales');
Route::get('/generate-sales-report1', [SampleController::class, 'generateSalesReport1'])->name('reports.sales1');
Route::resource('/masters/sample-type', SampleTypeController::class);
Route::any('/master/sample-type/bulk_delete', [SampleTypeController::class,'bulkDelete'])->name('sample-type.bulk_delete');

Route::resource('/sample', SampleMasterController::class);
Route::any('/sample-copy', [SampleMasterController::class,'sampleCopy'])->name('sample-copy');

Route::resource('/masters/courier', CourierMasterController::class);
Route::any('/master/courier/bulk_delete', [CourierMasterController::class,'bulkDelete'])->name('courier.bulk_delete');
//Route::get('/masters/customer/create-customer', [CustomerMasterController::class,'createCustomer'])->name('customer.create-customer');
Route::any('/ajax-get-customer-address', [CustomerMasterController::class,'getCustomerAddress'])->name('ajax-get-customer-address');

Route::any('/ajax-get-customer-sites', [CustomerSiteMasterController::class,'getCustomerSites'])->name('ajax-get-customer-sites');
Route::any('/ajax-get-site-contact-details', [UserController::class,'getSiteContactDetails'])->name('ajax-get-site-contact-details');

Route::any('/ajax/get-contacts', [ContactMasterController::class,'getContacts'])->name('ajax-get-contacts');
Route::any('/ajax/get-assigned-contact', [ContactAssignmentController::class,'getAssignedContacts'])->name('ajax-get-contacts');

Route::resource('/masters/customer', CustomerMasterController::class);
Route::any('/master/customer/bulk_delete', [CustomerMasterController::class,'bulkDelete'])->name('customer.bulk_delete');


Route::resource('/masters/make', MakeMasterController::class);
Route::any('/master/make/bulk_delete', [MakeMasterController::class,'bulkDelete'])->name('make.bulk_delete');


Route::resource('/masters/model', ModelMasterController::class);
Route::any('/master/model/bulk_delete', [ModelMasterController::class,'bulkDelete'])->name('model.bulk_delete');

Route::resource('/masters/subassembly', SubAssemblyController::class);
Route::any('/master/subassembly/bulk_delete', [SubAssemblyController::class,'bulkDelete'])->name('subassembly.bulk_delete');


Route::resource('/masters/item', ItemMasterController::class);
Route::any('/master/item/bulk_delete', [ItemMasterController::class,'bulkDelete'])->name('item.bulk_delete');

// Sample details
Route::get('/sample-details/{id}', [SampleDetailController::class,'addSampleDetials'])->name('sample-details');
Route::post('/sample-details/save', [SampleDetailController::class,'saveSampleDetials'])->name('save-sample-details');






});