

@section('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ url('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection


<label for="city_id">المدينة/المركز:</label>
<select name="city_id" id="city_id" class="form-control select2">
    <option selected value="">اختر مدينة</option>

    @if (isset($data['cities']) and !empty($data['cities']))
        @foreach ($data['cities'] as $info)
            <option {{ old('city_id') == $info->id ? 'selected' : '' }} value="{{ $info->id }}">{{ $info->name }}</option>
        @endforeach
    @endif
</select>

@error('city_id')
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
