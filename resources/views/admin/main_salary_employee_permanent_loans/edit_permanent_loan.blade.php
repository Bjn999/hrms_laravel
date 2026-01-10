    @if (!@empty($p_loan_data) and !@empty($employees))

    <div class="col-md-4">
        <div class="form-group">
            <label for="employee_code_edit_permanent_loan">الموظف: <span class="text-danger">*</span></label>
            <select class="form-control select2" id="employee_code_edit_permanent_loan" name="employee_code_edit_permanent_loan">
                <option selected value="">غير محدد</option>
                @if (isset($employees) and !empty($employees))
                @foreach ($employees as $info)
                <option @if($info->employee_code == $p_loan_data['employee_code']) selected @endif value="{{ $info->employee_code }}" data-salary="{{ $info->emp_sal }}" data-day_price="{{ $info->day_price }}">({{ $info->employee_code }}) {{ $info->emp_name }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-4 related_employee_edit">
        <div class="form-group">
            <label for="emp_sal_edit_permanent_loan">راتب الموظف:</label>
            <input readonly type="text" class="form-control" name="emp_sal_edit_permanent_loan" id="emp_sal_edit_permanent_loan" value="{{ $p_loan_data['emp_sal'] * 1 }}">
        </div>
    </div>
    <div class="col-md-4 related_employee_edit">
        <div class="form-group">
            <label for="day_price_edit_permanent_loan">راتب اليوم للموظف:</label>
            <input readonly type="text" class="form-control" name="day_price_edit_permanent_loan" id="day_price_edit_permanent_loan" value="{{ $p_loan_data->employee->day_price * 1 }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="total_edit_permanent_loan">اجمالي قيمة السلفة: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="total_edit_permanent_loan" name="total_edit_permanent_loan" value="{{ $p_loan_data['total'] * 1 }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="months_number_edit_permanent_loan">عدد الأشهر للأقساط: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="months_number_edit_permanent_loan" name="months_number_edit_permanent_loan" value="{{ $p_loan_data['months_number'] }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="monthly_installment_value_edit_permanent_loan">قيمة القسط الشهري:</label>
            <input readonly type="text" class="form-control" id="monthly_installment_value_edit_permanent_loan" name="monthly_installment_value_edit_permanent_loan" value="{{ $p_loan_data['monthly_installment_value'] * 1 }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="year_and_month_start_date_edit_permanent_loan">يبدأ السداد من تاريخ: <span class="text-danger">*</span></label>
            <input type="date" min="{{ date("Y-m-d") }}" value="{{ $p_loan_data['year_and_month_start_date'] }}" class="form-control" id="year_and_month_start_date_edit_permanent_loan" name="year_and_month_start_date_edit_permanent_loan">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="total_remain_edit_permanent_loan">البلغ المتبقي: <span class="text-danger">*</span></label>
            <input readonly type="text" class="form-control" id="total_remain_edit_permanent_loan" name="total_remain_edit_permanent_loan" value="{{ $p_loan_data['total_remain'] * 1 }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="notes_edit_permanent_loan">ملاحظات:</label>
            <input type="text" class="form-control" id="notes_edit_permanent_loan" name="notes_edit_permanent_loan" value="{{ $p_loan_data['notes'] }}">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mx-auto col-md-2">
            <button data-id="{{ $p_loan_data->id }}" type="submit" class="form-control btn-primary" id="do_edit_permanent_loan">تعديل</button>
        </div>
    </div>

    @else
    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
    @endif
