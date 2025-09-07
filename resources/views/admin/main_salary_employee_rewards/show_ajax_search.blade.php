    @if (@isset($data) and !@empty($data) and count($data) > 0)
    <table id="example2" class="table table-bordered table-hover text-center">
        <thead class="custom_thead">
            <th style="vertical-align: middle"> اسم الموظف </th>
            <th style="vertical-align: middle"> نوع المكافئة </th>
            <th style="vertical-align: middle"> قيمة المكافئة </th>
            <th style="vertical-align: middle"> تاريخ الاضافة </th>
            <th style="vertical-align: middle"> تاريخ التحديث </th>
            <th style="vertical-align: middle"> الحالة </th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($data as $info)
            <tr>
                <td style="vertical-align: middle">
                    {{ $info->employee->emp_name }}
                    @if(!empty($info->notes))
                    <br />
                    <br />
                    <span style="color: brown">
                        <i class="fa-solid fa-note-sticky"></i>
                        ملاحظة:
                    </span>
                    {{ $info->notes }}
                    @else
                    <br />
                    <br />
                    <span style="color: brown">
                        <i class="fa-solid fa-note-sticky"></i>
                        لا توجد ملاحظة
                    </span>
                    @endif
                </td>
                <td style="vertical-align: middle">
                    {{ $info->addition_type->name }}
                </td>
                <td style="vertical-align: middle"> {{ $info->total * 1 }} رس </td>
                <td style="vertical-align: middle">
                    @php
                    $dt = new DateTime($info->created_at);
                    $date = $dt->format("Y-m-d");
                    $time = $dt->format("h:i");
                    $newTime = $dt->format("A") == 'AM' ? 'صباحاً' : 'مساءاً';
                    @endphp
                    {{ $date }} <br />
                    {{ $time }}
                    {{ $newTime }} <br />
                    {{ $info->added->name }}
                </td>
                <td style="vertical-align: middle">
                    @if($info->updated_by > 0)
                    @php
                    $dt = new DateTime($info->updated_at);
                    $date = $dt->format("Y-m-d");
                    $time = $dt->format("h:i");
                    $newTime = $dt->format("A") == 'AM' ? 'صباحاً' : 'مساءاً';
                    @endphp
                    {{ $date }} <br />
                    {{ $time }}
                    {{ $newTime }} <br />
                    {{ $info->updatedby->name }}
                    @else
                    لا يوجد
                    @endif
                </td>
                <td class="@if ($info->is_approved == 1) bg-success @else bg-info @endif" style="vertical-align: middle">
                    @if ($info->is_archived == 1)
                    مؤرشف
                    @else
                    غير مؤرشف
                    @endif
                </td>
                <td style="vertical-align: middle">

                    <button data-id="{{ $info->id }}" data-main_salary_employee_id="{{ $info->main_salary_employee_id  }}" class="btn btn-success edit_reward">تعديل</button>
                    <button data-id="{{ $info->id }}" data-main_salary_employee_id="{{ $info->main_salary_employee_id  }}" class="btn btn-danger delete_reward">حذف</button>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @else
    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
    @endif
