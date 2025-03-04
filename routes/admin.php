<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\Admin_panel_settingsController;
use App\Http\Controllers\admin\Finance_calendersController;
use App\Http\Controllers\admin\BranchesController;
use App\Http\Controllers\admin\DepartmentsController;
use App\Http\Controllers\admin\jobs_categoriesController;
use App\Http\Controllers\Admin\OccasionsController;
use App\Http\Controllers\admin\QualificationsController;
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

