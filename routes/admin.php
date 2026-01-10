<?php

use App\Http\Controllers\admin\Additional_sal_typesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\Admin_panel_settingsController;
use App\Http\Controllers\admin\AllowancesController;
use App\Http\Controllers\admin\Finance_calendersController;
use App\Http\Controllers\admin\BranchesController;
use App\Http\Controllers\admin\DepartmentsController;
use App\Http\Controllers\admin\Discount_sal_typesController;
use App\Http\Controllers\admin\EmployeesController;
use App\Http\Controllers\admin\jobs_categoriesController;
use App\Http\Controllers\admin\Main_salary_employee_absenceController;
use App\Http\Controllers\admin\Main_salary_employee_additionController;
use App\Http\Controllers\admin\Main_salary_employee_allowancesController;
use App\Http\Controllers\Admin\Main_salary_employee_discountsController;
use App\Http\Controllers\admin\Main_salary_employee_loansController;
use App\Http\Controllers\admin\Main_salary_employee_permanent_loansController;
use App\Http\Controllers\admin\Main_salary_employee_rewardsController;
use App\Http\Controllers\admin\Main_salary_employee_sanctionsController;
use App\Http\Controllers\admin\Main_Salary_RecordController;
use App\Http\Controllers\admin\MainSalaryRecord;
use App\Http\Controllers\admin\NationalitiesController;
use App\Http\Controllers\Admin\OccasionsController;
use App\Http\Controllers\admin\QualificationsController;
use App\Http\Controllers\admin\ReligionsController;
use App\Http\Controllers\admin\ResignationsController;
use App\Http\Controllers\admin\Shifts_typeController;
use App\Models\Main_salary_employee_absence;
use App\Models\Main_salary_employee_allowance;
use App\Models\Main_salary_employee_permanent_loan;
use App\Models\Main_salary_employee_sanction;

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

