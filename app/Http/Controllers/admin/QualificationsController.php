<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Qualification;
use Illuminate\Support\Facades\DB;

class QualificationsController extends Controller
{
    //
    public function index () {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Qualification(), array('*'), array('com_code' => $com_code), "id", "DESC", P_C);

        return view('admin.qualifications.index', ['data' => $data]);
    }
    
    //
    public function create () {
        return view('admin.qualifications.create');

    }
}
