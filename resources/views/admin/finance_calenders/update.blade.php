@extends('layouts.admin')

@section('title')
    السنوات المالية
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('finance_calenders.index') }}">السنوات المالية</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> 
                    تعديل سنة مالية جديدة
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('finance_calenders.update', $data['id']) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="finance_yr">كود السنة المالية:</label>
                            <input type="text" name="finance_yr" id="finance_yr" class="form-control" value="{{ old('finance_yr', $data['finance_yr']) }}">
                            @error('finance_yr')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="finance_yr_desc">وصف السنة المالية:</label>
                            <input type="text" name="finance_yr_desc" id="finance_yr_desc" class="form-control"value="{{ old('finance_yr_desc', $data['finance_yr_desc']) }}">
                            @error('finance_yr_desc')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="start_date">تاريخ بداية السنة المالية:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $data['start_date']) }}">
                            @error('start_date')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="end_date">تاريخ نهاية السنة المالية:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $data['end_date']) }}">
                            @error('end_date')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button class="btn btn-success" type="submit" name="submit">تحديث السنة</button>
                            <a href=" {{ route('finance_calenders.index') }} " class="btn btn-danger">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection