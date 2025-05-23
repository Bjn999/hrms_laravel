@extends('layouts.admin')

@section('title')
    فئات الوظائف
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('jobs_categories.index') }}">فئات الوظائف</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> 
                    تعديل فئات وظائف الموظفين 
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('jobs_categories.update', $data['id']) }}" method="post">
                    @csrf

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">اسم الوظيفة:</label>
                            <input type="text" autofocus name="name" id="name" class="form-control" value="{{ old('name', $data['name']) }}">
                            @error('name')
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
                            <a href=" {{ route('jobs_categories.index') }} " class="btn btn-danger">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection