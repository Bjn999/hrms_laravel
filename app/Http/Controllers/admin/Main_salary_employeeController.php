<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin_panel_settings;
use App\Models\Branche;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Finance_calenders;
use App\Models\Finance_months_periods;
use App\Models\Jobs_categories;
use App\Models\Main_salary_employee;
use App\Models\Main_salary_p_loans_installment;
use Illuminate\Http\Request;
use App\Traits\generalTrait;

use function Laravel\Prompts\alert;

class Main_salary_employeeController extends Controller
{
    use generalTrait;

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

        return view("admin.main_salary_employee.index", ["data" => $data, 'finance_years' => $finance_years]);
    }

    // Show all salary record for the specific finance month 
    public function show($id)
    {
        $com_code = auth()->user()->com_code;
        $finance_month_data = get_cols_where_row(new Finance_months_periods(), array("*"), array("com_code" => $com_code, 'id' => $id));
        if (empty($finance_month_data)) {
            return redirect()->route('mainsalaryemployee.index')->with(['error' => 'عفواً غير قادر على الوصول الى البيانات المطلوبة']);
        }

        // Bring all Employees they are active and have salary record.
        // $data = Main_salary_employee::select(["employee_code", "total_benefits", "total_deduction", "final_the_net", "is_take_action_dissmiss_collect", "is_stoped"])->where(["com_code" => $com_code, 'finance_month_id' => $id])->distinct()->get("employee_code");
        $data = get_cols_where_p(new Main_salary_employee(), ["id", "finance_month_id", "employee_code", "total_benefits", "total_deduction", "final_the_net", "is_take_action_dissmiss_collect", "is_archived", "is_stoped", "emp_departments_id", "branch_id", "emp_job_id"], ["com_code" => $com_code, 'finance_month_id' => $id], "id", "DESC", P_C);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->employeeData = get_cols_where_row(new Employee(), array("emp_name", "emp_sal", "day_price"), array("com_code" => $com_code, "employee_code" => $info->employee_code));
                $info->branch = get_field_value(new Branche(), "name", array("com_code" => $com_code, "id" => $info->branch_id));
                $info->department = get_field_value(new Department(), "name", array("com_code" => $com_code, "id" => $info->emp_departments_id));
                $info->job = get_field_value(new Jobs_categories(), "name", array("com_code" => $com_code, "id" => $info->emp_job_id));
            }
        }

        $other['branches'] = get_cols_where(new Branche(), ["id", "name"], array("com_code" => $com_code, "active" => 1));
        $other['departments'] = get_cols_where(new Department(), ["id", "name"], array("com_code" => $com_code, "active" => 1));
        $other['jobs'] = get_cols_where(new Jobs_categories(), ["id", "name"], array("com_code" => $com_code, "active" => 1));
        $other['employees'] = get_cols_where(new Employee(), array("employee_code", "emp_name", "emp_sal", "day_price"), array("com_code" => $com_code), "employee_code", "ASC");
        $other['nothavesal'] = 0;

        if (!empty($other['employees'])) {
            foreach ($other['employees'] as $info) {
                $info->counter = get_count_where(new Main_salary_employee(), array("com_code" => $com_code, 'finance_month_id' => $id, 'employee_code' => $info->employee_code));
                if ($info->counter == 0) {
                    $other['nothavesal']++;
                }
            }
        }

        // $other['salaries_mirror'] = get_count_where(new Main_salary_employee(), ["com_code" => $com_code, 'finance_month_id' => $id]);
        // $other['archived_salaries_mirror'] = get_count_where(new Main_salary_employee(), ["com_code" => $com_code, 'finance_month_id' => $id, 'is_archived' => 1]);
        // $other['not_archived_salaries_mirror'] = get_count_where(new Main_salary_employee(), ["com_code" => $com_code, 'finance_month_id' => $id, 'is_archived' => 0]);
        // $other['stopped_salaries_mirror'] = get_count_where(new Main_salary_employee(), ["com_code" => $com_code, 'finance_month_id' => $id, 'is_stoped' => 1]);

        return view(
            'admin.main_salary_employee.show',
            [
                'data' => $data,
                'other' => $other,
                'financeMonth_data' => $finance_month_data,
            ]
        );
    }

    // Add new salary record for the specific finance month 
    public function add_salary(Request $request)
    {
        $com_code = auth()->user()->com_code;
        $finance_month_data = get_cols_where_row(new Finance_months_periods(), array("*"), array("com_code" => $com_code, 'id' => $request->finance_month_id));
        if (empty($finance_month_data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'عفواً غير قادر على الوصول الى البيانات المطلوبة'
            ]);
            // return redirect()->route('mainsalaryemployee.index')->with(['error' => 'عفواً غير قادر على الوصول الى البيانات المطلوبة']);
        }
        if ($finance_month_data['is_open'] != 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'عفواً غير قادر اضافة راتب في شهر مالي مغلق'
            ]);
            // return redirect()->back()->with(['error' => 'عفواً غير قادر اضافة راتب في هذه المرحلة (شهر مالي مغلق)']);
        }

        $employee = get_cols_where_row(
            new Employee(),
            array(
                'employee_code',
                'emp_name',
                'branch_id',
                'functional_status',
                'emp_departments_id',
                'emp_job_id',
                'emp_sal',
                'day_price',
                'sal_cash_or_visa',
                'motivation',
                'social_insurance_cut_monthly',
                'medical_insurance_cut_monthly',
                'is_sensitive_manager_data',
            ),
            array('com_code' => $com_code, 'employee_code' => $request->employee_code_add_salary)
        );

        if (!empty($employee)) {
            $dataSalaryToInsert['finance_month_id'] = $request->finance_month_id;
            $dataSalaryToInsert['employee_code'] = $employee->employee_code;
            $dataSalaryToInsert['com_code'] = $com_code;

            $checkExistCounter = get_count_where(new Main_salary_employee(), $dataSalaryToInsert);

            if ($checkExistCounter > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'هذا الموظف لديه سجل راتب في هذا الشهر'
                ]);
            }

            $dataSalaryToInsert['emp_name'] = $employee->emp_name;
            $dataSalaryToInsert['is_sensitive_manager_data'] = $employee->is_sensitive_manager_data;
            $dataSalaryToInsert['branch_id'] = $employee->branch_id;
            $dataSalaryToInsert['emp_departments_id'] = $employee->emp_departments_id;
            $dataSalaryToInsert['emp_job_id'] = $employee->emp_job_id;
            $dataSalaryToInsert['functional_status'] = $employee->functional_status;
            $dataSalaryToInsert['emp_sal'] = $employee->emp_sal;
            $dataSalaryToInsert['day_price'] = $employee->day_price;
            // Bring tha last salary record for the employee and get his salary 
            $lastsalaryData = get_cols_where_row_orderby(new Main_salary_employee(), ['final_the_net_after_close'], ['com_code' => $com_code, 'employee_code' => $employee->employee_code, 'is_archived' => 1], 'id', 'DESC');
            if (!empty($lastsalaryData)) {
                $dataSalaryToInsert['last_salary_remain_balance'] = $lastsalaryData['final_the_net_after_close'];
            } else {
                $dataSalaryToInsert['last_salary_remain_balance'] = 0;
            }

            // 
            $dataSalaryToInsert['year_and_month'] = $finance_month_data->year_and_month;
            $dataSalaryToInsert['finance_yr'] = $finance_month_data->finance_yr;
            $dataSalaryToInsert['sal_cash_or_visa'] = $employee->sal_cash_or_visa;

            $dataSalaryToInsert['added_by'] = auth()->user()->id;

            $flagInsert = insert(new Main_salary_employee(), $dataSalaryToInsert);

            if (!empty($flagInsert)) {
                $this->recaculate_main_salary_employee($flagInsert['id']);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'تم اضافة راتب الموظف بنجاح'
            ]);
        }
    }

    // Delete salary record -> Ajax
    public function delete_salary(Request $request) 
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;

            $finance_month_data = get_cols_where_row(new Finance_months_periods(), array("is_open"), array("com_code" => $com_code, 'id' => $request->finance_month_id, 'is_open' => 1));
            if (empty($finance_month_data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'عفواً غير قادر على الوصول الى البيانات المطلوبة'
                ]);
            }

            if ($finance_month_data['is_open'] != 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'عفواً غير قادر على حذف راتب في شهر مالي مغلق'
                ]);
            }

            $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("id"), array("com_code" => $com_code, 'id' => $request->id, "finance_month_id" => $request->finance_month_id, "is_archived" => 0));
            if (empty($main_salary_employee)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'عفواً غير قادر على الوصول الى البيانات المطلوبة'
                ]);
            }

            $check_p_loans_installments = get_cols_where_row(new Main_salary_p_loans_installment(), array("id"), array("com_code" => $com_code, 'main_salary_employee_id' => $request->id));
            if (!empty($check_p_loans_installments)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'عفواً غير قادر على حذف راتب الموظف لوجود اقساط'
                ]);
            }

            $flagDelete = destroy(new Main_salary_employee(), array("com_code" => $com_code, 'id' => $request->id));
            if (!empty($flagDelete)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'تم حذف راتب الموظف بنجاح'
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'عفواً غير قادر على حذف راتب الموظف'
            ]);
        }
    }

    // Show the details of a speceific salary record 
    public function showSalDetails($id) 
    {
        $com_code = auth()->user()->com_code;

            $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("*"), array("com_code" => $com_code, 'id' => $id));
            if (empty($main_salary_employee)) {
                // return response()->json([
                //     'status' => 'error',
                //     'message' => 'عفواً غير قادر على الوصول الى البيانات المطلوبة'
                // ]);
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى البيانات المطلوبة']);
            }

            $finance_month_data = get_cols_where_row(new Finance_months_periods(), array("*"), array("com_code" => $com_code, 'id' => $main_salary_employee->finance_month_id));
            if (empty($finance_month_data)) {
                // return response()->json([
                //     'status' => 'error',
                //     'message' => 'عفواً غير قادر على الوصول الى البيانات المطلوبة'
                // ]);
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى البيانات المطلوبة']);
            }

            // if ($finance_month_data['is_open'] != 1) {
                // return response()->json([
                //     'status' => 'error',
                //     'message' => 'عفواً غير قادر على عرض راتب في شهر مالي مغلق'
                // ]);
            //     return redirect()->back()->with(['error' => 'عفواً غير قادر على عرض راتب في شهر مالي مغلق']);
            // }

            if ($main_salary_employee['is_archived'] == 0) {
                $this->recaculate_main_salary_employee($id);
                $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("*"), array("com_code" => $com_code, 'id' => $id));
            }

            $main_salary_employee->emp_name = get_field_value(new Employee(), "emp_name", array("com_code" => $com_code, "employee_code" => $main_salary_employee->employee_code));
            $main_salary_employee->branch = get_field_value(new Branche(), "name", array("com_code" => $com_code, "id" => $main_salary_employee->branch_id));
            $main_salary_employee->department = get_field_value(new Department(), "name", array("com_code" => $com_code, "id" => $main_salary_employee->emp_departments_id));
            $main_salary_employee->job = get_field_value(new Jobs_categories(), "name", array("com_code" => $com_code, "id" => $main_salary_employee->emp_job_id));

            return view('admin.main_salary_employee.salary_details', ['data' => $main_salary_employee, 'financeMonth_data' => $finance_month_data]);
    }

    // Stop Employee salary -> Ajax
    public function stopSalary($id)
    {
        $com_code = auth()->user()->com_code;

        $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("finance_month_id", "is_archived", "is_stoped"), 
        array("com_code" => $com_code, 'id' => $id, "is_archived" => 0));
        if (empty($main_salary_employee)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى راتب الموظف المطلوب']);
        }

        $finance_month_data = get_cols_where_row(new Finance_months_periods(), array("is_open"), 
        array("com_code" => $com_code, 'id' => $main_salary_employee['finance_month_id'], 'is_open' => 1));
        // dd($main_salary_employee);
        if (empty($finance_month_data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى الشهر المالي المطلوب']);
        }

        if ($main_salary_employee['is_archived'] == 1 or $finance_month_data['is_open'] != 1) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على ايقاف راتب الموظف']);
        }

        if ($main_salary_employee['is_stoped'] == 1) {
            return redirect()->back()->with(['error' => 'عفواً هذا الراتب موقوف بالفعل']);
        }

        $dataToUpdate['is_stoped'] = 1;
        $dataToUpdate['updated_by'] = auth()->user()->id;

        $flagUpdate = update(new Main_salary_employee(), $dataToUpdate, array("com_code" => $com_code, 'id' => $id));
        if (!empty($flagUpdate)) {
            return redirect()->back()->with(['success' => 'تم ايقاف راتب الموظف بنجاح']);
        }

        return redirect()->back()->with(['error' => 'عفواً غير قادر على ايقاف راتب الموظف']);

    }

    // Stop Employee salary -> Ajax
    public function resumeSalary($id) 
    {
        $com_code = auth()->user()->com_code;

        $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("finance_month_id", "is_archived", "is_stoped"), array("com_code" => $com_code, 'id' => $id, "is_archived" => 0));
        if (empty($main_salary_employee)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى راتب الموظف المطلوب']);
        }

        $finance_month_data = get_cols_where_row(new Finance_months_periods(), array("is_open"), array("com_code" => $com_code, 'id' => $main_salary_employee['finance_month_id'], 'is_open' => 1));
        if (empty($finance_month_data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى الشهر المالي المطلوب']);
        }

        if ($main_salary_employee['is_archived'] == 1 or $finance_month_data['is_open'] != 1) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على ايقاف راتب الموظف']);
        }

        if ($main_salary_employee['is_stoped'] == 0) {
            return redirect()->back()->with(['error' => 'عفواً هذا الراتب مفعل بالفعل']);
        }

        $dataToUpdate['is_stoped'] = 0;
        $dataToUpdate['updated_by'] = auth()->user()->id;

        $flagUpdate = update(new Main_salary_employee(), $dataToUpdate, array("com_code" => $com_code, 'id' => $id));
        if (!empty($flagUpdate)) {
            return redirect()->back()->with(['success' => 'تم تفعيل راتب الموظف بنجاح']);
        }

        return redirect()->back()->with(['error' => 'عفواً غير قادر على تفعيل راتب الموظف']);

    }

    // Delete Employee salary from inside Details -> Ajax
    public function detailsDeleteSalary($id) 
    {
        // echo "<script>alert('Helllloooooo Woooorldddd')</script>";
        $com_code = auth()->user()->com_code;

        $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("finance_month_id", "is_archived", "is_stoped"), array("com_code" => $com_code, 'id' => $id, "is_archived" => 0));
        if (empty($main_salary_employee)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى راتب الموظف المطلوب']);
        }

        $finance_month_data = get_cols_where_row(new Finance_months_periods(), array("is_open"), array("com_code" => $com_code, 'id' => $main_salary_employee['finance_month_id'], 'is_open' => 1));
        if (empty($finance_month_data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى الشهر المالي المطلوب']);
        }

        if ($main_salary_employee['is_archived'] == 1 or $finance_month_data['is_open'] != 1 or $main_salary_employee['is_stoped'] == 1) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على حذف راتب الموظف']);
        }

        $check_p_loans_installments = get_cols_where_row(new Main_salary_p_loans_installment(), array("id"), array("com_code" => $com_code, 'main_salary_employee_id' => $id));
        if (!empty($check_p_loans_installments)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على حذف راتب الموظف لوجود اقساط']);
        }

        $flagUpdate = destroy(new Main_salary_employee(), array("com_code" => $com_code, 'id' => $id));
        if (!empty($flagUpdate)) {
            return redirect()->route('mainsalaryemployee.show', $main_salary_employee['finance_month_id'])->with(['success' => 'تم حذف راتب الموظف بنجاح']);
        }

        return redirect()->back()->with(['error' => 'عفواً غير قادر على حذف راتب الموظف']);

    }

    // Show Archive Employee salary from inside Details -> Ajax
    public function detailsShowArchiveSalary(Request $request) 
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("*"), array("com_code" => $com_code, 'id' => $request->id, "is_archived" => 0, "is_stoped" => 0));
            if (empty($main_salary_employee)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى راتب الموظف المطلوب']);
            }

            $finance_month_data = get_cols_where_row(new Finance_months_periods(), array("*"), array("com_code" => $com_code, 'id' => $main_salary_employee['finance_month_id'], 'is_open' => 1));

            return view("admin.main_salary_employee.archive_salary", ["main_salary_employee" => $main_salary_employee, 'finance_month_data' => $finance_month_data]);
        }

    }

    // Archive Employee salary -> Ajax
    public function detailsArchiveSalary($id)
    {
        $com_code = auth()->user()->com_code;

        $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("finance_month_id", "is_archived", "is_stoped"), 
        array("com_code" => $com_code, 'id' => $id, "is_archived" => 0));
        if (empty($main_salary_employee)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى راتب الموظف المطلوب']);
        }

        $finance_month_data = get_cols_where_row(new Finance_months_periods(), array("is_open"), 
        array("com_code" => $com_code, 'id' => $main_salary_employee['finance_month_id'], 'is_open' => 1));
        if (empty($finance_month_data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى الشهر المالي المطلوب']);
        }

        if ($main_salary_employee['is_archived'] == 1 or $main_salary_employee['is_stoped'] == 1 or $finance_month_data['is_open'] != 1) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على ايقاف راتب الموظف']);
        }

        $dataToUpdate['is_archived'] = 1;
        $dataToUpdate['archived_by'] = auth()->user()->id;
        $dataToUpdate['archived_date'] = date('Y-m-d H:i:s');
        $dataToUpdate['updated_by'] = auth()->user()->id;

        // If the final_the_net for the employee is less than 0, then set the final_the_net_after_close to the final_the_net
        // else set the final_the_net_after_close to 0
        if ($main_salary_employee['final_the_net'] < 0) {
            $dataToUpdate['final_the_net_after_close'] = $main_salary_employee['final_the_net'];
        } else {
            $dataToUpdate['final_the_net_after_close'] = 0;
        }

        $flagUpdate = update(new Main_salary_employee(), $dataToUpdate, array("com_code" => $com_code, 'id' => $id, 'is_archived' => 0, 'is_stoped' => 0));
        if (!empty($flagUpdate)) {
            return redirect()->back()->with(['success' => 'تم أرشفة راتب الموظف بنجاح']);
        }

        return redirect()->back()->with(['error' => 'عفواً غير قادر على ايقاف راتب الموظف']);

    }

    // Archive Employee salary -> Ajax
    public function detailsPrintSalary($id)
    {
        $com_code = auth()->user()->com_code;

        $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("*"), 
        array("com_code" => $com_code, 'id' => $id, "is_archived" => 0));
        if (empty($main_salary_employee)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى راتب الموظف المطلوب']);
        }

        $finance_month_data = get_cols_where_row(new Finance_months_periods(), array("*"), 
        array("com_code" => $com_code, 'id' => $main_salary_employee['finance_month_id'], 'is_open' => 1));
        if (empty($finance_month_data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول الى الشهر المالي المطلوب']);
        }

        $main_salary_employee->emp_name = get_field_value(new Employee(), "emp_name", array("com_code" => $com_code, "employee_code" => $main_salary_employee->employee_code));
        $main_salary_employee->branch = get_field_value(new Branche(), "name", array("com_code" => $com_code, "id" => $main_salary_employee->branch_id));
        $main_salary_employee->department = get_field_value(new Department(), "name", array("com_code" => $com_code, "id" => $main_salary_employee->emp_departments_id));
        $main_salary_employee->job = get_field_value(new Jobs_categories(), "name", array("com_code" => $com_code, "id" => $main_salary_employee->emp_job_id));

        $systemData = get_cols_where_row(new Admin_panel_settings(), array('company_name', 'image', 'phones', 'address'), array('com_code' => $com_code));
        return view('admin.main_salary_employee.print_salary', ['data' => $main_salary_employee, 'financeMonth_data' => $finance_month_data, 'systemData' => $systemData/*, 'totals' => $other*/]);

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

            return view('admin.main_salary_employee.ajax_search', ['data' => $data]);
        }
    }

    // Search Main Salary -> Ajax
    public function showAjaxSearch(Request $request)
    {
        if ($request->ajax()) {
            alert("Controller Work");
            $com_code = auth()->user()->id;
            $employee_code = $request->employee_code;
            $branch = $request->branch;
            $department = $request->department;
            $job_type = $request->job_type;
            $functional_status = $request->functional_status;
            $sal_cash_or_visa = $request->sal_cash_or_visa;
            $is_stoped = $request->is_stoped;
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

            if ($branch == "all") {
                $field2 = 'id';
                $operator2 = '>';
                $value2 = 0;
            } else {
                $field2 = 'branch_id';
                $operator2 = '=';
                $value2 = $branch;
            }

            if ($department == "all") {
                $field3 = 'id';
                $operator3 = '>';
                $value3 = 0;
            } else {
                $field3 = 'emp_departments_id';
                $operator3 = '=';
                $value3 = $department;
            }

            if ($job_type == "all") {
                $field4 = 'id';
                $operator4 = '>';
                $value4 = 0;
            } else {
                $field4 = 'emp_job_id';
                $operator4 = '=';
                $value4 = $job_type;
            }

            if ($functional_status == "all") {
                $field5 = 'id';
                $operator5 = '>';
                $value5 = 0;
            } else {
                $field5 = 'functional_status';
                $operator5 = '=';
                $value5 = $functional_status;
            }

            if ($sal_cash_or_visa == "all") {
                $field6 = 'id';
                $operator6 = '>';
                $value6 = 0;
            } else {
                $field6 = 'sal_cash_or_visa';
                $operator6 = '=';
                $value6 = $sal_cash_or_visa;
            }

            if ($is_stoped == "all") {
                $field7 = 'id';
                $operator7 = '>';
                $value7 = 0;
            } else {
                $field7 = 'is_stoped';
                $operator7 = '=';
                $value7 = $is_stoped;
            }

            if ($is_archived == "all") {
                $field8 = 'id';
                $operator8 = '>';
                $value8 = 0;
            } else {
                $field8 = 'is_archived';
                $operator8 = '=';
                $value8 = $is_archived;
            }

            $data = Main_salary_employee::select('*')
                ->where($field1, $operator1, $value1)
                ->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)
                ->where($field4, $operator4, $value4)
                ->where($field5, $operator5, $value5)
                ->where($field6, $operator6, $value6)
                ->where($field7, $operator7, $value7)
                ->where($field8, $operator8, $value8)
                ->where(['com_code' => $com_code, 'finance_month_id' => $finance_month_period_id])->orderby('id', 'DESC')->paginate(P_C);

            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->employeeData = get_cols_where_row(new Employee(), array("emp_name", "emp_sal", "day_price"), array("com_code" => $com_code, "employee_code" => $info->employee_code));
                    $info->branch = get_field_value(new Branche(), "name", array("com_code" => $com_code, "id" => $info->branch_id));
                    $info->department = get_field_value(new Department(), "name", array("com_code" => $com_code, "id" => $info->emp_departments_id));
                    $info->job = get_field_value(new Jobs_categories(), "name", array("com_code" => $com_code, "id" => $info->emp_job_id));
                }
            }

            return view('admin.main_salary_employee.show_ajax_search', ['data' => $data]);
        }
    }

    // Print the Main Salary search result 
    public function printSearch(Request $request)
    {
        $com_code = auth()->user()->id;
        $employee_code = $request->employee_code;
        $branch = $request->branch;
        $department = $request->department;
        $job_type = $request->job_type;
        $functional_status = $request->functional_status;
        $sal_cash_or_visa = $request->sal_cash_or_visa;
        $is_stoped = $request->is_stoped;
        $is_archived = $request->is_archived;
        $finance_month_period_id = $request->finance_month_period_id;

        $financeMonth_data = get_cols_where_row(new Finance_months_periods(), array('*'), array('com_code' => $com_code, 'id' => $finance_month_period_id));

        if ($employee_code == "all") {
            $field1 = 'id';
            $operator1 = '>';
            $value1 = 0;
        } else {
            $field1 = 'employee_code';
            $operator1 = '=';
            $value1 = $employee_code;
        }

        if ($branch == "all") {
            $field2 = 'id';
            $operator2 = '>';
            $value2 = 0;
        } else {
            $field2 = 'branch_id';
            $operator2 = '=';
            $value2 = $branch;
        }

        if ($department == "all") {
            $field3 = 'id';
            $operator3 = '>';
            $value3 = 0;
        } else {
            $field3 = 'emp_departments_id';
            $operator3 = '=';
            $value3 = $department;
        }

        if ($job_type == "all") {
            $field4 = 'id';
            $operator4 = '>';
            $value4 = 0;
        } else {
            $field4 = 'emp_job_id';
            $operator4 = '=';
            $value4 = $job_type;
        }

        if ($functional_status == "all") {
            $field5 = 'id';
            $operator5 = '>';
            $value5 = 0;
        } else {
            $field5 = 'functional_status';
            $operator5 = '=';
            $value5 = $functional_status;
        }

        if ($sal_cash_or_visa == "all") {
            $field6 = 'id';
            $operator6 = '>';
            $value6 = 0;
        } else {
            $field6 = 'sal_cash_or_visa';
            $operator6 = '=';
            $value6 = $sal_cash_or_visa;
        }

        if ($is_stoped == "all") {
            $field7 = 'id';
            $operator7 = '>';
            $value7 = 0;
        } else {
            $field7 = 'is_stoped';
            $operator7 = '=';
            $value7 = $is_stoped;
        }

        if ($is_archived == "all") {
            $field8 = 'id';
            $operator8 = '>';
            $value8 = 0;
        } else {
            $field8 = 'is_archived';
            $operator8 = '=';
            $value8 = $is_archived;
        }

        $data = Main_salary_employee::select('*')
            ->where($field1, $operator1, $value1)
            ->where($field2, $operator2, $value2)
            ->where($field3, $operator3, $value3)
            ->where($field4, $operator4, $value4)
            ->where($field5, $operator5, $value5)
            ->where($field6, $operator6, $value6)
            ->where($field7, $operator7, $value7)
            ->where($field8, $operator8, $value8)
            ->where(['com_code' => $com_code, 'finance_month_id' => $finance_month_period_id])->orderby('id', 'DESC')->paginate(P_C);

        if (!empty($data)) {
            foreach ($data as $info) {
                $info->employeeData = get_cols_where_row(new Employee(), array("emp_name", "emp_sal", "day_price"), array("com_code" => $com_code, "employee_code" => $info->employee_code));
                $info->branch = get_field_value(new Branche(), "name", array("com_code" => $com_code, "id" => $info->branch_id));
                $info->department = get_field_value(new Department(), "name", array("com_code" => $com_code, "id" => $info->emp_departments_id));
                $info->job = get_field_value(new Jobs_categories(), "name", array("com_code" => $com_code, "id" => $info->emp_job_id));
            }
        }

        $systemData = get_cols_where_row(new Admin_panel_settings(), array('company_name', 'image', 'phones', 'address'), array('com_code' => $com_code));

        if($request->print_search == "detailed") {
            return view('admin.main_salary_employee.print_search_detailed', ['data' => $data, 'financeMonth_data' => $financeMonth_data, 'systemData' => $systemData]);
        } else {
            $data->totalSalaries = Main_salary_employee::select('*')
            ->where($field1, $operator1, $value1)
            ->where($field2, $operator2, $value2)
            ->where($field3, $operator3, $value3)
            ->where($field4, $operator4, $value4)
            ->where($field5, $operator5, $value5)
            ->where($field6, $operator6, $value6)
            ->where($field7, $operator7, $value7)
            ->where($field8, $operator8, $value8)
            ->where(['com_code' => $com_code, 'finance_month_id' => $finance_month_period_id])->sum('emp_sal');
            $data->totalBenefits = Main_salary_employee::select('*')
            ->where($field1, $operator1, $value1)
            ->where($field2, $operator2, $value2)
            ->where($field3, $operator3, $value3)
            ->where($field4, $operator4, $value4)
            ->where($field5, $operator5, $value5)
            ->where($field6, $operator6, $value6)
            ->where($field7, $operator7, $value7)
            ->where($field8, $operator8, $value8)
            ->where(['com_code' => $com_code, 'finance_month_id' => $finance_month_period_id])->sum('total_benefits');
            $data->totalDeduction = Main_salary_employee::select('*')
            ->where($field1, $operator1, $value1)
            ->where($field2, $operator2, $value2)
            ->where($field3, $operator3, $value3)
            ->where($field4, $operator4, $value4)
            ->where($field5, $operator5, $value5)
            ->where($field6, $operator6, $value6)
            ->where($field7, $operator7, $value7)
            ->where($field8, $operator8, $value8)
            ->where(['com_code' => $com_code, 'finance_month_id' => $finance_month_period_id])->sum('total_deduction');
            $data->finalTheNet = Main_salary_employee::select('*')
            ->where($field1, $operator1, $value1)
            ->where($field2, $operator2, $value2)
            ->where($field3, $operator3, $value3)
            ->where($field4, $operator4, $value4)
            ->where($field5, $operator5, $value5)
            ->where($field6, $operator6, $value6)
            ->where($field7, $operator7, $value7)
            ->where($field8, $operator8, $value8)
            ->where(['com_code' => $com_code, 'finance_month_id' => $finance_month_period_id])->sum('final_the_net');

            return view('admin.main_salary_employee.print_search', ['data' => $data, 'financeMonth_data' => $financeMonth_data, 'systemData' => $systemData]);
        }
    }
}
