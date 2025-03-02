@extends('layouts.admin')

@section('title')
    الإدارات
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('shiftstypes.index') }}">الإدارات</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> 
                    تعديل بيانات الإدارة
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('departments.update', $data['id']) }}" method="post">
                    @csrf

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">اسم الإدارة:</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $data['name']) }}">
                            @error('name')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="phones">هاتف الإدارة:</label>
                            <input type="text" name="phones" id="phones" class="form-control" value="{{ old('phones', $data['phones']) }}" oninput="this.value = this.value.replace(/[^0-9,+]/g,'')">
                            @error('phones')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="notes">الملاحظات على الإدارة:</label>
                            <input type="text" name="notes" id="notes" class="form-control" value="{{ old('notes', $data['notes']) }}">
                            @error('notes')
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
                            <a href=" {{ route('departments.index') }} " class="btn btn-danger">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection