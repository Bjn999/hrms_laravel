    @if (!@empty($sanction_data) and !@empty($employees))

    <div class="col-md-3">
        <div class="form-group">
            <label for="employee_code_edit_sanction">الموظف: <span class="text-danger">*</span></label>
            <select class="form-control select2" id="employee_code_edit_sanction" name="employee_code_edit_sanction">
                <option selected value="">غير محدد</option>
                @if (isset($employees) and !empty($employees))
                @foreach ($employees as $info)
                <option @if($info->employee_code == $sanction_data['employee_code']) selected @endif value="{{ $info->employee_code }}" data-salary="{{ $info->employeeData['emp_sal'] }}" data-day_price="{{ $info->employeeData['day_price'] }}">({{ $info->employee_code }}) {{ $info->employeeData['emp_name'] }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-3 related_employee_edit">
        <div class="form-group">
            <label for="emp_sal_edit_sanction">راتب الموظف:</label>
            <input readonly type="text" class="form-control" name="emp_sal_edit_sanction" id="emp_sal_edit_sanction" value="{{ $sanction_data->employee->emp_sal }}">
        </div>
    </div>
    <div class="col-md-3 related_employee_edit">
        <div class="form-group">
            <label for="day_price_edit_sanction">راتب اليوم للموظف:</label>
            <input readonly type="text" class="form-control" name="day_price_edit_sanction" id="day_price_edit_sanction" value="{{ $sanction_data['day_price'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="sanctions_type_edit_sanction">نوع الجزاء: <span class="text-danger">*</span></label>
            <select class="form-control" id="sanctions_type_edit_sanction" name="sanctions_type_edit_sanction">
                <option selected value="">غير محدد</option>
                <option @if($sanction_data['sanctions_type'] == 1) selected @endif value="1">جزاء أيام</option>
                <option @if($sanction_data['sanctions_type'] == 2) selected @endif value="2">جزاء بصمة</option>
                <option @if($sanction_data['sanctions_type'] == 3) selected @endif value="3">جزاء تحقيق</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="value_edit_sanction">عدد ايام الجزاء: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="value_edit_sanction" name="value_edit_sanction" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" value="{{ $sanction_data['value'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="total_edit_sanction">اجمالي قيمة الجزاء:</label>
            <input readonly type="text" class="form-control" id="total_edit_sanction" name="total_edit_sanction" value="{{ $sanction_data['total'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="notes_edit_sanction">ملاحظات:</label>
            <input type="text" class="form-control" id="notes_edit_sanction" name="notes_edit_sanction" value="{{ $sanction_data['notes'] }}">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mx-auto col-md-2">
            <button data-id="{{ $sanction_data->id }}" data-main_salary_employee_id="{{ $sanction_data->main_salary_employee_id  }}" type="submit" class="form-control btn-primary" id="do_edit_sanction">تعديل</button>
        </div>
    </div>

    @else
    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
    @endif
