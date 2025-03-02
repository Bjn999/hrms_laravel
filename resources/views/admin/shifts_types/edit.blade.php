@extends('layouts.admin')

@section('title')
    الشفتات
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('shiftstypes.index') }}">الشفتات</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> 
                    تعديل بيانات الشفت
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('shiftstypes.update', $data['id']) }}" method="post">
                    @csrf

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="type">نوع الشفت:</label>
                            <select name="type" id="type" class="form-control">
                                <option value="">اختر نوع</option>
                                <option @if(old("type", $data['type']) == 1) selected @endif value="1">صباحي</option>
                                <option @if(old("type", $data['type']) == 2) selected @endif value="2">مسائي</option>
                            </select>
                            @error('type')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="from_time">يبدأ من الساعة:</label>
                            <input type="time" name="from_time" id="from_time" class="form-control" value="{{ old('from_time', $data['from_time']) }}">
                            @error('from_time')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="to_time">ينتهي الساعة:</label>
                            <input type="time" name="to_time" id="to_time" class="form-control" value="{{ old('to_time', $data['to_time']) }}">
                            @error('to_time')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="email">عدد ساعات العمل:</label>
                            <input type="text" name="total_hour" id="total_hour" class="form-control" value="{{ old('total_hour', $data['total_hour']) }}" oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                            @error('total_hour')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="active">حالة التفعيل:</label>
                            <select name="active" class="form-control" id="active">
                                <option {{ old('active', $data['active']) == 1 ? 'selected' : '' }} value="1">مفعل</option>
                                <option {{ old('active', $data['active']) == 0 ? 'selected' : '' }} value="0">معطل</option>
                            </select>
                            @error('active')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button class="btn btn-success" type="submit" name="submit">تعديل</button>
                            <a href=" {{ route('shiftstypes.index') }} " class="btn btn-danger">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection