    @if (@isset($data) and !@empty($data) and count($data) > 0)
        <h3 class="text-center border-top">
            مرآة الرواتب
        </h3>
        <table id="example2" class="table table-bordered table-hover text-center" style="width: 70%; margin: 15px auto;">
            <tr class="bg-gray">
                <th style="vertical-align: middle"> عدد الرواتب </th>
                <th style="vertical-align: middle"> الرواتب المؤرشفة </th>
                <th style="vertical-align: middle"> الرواتب الغير المؤرشفة </th>
                <th style="vertical-align: middle"> الرواتب الموقوفة </th>
            </tr>
            <tr>
                <td>{{ $data->count() }}</td>
                <td>{{ $data->where('is_archived', 1)->count() }}</td>
                <td>{{ $data->where('is_archived', 0)->count() }}</td>
                <td>{{ $data->where('is_stoped', 1)->count() }}</td>
            </tr>
        </table>
        <table id="example2" class="table table-bordered table-hover text-center">
            <table id="example2" class="table table-bordered table-hover text-center">
                <thead class="custom_thead">
                    <th style="vertical-align: middle"> الكود </th>
                    <th style="vertical-align: middle"> الاسم </th>
                    <th style="vertical-align: middle"> الفرع </th>
                    <th style="vertical-align: middle"> الادارة </th>
                    <th style="vertical-align: middle"> الوظيفة </th>
                    <th style="vertical-align: middle"> صافي الراتب </th>
                    <th style="vertical-align: middle"> الحالة </th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                        <tr>
                            <td style="vertical-align: middle">
                                {{ $info->employee_code }}
                            </td>
                            <td style="vertical-align: middle">
                                {{ $info->employeeData->emp_name }}
                            </td>
                            <td style="vertical-align: middle">
                                {{ $info->branch }}
                            </td>
                            <td style="vertical-align: middle">
                                {{ $info->department }}
                            </td>
                            <td style="vertical-align: middle">
                                {{ $info->job }}
                            </td>
                            <td style="vertical-align: middle">
                                {{ $info->employeeData->emp_sal * 1 }} رس
                            </td>
                            <td class="@if ($info->is_archived == 1) bg-success @else bg-info @endif"
                                style="vertical-align: middle">
                                @if ($info->is_take_action_dissmiss_collect == 1)
                                    تم الصرف
                                @else
                                    لم يتم
                                @endif
                            </td>
                            <td style="vertical-align: middle">
                                @if ($info->is_archived == 0)
                                    {{-- <form
                                                action="{{ route('mainsalaryemployee.delete_salary', ['id' => $info->id]) }}"
                                                method="get"> --}}
                                    <button data-id="{{ $info->id }}"
                                        data-finance_month_id="{{ $info->finance_month_id }}" class="btn btn-danger"
                                        type="submit" id="delete_salary">حذف</button>
                                    {{-- </form> --}}
                                @endif

                                <a href="{{ route('mainsalaryemployee.showSalDetails', $info->id) }}"
                                    class="btn btn-info">التفاصيل</a>
                                <a target="_blank" href="{{ route('mainsalaryemployee.detailsPrintSalary', $info->id) }}" class="btn btn btn-primary mx-2"> طباعة </a>

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
