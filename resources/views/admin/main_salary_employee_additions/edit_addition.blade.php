    @if (!@empty($addition_data) and !@empty($employees))

    <div class="col-md-3">
        <div class="form-group">
            <label for="employee_code_edit_addition">الموظف: <span class="text-danger">*</span></label>
            <select class="form-control select2" id="employee_code_edit_addition" name="employee_code_edit_addition">
                <option selected value="">غير محدد</option>
                @if (isset($employees) and !empty($employees))
                @foreach ($employees as $info)
                <option @if($info->employee_code == $addition_data['employee_code']) selected @endif value="{{ $info->employee_code }}" data-salary="{{ $info->employeeData['emp_sal'] }}" data-day_price="{{ $info->employeeData['day_price'] }}">({{ $info->employee_code }}) {{ $info->employeeData['emp_name'] }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-3 related_employee_edit">
        <div class="form-group">
            <label for="emp_sal_edit_addition">راتب الموظف:</label>
            <input readonly type="text" class="form-control" name="emp_sal_edit_addition" id="emp_sal_edit_addition" value="{{ $addition_data->employee->emp_sal }}">
        </div>
    </div>
    <div class="col-md-3 related_employee_edit">
        <div class="form-group">
            <label for="day_price_edit_addition">راتب اليوم للموظف:</label>
            <input readonly type="text" class="form-control" name="day_price_edit_addition" id="day_price_edit_addition" value="{{ $addition_data['day_price'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="value_edit_addition">عدد ايام الإضافي: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="value_edit_addition" name="value_edit_addition" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" value="{{ $addition_data['value'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="total_edit_addition">اجمالي قيمة الإضافي:</label>
            <input readonly type="text" class="form-control" id="total_edit_addition" name="total_edit_addition" value="{{ $addition_data['total'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="notes_edit_addition">ملاحظات:</label>
            <input type="text" class="form-control" id="notes_edit_addition" name="notes_edit_addition" value="{{ $addition_data['notes'] }}">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mx-auto col-md-2">
            <button data-id="{{ $addition_data->id }}" data-main_salary_employee_id="{{ $addition_data->main_salary_employee_id  }}" type="submit" class="form-control btn-primary" id="do_edit_addition">تعديل</button>
        </div>
    </div>

    @else
    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
    @endif
