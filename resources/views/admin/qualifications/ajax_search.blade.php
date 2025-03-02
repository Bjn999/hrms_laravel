@if (@isset($data) and !@empty($data) and count($data) > 0)
<table id="example2" class="table table-bordered table-hover text-center">
    <thead class="custom_thead">
        <tr>
            <th style="vertical-align: middle"> نوع الشفت </th>
            <th style="vertical-align: middle"> ساعة البداية </th>
            <th style="vertical-align: middle"> ساعة النهاية </th>
            <th style="vertical-align: middle"> عدد ساعات الدوام </th>
            <th style="vertical-align: middle"> حالة التفعيل </th>
            <th style="vertical-align: middle"> الإضافة بواسطة </th>
            <th style="vertical-align: middle"> التحديث بواسطة </th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $data as $info )
        <tr>
            <td style="vertical-align: middle"> @if ($info->type==1) صباحي @else مسائي @endif </td>
            <td style="vertical-align: middle">
                @php
                    $dt = new DateTime($info->from_time);
                    $time = date("h-i", strtotime($info->from_time));
                    $newDateTime = date("A", strtotime($info->from_time));
                    $newDateTimeType = (($newDateTime == "AM") ? "صباحاً" : "مساءاً")
                @endphp
                
                {{ $time }}
                {{ $newDateTimeType }}
            </td>
            <td style="vertical-align: middle">
                @php
                    $dt = new DateTime($info->to_time);
                    $time = date("h-i", strtotime($info->to_time));
                    $newDateTime = date("A", strtotime($info->to_time));
                    $newDateTimeType = (($newDateTime == "AM") ? "صباحاً" : "مساءاً")
                @endphp
                
                {{ $time }}
                {{ $newDateTimeType }}
            </td>
            <td style="vertical-align: middle"> {{ $info->total_hour*1 }} </td>
            <td style="vertical-align: middle" @if ($info->active==1) class="bg-success" @else class="bg-danger" @endif > @if ($info->active==1) مفعل @else معطل @endif </td>
            
            <td style="vertical-align: middle"> 
                @php
                    $dt = new DateTime($info->created_at);
                    $date = $dt->format("Y-m-d");
                    $time = $dt->format("h-i");
                    $newDateTime = date("A", strtotime($time));
                    $newDateTimeType = (($newDateTime == "AM") ? "صباحاً" : "مساءاً")
                @endphp
                
                {{ $date }} <br>
                {{ $time }}
                {{ $newDateTimeType }} <br>

                بواسطة
                {{ $info->added->name }} 
            </td>
            <td style="vertical-align: middle">
                @if ($info->updated_by>0)
                    @php
                        $dt = new DateTime($info->updated_at);
                        $date = $dt->format("Y-m-d");
                        $time = $dt->format("h-i");
                        $newDateTime = date("A", strtotime($time));
                        $newDateTimeType = (($newDateTime == "AM") ? "صباحاً" : "مساءاً")
                    @endphp
                    
                    {{ $date }} <br>
                    {{ $time }}
                    {{ $newDateTimeType }} <br>

                    بواسطة
                    {{ $info->updatedby->name }}
                @else
                    لا يوجد
                @endif
            </td>
            <td style="vertical-align: middle">

                <a href="{{ route('shiftstypes.edit', $info->id) }}" class="btn btn-success">تعديل</a>
                <a href="{{ route('shiftstypes.destroy', $info->id) }}" class="btn btn-danger r_u_sure">حذف</a>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="col-md-12 mt-3" id="ajax_pagination_search">
    {{ $data->links('pagination::bootstrap-5') }}
</div>

@else
    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
@endif

