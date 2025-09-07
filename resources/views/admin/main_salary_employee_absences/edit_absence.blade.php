    @if (!@empty($absence_data) and !@empty($employees))

    <div class="col-md-3">
        <div class="form-group">
            <label for="employee_code_edit_absence">الموظف: <span class="text-danger">*</span></label>
            <select class="form-control select2" id="employee_code_edit_absence" name="employee_code_edit_absence">
                <option selected value="">غير محدد</option>
                @if (isset($employees) and !empty($employees))
                @foreach ($employees as $info)
                <option @if($info->employee_code == $absence_data['employee_code']) selected @endif value="{{ $info->employee_code }}" data-salary="{{ $info->employeeData['emp_sal'] }}" data-day_price="{{ $info->employeeData['day_price'] }}">({{ $info->employee_code }}) {{ $info->employeeData['emp_name'] }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-3 related_employee_editabsence">
        <div class="form-group">
            <label for="emp_sal_edit_absence">راتب الموظف:</label>
            <input readonly type="text" class="form-control" name="emp_sal_edit_absence" id="emp_sal_edit_absence" value="{{ $absence_data->employee->emp_sal }}">
        </div>
    </div>
    <div class="col-md-3 related_employee_editabsence">
        <div class="form-group">
            <label for="day_price_edit_absence">راتب اليوم للموظف:</label>
            <input readonly type="text" class="form-control" name="day_price_edit_absence" id="day_price_edit_absence" value="{{ $absence_data['day_price'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="value_edit_absence">عدد ايام الغياب: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="value_edit_absence" name="value_edit_absence" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" value="{{ $absence_data['value'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="total_edit_absence">اجمالي قيمة الغياب:</label>
            <input readonly type="text" class="form-control" id="total_edit_absence" name="total_edit_absence" value="{{ $absence_data['total'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="notes_edit_absence">ملاحظات:</label>
            <input type="text" class="form-control" id="notes_edit_absence" name="notes_edit_absence" value="{{ $absence_data['notes'] }}">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mx-auto col-md-2">
            <button data-id="{{ $absence_data->id }}" data-main_salary_employee_id="{{ $absence_data->main_salary_employee_id  }}" type="submit" class="form-control btn-primary" id="do_edit_absence">تعديل</button>
        </div>
    </div>

    @else
    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
    @endif
