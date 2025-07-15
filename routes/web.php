<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BottleTypeController,BrandMasterController, CompanyMasterController, CourierMasterController,CustomerMasterController,EquipmentMasterController, FerrographyMasterController,GradeMasterController, ItemMasterController,MakeMstController, SampleController, SampleNatureController,SampleTypeController,SiteMasterController,SubAssemblyController,UnitMasterController,UserController};


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

   
Route::get('/dashboard', function () {  return view('dashboard');})->name('dashboard');;
Route::get('login', [UserController::class,'loginHtml'])->name('login');;
Route::get('logout', [UserController::class,'logout'])->name('logout');;
Route::get('register', [UserController::class,'registerHtml'])->name('register');;
Route::post('register-user', [UserController::class,'registerUser'])->name('register-user');
Route::get('registersuccess', [UserController::class,'registerSuccess'])->name('register-success');
Route::post('verify', [UserController::class,'verifyUser'])->name('verify-login');
Route::get('reset-password', [UserController::class,'resetPasswordView'])->name('reset-password');
Route::post('reset-password-update', [UserController::class,'updateResetPassword'])->name('reset-password.update');
Route::get('forgot-password', [UserController::class,'forgotPasswordView'])->name('reset-password');
Route::post('forgot-password-update', [UserController::class,'updateForgotPassword'])->name('forgot-password.update');
Route::any('/check-gst', [CompanyMasterController::class,'checkGST'])->name('check.gst');

Route::group(['middleware' => ['loginAuth']], function () {
//Route::middleware([loginAuth::class])->group(function () {
//Route::resource('/master/state', SiteMasterController::class);
Route::resource('/master/users', UserController::class);


Route::any('/master/site-masters/assign-users/{id}', [SiteMasterController::class,'assignUsers'])->name('site-masters.assign-users');  
Route::any('/master/site-masters/save-assign-users', [SiteMasterController::class, 'saveAssignUsers'])->name('site-masters.save-assign-users');     
Route::resource('/master/site-masters', SiteMasterController::class);
Route::patch('site-masters/{siteMaster}/toggle-status', [SiteMasterController::class, 'toggleStatus'])
         ->name('site-masters.toggle-status');
       
 
     
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

Route::resource('/masters/bottle-type', BottleTypeController::class);
Route::resource('/masters/grade', GradeMasterController::class);
Route::any('/master/grade/bulk_delete', [GradeMasterController::class,'bulkDelete'])->name('grade.bulk_delete');
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

});