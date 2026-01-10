    @if (@isset($data) and !@empty($data) and count($data) > 0)
    <table id="example2" class="table table-bordered table-hover text-center table-striped">
        <thead class="custom_thead">
            <th style="vertical-align: middle; width: 20%"> اسم الموظف </th>
            <th style="vertical-align: middle; width: 10%"> قيمة السلفة </th>
            <th style="vertical-align: middle; width: 10%"> عدد الأشهر </th>
            <th style="vertical-align: middle; width: 10%"> القسط الشهري </th>
            <th style="vertical-align: middle; width: 10%"> اجمالي المدفوع </th>
            <th style="vertical-align: middle; width: 10%"> هل صرفت </th>
            <th style="vertical-align: middle; width: 10%"> هل انتهت </th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($data as $info)
            <tr style="cursor: pointer">
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
                <td style="vertical-align: middle"> {{ $info->total * 1 }} رس </td>
                <td style="vertical-align: middle"> {{ $info->months_number }} </td>
                <td style="vertical-align: middle"> {{ $info->monthly_installment_value * 1 }} رس </td>
                <td style="vertical-align: middle"> {{ $info->total_paid * 1 }} رس </td>
                <td style="vertical-align: middle">
                    @if ($info->is_dismissal == 1)
                    نعم
                    @else
                    لا
                    @endif
                    @if ($info->is_dismissal == 0 and $info->is_archived == 0)
                    {{-- <a href="{{ route('mainsalarypermanent_loan.dismiss_p_loan', $info->id) }}" title="صرف السلفة المستدية" class="btn btn-warning">صرف الان</a> --}}
                    <button data-id="{{ $info->id }}" title="صرف السلفة المستدية" class="btn dismiss_permanent_loan btn-warning">صرف الان</button>
                    @endif
                </td>
                <td style="vertical-align: middle">
                    @if ($info->is_archived == 1)
                    نعم
                    @else
                    لا
                    @endif
                </td>
                <td style="vertical-align: middle">

                    @if ($info->is_archived == 0 and $info->is_dismissal == 0)
                    <button @if ($info->is_archived != 0 or $info->is_dismissal != 0) title="لا يمكن التعديل لأن هذه السلفة قد تم ارشفتها او تم صرفها بالفعل" @else title="تعديل السلفة" @endif data-id="{{ $info->id }}" class="btn btn-success @if ($info->is_archived == 0 and $info->is_dismissal == 0) edit_permanent_loan @endif">تعديل</button>
                    <button @if ($info->is_archived != 0 or $info->is_dismissal != 0) title="لا يمكن الحذف لأن هذه السلفة قد تم ارشفتها او تم صرفها بالفعل" @else title="حذف السلفة" @endif data-id="{{ $info->id }}" class="btn btn-danger @if ($info->is_archived == 0 and $info->is_dismissal == 0) delete_permanent_loan @endif">حذف</button>
                    @endif
                    <button title="تفاصيل الأقساط" data-id="{{ $info->id }}" class="btn btn-info permanent_loan_installments">الأقساط</button>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @else
    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
    @endif
