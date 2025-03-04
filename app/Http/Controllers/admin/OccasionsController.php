<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Occasion;
use App\Http\Requests\OccasionsRequest;
use Illuminate\Support\Facades\DB;

class OccasionsController extends Controller
{
    public function index() {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Occasion(), array('*'), array('com_code' => $com_code), 'id', "DESC", P_C);

        return view('admin.occasions.index', ['data' => $data]);
    }
    
    public function create() {
        return view('admin.occasions.create');
    }
    
    public function store(OccasionsRequest $request) {
        try {
            $com_code = auth()->user()->com_code;
            $checkExist = get_cols_where_row(new Occasion(), array('id'), array('com_code' => $com_code, 'name' => $request->name));
            
            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً هذه المناسبة مسجلة من قبل'])->withInput();
            }

            DB::beginTransaction();

            $dataToInsert['name'] = $request->name;
            $dataToInsert['from_date'] = $request->from_date;
            $dataToInsert['to_date'] = $request->to_date;

            // $timeDiffr = abs((strtotime($dataToInsert['to_date']) - strtotime($dataToInsert['from_date'])));
            // $dataToInsert['days_counter'] = intval($timeDiffr / 86400) + 1;
            $dataToInsert['days_counter'] = $request->days_counter;

            $dataToInsert['active'] = $request->active;
            $dataToInsert['added_by'] = auth()->user()->id;
            $dataToInsert['com_code'] = $com_code;

            insert(new Occasion(), $dataToInsert);

            DB::commit();

            return redirect()->route('occasions.index')->with(['success' => 'تم إدخال البيانات بنجاح']);
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()])->withInput();
        }
    }

    public function edit($id) {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Occasion(), array('*'), array('com_code' => $com_code, 'id' => $id));
        
        if (empty($data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }

        return view('admin.occasions.edit', ['data' => $data]);
    }
    
    public function update(OccasionsRequest $request, $id) {
        try {
            $com_code = auth()->user()->com_code;

            $data = get_cols_where_row(new Occasion(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            $checkExist = Occasion::select('id')->where(['com_code' => $com_code, 'name' => $request->name])->where('id', '!=', $id)->first();
            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً هذه المناسبة مسجلة من قبل'])->withInput();
            }

            DB::beginTransaction();

            $dataToUpdate['name'] = $request->name;
            $dataToUpdate['from_date'] = $request->from_date;
            $dataToUpdate['to_date'] = $request->to_date;
            $dataToUpdate['days_counter'] = $request->days_counter;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] = auth()->user()->id;

            update(new Occasion(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->route('occasions.index')->with(['success' => 'تم إدخال البيانات بنجاح']);
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()])->withInput();
        }
    }
    
    public function destroy($id) {
        try {
            $com_code = auth()->user()->com_code;

            $data = get_cols_where_row(new Occasion(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            DB::beginTransaction();

            destroy(new Occasion(), array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->route('occasions.index')->with(['success' => 'تم حذف البيانات بنجاح']);
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()])->withInput();
        }
    }
}
