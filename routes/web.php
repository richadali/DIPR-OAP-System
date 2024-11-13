<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\BillsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportsController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/password-reset', [PasswordResetContoller::class, 'index'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('password-reset');

Route::post('/user-reset-password', [PasswordResetContoller::class, 'ResetPassword'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('user-reset-password');

Route::get('/auth/google', [LoginController::class, 'redirectToProvider']);
Route::get('/auth/google/callback', [LoginController::class, 'handleProviderCallback']);

Route::middleware('auth')->group(function () {


    Route::get('/home', [HomeController::class, 'index'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('home');

    Route::post('view-data', [TableContoller::class, 'ViewContent'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('view-data');

    Route::post('store-data', [TableContoller::class, 'StoreData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('store-data');

    Route::post('show-data', [TableContoller::class, 'ShowData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('show-data');

    Route::post('delete-data', [TableContoller::class, 'DeleteData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('delete-data');

    Route::post('change-active', [TableContoller::class, 'ChangeActive'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('change-active');

    //ADMIN ROUTES

    Route::get('/admin', [AdminController::class, 'index'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('admin');

    Route::post('admin-view-data', [AdminController::class, 'ViewContent'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('admin-view-data');

    Route::post('/admin-store-data', [AdminController::class, 'StoreData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('admin-store-data');

    Route::post('admin-show-data', [AdminController::class, 'ShowData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('admin-show-data');

    Route::post('admin-delete-data', [AdminController::class, 'DeleteData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('admin-data');

    //USER ROUTES

    Route::get('/user', [UserController::class, 'index'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('user');

    Route::post('user-view-data', [UserController::class, 'ViewContent'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('user-view-data');

    Route::post('/user-store-data', [UserController::class, 'StoreData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('user-store-data');

    Route::post('user-show-data', [UserController::class, 'ShowData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('user-show-data');

    Route::post('user-delete-data', [UserController::class, 'DeleteData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('user-data');

    // MASTER DATA EMPANELLED NEWSPAPER
    Route::get('/master-data/empanelled-newspaper', [MasterDataController::class, 'newspaper_index'])
        ->name('/master-data/empanelled-newspaper');

    Route::post('empanelled-view-data', [MasterDataController::class, 'ViewNewspaperContent'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('empanelled-view-data');

    Route::post('/empanelled-store-data', [MasterDataController::class, 'StoreNewspaperData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('empanelled-store-data');

    Route::post('empanelled-show-data', [MasterDataController::class, 'ShowNewspaperData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('empanelled-show-data');

    Route::post('empanelled-delete-data', [MasterDataController::class, 'DeleteNewspaperData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('empanelled-data');

    // MASTER DATA SUBJECT TYPE

    Route::get('/master-data/subject', [MasterDataController::class, 'subject_index'])
        ->name('/master-data/subject');

    Route::post('subject-view-data', [MasterDataController::class, 'ViewSubjectContent'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('subject-view-data');

    Route::post('/subject-store-data', [MasterDataController::class, 'StoreSubjectData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('subject-store-data');

    Route::post('subject-show-data', [MasterDataController::class, 'ShowSubjectData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('subject-show-data');

    Route::post('subject-delete-data', [MasterDataController::class, 'DeleteSubjectData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('subject-data');

    // MASTER DATA ADVERTISEMENT TYPE

    Route::get('/master-data/advertisement-types', [MasterDataController::class, 'advertisementTypesIndex'])
        ->name('/master-data/advertisement-types');

    Route::post('advertisement-types-view-data', [MasterDataController::class, 'ViewAdvertisementTypes'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('advertisement-types-view-data');

    Route::post('advertisement-types-store-data', [MasterDataController::class, 'StoreAdvertisementTypes'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('advertisement-types-store-data');

    Route::post('advertisement-types-show-data', [MasterDataController::class, 'ShowAdvertisementTypes'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('advertisement-types-show-data');

    Route::post('advertisement-types-delete-data', [MasterDataController::class, 'DeleteAdvertisementTypes'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('advertisement-types-delete-data');

    // MASTER DATA rates-for-advertisements

    Route::get('/master-data/rates-for-advertisements', [MasterDataController::class, 'rates_index'])
        ->name('/master-data/rates-for-advertisements');

    Route::post('rates-view-data', [MasterDataController::class, 'ViewRatesContent'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('rates-view-data');

    Route::post('rates-store-data', [MasterDataController::class, 'StoreRatesData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('rates-store-data');

    Route::post('rates-show-data', [MasterDataController::class, 'ShowRatesData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('rates-show-data');

    Route::post('rates-delete-data', [MasterDataController::class, 'DeleteRatesData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('rates-data');

    //MASTER DATA NEWSPAPER TYPES

    Route::get('/master-data/newspaper_types', [MasterDataController::class, 'newspaper_types_index'])
        ->name('/master-data/newspaper_types');

    Route::post('newspaper_types-view-data', [MasterDataController::class, 'ViewNewspaperTypesContent'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('newspaper_types-view-data');

    Route::post('newspaper_types-store-data', [MasterDataController::class, 'StoreNewspaperTypesData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('newspaper_types-store-data');

    Route::post('newspaper_types-show-data', [MasterDataController::class, 'ShowNewspaperTypesData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('newspaper_types-show-data');

    Route::post('newspaper_types_delete', [MasterDataController::class, 'DeleteNewspaperTypesData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('newspaper-delete');


    // MASTER DATA COLOR TYPE

    Route::get('/master-data/color', [MasterDataController::class, 'color_index'])
        ->name('/master-data/color');

    Route::post('color-view-data', [MasterDataController::class, 'ViewColorContent'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('color-view-data');

    Route::post('/color-store-data', [MasterDataController::class, 'StoreColorData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('color-store-data');

    Route::post('color-show-data', [MasterDataController::class, 'ShowColorData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('color-show-data');

    Route::post('color-delete-data', [MasterDataController::class, 'DeleteColorData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('color-data');


    // MASTER DATA PAGE INFO

    Route::get('/master-data/page-info', [MasterDataController::class, 'page_info_index'])
        ->name('/master-data/page-info');

    Route::post('page-info-view-data', [MasterDataController::class, 'ViewPageInfoContent'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('page-info-view-data');

    Route::post('/page-info-store-data', [MasterDataController::class, 'StorePageInfoData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('page-info-store-data');

    Route::post('page-info-show-data', [MasterDataController::class, 'ShowPageInfoData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('page-info-show-data');

    Route::post('page-info-delete-data', [MasterDataController::class, 'DeletePageInfoData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('page-info-data');

    // MASTER DATA MIPR-NO    

    Route::get('/get-current-mipr', [AdvertisementController::class, 'getCurrentMIPRNo'])
        ->name('get-current-mipr');

    Route::get('/master-data/mipr-no', [MasterDataController::class, 'miprNoIndex'])
        ->name('/master-data/mipr-no');

    Route::post('mipr-no-view-data', [MasterDataController::class, 'ViewMiprNo'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('mipr-no-view-data');

    Route::post('mipr-no-store-data', [MasterDataController::class, 'StoreMiprNo'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('mipr-no-store-data');

    Route::post('mipr-no-show-data', [MasterDataController::class, 'ShowMiprNo'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('mipr-no-show-data');


    // MASTER DATA MIPR-FILE-NO    

    Route::get('/master-data/mipr-file-no', [MasterDataController::class, 'miprFileNoIndex'])
        ->name('/master-data/mipr-file-no');

    Route::post('mipr-file-no-view-data', [MasterDataController::class, 'ViewMiprFileNo'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('mipr-file-no-view-data');

    Route::post('mipr-file-no-store-data', [MasterDataController::class, 'StoreMiprFileNo'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('mipr-file-no-store-data');

    Route::post('mipr-file-no-show-data', [MasterDataController::class, 'ShowMiprFileNo'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('mipr-file-no-show-data');


    // MASTER DATA DEPARTMENT CATEGORY
    Route::get('/master-data/department-category', [MasterDataController::class, 'departmentCategoryIndex'])
        ->name('/master-data/department-category');


    Route::post('department-category-view-data', [MasterDataController::class, 'viewDepartmentCategoryData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('department-category-view-data');

    Route::post('/department-category-store-data', [MasterDataController::class, 'storeDepartmentCategoryData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('department-category-store-data');

    Route::post('department-category-show-data', [MasterDataController::class, 'showDepartmentCategoryData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('department-category-show-data');

    Route::post('department-category-delete-data', [MasterDataController::class, 'deleteDepartmentCategoryData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('department-category-delete-data');


    // MASTER DATA DEPARTMENTS

    Route::post('/get-departments-by-category', [MasterDataController::class, 'getDepartmentsByCategory'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('get-departments-by-category');

    Route::get('/master-data/departments', [MasterDataController::class, 'departmentIndex'])
        ->name('/master-data/departments');

    Route::post('/department-view-data', [MasterDataController::class, 'viewDepartmentData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('department-view-data');

    Route::post('/department-store-data', [MasterDataController::class, 'storeDepartmentData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('department-store-data');

    Route::post('/department-show-data', [MasterDataController::class, 'showDepartmentData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('department-show-data');

    Route::post('/department-delete-data', [MasterDataController::class, 'deleteDepartmentData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('department-delete-data');


    // MASTER DATA GST-RATES

    Route::get('/master-data/gst-rates', [MasterDataController::class, 'gstRatesIndex'])
        ->name('/master-data/gst-rates');

    Route::post('/gst-rates-view-data', [MasterDataController::class, 'viewGstRatesData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('gst-rates-view-data');

    Route::post('/gst-rates-store-data', [MasterDataController::class, 'storeGstRatesData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('gst-rates-store-data');

    Route::post('/gst-rates-show-data', [MasterDataController::class, 'showGstRatesData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('gst-rates-show-data');

    Route::post('/gst-rates-delete-data', [MasterDataController::class, 'deleteGstRatesData'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('gst-rates-delete-data');



    // ADVERTISEMENTS 

    Route::get('advertisements', [UserController::class, 'advertisements'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('advertisements');

    Route::post('advertisement-view-data', [AdvertisementController::class, 'ViewContent'])
        ->middleware('cache.headers')
        ->middleware('throttle')
        ->name('advertisement-view-data');

    Route::post('/advertisement-store-data', [AdvertisementController::class, 'StoreData'])
        ->name('/advertisements/add');

    Route::post('/advertisement-edit-data', [AdvertisementController::class, 'ShowData'])
        ->name('editAdvertisement');

    Route::post('/advertisement-delete-data', [AdvertisementController::class, 'DeleteData'])
        ->name('deleteAdvertisement');

    Route::post('/get-newspapers-by-type', [AdvertisementController::class, 'getNewspapersByType'])
        ->name('get-newspapers-by-type');

    Route::post('/update-advertisement-publish-status', [AdvertisementController::class, 'updateAdvertisementStatus']);

    Route::get('/advertisement/{id}', [AdvertisementController::class, 'getAdvertisementDetails']);
});

Route::post('getAmount', [AdvertisementController::class, 'getAmount'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('getAmount');


// BILLS 
Route::get('bills', [BillsController::class, 'index'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('bills');

Route::post('bill-view-data', [BillsController::class, 'ViewContent'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('bills-view-data');

Route::post('/bill-get-dept-letter-no', [BillsController::class, 'getDeptLetterNo']);

Route::post('/bill-get-newspaper', [BillsController::class, 'getNewspaper'])
    ->name('getNewspaper');

Route::post('/get_bill_details', [BillsController::class, 'getBillDetails'])
    ->name('getBillDetails');

Route::post('/bill-store-data', [BillsController::class, 'StoreData'])
    ->name('/bill/add');

Route::post('/bill-edit-data', [BillsController::class, 'ShowData'])
    ->name('editBill');

Route::post('/bill-delete-data', [BillsController::class, 'DeleteData'])
    ->name('deleteBill');

Route::post('/bill-get-amount', [BillsController::class, 'getAmount']);

// REPORT ROUTES

Route::get('/reports/release_order/{id}', [ReportsController::class, 'releaseOrder'])
    ->name('/reports/release_order');

//issue register
Route::get('/reports/issue-register', [ReportsController::class, 'indexIssueRegister'])
    ->name('/reports/issue-register');

Route::post('issue_register-view-data', [ReportsController::class, 'ViewIssueRegister'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('issue_register-view-data');

Route::post('get_issue_register', [ReportsController::class, 'GetIssueRegister'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('get_issue_register');

Route::get('/reports/print_issue_register/{from}/{to}', [ReportsController::class, 'printIssueRegister'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('/reports/print_issue_register');

Route::get('/reports/export_issue_register/{from}/{to}', [ReportsController::class, 'exportIssueRegisterToExcel'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('reports.export_issue_register');


//BILLING register
Route::get('/reports/billing-register', [ReportsController::class, 'indexBillingRegister'])
    ->name('/reports/billing-register');

Route::post('billing_register-view-data', [ReportsController::class, 'ViewBillingRegister'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('billing_register-view-data');

Route::post('get_billing_register', [ReportsController::class, 'GetBillingRegister'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('get_billing_register');

Route::get('/reports/print_billing_register/{from}/{to}', [ReportsController::class, 'printBillingRegister'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('/reports/print_billing_register');

Route::get('/reports/export_billing_register/{from?}/{to?}/{department?}/{newspaper?}', [ReportsController::class, 'exportBillingRegisterToExcel'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('reports.export_billing_register');


//BILLS not_paid_by_dipr

Route::get('reports/not_paid_by_dipr', [ReportsController::class, 'indexNonDIPRRegister'])
    ->name('reports/not_paid_by_dipr');

Route::post('nonDIPR_register-view-data', [ReportsController::class, 'ViewNonDIPRRegister'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('nonDIPR_register-view-data');

Route::post('get_nonDIPR_register', [ReportsController::class, 'GetNonDIPRRegister'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('get_nonDIPR_register');

Route::get('/reports/print_nonDIPR_register/{from}/{to}', [ReportsController::class, 'printNonDIPRRegister'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('/reports/print_nonDIPR_register');

Route::get('/reports/export_nonDIPR_register/{from?}/{to?}/{department?}/{newspaper?}', [ReportsController::class, 'exportNonDIPRRegisterToExcel'])
    ->middleware('cache.headers')
    ->middleware('throttle')
    ->name('reports.export_nonDIPR_register');

//Forwarding Letter

Route::get('/reports/forwarding_letter/{id}', [ReportsController::class, 'forwardingLetter'])
    ->name('/reports/forwarding_letter');

Route::get('/reports/detailed-expenditure-report', [ReportsController::class, 'detailedExpenditureReport'])
    ->name('/reports/detailed-expenditure-report');

Route::get('/reports/bills-not-paid-by-dipr', [ReportsController::class, 'billsNotPaidByDIPR'])
    ->name('/reports/bills-not-paid-by-dipr');

Route::get('/read', function () {
    return DB::SELECT('SELECT * from public.users');
});




Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
