<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin_panel_settings;
use App\Http\Requests\Admin_panel_settingsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin_panel_settingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code = Auth()->user()->com_code;
        $data = Admin_panel_settings::select('*')->where('com_code', $com_code)->first();
        
        return view('admin.admin_panel_settings.index', ['data' => $data]);
    }

    /**
     * Display an editin page of resource.
     */
    public function edit()
    {
        $com_code = Auth()->user()->com_code;
        $data = Admin_panel_settings::select('*')->where('com_code', $com_code)->first();
        
        return view('admin.admin_panel_settings.edit', ['data' => $data]);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Admin_panel_settingsRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $dataToUpdate['company_name'] = $request->input('company_name');
            $dataToUpdate['phones'] = $request->input('phones');
            $dataToUpdate['address'] = $request->input('address');
            $dataToUpdate['email'] = $request->input('email');
            $dataToUpdate['after_miniute_calculate_delay'] = $request->input('after_miniute_calculate_delay');
            $dataToUpdate['after_miniute_calculate_early_departure'] = $request->input('after_miniute_calculate_early_departure');
            $dataToUpdate['after_miniute_quarterday'] = $request->input('after_miniute_quarterday');
            $dataToUpdate['after_time_half_daycut'] = $request->input('after_time_half_daycut');
            $dataToUpdate['after_time_allday_daycut'] = $request->input('after_time_allday_daycut');
            $dataToUpdate['monthly_vacation_balance'] = $request->input('monthly_vacation_balance');
            $dataToUpdate['after_days_begin_vacation'] = $request->input('after_days_begin_vacation');
            $dataToUpdate['first_balance_begin_vacation'] = $request->input('first_balance_begin_vacation');
            $dataToUpdate['sanctions_value_first_abcence'] = $request->sanctions_value_first_abcence;
            $dataToUpdate['sanctions_value_second_abcence'] = $request->sanctions_value_second_abcence;
            $dataToUpdate['sanctions_value_third_abcence'] = $request->sanctions_value_third_abcence;
            $dataToUpdate['sanctions_value_fourth_abcence'] = $request->sanctions_value_fourth_abcence;
            $dataToUpdate['updated_by'] = auth()->user()->id;
            
            Admin_panel_settings::where(['com_code' => $com_code])->update($dataToUpdate);
            
            return redirect()->route('admin_panel_settings.index')->with(['success' => 'تم تعديل البيانات بنجاح']);
            
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ!'])->withInput();
        }
    }

}
