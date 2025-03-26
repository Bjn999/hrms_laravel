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
    إضافة
@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> 
                    إضافة شفت جديد
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('shiftstypes.store') }}" method="post">
                    @csrf

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="type">نوع الشفت:</label>
                            <select name="type" autofocus id="type" class="form-control">
                                <option value="">اختر نوع</option>
                                <option @if(old("type") == 1) selected @endif value="1">صباحي</option>
                                <option @if(old("type") == 2) selected @endif value="2">مسائي</option>
                                <option @if(old("type") == 3) selected @endif value="3">يوم كامل</option>
                            </select>
                            @error('type')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="from_time">يبدأ من الساعة:</label>
                            <input type="time" name="from_time" id="from_time" class="form-control" value="{{ old('from_time') }}">
                            @error('from_time')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="to_time">ينتهي الساعة:</label>
                            <input type="time" name="to_time" id="to_time" class="form-control" value="{{ old('to_time') }}">
                            @error('to_time')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="email">عدد ساعات العمل:</label>
                            <input type="text" name="total_hour" id="total_hour" class="form-control" value="{{ old('total_hour') }}" oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                            @error('total_hour')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="active">حالة التفعيل:</label>
                            <select name="active" id="active" class="form-control">
                                <option @if(old("active") == 1) selected @endif value="1">مفعل</option>
                                <option @if(old("active") == 0 and old("active") != "") selected @endif value="0">معطل</option>
                            </select>
                            @error('active')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button class="btn btn-success" type="submit" name="submit">إضافة الشفت</button>
                            <a href=" {{ route('shiftstypes.index') }} " class="btn btn-danger">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection