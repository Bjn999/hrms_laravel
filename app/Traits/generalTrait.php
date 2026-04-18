<?php

namespace App\Traits;

use App\Http\Controllers\admin\Main_salary_employee_permanent_loansController;
use App\Models\Additional_sal_type;
use App\Models\Employee;
use App\Models\Employee_fixed_allowance;
use App\Models\Finance_months_periods;
use App\Models\Main_salary_employee;
use App\Models\Main_salary_employee_sanction;
use App\Models\Main_salary_employee_addition;
use App\Models\Main_salary_employee_absence;
use App\Models\Main_salary_employee_discount;
use App\Models\Main_salary_employee_reward;
use App\Models\Main_salary_employee_allowance;
use App\Models\Main_salary_employee_loan;
use App\Models\Main_salary_employee_permanent_loan;
use App\Models\Main_salary_p_loans_installment;

trait generalTrait
{
    function recaculate_main_salary_employee($main_salary_employee_Id)
    {
        $com_code = auth()->user()->com_code;
        $main_salary_employee_data = get_cols_where_row(new Main_salary_employee(), ['*'], ['com_code' => $com_code, 'id' => $main_salary_employee_Id, 'is_archived' => 0]);
        if (!empty($main_salary_employee_data)) {
            $employeeData = get_cols_where_row(new Employee(), ['id', 'motivation', 'social_insurance_cut_monthly', 'medical_insurance_cut_monthly', 'emp_sal', 'day_price', 'branch_id', 'functional_status', 'emp_job_id', 'emp_departments_id', 'sal_cash_or_visa'], ['com_code' => $com_code, 'employee_code' => $main_salary_employee_data->employee_code]);
            $finance_month_Data = get_cols_where_row(new Finance_months_periods(), ['year_and_month', 'finance_yr'], ['com_code' => $com_code, 'is_open' => 1, 'id' => $main_salary_employee_data->finance_month_id]);

            if (!empty($employeeData) and !empty($finance_month_Data)) {
                //// Additions To Salary ( + ) 
                $dataToUpdate['emp_sal'] = $employeeData['emp_sal'];
                $dataToUpdate['day_price'] = $employeeData['day_price'];
                $dataToUpdate['motivation'] = $employeeData['motivation']; // there are two types fixed & changable 
                $dataToUpdate['fixed_allowances'] = get_sum_where(new Employee_fixed_allowance(), 'value', ['com_code' => $com_code, 'employee_id' => $employeeData->id]); // Temp. until handle it 
                $dataToUpdate['changable_allowances'] = get_sum_where(new Main_salary_employee_allowance(), 'total', ['com_code' => $com_code, 'main_salary_employee_id' => $main_salary_employee_Id]);
                $dataToUpdate['reward'] = get_sum_where(new Main_salary_employee_reward(), 'total', ['com_code' => $com_code, 'main_salary_employee_id' => $main_salary_employee_Id]);
                $dataToUpdate['additional_days_counter'] = get_sum_where(new Main_salary_employee_addition(), 'value', ['com_code' => $com_code, 'main_salary_employee_id' => $main_salary_employee_Id]);
                $dataToUpdate['additional_days_total'] = get_sum_where(new Main_salary_employee_addition(), 'total', ['com_code' => $com_code, 'main_salary_employee_id' => $main_salary_employee_Id]);

                // Calculate all salary benefits 
                $dataToUpdate['total_benefits'] =
                    $dataToUpdate['emp_sal'] +
                    $dataToUpdate['motivation'] +
                    $dataToUpdate['fixed_allowances'] +
                    $dataToUpdate['changable_allowances'] +
                    $dataToUpdate['reward'] +
                    $dataToUpdate['additional_days_total'];

                //// Deductions From Salary ( - ) 
                $dataToUpdate['socialinsurancecutmonthly'] = $employeeData['social_insurance_cut_monthly'];
                $dataToUpdate['medicalinsurancecutmonthly'] = $employeeData['medical_insurance_cut_monthly'];
                $dataToUpdate['sanctions_days_counter'] = get_sum_where(new Main_salary_employee_sanction(), 'value', ['com_code' => $com_code, 'main_salary_employee_id' => $main_salary_employee_Id]);
                $dataToUpdate['sanctions_days_total'] = get_sum_where(new Main_salary_employee_sanction(), 'total', ['com_code' => $com_code, 'main_salary_employee_id' => $main_salary_employee_Id]);
                $dataToUpdate['absence_days_counter'] = get_sum_where(new Main_salary_employee_absence(), 'value', ['com_code' => $com_code, 'main_salary_employee_id' => $main_salary_employee_Id]);
                $dataToUpdate['absence_days_total'] = get_sum_where(new Main_salary_employee_absence(), 'total', ['com_code' => $com_code, 'main_salary_employee_id' => $main_salary_employee_Id]);
                $dataToUpdate['discount'] = get_sum_where(new Main_salary_employee_discount(), 'total', ['com_code' => $com_code, 'main_salary_employee_id' => $main_salary_employee_Id]);
                // Calculate total monthly loans for the finance month 
                $dataToUpdate['monthly_loan'] = get_sum_where(new Main_salary_employee_loan(), 'total', ['com_code' => $com_code, 'main_salary_employee_id' => $main_salary_employee_Id]);
                // Calculate all permanent loans installments for the finance month 
                $dataToUpdate['permanent_loan'] = Main_salary_p_loans_installment::where(
                    [
                        'employee_code' => $main_salary_employee_data->employee_code,
                        'com_code' => $com_code,
                        'year_and_month' => $finance_month_Data['year_and_month'],
                        'is_archived' => 0,
                        'is_parent_dismissal' => 1,
                    ]
                )->where('status', '!=', 2)->sum('monthly_installment_value');

                // Update the status of the installments for the finance month 
                $dataToUpdateInstallment['status'] = 1;
                $dataToUpdateInstallment['main_salary_employee_id'] = $main_salary_employee_Id;
                Main_salary_p_loans_installment::where(
                    [
                        'employee_code' => $main_salary_employee_data->employee_code,
                        'com_code' => $com_code,
                        'year_and_month' => $finance_month_Data['year_and_month'],
                        'is_archived' => 0,
                        'is_parent_dismissal' => 1,
                    ]
                )->where('status', '!=', 2)->update($dataToUpdateInstallment);

                // Calculate all salary deduction 
                $dataToUpdate['total_deduction'] =
                    $dataToUpdate['socialinsurancecutmonthly'] +
                    $dataToUpdate['medicalinsurancecutmonthly'] +
                    $dataToUpdate['sanctions_days_total'] +
                    $dataToUpdate['absence_days_total'] +
                    $dataToUpdate['discount'] +
                    $dataToUpdate['monthly_loan'] +
                    $dataToUpdate['permanent_loan'];

                // The finall salary for the employee 
                $dataToUpdate['final_the_net'] = $main_salary_employee_data['last_salary_remain_balance'] + ($dataToUpdate['total_benefits'] - $dataToUpdate['total_deduction']);

                $dataToUpdate['sal_cash_or_visa'] = $employeeData['sal_cash_or_visa'];
                $dataToUpdate['branch_id'] = $employeeData['branch_id'];
                $dataToUpdate['emp_departments_id'] = $employeeData['emp_departments_id'];
                $dataToUpdate['emp_job_id'] = $employeeData['emp_job_id'];
                $dataToUpdate['functional_status'] = $employeeData['functional_status'];
                $dataToUpdate['phones'] = 0;
                $dataToUpdate['year_and_month'] = $finance_month_Data['year_and_month'];
                $dataToUpdate['finance_yr'] = $finance_month_Data['finance_yr'];
                update(new Main_salary_employee(), $dataToUpdate, ['com_code' => $com_code, 'id' => $main_salary_employee_Id, 'is_archived' => 0]);
            }
        }
    }
}
