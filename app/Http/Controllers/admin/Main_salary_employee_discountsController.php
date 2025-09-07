<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin_panel_settings;
use App\Models\Discount_sal_type;
use App\Models\Employee;
use App\Models\Finance_calenders;
use App\Models\Finance_months_periods;
use App\Models\Main_salary_employee;
use App\Models\Main_salary_employee_discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Main_salary_employee_discountsController extends Controller
{
    // 
    public function index()
    {
        $com_code = auth()->user()->com_code;

        $data = get_cols_where_p_order2(new Finance_months_periods(), array("*"), array("com_code" => $com_code), "finance_yr", "DESC", "month_id", "ASC", 12);
        $finance_years = get_cols_where(new Finance_calenders(), array("finance_yr"), array("com_code" => $com_code), "id", "ASC");

        if (!empty($data)) {
            foreach ($data as $info) {
                // check status of current year is open and 
                // the current month is open and 
                // there's no other month open or 
                // there's no other monthes befor current month didn't open 
                $info->currentYear = get_cols_where_row(new Finance_calenders(), array('is_open'), array('com_code' => $com_code, 'finance_yr' => $info->finance_yr));
                $info->counterOpenMonth = get_count_where(new Finance_months_periods(), array('com_code' => $com_code, 'is_open' => 1));
                $info->counterPreviousMonthWaitingOpen = Finance_months_periods::where(['com_code' => $com_code, 'is_open' => 0, 'finance_yr' => $info->finance_yr])
                    ->where('month_id', '<', $info->month_id)->count();
            }
        }

        return view("admin.Main_salary_employee_discounts.index", ["data" => $data, 'finance_years' => $finance_years]);
    }

    //
    public function show($id)
    {
        $com_code = auth()->user()->com_code;
        $finance_month_data = get_cols_where_row(new Finance_months_periods(), array("*"), array("com_code" => $com_code, 'id' => $id));
        if (empty($finance_month_data)) {
            return redirect()->route('mainsalarydiscount.index')->with(['error' => 'عفواً غير قادر على الوصول الى البيانات المطلوبة']);
        }

        // Bring all discounts for the specific Finance Month.
        $discounts_data = get_cols_where(new Main_salary_employee_discount(), array("*"), array("com_code" => $com_code, "finance_months_periods_id" => $id), "id", "DESC");

        // Bring all discountal Types form the Discount_sal_types table. 
        $discountal_types = get_cols_where(new Discount_sal_type(), array("id", "name"), array("com_code" => $com_code, "active" => 1));

        // Bring all Employees they are active and have salary record.
        $employees = Main_salary_employee::where(["com_code" => $com_code, 'finance_month_id' => $id])->distinct()->get("employee_code");
        if (!empty($employees)) {
            foreach ($employees as $info) {
                $info->employeeData = get_cols_where_row(new Employee(), array("emp_name", "emp_sal", "day_price"), array("com_code" => $com_code, "employee_code" => $info->employee_code));
            }
        }

        $employees_for_search = get_cols_where(new Employee(), array("employee_code", "emp_name", "emp_sal", "day_price"), array("com_code" => $com_code), "employee_code", "ASC");

        return view('admin.Main_salary_employee_discounts.show', ['data' => $discounts_data, 'discountal_types' => $discountal_types, 'financeMonth_data' => $finance_month_data, 'employees' => $employees, 'employees_for_search' => $employees_for_search]);
    }

    // Check If the employee has discounts before for this finance month -> Ajax
    public function checkExist(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->id;
            $checkExist = get_count_where(new Main_salary_employee_discount(), array('com_code' => $com_code, 'employee_code' => $request->employee_code, 'finance_months_periods_id' => $request->finance_month_period_id));
            if ($checkExist > 0) {
                return json_encode("exists");
            } else {
                return json_encode("not_exists");
            }
        }
    }

    // Add discount for employee in a current finance month -> Ajax
    public function discountStore(Request $request)
    {
        try {
            if ($request->ajax()) {
                $com_code = auth()->user()->id;
                $financeMonth_data = get_cols_where_row(new Finance_months_periods(), array('id'), array('com_code' => $com_code, 'id' => $request->finance_month_period_id, 'is_open' => 1));
                $mainSalaryEmployee_data = get_cols_where_row(new Main_salary_employee(), array('*'), array('com_code' => $com_code, 'finance_month_id' => $request->finance_month_period_id, 'employee_code' => $request->employee_code, 'is_archived' => 0));
                if (!empty($financeMonth_data) and !empty($mainSalaryEmployee_data)) {
                    DB::beginTransaction();
                    $dataToInsert['main_salary_employee_id'] = $mainSalaryEmployee_data['id'];
                    $dataToInsert['finance_months_periods_id'] = $request->finance_month_period_id;
                    $dataToInsert['is_auto'] = 1;
                    $dataToInsert['employee_code'] = $request->employee_code;
                    $dataToInsert['day_price'] = $request->day_price;
                    $dataToInsert['discounts_type'] = $request->discounts_type;
                    $dataToInsert['total'] = $request->total;
                    $dataToInsert['notes'] = $request->notes;
                    $dataToInsert['com_code'] = $com_code;
                    $dataToInsert['added_by'] = auth()->user()->id;

                    insert(new Main_salary_employee_discount(), $dataToInsert);
                    DB::commit();

                    return json_encode('success');
                }
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return json_encode('error : ' . $ex->getMessage());
        }
    }

    // Show Edit discount modalForm for employee in a current finance month -> Ajax 
    public function discountEdit(Request $request)
    {
        try {
            if ($request->ajax()) {
                $com_code = auth()->user()->id;
                $financeMonth_data = get_cols_where_row(new Finance_months_periods(), array('id'), array('com_code' => $com_code, 'id' => $request->finance_month_period_id, 'is_open' => 1));
                $mainSalaryEmployee_data = get_cols_where_row(new Main_salary_employee(), array('id'), array('com_code' => $com_code, 'id' => $request->main_salary_employee_id, 'finance_month_id' => $request->finance_month_period_id, 'is_archived' => 0));
                $mainSalarydiscount_data = get_cols_where_row(new Main_salary_employee_discount(), array('*'), array('com_code' => $com_code, 'id' => $request->id, 'finance_months_periods_id' => $request->finance_month_period_id, 'main_salary_employee_id' => $request->main_salary_employee_id, 'is_archived' => 0));

                // Bring all discountal Types form the Discount_sal_types table. 
                $discountal_types = get_cols_where(new Discount_sal_type(), array("id", "name"), array("com_code" => $com_code, "active" => 1));

                if (!empty($financeMonth_data) and !empty($mainSalaryEmployee_data) and !empty($mainSalarydiscount_data)) {
                    //$employees = get_cols_where(new Employee(), array("employee_code", "emp_name", "emp_sal", "day_price"), array("com_code" => $com_code), "employee_code", "ASC");
                    $employees = Main_salary_employee::where(["com_code" => $com_code, 'finance_month_id' => $request->finance_month_period_id])->distinct()->get("employee_code");
                    if (!empty($employees)) {
                        foreach ($employees as $info) {
                            // $info->emp_name = get_field_value(new Employee(), 'emp_name', array("com_code" => $com_code, "employee_code" => $info->employee_code));
                            $info->employeeData = get_cols_where_row(new Employee(), array("emp_name", "emp_sal", "day_price"), array("com_code" => $com_code, "employee_code" => $info->employee_code));
                        }
                    }
                    return view('admin.Main_salary_employee_discounts.edit_discount', ['discount_data' => $mainSalarydiscount_data, "discountal_types" => $discountal_types, 'employees' => $employees]);
                }
            }
        } catch (\Exception $ex) {
            return json_encode('error : ' . $ex->getMessage());
        }
    }

    // Add discount for employee in a current finance month -> Ajax
    public function discountUpdate(Request $request)
    {
        try {
            if ($request->ajax()) {
                $com_code = auth()->user()->id;
                $financeMonth_data = get_cols_where_row(new Finance_months_periods(), array('id'), array('com_code' => $com_code, 'id' => $request->finance_month_period_id, 'is_open' => 1));
                $mainSalaryEmployee_data = get_cols_where_row(new Main_salary_employee(), array('*'), array('com_code' => $com_code, 'finance_month_id' => $request->finance_month_period_id, 'employee_code' => $request->employee_code, 'is_archived' => 0));
                $mainSalarydiscount_data = get_cols_where_row(new Main_salary_employee_discount(), array('*'), array('com_code' => $com_code, 'id' => $request->id, 'finance_months_periods_id' => $request->finance_month_period_id, 'main_salary_employee_id' => $request->main_salary_employee_id, 'is_archived' => 0));
                if (!empty($financeMonth_data) and !empty($mainSalaryEmployee_data) and !empty($mainSalarydiscount_data)) {
                    DB::beginTransaction();
                    $dataToUpdate['employee_code'] = $request->employee_code;
                    $dataToUpdate['day_price'] = $request->day_price;
                    $dataToUpdate['discounts_type'] = $request->discounts_type;
                    $dataToUpdate['total'] = $request->total;
                    $dataToUpdate['notes'] = $request->notes;
                    $dataToUpdate['updated_by'] = auth()->user()->id;

                    update(new Main_salary_employee_discount(), $dataToUpdate, array('com_code' => $com_code, 'id' => $request->id, 'finance_months_periods_id' => $request->finance_month_period_id, 'main_salary_employee_id' => $request->main_salary_employee_id, 'is_archived' => 0));
                    DB::commit();

                    return json_encode('success');
                }
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return json_encode('error : ' . $ex->getMessage());
        }
    }

    // Delete discount for employee in a current finance month -> Ajax 
    public function discountDelete(Request $request)
    {
        try {
            if ($request->ajax()) {
                $com_code = auth()->user()->id;
                $financeMonth_data = get_cols_where_row(new Finance_months_periods(), array('id'), array('com_code' => $com_code, 'id' => $request->finance_month_period_id, 'is_open' => 1));
                $mainSalaryEmployee_data = get_cols_where_row(new Main_salary_employee(), array('*'), array('com_code' => $com_code, 'id' => $request->main_salary_employee_id, 'finance_month_id' => $request->finance_month_period_id, 'is_archived' => 0));
                $mainSalarydiscount_data = get_cols_where_row(new Main_salary_employee_discount(), array('id'), array('com_code' => $com_code, 'id' => $request->id, 'finance_months_periods_id' => $request->finance_month_period_id, 'main_salary_employee_id' => $request->main_salary_employee_id, 'is_archived' => 0));
                if (!empty($financeMonth_data) and !empty($mainSalarydiscount_data) and !empty($mainSalaryEmployee_data)) {
                    DB::beginTransaction();

                    destroy(new Main_salary_employee_discount(), array('com_code' => $com_code, 'id' => $request->id, 'finance_months_periods_id' => $request->finance_month_period_id, 'is_archived' => 0));

                    DB::commit();

                    return json_encode('success');
                }
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return json_encode('error : ' . $ex->getMessage());
        }
    }

    // Search discounts By EmpCode - discountsType - IsArchived. In the specific FinanceMonth!! -> Ajax
    public function showAjaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->id;
            $employee_code = $request->employee_code;
            $discounts_type = $request->discounts_type;
            $is_archived = $request->is_archived;
            $finance_month_period_id = $request->finance_month_period_id;

            if ($employee_code == "all") {
                $field1 = 'id';
                $operator1 = '>';
                $value1 = 0;
            } else {
                $field1 = 'employee_code';
                $operator1 = '=';
                $value1 = $employee_code;
            }

            if ($discounts_type == "all") {
                $field2 = 'id';
                $operator2 = '>';
                $value2 = 0;
            } else {
                $field2 = 'discounts_type';
                $operator2 = '=';
                $value2 = $discounts_type;
            }

            if ($is_archived == "all") {
                $field3 = 'id';
                $operator3 = '>';
                $value3 = 0;
            } else {
                $field3 = 'is_archived';
                $operator3 = '=';
                $value3 = $is_archived;
            }

            $data = Main_salary_employee_discount::select('*')->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)->where(['com_code' => $com_code, 'finance_months_periods_id' => $finance_month_period_id])->orderby('id', 'DESC')->get();

            return view('admin.Main_salary_employee_discounts.show_ajax_search', ['data' => $data]);
        }
    }

    // Print the discounts search result 
    public function printSearch(Request $request)
    {
        $com_code = auth()->user()->id;
        $employee_code = $request->employee_code_search_discount;
        $discounts_type = $request->discounts_type_search_discount;
        $is_archived = $request->is_archived_search_discount;
        $finance_month_period_id = $request->finance_month_period_id;

        $financeMonth_data = get_cols_where_row(new Finance_months_periods(), array('*'), array('com_code' => $com_code, 'id' => $request->finance_month_period_id, 'is_open' => 1));

        if ($employee_code == "all") {
            $field1 = 'id';
            $operator1 = '>';
            $value1 = 0;
        } else {
            $field1 = 'employee_code';
            $operator1 = '=';
            $value1 = $employee_code;
        }

        if ($discounts_type == "all") {
            $field2 = 'id';
            $operator2 = '>';
            $value2 = 0;
        } else {
            $field2 = 'discounts_type';
            $operator2 = '=';
            $value2 = $discounts_type;
        }

        if ($is_archived == "all") {
            $field3 = 'id';
            $operator3 = '>';
            $value3 = 0;
        } else {
            $field3 = 'is_archived';
            $operator3 = '=';
            $value3 = $is_archived;
        }

        $other['value_sum'] = 0;
        $other['total_sum'] = 0;
        $data = Main_salary_employee_discount::select('*')->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)
            ->where($field3, $operator3, $value3)->where(['com_code' => $com_code, 'finance_months_periods_id' => $finance_month_period_id])->orderby('id', 'DESC')->get();
        $systemData = get_cols_where_row(new Admin_panel_settings(), array('company_name', 'image', 'phones', 'address'), array('com_code' => $com_code));

        if (!empty($data)) {
            foreach ($data as $info) {
                $other['value_sum'] += $info->value;
                $other['total_sum'] += $info->total;
            }
        }

        return view('admin.Main_salary_employee_discounts.print_search', ['data' => $data, 'financeMonth_data' => $financeMonth_data, 'systemData' => $systemData, 'totals' => $other]);
    }

    // Search Monthes By Finance year !! -> Ajax 
    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->id;
            if ($request->finance_yr == "all") {
                $data = get_cols_where_p_order2(new Finance_months_periods(), array("*"), array("com_code" => $com_code), "finance_yr", "DESC", "month_id", "ASC", 12);
            } else {
                $data = get_cols_where_p(new Finance_months_periods(), array("*"), array("com_code" => $com_code, 'finance_yr' => $request->finance_yr), "month_id", "ASC", 12);
            }

            if (!empty($data)) {
                foreach ($data as $info) {
                    // check status of current year is open and 
                    // the current month is open and 
                    // there's no other month open or 
                    // there's no other monthes befor current month didn't open 
                    $info->currentYear = get_cols_where_row(new Finance_calenders(), array('is_open'), array('com_code' => $com_code, 'finance_yr' => $info->finance_yr));
                    $info->counterOpenMonth = get_count_where(new Finance_months_periods(), array('com_code' => $com_code, 'is_open' => 1));
                    $info->counterPreviousMonthWaitingOpen = Finance_months_periods::where(['com_code' => $com_code, 'is_open' => 0, 'finance_yr' => $info->finance_yr])
                        ->where('month_id', '<', $info->month_id)->count();
                }
            }

            return view('admin.Main_salary_employee_discounts.ajax_search', ['data' => $data]);
        }
    }
}
