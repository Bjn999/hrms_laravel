<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\Admin_panel_settingsController;
use App\Http\Controllers\admin\Finance_calendersController;
use App\Http\Controllers\admin\BranchesController;
use App\Http\Controllers\admin\DepartmentsController;
use App\Http\Controllers\admin\EmployeesController;
use App\Http\Controllers\admin\jobs_categoriesController;
use App\Http\Controllers\admin\NationalitiesController;
use App\Http\Controllers\Admin\OccasionsController;
use App\Http\Controllers\admin\QualificationsController;
use App\Http\Controllers\admin\ReligionsController;
use App\Http\Controllers\admin\ResignationsController;
use App\Http\Controllers\admin\Shifts_typeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

define('P_C', 11);
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function (){
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');

    // بداية الضبط الجديد
    Route::get('/generalSettings', [Admin_panel_settingsController::class, 'index'])->name('admin_panel_settings.index');
    Route::get('/generalSettingsEdit', [Admin_panel_settingsController::class, 'edit'])->name('admin_panel_settings.edit');
    Route::post('/generalSettingsUpdate', [Admin_panel_settingsController::class, 'update'])->name('admin_panel_settings.update');

    // بداية السنوات المالية
    Route::get('/finance_calenders/delete/{id}', [Finance_calendersController::class, 'destroy'])->name('finance_calenders.delete');
    Route::post('/finance_calenders/show_year_monthes', [Finance_calendersController::class, 'show_year_monthes'])->name('finance_calenders.show_year_monthes');
    Route::get('/finance_calenders/do_open/{id}', [Finance_calendersController::class, 'do_open'])->name('finance_calenders.do_open');
    Route::resource('/finance_calenders', Finance_calendersController::class);

    // بداية الفروع
    Route::get('/branches', [BranchesController::class, 'index'])->name('branches.index');
    Route::get('/branchesCreate', [BranchesController::class, 'create'])->name('branches.create');
    Route::post('/branchesStore', [BranchesController::class, 'store'])->name('branches.store');
    Route::get('/branchesEdit/{id}', [BranchesController::class, 'edit'])->name('branches.edit');
    Route::post('/branchesUpdate/{id}', [BranchesController::class, 'update'])->name('branches.update');
    Route::get('/branchesDelete/{id}', [BranchesController::class, 'destroy'])->name('branches.destroy');

    // بداية أنواع شفتات الموضفين
    Route::get('/shiftTypes', [Shifts_typeController::class, 'index'])->name('shiftstypes.index');
    Route::get('/shiftTypes/create', [Shifts_typeController::class, 'create'])->name('shiftstypes.create');
    Route::post('/shiftTypes/Store', [Shifts_typeController::class, 'store'])->name('shiftstypes.store');
    Route::get('/shiftTypesEdit/{id}', [Shifts_typeController::class, 'edit'])->name('shiftstypes.edit');
    Route::post('/shiftTypesUpdate/{id}', [Shifts_typeController::class, 'update'])->name('shiftstypes.update');
    Route::get('/shiftTypesDelete/{id}', [Shifts_typeController::class, 'destroy'])->name('shiftstypes.destroy');
    Route::post('/shiftTypesSearch', [Shifts_typeController::class, 'ajax_search'])->name('shiftstypes.ajaxSearch');
    
    // بداية الإدارات
    Route::get('/departments', [DepartmentsController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentsController::class, 'create'])->name('departments.create');
    Route::post('/departments/store', [DepartmentsController::class, 'store'])->name('departments.store');
    Route::get('/departmentsEdit/{id}', [DepartmentsController::class, 'edit'])->name('departments.edit');
    Route::post('/departmentsUpdate/{id}', [DepartmentsController::class, 'update'])->name('departments.update');
    Route::get('/departmentsDelete/{id}', [DepartmentsController::class, 'destroy'])->name('departments.destroy');
    
    // بداية فئات الوظائف
    Route::get('/jobs_categories', [jobs_categoriesController::class, 'index'])->name('jobs_categories.index');
    Route::get('/jobs_categories/create', [jobs_categoriesController::class, 'create'])->name('jobs_categories.create');
    Route::post('/jobs_categories/store', [jobs_categoriesController::class, 'store'])->name('jobs_categories.store');
    Route::get('/jobs_categoriesEdit/{id}', [jobs_categoriesController::class, 'edit'])->name('jobs_categories.edit');
    Route::post('/jobs_categoriesUpdate/{id}', [jobs_categoriesController::class, 'update'])->name('jobs_categories.update');
    Route::get('/jobs_categoriesDelete/{id}', [jobs_categoriesController::class, 'destroy'])->name('jobs_categories.destroy');
    
    // بداية مؤهلات الموظفين
    Route::get('/qualifications', [QualificationsController::class, 'index'])->name('qualifications.index');
    Route::get('/qualifications/create', [QualificationsController::class, 'create'])->name('qualifications.create');
    Route::post('/qualifications/store', [QualificationsController::class, 'store'])->name('qualifications.store');
    Route::get('/qualificationsEdit/{id}', [QualificationsController::class, 'edit'])->name('qualifications.edit');
    Route::post('/qualificationsUpdate/{id}', [QualificationsController::class, 'update'])->name('qualifications.update');
    Route::get('/qualificationsDelete/{id}', [QualificationsController::class, 'destroy'])->name('qualifications.destroy');
    
    // بداية المناسبات الرسمية
    Route::get('/occasions', [OccasionsController::class, 'index'])->name('occasions.index');
    Route::get('/occasions/create', [OccasionsController::class, 'create'])->name('occasions.create');
    Route::post('/occasions/store', [OccasionsController::class, 'store'])->name('occasions.store');
    Route::get('/occasionsEdit/{id}', [OccasionsController::class, 'edit'])->name('occasions.edit');
    Route::post('/occasionsUpdate/{id}', [OccasionsController::class, 'update'])->name('occasions.update');
    Route::get('/occasionsDelete/{id}', [OccasionsController::class, 'destroy'])->name('occasions.destroy');
    
    // بداية إنهاء الخدمة
    Route::get('/resignations', [ResignationsController::class, 'index'])->name('resignations.index');
    Route::get('/resignations/create', [ResignationsController::class, 'create'])->name('resignations.create');
    Route::post('/resignations/store', [ResignationsController::class, 'store'])->name('resignations.store');
    Route::get('/resignationsEdit/{id}', [ResignationsController::class, 'edit'])->name('resignations.edit');
    Route::post('/resignationsUpdate/{id}', [ResignationsController::class, 'update'])->name('resignations.update');
    Route::get('/resignationsDelete/{id}', [ResignationsController::class, 'destroy'])->name('resignations.destroy');
    
    // بداية الجنسيات
    Route::get('/nationalities', [NationalitiesController::class, 'index'])->name('nationalities.index');
    Route::get('/nationalities/create', [NationalitiesController::class, 'create'])->name('nationalities.create');
    Route::post('/nationalities/store', [NationalitiesController::class, 'store'])->name('nationalities.store');
    Route::get('/nationalitiesEdit/{id}', [NationalitiesController::class, 'edit'])->name('nationalities.edit');
    Route::post('/nationalitiesUpdate/{id}', [NationalitiesController::class, 'update'])->name('nationalities.update');
    Route::get('/nationalitiesDelete/{id}', [NationalitiesController::class, 'destroy'])->name('nationalities.destroy');
    
    // بداية الديانات
    Route::get('/religions', [ReligionsController::class, 'index'])->name('religions.index');
    Route::get('/religions/create', [ReligionsController::class, 'create'])->name('religions.create');
    Route::post('/religions/store', [ReligionsController::class, 'store'])->name('religions.store');
    Route::get('/religionsEdit/{id}', [ReligionsController::class, 'edit'])->name('religions.edit');
    Route::post('/religionsUpdate/{id}', [ReligionsController::class, 'update'])->name('religions.update');
    Route::get('/religionsDelete/{id}', [ReligionsController::class, 'destroy'])->name('religions.destroy');
    
    /************************ شؤون الموظفين ************************/

    // بداية الموظفين
    Route::get('/employees', [EmployeesController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeesController::class, 'create'])->name('employees.create');
    Route::post('/employees/store', [EmployeesController::class, 'store'])->name('employees.store');
    Route::get('/employeesEdit/{id}', [EmployeesController::class, 'edit'])->name('employees.edit');
    Route::post('/employeesUpdate/{id}', [EmployeesController::class, 'update'])->name('employees.update');
    Route::get('/employeesDelete/{id}', [EmployeesController::class, 'destroy'])->name('employees.destroy');
    Route::post('/employees/getGovernorate', [EmployeesController::class, 'ajax_getGovernorate'])->name('employees.ajax_getGovernorate');
    Route::post('/employees/getCenter', [EmployeesController::class, 'ajax_getCity'])->name('employees.ajax_getCity');
    Route::get('/employees/show/{id}', [EmployeesController::class, 'show'])->name('employees.show');
    Route::post('/employees/search', [EmployeesController::class, 'ajax_search'])->name('employees.ajax_search');
    Route::get('/employees/download/{id}/{field_name}', [EmployeesController::class, 'download'])->name('employees.download');
    Route::post('/employees/addFiles/{id}', [EmployeesController::class, 'add_files'])->name('employees.add_files');
    Route::get('/employees/download_file/{id}', [EmployeesController::class, 'download_file'])->name('employees.download_file');
    Route::get('/employees/destroy_file/{id}', [EmployeesController::class, 'destroy_file'])->name('employees.destroy_file');

});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'guest:admin'], function (){
    
    Route::get('login', [LoginController::class, 'show_login_view'])->name('admin.showlogin');
    
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
    
    // Route::get('test', function () {
    //     return view('admin.test');
    // });

});

Route::fallback(function(){
    return redirect()->route('admin.showlogin');
});

