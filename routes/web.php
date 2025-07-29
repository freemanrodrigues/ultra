<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BottleTypeController,BrandMasterController, CompanyMasterController, CourierMasterController,CustomerMasterController,EquipmentMasterController, FerrographyMasterController,GradeMasterController, ItemMasterController,MakeMstController, SampleController, SampleDetailController, SampleNatureController,SampleTypeController, StateController,SiteMasterController,SiteMachineDetailController,SubAssemblyController,UnitMasterController,UserController};


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

Route::get('/blank', function () {
    return view('blank');
});

Route::get('/blank1', function () {
    return view('blank1');
});
   
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

    // setup process
Route::get('/masters/customer/setup-customer', [CustomerMasterController::class,'setupCustomer'])->name('customer.setup-customer');
Route::post('/masters/customer/save-customer', [CustomerMasterController::class,'saveCustomer'])->name('customer.save-customer');
Route::get('/masters/user/setup-user', [UserController::class,'setupUser'])->name('user.setup-user');
Route::post('/masters/user/save-user', [UserController::class,'saveUser'])->name('user.save-user');

Route::get('/masters/site-masters/setup-sitemaster', [SiteMasterController::class,'setupSitemaster'])->name('site-masters.setup-sitemaster');
Route::post('/masters/site-masters/save-sitemaster', [SiteMasterController::class,'saveSitemaster'])->name('site-masters.save-sitemaster');



// Ajax
Route::any('/ajax/check-gst', [CompanyMasterController::class,'checkGST'])->name('check.gst');
Route::any('/ajax/get-state', [StateController::class,'checkGST'])->name('get-state');


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
       


Route::get('/master/site-device-list/{id}', [SiteMachineDetailController::class,'deviceBySiteMaster'])->name('site-device-list');

Route::resource('/master/site-master-device', SiteMachineDetailController::class);
         
     
Route::resource('/master/brand', BrandMasterController::class);
Route::any('/master/brand/bulk_delete', [BrandMasterController::class,'bulkDelete'])->name('brand.bulk_delete');

Route::resource('/masters/equipment-masters', EquipmentMasterController::class);
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

Route::resource('/sample', SampleController::class);
Route::any('/sample-copy', [SampleController::class,'sampleCopy'])->name('sample-copy');

Route::resource('/masters/courier', CourierMasterController::class);
Route::any('/master/courier/bulk_delete', [CourierMasterController::class,'bulkDelete'])->name('courier.bulk_delete');
//Route::get('/masters/customer/create-customer', [CustomerMasterController::class,'createCustomer'])->name('customer.create-customer');
Route::any('/ajax-get-customer-address', [CustomerMasterController::class,'getCustomerAddress'])->name('ajax-get-customer-address');
Route::any('/ajax-get-site-contact-details', [UserController::class,'getSiteContactDetails'])->name('ajax-get-site-contact-details');

Route::resource('/masters/customer', CustomerMasterController::class);
Route::any('/master/customer/bulk_delete', [CustomerMasterController::class,'bulkDelete'])->name('customer.bulk_delete');


Route::resource('/masters/make', MakeMstController::class);
Route::any('/master/make/bulk_delete', [MakeMstController::class,'bulkDelete'])->name('make.bulk_delete');


Route::resource('/masters/subassembly', SubAssemblyController::class);
Route::any('/master/subassembly/bulk_delete', [SubAssemblyController::class,'bulkDelete'])->name('subassembly.bulk_delete');


Route::resource('/masters/item', ItemMasterController::class);
Route::any('/master/item/bulk_delete', [ItemMasterController::class,'bulkDelete'])->name('item.bulk_delete');

// Sample details
Route::get('/sample-details/{id}', [SampleDetailController::class,'addSampleDetials'])->name('sample-details');
Route::post('/sample-details/save', [SampleDetailController::class,'saveSampleDetials'])->name('save-sample-details');






});