@extends('layouts.admin')

@section('title')
    الفروع
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('branches.index') }}">الفروع</a>
@endsection

@section('contentheaderactive')
    إضافة
@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> 
                    إضافة فرع جديدة
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('branches.store') }}" method="post">
                    @csrf

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">اسم الفرع:</label>
                            <input type="text" autofocus name="name" id="name" class="form-control"value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="address">عنوان الفرع:</label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
                            @error('address')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="phones">هاتف الفرع:</label>
                            <input type="text" name="phones" id="phones" class="form-control" value="{{ old('phones') }}">
                            @error('phones')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="email">البريد الإلكتروني للفرع:</label>
                            <input type="text" name="email" id="email" class="form-control" value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="active">حالة التفعيل:</label>
                            <select name="active" class="form-control" id="active">
                                <option selected value="1">مفعل</option>
                                <option value="0">معطل</option>
                            </select>
                            @error('active')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button class="btn btn-success" type="submit" name="submit">إضافة الفرع</button>
                            <a href=" {{ route('branches.index') }} " class="btn btn-danger">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection