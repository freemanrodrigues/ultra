<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BottleTypeController, CompanyMasterController, ContactMasterController, ContactAssignmentController,CourierMasterController,CustomerMasterController,CustomerSiteMasterController,EquipmentMasterController,EquipmentAssignmentController,EquipmentComponentController, MakeMasterController,MakeModelMasterController,ModelMasterController, POMasterController,POTestLineController, SampleMasterController, SampleDetailController, SampleNatureController,SampleOilTypeController,SampleTypeController, StateController,SiteMasterController,TestMasterController,UserController};


Route::get('/', function () {
    if (Auth::check()) { 
         return view('dashboard');
    } else{ return view('login');}
    
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
Route::any('/ajax/save-equipment-n-more', [EquipmentMasterController::class,'ajaxSaveEquipmentAndMore'])->name('save-equipment-n-more');
Route::any('/ajax/get-customer-address', [SampleMasterController::class,'getCustomerAddress'])->name('get-customer-address');

//Route::resource('/master/state', SiteMasterController::class);
Route::get('profile', [UserController::class,'userProfile'])->name('profile');
Route::get('reset-password', [UserController::class,'resetPasswordView'])->name('reset-password');
Route::post('reset-password-update', [UserController::class,'updateResetPassword'])->name('reset-password.update');
Route::resource('/masters/users', UserController::class);


Route::any('/masters/site-masters/assign-users/{id}', [SiteMasterController::class,'assignUsers'])->name('site-masters.assign-users');  
Route::any('/masters/site-masters/save-assign-users', [SiteMasterController::class, 'saveAssignUsers'])->name('site-masters.save-assign-users');     
Route::resource('/masters/site-masters', SiteMasterController::class);
Route::patch('site-masters/{siteMaster}/toggle-status', [SiteMasterController::class, 'toggleStatus'])
         ->name('site-masters.toggle-status');

Route::any('/masters/customer-site-masters/assign-contact/{id}', [CustomerSiteMasterController::class,'assignContact'])->name('customer-site-masters.assign-contact');  
Route::any('/masters/customer-site-masters/save-assign-contact', [CustomerSiteMasterController::class, 'saveAssignContact'])->name('customer-site-masters.save-assign-contact'); 
Route::get('/masters/customer-site-masters/export-csv', [CustomerSiteMasterController::class, 'exportCsv'])->name('customer-site-masters.export-csv');
Route::resource('/masters/customer-site-masters', CustomerSiteMasterController::class);

Route::patch('/masters/make-model-masters/toggle-status', [MakeModelMasterController::class, 'toggleStatus'])->name('make-model-masters.toggle-status');
Route::resource('/masters/make-model-masters', MakeModelMasterController::class);

Route::resource('/masters/equipment-masters', EquipmentMasterController::class);




Route::resource('/masters/equipment-assignments', EquipmentAssignmentController::class);
Route::resource('/masters/equipment-components', EquipmentComponentController::class);

Route::resource('/masters/po', POMasterController::class);
Route::resource('/masters/po-test', POTestLineController::class);

// API route for getting customer sites
Route::get('/api/customers/{customerId}/sites', [POMasterController::class, 'getCustomerSites'])->name('api.customer.sites');
Route::get('/api/sample-type-rates', [POMasterController::class, 'getSampleTypeRates'])->name('api.sample-type-rates');

Route::any('/masters/assign-contact-assignments', [ContactAssignmentController::class,'contactAssignment'])->name('assign-contact-assignments');

Route::get('/masters/contacts-masters/search', [ContactMasterController::class,'search'])->name('contacts-masters.search');
Route::resource('/masters/contacts-masters', ContactMasterController::class);
Route::resource('/masters/contact-assignments', ContactAssignmentController::class);     




Route::resource('/masters/sample-nature', SampleNatureController::class);
Route::any('/masters/ferrography/bulk_delete', [SampleNatureController::class,'bulkDelete'])->name('sample-nature.bulk_delete');

Route::any('/masters/bottle-type/bulk_delete', [BottleTypeController::class,'bulkDelete'])->name('bottle-type.bulk_delete');
Route::resource('/masters/bottle-type', BottleTypeController::class);


Route::get('/generate-sales-report', [SampleMasterController::class, 'generateSalesReport'])->name('reports.sales');
Route::get('/generate-sales-report1', [SampleMasterController::class, 'generateSalesReport1'])->name('reports.sales1');
Route::resource('/masters/sample-oil-type', SampleOilTypeController::class);
Route::any('/masters/sample-oil-type/bulk_delete', [SampleOilTypeController::class,'bulkDelete'])->name('sample-oil-type.bulk_delete');
Route::resource('/masters/sample-type', SampleTypeController::class);
Route::any('/masters/sample-type/bulk_delete', [SampleTypeController::class,'bulkDelete'])->name('sample-type.bulk_delete');

Route::resource('/masters/test', TestMasterController::class);
Route::any('/masters/test/bulk_delete', [TestMasterController::class,'bulkDelete'])->name('test.bulk_delete');

Route::resource('/sample', SampleMasterController::class);
Route::any('/sample-copy', [SampleMasterController::class,'sampleCopy'])->name('sample-copy');

Route::resource('/masters/courier', CourierMasterController::class);
Route::any('/masters/courier/bulk_delete', [CourierMasterController::class,'bulkDelete'])->name('courier.bulk_delete');
//Route::get('/masters/customer/create-customer', [CustomerMasterController::class,'createCustomer'])->name('customer.create-customer');
Route::any('/ajax-get-customer-address', [CustomerMasterController::class,'getCustomerAddress'])->name('ajax-get-customer-address');

Route::any('/ajax-get-customer-sites', [CustomerSiteMasterController::class,'getCustomerSites'])->name('ajax-get-customer-sites');
Route::get('/ajax/company-sites/{id}', [CustomerSiteMasterController::class,'getSitesByCompany'])->name('company-sites');
Route::any('/ajax-get-site-contact-details', [UserController::class,'getSiteContactDetails'])->name('ajax-get-site-contact-details');

Route::any('/ajax/get-contacts', [ContactMasterController::class,'getContacts'])->name('ajax-get-contacts');
Route::any('/ajax/get-assigned-contact', [ContactAssignmentController::class,'getAssignedContacts'])->name('ajax-get-contacts');

Route::get('/masters/customer/search', [CustomerMasterController::class,'search'])->name('customer.search');

Route::resource('/masters/customer', CustomerMasterController::class);
Route::any('/masters/customer/bulk_delete', [CustomerMasterController::class,'bulk_delete'])->name('customer.bulk_delete');


Route::resource('/masters/make', MakeMasterController::class);
Route::any('/masters/make/bulk_delete', [MakeMasterController::class,'bulkDelete'])->name('make.bulk_delete');


Route::resource('/masters/model', ModelMasterController::class);
Route::any('/masters/model/bulk_delete', [ModelMasterController::class,'bulkDelete'])->name('model.bulk_delete');



// Sample details
Route::get('/sample-details_x/{id}', [SampleDetailController::class,'addSampleDetialsX'])->name('sample-details');
// Sample details
Route::get('/sample-details/{id}', [SampleDetailController::class,'addSampleDetials'])->name('sample-details');
Route::post('/sample-details/save', [SampleDetailController::class,'saveSampleDetials'])->name('save-sample-details');






});
