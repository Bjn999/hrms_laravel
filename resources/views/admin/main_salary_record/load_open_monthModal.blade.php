@if (@isset($data) and !@empty($data))
@if ($data['is_open'] == 0)

<form action="{{ route('mainsalaryrecord.do_open_month', $data['id']) }}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="start_date_for_pasma">تاريخ بداية البصمة للشهر:</label>
                <input type="date" name="start_date_for_pasma" id="from_time" class="form-control" value="{{ $data['start_date_for_pasma'] }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="end_date_for_pasma">تاريخ نهاية البصمة للشهر:</label>
                <input type="date" name="end_date_for_pasma" id="end_date_for_pasma" class="form-control" value="{{ $data['end_date_for_pasma'] }}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group text-center">
                <button class="btn btn-success r_u_sure" type="submit" name="submit">فتح الشهر المالي الان</button>
            </div>
        </div>
    </div>
</form>

@endif
@else
<p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
@endif
