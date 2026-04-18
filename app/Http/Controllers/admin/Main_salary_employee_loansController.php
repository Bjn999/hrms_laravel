<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin_panel_settings;
use App\Models\Employee;
use App\Models\Finance_calenders;
use App\Models\Finance_months_periods;
use App\Models\Main_salary_employee;
use App\Models\Main_salary_employee_loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\generalTrait;

class Main_salary_employee_loansController extends Controller
{
    use generalTrait;
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

        return view("admin.main_salary_employee_loans.index", ["data" => $data, 'finance_years' => $finance_years]);
    }

    //
    public function show($id)
    {
        $com_code = auth()->user()->com_code;
        $finance_month_data = get_cols_where_row(new Finance_months_periods(), array("*"), array("com_code" => $com_code, 'id' => $id));
        if (empty($finance_month_data)) {
            return redirect()->route('mainsalaryloan.index')->with(['error' => 'عفواً غير قادر على الوصول الى البيانات المطلوبة']);
        }

        // Bring all loans for the specific Finance Month.
        $loans_data = get_cols_where(new Main_salary_employee_loan(), array("*"), array("com_code" => $com_code, "finance_month_periods_id" => $id), "id", "DESC");

        // Bring all Employees they are active and have salary record.
        $employees = Main_salary_employee::where(["com_code" => $com_code, 'finance_month_id' => $id])->distinct()->get("employee_code");
        if (!empty($employees)) {
            foreach ($employees as $info) {
                $info->employeeData = get_cols_where_row(new Employee(), array("emp_name", "emp_sal", "day_price"), array("com_code" => $com_code, "employee_code" => $info->employee_code));
            }
        }

        $employees_for_search = get_cols_where(new Employee(), array("employee_code", "emp_name", "emp_sal", "day_price"), array("com_code" => $com_code), "employee_code", "ASC");

        return view('admin.main_salary_employee_loans.show', ['data' => $loans_data, 'financeMonth_data' => $finance_month_data, 'employees' => $employees, 'employees_for_search' => $employees_for_search]);
    }

    // Check If the employee has loans before for this finance month -> Ajax
    public function checkExist(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->id;
            $checkExist = get_count_where(new Main_salary_employee_loan(), array('com_code' => $com_code, 'employee_code' => $request->employee_code, 'finance_month_periods_id' => $request->finance_month_period_id));
            if ($checkExist > 0) {
                return json_encode("exists");
            } else {
                return json_encode("not_exists");
            }
        }
    }

    // Add loan for employee in a current finance month -> Ajax
    public function store(Request $request)
    {
        try {
            if ($request->ajax()) {
                $com_code = auth()->user()->id;
                $financeMonth_data = get_cols_where_row(new Finance_months_periods(), array('id'), array('com_code' => $com_code, 'id' => $request->finance_month_period_id, 'is_open' => 1));
                $mainSalaryEmployee_data = get_cols_where_row(new Main_salary_employee(), array('*'), array('com_code' => $com_code, 'finance_month_id' => $request->finance_month_period_id, 'employee_code' => $request->employee_code, 'is_archived' => 0));
                if (!empty($financeMonth_data) and !empty($mainSalaryEmployee_data)) {
                    DB::beginTransaction();
                    $dataToInsert['main_salary_employee_id'] = $mainSalaryEmployee_data['id'];
                    $dataToInsert['finance_month_periods_id'] = $request->finance_month_period_id;
                    $dataToInsert['employee_code'] = $request->employee_code;
                    $dataToInsert['day_price'] = $request->day_price;
                    $dataToInsert['total'] = $request->total;
                    $dataToInsert['notes'] = $request->notes;
                    $dataToInsert['com_code'] = $com_code;
                    $dataToInsert['added_by'] = auth()->user()->id;

                    $flag = insert(new Main_salary_employee_loan(), $dataToInsert);
                    
                    if ($flag) {
                        $this->recaculate_main_salary_employee($mainSalaryEmployee_data['id']);
                    }

                    DB::commit();

                    return json_encode('success');
                }
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return json_encode('error : ' . $ex->getMessage());
        }
    }

    // Show Edit loan modalForm for employee in a current finance month -> Ajax 
    public function edit(Request $request)
    {
        try {
            if ($request->ajax()) {
                $com_code = auth()->user()->id;
                $financeMonth_data = get_cols_where_row(new Finance_months_periods(), array('id'), array('com_code' => $com_code, 'id' => $request->finance_month_period_id, 'is_open' => 1));
                $mainSalaryEmployee_data = get_cols_where_row(new Main_salary_employee(), array('id'), array('com_code' => $com_code, 'id' => $request->main_salary_employee_id, 'finance_month_id' => $request->finance_month_period_id, 'is_archived' => 0));
                $mainSalaryloan_data = get_cols_where_row(new Main_salary_employee_loan(), array('*'), array('com_code' => $com_code, 'id' => $request->id, 'finance_month_periods_id' => $request->finance_month_period_id, 'main_salary_employee_id' => $request->main_salary_employee_id, 'is_archived' => 0));
                
                // return(isEmpty($employees));
                if (!empty($financeMonth_data) and !empty($mainSalaryEmployee_data) and !empty($mainSalaryloan_data)) {
                    //$employees = get_cols_where(new Employee(), array("employee_code", "emp_name", "emp_sal", "day_price"), array("com_code" => $com_code), "employee_code", "ASC");
                    $employees = Main_salary_employee::where(["com_code" => $com_code, 'finance_month_id' => $request->finance_month_period_id])->distinct()->get("employee_code");
                    if (!empty($employees)) {
                        foreach ($employees as $info) {
                            $info->employeeData = get_cols_where_row(new Employee(), array("emp_name", "emp_sal", "day_price"), array("com_code" => $com_code, "employee_code" => $info->employee_code));
                        }
                    }
                    return view('admin.main_salary_employee_loans.edit_loan', ['loan_data' => $mainSalaryloan_data, 'employees' => $employees]);
                }
            }
        } catch (\Exception $ex) {
            return json_encode('error : ' . $ex->getMessage());
        }
    }

    // Add loan for employee in a current finance month -> Ajax
    public function update(Request $request)
    {
        try {
            if ($request->ajax()) {
                $com_code = auth()->user()->id;
                $financeMonth_data = get_cols_where_row(new Finance_months_periods(), array('id'), array('com_code' => $com_code, 'id' => $request->finance_month_period_id, 'is_open' => 1));
                $mainSalaryEmployee_data = get_cols_where_row(new Main_salary_employee(), array('*'), array('com_code' => $com_code, 'id' => $request->main_salary_employee_id, 'finance_month_id' => $request->finance_month_period_id, 'employee_code' => $request->employee_code, 'is_archived' => 0));
                $mainSalaryloan_data = get_cols_where_row(new Main_salary_employee_loan(), array('*'), array('com_code' => $com_code, 'id' => $request->id, 'finance_month_periods_id' => $request->finance_month_period_id, 'main_salary_employee_id' => $request->main_salary_employee_id, 'is_archived' => 0));
                if (!empty($financeMonth_data) and !empty($mainSalaryEmployee_data) and !empty($mainSalaryloan_data)) {
                    DB::beginTransaction();
                    $dataToUpdate['employee_code'] = $request->employee_code;
                    $dataToUpdate['day_price'] = $request->day_price;
                    $dataToUpdate['total'] = $request->total;
                    $dataToUpdate['notes'] = $request->notes;
                    $dataToUpdate['updated_by'] = auth()->user()->id;

                    $flag = update(new Main_salary_employee_loan(), $dataToUpdate, array('com_code' => $com_code, 'id' => $request->id, 'finance_month_periods_id' => $request->finance_month_period_id, 'main_salary_employee_id' => $request->main_salary_employee_id, 'is_archived' => 0));
                    
                    if ($flag) {
                        $this->recaculate_main_salary_employee($mainSalaryEmployee_data['id']);
                    }

                    DB::commit();

                    return json_encode('success');
                }
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return json_encode('error : ' . $ex->getMessage());
        }
    }

    // Delete loan for employee in a current finance month -> Ajax 
    public function delete(Request $request)
    {
        try {
            if ($request->ajax()) {
                $com_code = auth()->user()->id;
                $financeMonth_data = get_cols_where_row(new Finance_months_periods(), array('id'), array('com_code' => $com_code, 'id' => $request->finance_month_period_id, 'is_open' => 1));
                $mainSalaryEmployee_data = get_cols_where_row(new Main_salary_employee(), array('*'), array('com_code' => $com_code, 'id' => $request->main_salary_employee_id, 'finance_month_id' => $request->finance_month_period_id, 'is_archived' => 0));
                $mainSalaryloan_data = get_cols_where_row(new Main_salary_employee_loan(), array('id'), array('com_code' => $com_code, 'id' => $request->id, 'finance_month_periods_id' => $request->finance_month_period_id, 'main_salary_employee_id' => $request->main_salary_employee_id, 'is_archived' => 0));
                if (!empty($financeMonth_data) and !empty($mainSalaryloan_data) and !empty($mainSalaryEmployee_data)) {
                    DB::beginTransaction();

                    $flag = destroy(new Main_salary_employee_loan(), array('com_code' => $com_code, 'id' => $request->id, 'finance_month_periods_id' => $request->finance_month_period_id, 'is_archived' => 0));

                    if ($flag) {
                        $this->recaculate_main_salary_employee($mainSalaryEmployee_data['id']);
                    }

                    DB::commit();

                    return json_encode('success');
                }
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return json_encode('error : ' . $ex->getMessage());
        }
    }

    // Search loans By EmpCode - IsArchived. In the specific FinanceMonth!! -> Ajax
    public function showAjaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->id;
            $employee_code = $request->employee_code;
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

            if ($is_archived == "all") {
                $field2 = 'id';
                $operator2 = '>';
                $value2 = 0;
            } else {
                $field2 = 'is_archived';
                $operator2 = '=';
                $value2 = $is_archived;
            }

            $data = Main_salary_employee_loan::select('*')->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)
                ->where(['com_code' => $com_code, 'finance_month_periods_id' => $finance_month_period_id])->orderby('id', 'DESC')->get();

            return view('admin.main_salary_employee_loans.show_ajax_search', ['data' => $data]);
        }
    }

    // Print the loans search result 
    public function printSearch(Request $request)
    {
        $com_code = auth()->user()->id;
        $employee_code = $request->employee_code_search_loan;
        $is_archived = $request->is_archived_search_loan;
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

        if ($is_archived == "all") {
            $field2 = 'id';
            $operator2 = '>';
            $value2 = 0;
        } else {
            $field2 = 'is_archived';
            $operator2 = '=';
            $value2 = $is_archived;
        }

        $other['total_sum'] = 0;
        $data = Main_salary_employee_loan::select('*')->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)
            ->where(['com_code' => $com_code, 'finance_month_periods_id' => $finance_month_period_id])->orderby('id', 'DESC')->get();
        $systemData = get_cols_where_row(new Admin_panel_settings(), array('company_name', 'image', 'phones', 'address'), array('com_code' => $com_code));

        if (!empty($data)) {
            foreach ($data as $info) {
                $other['total_sum'] += $info->total;
            }
        }

        return view('admin.main_salary_employee_loans.print_search', ['data' => $data, 'financeMonth_data' => $financeMonth_data, 'systemData' => $systemData, 'totals' => $other]);
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

            return view('admin.main_salary_employee_loans.ajax_search', ['data' => $data]);
        }
    }
}
