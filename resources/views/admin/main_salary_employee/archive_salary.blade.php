    @if (!@empty($main_salary_employee) and !@empty($finance_month_data))

    <form class="row" action="{{ route('mainsalaryemployee.detailsArchiveSalary', $main_salary_employee->id) }}" method="post">
        @csrf
        <div class="col-md-6">
            <div class="form-group">
                <label for="employee_code"> حالة راتب الموضف الان: </label>
                <select class="form-control select2" id="employee_code" name="employee_code">
                    @if($main_salary_employee->final_the_net > 0)
                    <option value="1">دائن مستحق له</option>
                    @elseif ($main_salary_employee->final_the_net < 0)
                    <option value="2">مدين مستحق عليه</option>
                    @else
                    <option value="0">متزن ليس له أو عليه</option>
                    @endif
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                @if($main_salary_employee->final_the_net > 0)
                <label for="final_the_net">صافي المبلغ المستحق له: (رس)</label>
                <input readonly type="text" class="form-control" name="final_the_net" id="final_the_net" value="{{ $main_salary_employee->final_the_net * 1 }}">
                @elseif ($main_salary_employee->final_the_net < 0)
                <label for="final_the_net">صافي المبلغ المستحق عليه: (رس)</label>
                <input readonly type="text" class="form-control" name="final_the_net" id="final_the_net" value="{{ $main_salary_employee->final_the_net * 1 }}">
                @else
                <label for="final_the_net">المبلغ المتزن: (رس)</label>
                <input readonly type="text" class="form-control" name="final_the_net" id="final_the_net" value="{{ $main_salary_employee->final_the_net * 1 }}">
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                @if($main_salary_employee->final_the_net > 0)
                <label for="actual_money_value">صافي المبلغ المصروف له: (رس)</label>
                <input readonly type="text" class="form-control" name="actual_money_value" id="actual_money_value" value="{{ $main_salary_employee->final_the_net * 1 }}" data-max="{{ $main_salary_employee->final_the_net * 1 }}">
                @elseif ($main_salary_employee->final_the_net < 0)
                <label for="actual_money_value">صافي المبلغ المدين به الموظف وسيرحل الى الشهر القادم: (رس)</label>
                <input readonly type="text" class="form-control" name="actual_money_value" id="actual_money_value" value="{{ $main_salary_employee->final_the_net * 1 }}" data-max="{{ $main_salary_employee->final_the_net * 1 }}">
                @else
                <label for="actual_money_value">المبلغ المتزن: (رس)</label>
                <input readonly type="text" class="form-control" name="actual_money_value" id="actual_money_value" value="{{ $main_salary_employee->final_the_net * 1 }}" data-max="{{ $main_salary_employee->final_the_net * 1 }}">
                @endif
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group mx-auto col-md-6">
                <button type="submit" class="form-control btn-primary" id="do_archive_salary"> أرشفة </button>
            </div>
        </div>
    </form>

    @else
    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
    @endif
