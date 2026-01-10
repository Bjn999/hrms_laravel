    @if (!@empty($allowance_data) and !@empty($employees))

    <div class="col-md-3">
        <div class="form-group">
            <label for="employee_code_edit_allowance">الموظف: <span class="text-danger">*</span></label>
            <select class="form-control select2" id="employee_code_edit_allowance" name="employee_code_edit_allowance">
                <option selected value="">غير محدد</option>
                @if (isset($employees) and !empty($employees))
                @foreach ($employees as $info)
                <option @if($info->employee_code == $allowance_data['employee_code']) selected @endif value="{{ $info->employee_code }}" data-salary="{{ $info->employeeData['emp_sal'] }}" data-day_price="{{ $info->employeeData['day_price'] }}">({{ $info->employee_code }}) {{ $info->employeeData['emp_name'] }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-3 related_employee_edit">
        <div class="form-group">
            <label for="emp_sal_edit_allowance">راتب الموظف:</label>
            <input readonly type="text" class="form-control" name="emp_sal_edit_allowance" id="emp_sal_edit_allowance" value="{{ $allowance_data->employee->emp_sal }}">
        </div>
    </div>
    <div class="col-md-3 related_employee_edit">
        <div class="form-group">
            <label for="day_price_edit_allowance">راتب اليوم للموظف:</label>
            <input readonly type="text" class="form-control" name="day_price_edit_allowance" id="day_price_edit_allowance" value="{{ $allowance_data['day_price'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="allowances_id_edit_allowance">نوع البدل: <span class="text-danger">*</span></label>
            <select class="form-control" id="allowances_id_edit_allowance" name="allowances_id_edit_allowance">
                <option selected value="">غير محدد</option>
                @if (isset($allowances) and !empty($allowances))
                @foreach ($allowances as $info)
                <option @if($allowance_data['allowances_id'] == $info->id) selected @endif value="{{ $info->id }}">{{ $info->name }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="total_edit_allowance">اجمالي قيمة البدل: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="total_edit_allowance" name="total_edit_allowance" value="{{ $allowance_data['total'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="notes_edit_allowance">ملاحظات:</label>
            <input type="text" class="form-control" id="notes_edit_allowance" name="notes_edit_allowance" value="{{ $allowance_data['notes'] }}">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mx-auto col-md-2">
            <button data-id="{{ $allowance_data->id }}" data-main_salary_employee_id="{{ $allowance_data->main_salary_employee_id  }}" type="submit" class="form-control btn-primary" id="do_edit_allowance">تعديل</button>
        </div>
    </div>

    @else
    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
    @endif
