

@section('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ url('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection


<label for="governorate_id">المحافظة:</label>
<select name="governorate_id" id="governorate_id" class="form-control select2">
    <option selected value="">اختر دولة</option>

    @if (isset($data['governorates']) and !empty($data['governorates']))
        @foreach ($data['governorates'] as $info)
            <option {{ old('governorate_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
        @endforeach
    @endif
</select>

@error('governorate_id')
    <span class="text-danger"> {{ $message }} </span>
@enderror


@section('script')
    <script src="{{ url('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>

@endsection
