<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blood_Group;
use App\Models\Branche;
use App\Models\Center;
use App\Models\Country;
use App\Models\Department;
use App\Models\Driving_license_type;
use App\Models\Employee;
use App\Models\Governorate;
use App\Models\Jobs_categories;
use App\Models\Language;
use App\Models\Military_Status;
use App\Models\Nationality;
use App\Models\Qualification;
use App\Models\Religion;
use App\Models\Resignation;
use App\Models\shifts_type;
use App\Models\Social_Status_Type;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function index() {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Employee(), array('*'), array('com_code' => $com_code), "id", "DESC", P_C);

        return view('admin.employees.index', ['data' => $data]);
    }

    public function create() {
        $com_code = auth()->user()->com_code;
        $data['branches'] = get_cols_where(new Branche(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['departments'] = get_cols_where(new Department(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['jobs'] = get_cols_where(new Jobs_categories(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['qualifications'] = get_cols_where(new Qualification(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['blood_groups'] = get_cols_where(new Blood_Group(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['nationalities'] = get_cols_where(new Nationality(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['languages'] = get_cols_where(new Language(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['social_status'] = get_cols_where(new Social_Status_Type(), array('id', 'name'), array('active' => 1), 'id', 'ASC');
        $data['religions'] = get_cols_where(new Religion(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['countries'] = get_cols_where(new Country(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['military_status'] = get_cols_where(new Military_Status(), array('id', 'name'), array('active' => 1), 'id', 'ASC');
        $data['driving_license_type'] = get_cols_where(new Driving_license_type(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1));
        $data['shifts_type'] = get_cols_where(new shifts_type(), array('id', 'type', 'from_time', 'to_time', 'total_hour'), array('com_code' => $com_code, 'active' => 1), 'id', 'ASC');
        $data['resignations'] = get_cols_where(new Resignation(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'ASC');
        
        return view('admin.employees.create', ['data' => $data]);
    }

    public function ajax_getGovernorate(Request $request) {
        if ($request->ajax()) {
            $country_id = $request->country_id;
            $com_code = auth()->user()->com_code;

            $data['governorates'] = get_cols_where(new Governorate(), array('id', 'name'), array('com_code' => $com_code, 'country_id' => $country_id, 'active' => '1'));

            return view('admin.employees.ajax_getGovernorate', ['data' => $data]);
        }
    }

    public function ajax_getCity(Request $request) {
        if ($request->ajax()) {
            $governorate_id = $request->governorate_id;
            $country_id = $request->country_id;
            $com_code = auth()->user()->com_code;

            $data['cities'] = get_cols_where(new Center(), array('id', 'name'), array('com_code' => $com_code, 'governorate_id' => $governorate_id, 'active' => '1'));

            return view('admin.employees.ajax_getCity', ['data' => $data]);
        }
    }
}
