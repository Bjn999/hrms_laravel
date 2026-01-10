<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin_panel_settings;
use App\Models\Employee;
use App\Models\Finance_calenders;
use App\Models\Finance_months_periods;
use App\Models\Main_salary_employee;
use App\Models\Main_salary_employee_permanent_loan;
use App\Models\Main_salary_p_loans_installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Generator\StringManipulation\Pass\Pass;

class Main_salary_employee_permanent_loansController extends Controller
{
    // 
    public function index()
    {
        $com_code = auth()->user()->com_code;

        $data = get_cols_where_p(new Main_salary_employee_permanent_loan(), array("*"), array("com_code" => $com_code), "id", "DESC", P_C);

        $other['employees'] = get_cols_where(new Employee(), array("employee_code", "emp_name", "emp_sal", "day_price"), array("com_code" => $com_code, 'functional_status' => 1));

        return view("admin.main_salary_employee_permanent_loans.index", ["data" => $data, 'other' => $other]);
    }

    // Check If the employee has permanent loans before -> Ajax
    public function checkExist(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->id;
            $checkExist = get_count_where(new Main_salary_employee_permanent_loan(), array('com_code' => $com_code, 'employee_code' => $request->employee_code, 'is_archived' => 0));
            if ($checkExist > 0) {
                return json_encode("exists");
            } else {
                return json_encode("not_exists");
            }
        }
    }

    // Add permanent loan for employee in a current finance month -> Ajax
    public function store(Request $request)
    {
        try {
            if ($request->ajax()) {
                $com_code = auth()->user()->id;

                $employee = get_cols_where_row(new Employee(), array("id"), array("com_code" => $com_code, 'employee_code' => $request->employee_code, 'functional_status' => 1));
                if (!empty($employee)) {
                    DB::beginTransaction();
                    $dataToInsert['employee_code'] = $request->employee_code;
                    $dataToInsert['emp_sal'] = $request->emp_sal;
                    $dataToInsert['total'] = $request->total;
                    $dataToInsert['months_number'] = $request->months_number;
                    $dataToInsert['monthly_installment_value'] = $request->monthly_installment_value;
                    $dataToInsert['year_and_month_start_date'] = $request->year_and_month_start_date;
                    $dataToInsert['year_and_month_start'] = date("Y-m", strtotime($request->year_and_month_start_date));
                    $dataToInsert['total_remain'] = $request->total;
                    $dataToInsert['notes'] = $request->notes;

                    $dataToInsert['com_code'] = $com_code;
                    $dataToInsert['added_by'] = auth()->user()->id;

                    $flagParent_loan = insert(new Main_salary_employee_permanent_loan(), $dataToInsert, true);

                    if ($flagParent_loan) {
                        # Partitioning the new permanent loan 
                        $i = 1;
                        $effectivedate = $flagParent_loan['year_and_month_start'];
                        while ($i <= $flagParent_loan['months_number']) {
                            $installmentToInsert['main_salary_p_loans_id'] = $flagParent_loan['id'];
                            $installmentToInsert['monthly_installment_value'] = $dataToInsert['monthly_installment_value'];
                            $installmentToInsert['year_and_month'] = $effectivedate;
                            $installmentToInsert['added_by'] = auth()->user()->id;
                            $installmentToInsert['com_code'] = auth()->user()->com_code;

                            insert(new Main_salary_p_loans_installment(), $installmentToInsert);

                            $i++;
                            $effectivedate = date('Y-m', strtotime('+1 months', strtotime($effectivedate)));
                        }
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

    // Show Edit permanent loan modalForm for employee in a current finance month -> Ajax 
    public function edit(Request $request)
    {
        try {
            $com_code = auth()->user()->id;
            // $p_loan_data = get_cols_where_row(new Main_salary_employee_permanent_loan(), array('*'), array('com_code' => $com_code, 'id' => $request->id, 'is_archived' => 0, 'is_dismissal' => 0));
            $p_loan_data = get_cols_where_row(new Main_salary_employee_permanent_loan(), array('*'), array('com_code' => $com_code, 'id' => $request->id));

            if (!empty($p_loan_data)) {

                if ($p_loan_data->is_archived != 0 || $p_loan_data->is_dismissal != 0) {
                    return json_encode('unable');
                }

                $employees = get_cols_where(new Employee(), array("employee_code", "emp_name", "emp_sal", "day_price"), array("com_code" => $com_code, 'functional_status' => 1));

                return view('admin.main_salary_employee_permanent_loans.edit_permanent_loan', ['p_loan_data' => $p_loan_data, 'employees' => $employees]);
            } else {
                return json_encode('none');
            }
        } catch (\Exception $ex) {
            return json_encode('error : ' . $ex->getMessage());
        }
    }

    // 
    public function update(Request $request)
    {
        // dd($request);
        try {
            $com_code = auth()->user()->id;

            $p_loan_data = get_cols_where_row(new Main_salary_employee_permanent_loan(), array("*"), array('com_code' => $com_code, 'id' => $request->id));
            if (!empty($p_loan_data)) {
                DB::beginTransaction();
                $dataToUpdate['employee_code'] = $request->employee_code;
                $dataToUpdate['total'] = $request->total;
                $dataToUpdate['months_number'] = $request->months_number;
                $dataToUpdate['monthly_installment_value'] = $request->monthly_installment_value;
                $dataToUpdate['year_and_month_start_date'] = $request->year_and_month_start_date;
                $dataToUpdate['year_and_month_start'] = date("Y-m", strtotime($request->year_and_month_start_date));
                $dataToUpdate['total_remain'] = $request->total;
                $dataToUpdate['notes'] = $request->notes;

                $dataToUpdate['updated_by'] = auth()->user()->id;

                $flagParent_loan = update(new Main_salary_employee_permanent_loan(), $dataToUpdate, ['com_code' => $com_code, 'id' => $request->id]);

                // Updating its installments 
                if ($flagParent_loan) {
                    # Partitioning the new permanent loan 
                    // $child_loans = get_cols_where(new Main_salary_p_loans_installment(), ['*'], ['com_code' => $com_code, 'main_salary_p_loans_id' => $request->id], 'id', 'ASC');

                    /////////////////////////////////////////////////////////////////
                    if ($p_loan_data['total'] != $dataToUpdate['total'] or $p_loan_data['months_number'] != $dataToUpdate['months_number'] or $p_loan_data['monthly_installment_value'] != $dataToUpdate['monthly_installment_value'] or $p_loan_data['year_and_month_start_date'] != $dataToUpdate['year_and_month_start_date']) {
                        ///////////////////////////////////////// 
                        // Delete All current installments 
                        $flagDelete = destroy(new Main_salary_p_loans_installment(), [['com_code', '=', $com_code], ['main_salary_p_loans_id', '=', $request->id]]);

                        if ($flagDelete) {
                            //////////////////////////
                            // Add new installments 
                            $i = 1;
                            $effectivedate = $dataToUpdate['year_and_month_start'];
                            while ($i <= $dataToUpdate['months_number']) {
                                $newInstallmentToInsert['main_salary_p_loans_id'] = $request->id;
                                $newInstallmentToInsert['monthly_installment_value'] = $dataToUpdate['monthly_installment_value'];
                                $newInstallmentToInsert['year_and_month'] = $effectivedate;
                                $newInstallmentToInsert['added_by'] = auth()->user()->id;
                                $newInstallmentToInsert['com_code'] = auth()->user()->com_code;

                                insert(new Main_salary_p_loans_installment(), $newInstallmentToInsert);

                                $i++;
                                $effectivedate = date('Y-m', strtotime('+1 months', strtotime($effectivedate)));
                            }
                        }
                    }
                }

                DB::commit();

                // return redirect()->route('mainsalarypermanent_loan.index')->with(['success' => 'تم تحديث السلفة بنجاح']);
                return json_encode('success');
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            // return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما : ' . $ex->getMessage()])->withInput();
            return json_encode('error');
        }
    }

    // Show All permanent loan installments -> Ajax 
    public function p_loan_installments(Request $request)
    {
        try {
            if ($request->ajax()) {
                $com_code = auth()->user()->id;
                $p_loan_data = get_cols_where_row(new Main_salary_employee_permanent_loan(), array('*'), array('com_code' => $com_code, 'id' => $request->id));

                if (!empty($p_loan_data)) {
                    $p_loan_installments_data = get_cols_where(new Main_salary_p_loans_installment(), array('*'), array('com_code' => $com_code, 'main_salary_p_loans_id' => $request->id), "id", "ASC");
                }

                return view('admin.main_salary_employee_permanent_loans.permanent_loan_installments', ['p_loan_installments_data' => $p_loan_installments_data, 'p_loan_data' => $p_loan_data]);
            }
        } catch (\Exception $ex) {
            return json_encode('error : ' . $ex->getMessage());
        }
    }

    // Delete permanent loan for employee -> Ajax 
    public function delete(Request $request)
    {
        try {
            if ($request->ajax()) {
                $com_code = auth()->user()->id;
                $p_loan_data = get_cols_where_row(
                    new Main_salary_employee_permanent_loan(),
                    array('*'),
                    array('com_code' => $com_code, 'id' => $request->id)
                );

                if (!empty($p_loan_data)) {

                    if ($p_loan_data->is_archived != 0) {
                        return json_encode('تم أرشفة هذا السلفة بالفعل');
                    }
                    if ($p_loan_data->is_dismissal != 0) {
                        return json_encode('تم صرفة هذا السلفة بالفعل');
                    }

                    DB::beginTransaction();

                    $flagP = destroy(new Main_salary_employee_permanent_loan(), array('com_code' => $com_code, 'id' => $request->id, 'is_archived' => 0, 'is_dismissal' => 0));
                    if ($flagP) {
                        destroy(new Main_salary_p_loans_installment(), array('com_code' => $com_code, 'main_salary_p_loans_id' => $request->id, 'state' => 0));
                    }

                    return json_encode('success');
                } else {
                    return json_encode('هذه السلفة غير موجودة');
                }
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return json_encode('error : ' . $ex->getMessage());
        }
    }

    // Dismiss permanent loan for employee in a current finance month -> Ajax 
    // public function dismiss_p_loan($id)
    public function dismiss_p_loan(Request $request)
    {
        try {
            if ($request->ajax()) {

                // dd($request->id);

                $com_code = auth()->user()->id;
                $p_loan_data = get_cols_where_row(
                    new Main_salary_employee_permanent_loan(),
                    array('*'),
                    /* array('com_code' => $com_code, 'id' => $id) */
                    array('com_code' => $com_code, 'id' => $request->id)
                );

                if (empty($p_loan_data)) {
                    return json_encode('غير قادر على الوصول لهذه السلفة');
                }

                if ($p_loan_data->is_archived != 0) {
                    return json_encode('تم أرشفة هذا السلفة بالفعل');
                }
                if ($p_loan_data->is_dismissal != 0) {
                    return json_encode('تم صرفة هذا السلفة بالفعل');
                }

                DB::beginTransaction();

                $dataToUpdate['is_dismissal'] = 1;
                $dataToUpdate['dismissal_by'] = auth()->user()->id;
                $dataToUpdate['dismissal_at'] = date('Y-m-d H:i:s');

                $dataToUpdate['updated_by'] = auth()->user()->id;

                // $flagParent_loan = update(new Main_salary_employee_permanent_loan(), $dataToUpdate, ['com_code' => $com_code, 'id' => $id]);
                $flagParent_loan = update(new Main_salary_employee_permanent_loan(), $dataToUpdate, ['com_code' => $com_code, 'id' => $request->id]);

                DB::commit();

                // return redirect()->route('mainsalarypermanent_loan.index');
                return json_encode('success');
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return json_encode('Error : Dismiss Faild\n\n' . $ex->getMessage());
            // return json_encode('Error : Dismiss Faild');
        }
    }

    // Search permanent loans By EmpCode - IsArchived.!! -> Ajax
    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->id;
            $employee_code = $request->employee_code;
            $is_archived = $request->is_archived;
            $is_dismissal = $request->is_dismissal;


            if ($employee_code == "all") {
                $field1 = 'id';
                $operator1 = '>';
                $value1 = 0;
            } else {
                $field1 = 'employee_code';
                $operator1 = '=';
                $value1 = $employee_code;
            }

            if ($is_dismissal == "all") {
                $field2 = 'id';
                $operator2 = '>';
                $value2 = 0;
            } else {
                $field2 = 'is_dismissal';
                $operator2 = '=';
                $value2 = $is_dismissal;
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

            $data = Main_salary_employee_permanent_loan::select('*')->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)
                ->where(['com_code' => $com_code])->orderby('id', 'DESC')->get();

            return view('admin.main_salary_employee_permanent_loans.ajax_search', ['data' => $data]);
        }
    }

    // Print the permanent loans search result 
    public function printSearch(Request $request)
    {
        $com_code = auth()->user()->id;
        $employee_code = $request->employee_code_search_permanent_loan;
        $is_dismissal = $request->is_dismissal_search_permanent_loan;
        $is_archived = $request->is_archived_search_permanent_loan;

        if ($employee_code == "all") {
            $field1 = 'id';
            $operator1 = '>';
            $value1 = 0;
        } else {
            $field1 = 'employee_code';
            $operator1 = '=';
            $value1 = $employee_code;
        }

        if ($is_dismissal == "all") {
            $field2 = 'id';
            $operator2 = '>';
            $value2 = 0;
        } else {
            $field2 = 'is_dismissal';
            $operator2 = '=';
            $value2 = $is_dismissal;
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

        $other['total_sum'] = 0;
        $data = Main_salary_employee_permanent_loan::select('*')->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)
            ->where(['com_code' => $com_code])->orderby('id', 'DESC')->get();
        $systemData = get_cols_where_row(new Admin_panel_settings(), array('company_name', 'image', 'phones', 'address'), array('com_code' => $com_code));

        if (!empty($data)) {
            foreach ($data as $info) {
                $other['total_sum'] += $info->total;
            }
        }

        return view('admin.main_salary_employee_permanent_loans.print_search', ['data' => $data, 'systemData' => $systemData, 'totals' => $other]);
    }
}
