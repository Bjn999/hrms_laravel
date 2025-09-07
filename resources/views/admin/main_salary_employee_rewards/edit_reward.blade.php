    @if (!@empty($reward_data) and !@empty($employees))

    <div class="col-md-3">
        <div class="form-group">
            <label for="employee_code_edit_reward">الموظف: <span class="text-danger">*</span></label>
            <select class="form-control select2" id="employee_code_edit_reward" name="employee_code_edit_reward">
                <option selected value="">غير محدد</option>
                @if (isset($employees) and !empty($employees))
                @foreach ($employees as $info)
                <option @if($info->employee_code == $reward_data['employee_code']) selected @endif value="{{ $info->employee_code }}" data-salary="{{ $info->employeeData['emp_sal'] }}" data-day_price="{{ $info->employeeData['day_price'] }}">({{ $info->employee_code }}) {{ $info->employeeData['emp_name'] }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-3 related_employee_edit">
        <div class="form-group">
            <label for="emp_sal_edit_reward">راتب الموظف:</label>
            <input readonly type="text" class="form-control" name="emp_sal_edit_reward" id="emp_sal_edit_reward" value="{{ $reward_data->employee->emp_sal }}">
        </div>
    </div>
    <div class="col-md-3 related_employee_edit">
        <div class="form-group">
            <label for="day_price_edit_reward">راتب اليوم للموظف:</label>
            <input readonly type="text" class="form-control" name="day_price_edit_reward" id="day_price_edit_reward" value="{{ $reward_data['day_price'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="additions_type_edit_reward">نوع المكافئة: <span class="text-danger">*</span></label>
            <select class="form-control" id="additions_type_edit_reward" name="additions_type_edit_reward">
                <option selected value="">غير محدد</option>
                @if (isset($additional_types) and !empty($additional_types))
                @foreach ($additional_types as $info)
                <option @if($reward_data['additions_type'] == $info->id) selected @endif value="{{ $info->id }}">{{ $info->name }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="total_edit_reward">اجمالي قيمة المكافئة: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="total_edit_reward" name="total_edit_reward" value="{{ $reward_data['total'] * 1 }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="notes_edit_reward">ملاحظات:</label>
            <input type="text" class="form-control" id="notes_edit_reward" name="notes_edit_reward" value="{{ $reward_data['notes'] }}">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mx-auto col-md-2">
            <button data-id="{{ $reward_data->id }}" data-main_salary_employee_id="{{ $reward_data->main_salary_employee_id  }}" type="submit" class="form-control btn-primary" id="do_edit_reward">تعديل</button>
        </div>
    </div>

    @else
    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
    @endif
