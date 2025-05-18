@extends('layouts.admin')

@section('title')
شؤون الموظفين
@endsection

@section('contentheader')
شؤون الموظفين
@endsection

@section('contentheaderactivelink')
<a href="{{ route('discountsaltypes.index') }}">أنواع الخصم للراتب</a>
@endsection

@section('contentheaderactive')
إضافة
@endsection

@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                إضافة نوع الخصم للراتب
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('discountsaltypes.store') }}" method="post">
                @csrf

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">اسم النوع:</label>
                        <input type="text" autofocus name="name" id="name" class="form-control" value="{{ old('name') }}">
                        @error('name')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="active">حالة التفعيل:</label>
                        <select name="active" id="active" class="form-control">
                            <option @if(old("active")==1) selected @endif value="1">مفعل</option>
                            <option @if(old("active")==0 and old("active") !="" ) selected @endif value="0">معطل</option>
                        </select>
                        @error('active')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button class="btn btn-success" type="submit" name="submit">إضافة النوع</button>
                        <a href=" {{ route('discountsaltypes.index') }} " class="btn btn-danger">إلغاء</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
