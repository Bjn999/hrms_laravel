    @if (!@empty($discount_data) and !@empty($employees))

    <div class="col-md-3">
        <div class="form-group">
            <label for="employee_code_edit_discount">الموظف: <span class="text-danger">*</span></label>
            <select class="form-control select2" id="employee_code_edit_discount" name="employee_code_edit_discount">
                <option selected value="">غير محدد</option>
                @if (isset($employees) and !empty($employees))
                @foreach ($employees as $info)
                <option @if($info->employee_code == $discount_data['employee_code']) selected @endif value="{{ $info->employee_code }}" data-salary="{{ $info->employeeData['emp_sal'] }}" data-day_price="{{ $info->employeeData['day_price'] }}">({{ $info->employee_code }}) {{ $info->employeeData['emp_name'] }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-3 related_employee_edit">
        <div class="form-group">
            <label for="emp_sal_edit_discount">راتب الموظف:</label>
            <input readonly type="text" class="form-control" name="emp_sal_edit_discount" id="emp_sal_edit_discount" value="{{ $discount_data->employee->emp_sal }}">
        </div>
    </div>
    <div class="col-md-3 related_employee_edit">
        <div class="form-group">
            <label for="day_price_edit_discount">راتب اليوم للموظف:</label>
            <input readonly type="text" class="form-control" name="day_price_edit_discount" id="day_price_edit_discount" value="{{ $discount_data['day_price'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="discounts_type_edit_discount">نوع الخصم: <span class="text-danger">*</span></label>
            <select class="form-control" id="discounts_type_edit_discount" name="discounts_type_edit_discount">
                <option selected value="">غير محدد</option>
                @if (isset($discountal_types) and !empty($discountal_types))
                @foreach ($discountal_types as $info)
                <option @if($discount_data['discounts_type'] == $info->id) selected @endif value="{{ $info->id }}">{{ $info->name }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="total_edit_discount">اجمالي قيمة الخصم: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="total_edit_discount" name="total_edit_discount" value="{{ $discount_data['total'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="notes_edit_discount">ملاحظات:</label>
            <input type="text" class="form-control" id="notes_edit_discount" name="notes_edit_discount" value="{{ $discount_data['notes'] }}">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mx-auto col-md-2">
            <button data-id="{{ $discount_data->id }}" data-main_salary_employee_id="{{ $discount_data->main_salary_employee_id  }}" type="submit" class="form-control btn-primary" id="do_edit_discount">تعديل</button>
        </div>
    </div>

    @else
    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
    @endif
