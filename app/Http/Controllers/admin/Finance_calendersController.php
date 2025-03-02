<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Finance_calenders;
use App\Models\Finance_months_periods;
use App\Models\Monthes;
use App\Http\Requests\Finance_calendersRequest;
use App\Http\Requests\Finance_calendersUpdate;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Finance_calendersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        // $data = Finance_calenders::select('*')->where(["com_code" => $com_code])->orderby('finance_yr', 'DESC')->paginate(PAGINATION_COUNTER);
        $data = get_cols_where_p(new Finance_calenders,array("*"), array("com_code" => $com_code), "id", "DESC", P_C);
        $checkDataOpenCounter = Finance_calenders::where(['is_open' => 1])->count();
        return view('admin.finance_calenders.index', ['data' => $data, 'checkdataopen' => $checkDataOpenCounter]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.finance_calenders.create');
    }

    /**
     * Store newly created resource in storage.
     */
    public function store(Finance_calendersRequest $request)
    {
        //
        try {
            DB::beginTransaction();
            $datayoInsert['finance_yr'] = $request->finance_yr;
            $datayoInsert['finance_yr_desc'] = $request->finance_yr_desc;
            $datayoInsert['start_date'] = $request->start_date;
            $datayoInsert['end_date'] = $request->end_date;
            $datayoInsert['added_by'] = auth()->user()->id;
            $datayoInsert['com_code'] = auth()->user()->com_code;
            $flag = Finance_calenders::insert($datayoInsert);
            
            if ($flag) {
                $dataParent = Finance_calenders::select('id')->where($datayoInsert)->first();

                $startDate = new DateTime($request->start_date); 
                $endDate = new DateTime($request->end_date);
                $dateInterval = new DateInterval('P1M');
                $datePeriod = new DatePeriod($startDate, $dateInterval, $endDate);
                
                foreach ($datePeriod as $date) {
                    $dataMonth['finance_calenders_id'] = $dataParent['id'];
                    $monthName_en = $date->format('F');
                    $dataParentmonth = Monthes::select('id')->where(['name_en' => $monthName_en])->first();
                    $dataMonth['month_id'] = $dataParentmonth['id'];
                    $dataMonth['finance_yr'] = $datayoInsert['finance_yr'];
                    $dataMonth['start_date_m'] = date('Y-m-01', strtotime($date->format('Y-m-d')));
                    $dataMonth['end_date_m'] = date('Y-m-t', strtotime($date->format('Y-m-d')));
                    $dataMonth['year_and_month'] = date('Y-m', strtotime($date->format('Y-m-d')));
                    $datediff = strtotime($dataMonth['end_date_m']) - strtotime($dataMonth['start_date_m'] );
                    $dataMonth['number_of_days'] = round($datediff / (60*60*24)) + 1;
                    $dataMonth['com_code'] = auth()->user()->com_code;
                    $dataMonth['added_by'] = auth()->user()->id;
                    $dataMonth['updated_by'] = auth()->user()->id;
                    $dataMonth['created_at'] = date('Y-m-d H:i:s');
                    $dataMonth['updated_at'] = date('Y-m-d H:i:s');
                    $dataMonth['start_date_for_pasma'] = date('Y-m-01', strtotime($date->format('Y-m-d')));
                    $dataMonth['end_date_for_pasma'] = date('Y-m-t', strtotime($date->format('Y-m-d')));
                    Finance_months_periods::insert($dataMonth);

                }
            }

            DB::commit();
            return redirect()->route('finance_calenders.index')->with(['success' => 'تم إضافة البيانات بنجاح']);
        } 
        catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ <br><br>'.$ex->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Finance_calendersRequest $finance_calender)
    {
        //
    }

    /**
     * Show the form for editining the specified resource.
     */
    public function edit($id)
    {
        //
        $data = Finance_calenders::select('*')->where(['id' => $id])->first();
        if (empty($data)) {
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ']);
        }
        if ($data['is_open'] != 0) {
            return redirect()->back()->with(['error' => 'عفواً لا يمكن تعديل السنة المالية في هذه الحالة']);
        }

        return view("admin.finance_calenders.update", ['data' => $data]);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update($id, Finance_calendersUpdate $request)
    {
        //
        try {
            $data = Finance_calenders::select('*')->where(['id' => $id])->first();
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً حدث خطأ']);
            }
            if ($data['is_open'] != 0) {
                return redirect()->back()->with(['error' => 'عفواً لا يمكن تعديل السنة المالية في هذه الحالة'])->withInput();
            }

            // Validation in case there is another finance year with the same name and ignor itself 
            $validator = Validator::make($request->all(), [
                'finance_yr' => ['required', Rule::unique('finance_calenders')->ignore($id)],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with(['error' => 'عفواً اسم السنة المالية مسجل من قبل'])->withInput();
            }

            DB::beginTransaction();
            $dataToUpdate['finance_yr'] = $request->finance_yr;
            $dataToUpdate['finance_yr_desc'] = $request->finance_yr_desc;
            $dataToUpdate['start_date'] = $request->start_date;
            $dataToUpdate['end_date'] = $request->end_date;
            $dataToUpdate['updated_by'] = auth()->user()->id;
            $flag = Finance_calenders::where(['id' => $id])->update($dataToUpdate);
            if ($flag) {
                if ($data['start_date'] != $request->start_date or $data['end_date'] != $request->end_date) {
                    $flagDelete = Finance_months_periods::where(['finance_calenders_id' => $id])->delete();
                    if ($flagDelete) {        
                        $startDate = new DateTime($request->start_date); 
                        $endDate = new DateTime($request->end_date);
                        $dateInterval = new DateInterval('P1M');
                        $datePeriod = new DatePeriod($startDate, $dateInterval, $endDate);
                        
                        foreach ($datePeriod as $date) {
                            $dataMonth['finance_calenders_id'] = $id;
                            $monthName_en = $date->format('F');
                            $dataParentmonth = Monthes::select('id')->where(['name_en' => $monthName_en])->first();
                            $dataMonth['month_id'] = $dataParentmonth['id'];
                            $dataMonth['finance_yr'] = $dataToUpdate['finance_yr'];
                            $dataMonth['start_date_m'] = date('Y-m-01', strtotime($date->format('Y-m-d')));
                            $dataMonth['end_date_m'] = date('Y-m-t', strtotime($date->format('Y-m-d')));
                            $dataMonth['year_and_month'] = date('Y-m', strtotime($date->format('Y-m-d')));
                            $datediff = strtotime($dataMonth['end_date_m']) - strtotime($dataMonth['start_date_m'] );
                            $dataMonth['number_of_days'] = round($datediff / (60*60*24)) + 1;
                            $dataMonth['com_code'] = auth()->user()->com_code;
                            $dataMonth['added_by'] = auth()->user()->id;
                            $dataMonth['updated_by'] = auth()->user()->id;
                            $dataMonth['created_at'] = date('Y-m-d H:i:s');
                            $dataMonth['updated_at'] = date('Y-m-d H:i:s');
                            $dataMonth['start_date_for_pasma'] = date('Y-m-01', strtotime($date->format('Y-m-d')));
                            $dataMonth['end_date_for_pasma'] = date('Y-m-t', strtotime($date->format('Y-m-d')));
                            Finance_months_periods::insert($dataMonth);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('finance_calenders.index')->with(['success' => 'تم تحديث السنة المالية بنجاح']);
        } 
        catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ'.$ex->getMessage()]);
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function destroy($id)
    {
        //
        try {
            $data = Finance_calenders::select('*')->where(['id' => $id])->first();
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً حدث خطأ']);
            }
            if ($data['is_open'] != 0) {
                return redirect()->back()->with(['error' => 'عفواً لا يمكن حذف السنة المالية في هذه الحالة']);
            }

            // Delete specified Financeial year record with related months 
            Finance_calenders::where(['id' => $id])->delete();
            
            // This is optional in case there is no relationship
            // $flag = Finance_calenders::where(['id' => $id])->delete();
            // if ($flag) {
            //     Finance_months_periods::where(['finance_calenders_id ' => $id])->delete();
            // }

            return redirect()->route('finance_calenders.index')->with(['success' => 'تم حذف البيانات بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ'.$ex->getMessage()]);
        }
    }
    
    /**
     * Open the specified resource in storage.
     */
    public function do_open($id)
    {
        //
        try {
            $data = Finance_calenders::select('*')->where(['id' => $id])->first();
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً حدث خطأ']);
            }
            if ($data['is_open'] != 0) {
                return redirect()->back()->with(['error' => 'عفواً لا يمكن فتح السنة المالية في هذه الحالة']);
            }

            $checkDataOpen = Finance_calenders::select('id')->where(['is_open' => 1])->first();
            if (!empty($checkDataOpen)) {
                return redirect()->back()->with(['error' => 'عفواً هناك بالفعل سنة مالية مفتوحة']);
            }
            
            // Update specified Financeial year record to be open
            $dataToUpdate['is_open'] = 1;
            $dataToUpdate['updated_by'] = auth()->user()->id;
            Finance_calenders::where(['id' => $id])->update($dataToUpdate);

            return redirect()->route('finance_calenders.index')->with(['success' => 'تم تحديث البيانات بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ'.$ex->getMessage()]);
        }
    }

    /**
     * Display the monthes of a specified Finance year.
     */
    public function show_year_monthes(Request $request)
    {
        //
        if ($request->ajax()) {

            $finance_months_periods = Finance_months_periods::select("*")->where(['finance_calenders_id' => $request->id])->get();
            return view('admin.finance_calenders.show_year_monthes', ['finance_months_periods' => $finance_months_periods]);
        
        }
    }

}
