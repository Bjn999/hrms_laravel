<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NationalitiesRequest;
use App\Models\Nationality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NationalitiesController extends Controller
{
    public function index() {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Nationality(), array('*'), array('com_code' => $com_code), "id", "DESC", P_C);

        return view('admin.nationalities.index', ['data' => $data]);
    }

    public function create() {
        return view('admin.nationalities.create');
    }

    public function store(NationalitiesRequest $request) {
        try {
            $com_code = auth()->user()->com_code;
            $chechExist = get_cols_where_row(new Nationality(), array('id'), array('com_code' => $com_code, 'name' => $request->name));

            if (!empty($chechExist)) {
                return redirect()->back()->with(['error' => 'عفواً هذه الجنسية مسجلة من قبل'])->withInput();
            }

            DB::beginTransaction();
            
            $dataToInsert['name'] = $request->name;
            $dataToInsert['active'] = $request->active;
            $dataToInsert['com_code'] = $com_code;
            $dataToInsert['added_by'] = auth()->user()->id;

            insert(new Nationality(), $dataToInsert);

            DB::commit();

            return redirect()->route('nationalities.index')->with(['success' => 'تم اضافة الجنسية بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()])->withInput();
        }
    }
    
    public function edit($id) {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Nationality(), array('*'), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }

        return view('admin.nationalities.edit', ['data' => $data]);
    }
    
    public function update(NationalitiesRequest $request, $id) {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Nationality(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة'])->withInput();
            }
            
            $chechExist = Nationality::select('*')->where(['com_code' => $com_code, 'name' => $request->name])->where('id', '!=', $id)->first();
            if (!empty($chechExist)) {
                return redirect()->back()->with(['error' => 'عفواً هذه الجنسية مسجلة من قبل'])->withInput();
            }

            DB::beginTransaction();
            
            $dataToUpdate['name'] = $request->name;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] =  auth()->user()->id;

            update(new Nationality(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));

            DB::commit();
            
            return redirect()->route('nationalities.index')->with(['success' => 'تم تحديث البيانات بنجاح']);
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما'.$ex->getMessage()])->withInput();
        }
    }
    
    public function destroy($id) {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Nationality(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            DB::beginTransaction();
            
            destroy(new Nationality(), array('com_code' => $com_code, 'id' => $id));

            DB::commit();
            
            return redirect()->route('nationalities.index')->with(['success' => 'تم حذف الجنسية بنجاح']);
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما'.$ex->getMessage()]);
        }
    }
}
