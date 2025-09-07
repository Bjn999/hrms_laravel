<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Finance_calenders;
use App\Models\Finance_months_periods;
use App\Models\Main_salary_employee;

class Main_Salary_RecordController extends Controller
{
    // Show the page of Months records 
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

        return view("admin.main_salary_record.index", ["data" => $data, 'finance_years' => $finance_years]);
    }

    // Open the month record 
    public function do_open_month(Request $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Finance_months_periods(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route("mainsalaryrecord.index")->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            // Check if the year of this month is Exists 
            $currentYear = get_cols_where_row(new Finance_calenders(), array('is_open'), array('com_code' => $com_code, 'finance_yr' => $data->finance_yr));
            if (empty($currentYear)) {
                return redirect()->route("mainsalaryrecord.index")->with(['error' => 'عفواً غير قادر على الوصول لبيانات السنة المالية المطلوبة']);
            }

            // Check if the year of this month is not opened 
            if ($currentYear['is_open'] != 1) {
                return redirect()->route("mainsalaryrecord.index")->with(['error' => 'عفواً السنة المالية التابع لها هذا الشهر غير مفتوحة حالياً']);
            }

            // Check if the month is opened 
            if ($data['is_open'] == 1) {
                return redirect()->route("mainsalaryrecord.index")->with(['error' => 'عفواً هذا الشهر بالفعل مفتوح حالياً']);
            }

            // Check if the month is Archived 
            if ($data['is_open'] == 2) {
                return redirect()->route("mainsalaryrecord.index")->with(['error' => 'عفواً هذا الشهر بالفعل مؤرشف من قبل']);
            }

            // Check if there is other month already open 
            $counterOpenMonth = get_count_where(new Finance_months_periods(), array('com_code' => $com_code, 'is_open' => 1));
            if ($counterOpenMonth > 0) {
                return redirect()->route("mainsalaryrecord.index")->with(['error' => 'عفواً لا يمكن فتح هذا الشهر لوجود شهر آخر مفتوح حالياً']);
            }

            // Check if there is other month before this month doesn't open yet 
            $counterPreviousMonthWaitingOpen = Finance_months_periods::where(['com_code' => $com_code, 'is_open' => 0, 'finance_yr' => $data->finance_yr])
                ->where('month_id', '<', $data->month_id)->count();
            if ($counterPreviousMonthWaitingOpen > 0) {
                return redirect()->route("mainsalaryrecord.index")->with(['error' => 'عفواً لا يمكن فتح هذا الشهر لوجود شهر آخر قبله يستحق الفتح أولاً']);
            }

            $dataToUpdate['start_date_for_pasma'] = $request->start_date_for_pasma;
            $dataToUpdate['end_date_for_pasma'] = $request->end_date_for_pasma;
            $dataToUpdate['is_open'] = 1;
            $dataToUpdate['updated_by'] = auth()->user()->id;

            DB::beginTransaction();

            $falg = update(new Finance_months_periods(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));

            ////
            // Open Employees Salary Codes 
            ////
            if ($falg) {
                // Get All Active Employees with functional_status = 1 
                $all_active_employees = get_cols_where( 
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
                    array('com_code' => $com_code, 'functional_status' => 1),
                    "employee_code",
                    "ASC"
                );

                if (!empty($all_active_employees)) {
                    foreach ($all_active_employees as $info) {
                        $dataSalaryToInsert['finance_month_id'] = $id;
                        $dataSalaryToInsert['employee_code'] = $info->employee_code;
                        $dataSalaryToInsert['com_code'] = $com_code;

                        $checkExistCounter = get_count_where(new Main_salary_employee(), $dataSalaryToInsert);

                        if ($checkExistCounter == 0) {
                            $dataSalaryToInsert['emp_name'] = $info->emp_name;
                            $dataSalaryToInsert['is_sensitive_manager_data'] = $info->is_sensitive_manager_data;
                            $dataSalaryToInsert['branch_id'] = $info->branch_id;
                            $dataSalaryToInsert['emp_departments_id'] = $info->emp_departments_id;
                            $dataSalaryToInsert['emp_job_id'] = $info->emp_job_id;
                            $dataSalaryToInsert['functional_status'] = $info->functional_status;
                            $dataSalaryToInsert['emp_sal'] = $info->emp_sal;
                            $dataSalaryToInsert['day_price'] = $info->day_price;
                            // Re-Explain it 
                            $dataSalaryToInsert['last_salary_remain_balance'] = 0;
                            // 
                            $dataSalaryToInsert['year_and_month'] = $data->year_and_month;
                            $dataSalaryToInsert['finance_yr'] = $data->finance_yr;
                            $dataSalaryToInsert['sal_cash_or_visa'] = $info->sal_cash_or_visa;

                            $dataSalaryToInsert['added_by'] = auth()->user()->id;

                            insert(new Main_salary_employee(), $dataSalaryToInsert);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route("mainsalaryrecord.index")->with(['success' => 'تم فتح الشهر المالي بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما ' . $ex->getMessage()]);
        }
    }

    public function load_open_month(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Finance_months_periods(), array('*'), array('com_code' => $com_code, 'id' => $id));
            // if (empty($data)) {
            //     return redirect()->route("mainsalaryrecord.index")->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            // }
            
            return view('admin.main_salary_record.load_open_monthModal', ['data' => $data]);
        }
    }

    // Search By Finance year !!
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

            return view('admin.main_salary_record.ajax_search', ['data' => $data]);
        }
    }
}
