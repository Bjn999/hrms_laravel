@if (isset($data) and !empty($data) and count($data) > 0)
<table id="example2" class="table table-bordered table-hover text-center">
    <thead class="custom_thead">
        <tr>
            <th style="vertical-align: middle"> الكود </th>
            <th style="vertical-align: middle"> الاسم </th>
            <th style="vertical-align: middle"> الفرع </th>
            <th style="vertical-align: middle"> الإدارة </th>
            <th style="vertical-align: middle"> الوظيفة </th>
            <th style="vertical-align: middle"> حالة الوظيفية </th>
            <th style="vertical-align: middle"> الصورة </th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $data as $info )
        <tr>
            <td style="vertical-align: middle"> {{ $info->employee_code }} </td>
            <td style="vertical-align: middle"> {{ $info->emp_name }} </td>
            <td style="vertical-align: middle"> {{ $info->branch->name }} </td>
            <td style="vertical-align: middle"> {{ $info->department->name }} </td>
            <td style="vertical-align: middle"> {{ $info->job->name }} </td>
            <td style="vertical-align: middle" @if ($info->functional_status == 1) class="bg-success" @else class="bg-danger" @endif> @if ($info->functional_status == 1) في الخدمة @else خارج الخدمة @endif </td>

            <td style="vertical-align: middle">
                @if (!empty($info->emp_photo))
                <img class="emp_logo rounded-circle" src="{{ url('assets/admin/uploads').'/'.$info->emp_photo }}">
                @else
                @if ($info->emp_gender == 1)
                <img class="emp_logo rounded-circle" src="{{ url('assets/admin/imgs/default_m.png') }}">
                @else
                <img class="emp_logo rounded-circle" src="{{ url('assets/admin/imgs/default_f.png') }}">
                @endif
                @endif
            </td>

            <td style="vertical-align: middle">

                <a href="{{ route('employees.edit', $info->id) }}" class="btn btn-success">تعديل</a>
                <a href="{{ route('employees.destroy', $info->id) }}" class="btn btn-danger r_u_sure">حذف</a>
                <a href="{{ route('employees.show', $info->id) }}" class="btn btn-info">المزيد</a>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="col-md-12 mt-3">
    {{ $data->links('pagination::bootstrap-5') }}
</div>

@else
<p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
@endif
