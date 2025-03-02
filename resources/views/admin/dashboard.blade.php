@extends('layouts.admin')

@section('title')
    الرئيسية
@endsection

@section('contentheader')
    HRMS
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('admin.dashboard') }}">الرئيسية</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')

    <div style="background-image: url( {{ url('assets/admin/imgs/dashboard.jpg') }} ); 
        min-width: 100%;
        min-height: 700px; 
        background-size: cover">

    </div>

@endsection