define('P_C', 10);
define('Dev', 'Ali Bajhnoon');
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

    // بداية الإضافي على الراتب
    Route::get('/additionalSalTypes', [Additional_sal_typesController::class, 'index'])->name('additionalsaltypes.index');
    Route::get('/additionalSalTypes/create', [Additional_sal_typesController::class, 'create'])->name('additionalsaltypes.create');
    Route::post('/additionalSalTypes/store', [Additional_sal_typesController::class, 'store'])->name('additionalsaltypes.store');
    Route::get('/additionalSalTypesEdit/{id}', [Additional_sal_typesController::class, 'edit'])->name('additionalsaltypes.edit');
    Route::post('/additionalSalTypesUpdate/{id}', [Additional_sal_typesController::class, 'update'])->name('additionalsaltypes.update');
    Route::get('/additionalSalTypesDelete/{id}', [Additional_sal_typesController::class, 'destroy'])->name('additionalsaltypes.destroy');

    // بداية الخصم على الراتب
    Route::get('/discountSalTypes', [Discount_sal_typesController::class, 'index'])->name('discountsaltypes.index');
    Route::get('/discountSalTypes/create', [Discount_sal_typesController::class, 'create'])->name('discountsaltypes.create');
    Route::post('/discountSalTypes/store', [Discount_sal_typesController::class, 'store'])->name('discountsaltypes.store');
    Route::get('/discountSalTypesEdit/{id}', [Discount_sal_typesController::class, 'edit'])->name('discountsaltypes.edit');
    Route::post('/discountSalTypesUpdate/{id}', [Discount_sal_typesController::class, 'update'])->name('discountsaltypes.update');
    Route::get('/discountSalTypesDelete/{id}', [Discount_sal_typesController::class, 'destroy'])->name('discountsaltypes.destroy');

    // بداية البدلات للموضف
    Route::get('/allowances', [AllowancesController::class, 'index'])->name('allowances.index');
    Route::get('/allowances/create', [AllowancesController::class, 'create'])->name('allowances.create');
    Route::post('/allowances/store', [AllowancesController::class, 'store'])->name('allowances.store');
    Route::get('/allowancesEdit/{id}', [AllowancesController::class, 'edit'])->name('allowances.edit');
    Route::post('/allowancesUpdate/{id}', [AllowancesController::class, 'update'])->name('allowances.update');
    Route::get('/allowancesDelete/{id}', [AllowancesController::class, 'destroy'])->name('allowances.destroy');

    // بداية السجلات الرئيسية للرواتب
    Route::get('/mainsalaryrecord', [Main_Salary_RecordController::class, 'index'])->name('mainsalaryrecord.index');
    Route::post('/mainsalaryrecord/do_open_month/{id}', [Main_Salary_RecordController::class, 'do_open_month'])->name('mainsalaryrecord.do_open_month');
    Route::post('/mainsalaryrecord/load_open_month', [Main_Salary_RecordController::class, 'load_open_month'])->name('mainsalaryrecord.load_open_month');
    Route::post('/mainsalaryrecord/ajaxSearch', [Main_Salary_RecordController::class, 'ajaxSearch'])->name('mainsalaryrecord.ajaxSearch');
    
    // بداية الجزاءات للرواتب
    Route::get('/mainsalarysanction', [Main_salary_employee_sanctionsController::class, 'index'])->name('mainsalarysanction.index');
    Route::get('/mainsalarysanction/show/{id}', [Main_salary_employee_sanctionsController::class, 'show'])->name('mainsalarysanction.show');
    Route::post('/mainsalarysanction/checkExist', [Main_salary_employee_sanctionsController::class, 'checkExist'])->name('mainsalarysanction.checkExist');
    Route::post('/mainsalarysanction/store', [Main_salary_employee_sanctionsController::class, 'store'])->name('mainsalarysanction.store');
    Route::post('/mainsalarysanction/ajaxSearch', [Main_salary_employee_sanctionsController::class, 'ajaxSearch'])->name('mainsalarysanction.ajaxSearch');
    Route::post('/mainsalarysanction/showAjaxSearch', [Main_salary_employee_sanctionsController::class, 'showAjaxSearch'])->name('mainsalarysanction.showAjaxSearch');
    Route::post('/mainsalarysanction/edit', [Main_salary_employee_sanctionsController::class, 'edit'])->name('mainsalarysanction.edit');
    Route::post('/mainsalarysanction/update', [Main_salary_employee_sanctionsController::class, 'update'])->name('mainsalarysanction.update');
    Route::post('/mainsalarysanction/delete', [Main_salary_employee_sanctionsController::class, 'delete'])->name('mainsalarysanction.delete');
    Route::post('/mainsalarysanction/printSearch', [Main_salary_employee_sanctionsController::class, 'printSearch'])->name('mainsalarysanction.printSearch');
    
    // بداية الغيابات للرواتب
    Route::get('/mainsalaryabsence', [Main_salary_employee_absenceController::class, 'index'])->name('mainsalaryabsence.index');
    Route::get('/mainsalaryabsence/show/{id}', [Main_salary_employee_absenceController::class, 'show'])->name('mainsalaryabsence.show');
    Route::post('/mainsalaryabsence/checkExist', [Main_salary_employee_absenceController::class, 'checkExist'])->name('mainsalaryabsence.checkExist');
    Route::post('/mainsalaryabsence/store', [Main_salary_employee_absenceController::class, 'store'])->name('mainsalaryabsence.store');
    Route::post('/mainsalaryabsence/ajaxSearch', [Main_salary_employee_absenceController::class, 'ajaxSearch'])->name('mainsalaryabsence.ajaxSearch');
    Route::post('/mainsalaryabsence/showAjaxSearch', [Main_salary_employee_absenceController::class, 'showAjaxSearch'])->name('mainsalaryabsence.showAjaxSearch');
    Route::post('/mainsalaryabsence/edit', [Main_salary_employee_absenceController::class, 'edit'])->name('mainsalaryabsence.edit');
    Route::post('/mainsalaryabsence/update', [Main_salary_employee_absenceController::class, 'update'])->name('mainsalaryabsence.update');
    Route::post('/mainsalaryabsence/delete', [Main_salary_employee_absenceController::class, 'delete'])->name('mainsalaryabsence.delete');
    Route::post('/mainsalaryabsence/printSearch', [Main_salary_employee_absenceController::class, 'printSearch'])->name('mainsalaryabsence.printSearch');
    
    // بداية الإضافي للرواتب
    Route::get('/mainsalaryaddition', [Main_salary_employee_additionController::class, 'index'])->name('mainsalaryaddition.index');
    Route::get('/mainsalaryaddition/show/{id}', [Main_salary_employee_additionController::class, 'show'])->name('mainsalaryaddition.show');
    Route::post('/mainsalaryaddition/checkExist', [Main_salary_employee_additionController::class, 'checkExist'])->name('mainsalaryaddition.checkExist');
    Route::post('/mainsalaryaddition/store', [Main_salary_employee_additionController::class, 'store'])->name('mainsalaryaddition.store');
    Route::post('/mainsalaryaddition/ajaxSearch', [Main_salary_employee_additionController::class, 'ajaxSearch'])->name('mainsalaryaddition.ajaxSearch');
    Route::post('/mainsalaryaddition/showAjaxSearch', [Main_salary_employee_additionController::class, 'showAjaxSearch'])->name('mainsalaryaddition.showAjaxSearch');
    Route::post('/mainsalaryaddition/edit', [Main_salary_employee_additionController::class, 'edit'])->name('mainsalaryaddition.edit');
    Route::post('/mainsalaryaddition/update', [Main_salary_employee_additionController::class, 'update'])->name('mainsalaryaddition.update');
    Route::post('/mainsalaryaddition/delete', [Main_salary_employee_additionController::class, 'delete'])->name('mainsalaryaddition.delete');
    Route::post('/mainsalaryaddition/printSearch', [Main_salary_employee_additionController::class, 'printSearch'])->name('mainsalaryaddition.printSearch');
    
    // بداية الخصومات للرواتب
    Route::get('/mainsalarydiscount', [Main_salary_employee_discountsController::class, 'index'])->name('mainsalarydiscount.index');
    Route::get('/mainsalarydiscount/show/{id}', [Main_salary_employee_discountsController::class, 'show'])->name('mainsalarydiscount.show');
    Route::post('/mainsalarydiscount/checkExist', [Main_salary_employee_discountsController::class, 'checkExist'])->name('mainsalarydiscount.checkExist');
    Route::post('/mainsalarydiscount/store', [Main_salary_employee_discountsController::class, 'store'])->name('mainsalarydiscount.store');
    Route::post('/mainsalarydiscount/ajaxSearch', [Main_salary_employee_discountsController::class, 'ajaxSearch'])->name('mainsalarydiscount.ajaxSearch');
    Route::post('/mainsalarydiscount/showAjaxSearch', [Main_salary_employee_discountsController::class, 'showAjaxSearch'])->name('mainsalarydiscount.showAjaxSearch');
    Route::post('/mainsalarydiscount/edit', [Main_salary_employee_discountsController::class, 'edit'])->name('mainsalarydiscount.edit');
    Route::post('/mainsalarydiscount/update', [Main_salary_employee_discountsController::class, 'update'])->name('mainsalarydiscount.update');
    Route::post('/mainsalarydiscount/delete', [Main_salary_employee_discountsController::class, 'delete'])->name('mainsalarydiscount.delete');
    Route::post('/mainsalarydiscount/printSearch', [Main_salary_employee_discountsController::class, 'printSearch'])->name('mainsalarydiscount.printSearch');
    
    // بداية المكافئات للرواتب
    Route::get('/mainsalaryreward', [Main_salary_employee_rewardsController::class, 'index'])->name('mainsalaryreward.index');
    Route::get('/mainsalaryreward/show/{id}', [Main_salary_employee_rewardsController::class, 'show'])->name('mainsalaryreward.show');
    Route::post('/mainsalaryreward/checkExist', [Main_salary_employee_rewardsController::class, 'checkExist'])->name('mainsalaryreward.checkExist');
    Route::post('/mainsalaryreward/store', [Main_salary_employee_rewardsController::class, 'store'])->name('mainsalaryreward.store');
    Route::post('/mainsalaryreward/ajaxSearch', [Main_salary_employee_rewardsController::class, 'ajaxSearch'])->name('mainsalaryreward.ajaxSearch');
    Route::post('/mainsalaryreward/showAjaxSearch', [Main_salary_employee_rewardsController::class, 'showAjaxSearch'])->name('mainsalaryreward.showAjaxSearch');
    Route::post('/mainsalaryreward/edit', [Main_salary_employee_rewardsController::class, 'edit'])->name('mainsalaryreward.edit');
    Route::post('/mainsalaryreward/update', [Main_salary_employee_rewardsController::class, 'update'])->name('mainsalaryreward.update');
    Route::post('/mainsalaryreward/delete', [Main_salary_employee_rewardsController::class, 'delete'])->name('mainsalaryreward.delete');
    Route::post('/mainsalaryreward/printSearch', [Main_salary_employee_rewardsController::class, 'printSearch'])->name('mainsalaryreward.printSearch');
    
    // بداية البدلات للرواتب
    Route::get('/mainsalaryallowance', [Main_salary_employee_allowancesController::class, 'index'])->name('mainsalaryallowance.index');
    Route::get('/mainsalaryallowance/show/{id}', [Main_salary_employee_allowancesController::class, 'show'])->name('mainsalaryallowance.show');
    Route::post('/mainsalaryallowance/checkExist', [Main_salary_employee_allowancesController::class, 'checkExist'])->name('mainsalaryallowance.checkExist');
    Route::post('/mainsalaryallowance/store', [Main_salary_employee_allowancesController::class, 'store'])->name('mainsalaryallowance.store');
    Route::post('/mainsalaryallowance/ajaxSearch', [Main_salary_employee_allowancesController::class, 'ajaxSearch'])->name('mainsalaryallowance.ajaxSearch');
    Route::post('/mainsalaryallowance/showAjaxSearch', [Main_salary_employee_allowancesController::class, 'showAjaxSearch'])->name('mainsalaryallowance.showAjaxSearch');
    Route::post('/mainsalaryallowance/edit', [Main_salary_employee_allowancesController::class, 'edit'])->name('mainsalaryallowance.edit');
    Route::post('/mainsalaryallowance/update', [Main_salary_employee_allowancesController::class, 'update'])->name('mainsalaryallowance.update');
    Route::post('/mainsalaryallowance/delete', [Main_salary_employee_allowancesController::class, 'delete'])->name('mainsalaryallowance.delete');
    Route::post('/mainsalaryallowance/printSearch', [Main_salary_employee_allowancesController::class, 'printSearch'])->name('mainsalaryallowance.printSearch');
    
    // بداية السلف الشهرية للرواتب
    Route::get('/mainsalaryloan', [Main_salary_employee_loansController::class, 'index'])->name('mainsalaryloan.index');
    Route::get('/mainsalaryloan/show/{id}', [Main_salary_employee_loansController::class, 'show'])->name('mainsalaryloan.show');
    Route::post('/mainsalaryloan/checkExist', [Main_salary_employee_loansController::class, 'checkExist'])->name('mainsalaryloan.checkExist');
    Route::post('/mainsalaryloan/store', [Main_salary_employee_loansController::class, 'store'])->name('mainsalaryloan.store');
    Route::post('/mainsalaryloan/ajaxSearch', [Main_salary_employee_loansController::class, 'ajaxSearch'])->name('mainsalaryloan.ajaxSearch');
    Route::post('/mainsalaryloan/showAjaxSearch', [Main_salary_employee_loansController::class, 'showAjaxSearch'])->name('mainsalaryloan.showAjaxSearch');
    Route::post('/mainsalaryloan/edit', [Main_salary_employee_loansController::class, 'edit'])->name('mainsalaryloan.edit');
    Route::post('/mainsalaryloan/update', [Main_salary_employee_loansController::class, 'update'])->name('mainsalaryloan.update');
    Route::post('/mainsalaryloan/delete', [Main_salary_employee_loansController::class, 'delete'])->name('mainsalaryloan.delete');
    Route::post('/mainsalaryloan/printSearch', [Main_salary_employee_loansController::class, 'printSearch'])->name('mainsalaryloan.printSearch');
    
    // بداية السلف المستديمة للرواتب
    Route::get('/mainsalarypermanent_loan', [Main_salary_employee_permanent_loansController::class, 'index'])->name('mainsalarypermanent_loan.index');
    // Route::get('/mainsalarypermanent_loan/show/{id}', [Main_salary_employee_permanent_loansController::class, 'show'])->name('mainsalarypermanent_loan.show');
    Route::post('/mainsalarypermanent_loan/checkExist', [Main_salary_employee_permanent_loansController::class, 'checkExist'])->name('mainsalarypermanent_loan.checkExist');
    Route::post('/mainsalarypermanent_loan/store', [Main_salary_employee_permanent_loansController::class, 'store'])->name('mainsalarypermanent_loan.store');
    Route::post('/mainsalarypermanent_loan/ajaxSearch', [Main_salary_employee_permanent_loansController::class, 'ajaxSearch'])->name('mainsalarypermanent_loan.ajaxSearch');
    Route::post('/mainsalarypermanent_loan/edit', [Main_salary_employee_permanent_loansController::class, 'edit'])->name('mainsalarypermanent_loan.edit');
    Route::post('/mainsalarypermanent_loan/update', [Main_salary_employee_permanent_loansController::class, 'update'])->name('mainsalarypermanent_loan.update');
    Route::post('/mainsalarypermanent_loan/delete', [Main_salary_employee_permanent_loansController::class, 'delete'])->name('mainsalarypermanent_loan.delete');
    Route::post('/mainsalarypermanent_loan/p_loan_installments', [Main_salary_employee_permanent_loansController::class, 'p_loan_installments'])->name('mainsalarypermanent_loan.p_loan_installments');
    Route::post('/mainsalarypermanent_loan/printSearch', [Main_salary_employee_permanent_loansController::class, 'printSearch'])->name('mainsalarypermanent_loan.printSearch');
    // Route::get('/mainsalarypermanent_loan/dismiss_p_loan/{id}', [Main_salary_employee_permanent_loansController::class, 'dismiss_p_loan'])->name('mainsalarypermanent_loan.dismiss_p_loan');
    Route::post('/mainsalarypermanent_loan/dismiss_p_loan', [Main_salary_employee_permanent_loansController::class, 'dismiss_p_loan'])->name('mainsalarypermanent_loan.dismiss_p_loan');
    
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'guest:admin'], function (){
    
    Route::get('login', [LoginController::class, 'show_login_view'])->name('admin.showlogin');
    
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');

});

Route::fallback(function(){
    return redirect()->route('admin.showlogin');
});

