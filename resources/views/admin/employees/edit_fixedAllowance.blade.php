    @if (!@empty($data))

    <form action="{{ route('employees.updateFixedAllowance', $data->id) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mx-auto d-flex align-items-center" style="height: 100%;">
                <div class="form-group w-100 mb-0 pt-5 d-flex align-items-center" style="height: 100%;">
                    <div class="w-100 text-center" style="font-weight: bold;">{{ $data->allowance->name }}</div>
                </div>
            </div>

            <div class="col-md-6 mx-auto">
                <div class="form-group">
                    <label for="edit_value">قيمة البدل:</label>
                    <input type="number" name="edit_value" value="{{ $data->value * 1 }}" id="edit_value" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g,'')">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group text-center">
                    <button class="btn btn-success" id="update_fixed_allowance" type="submit">حفظ</button>
                </div>
            </div>
        </div>
    </form>

    @else
    <p class="text-danger text-center font-weight-bold my-5">لا توجد بيانات لعرضها</p>
    @endif
