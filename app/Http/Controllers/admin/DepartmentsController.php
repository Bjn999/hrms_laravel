<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Http\Requests\DepartmentsRequest;
use Illuminate\Support\Facades\DB;

class DepartmentsController extends Controller
{
    //
    public function index () {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Department(), array('*'), array('com_code' => $com_code), "id", "DESC", P_C);

        return view('admin.departments.index', ['data' => $data]);
    }
    
    //
    public function create () {
        return view('admin.departments.create');

    }
    
    //
    public function store (DepartmentsRequest $request) {
        try {
            $com_code = auth()->user()->com_code;
            $checkExist = get_cols_where_row(new Department(), array('id'), array('com_code' => $com_code, 'name' => $request->name));

            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً اسم الإدارة مسجل من قبل'])->withInput();
            }
            
            DB::beginTransaction();

            $dataToInsert['name'] = $request->name;
            $dataToInsert['phones'] = $request->phones;
            $dataToInsert['notes'] = $request->notes;
            $dataToInsert['active'] = $request->active;
            $dataToInsert['com_code'] = $com_code;
            $dataToInsert['added_by'] = auth()->user()->id;

            insert(new Department(), $dataToInsert);
            
            DB::commit();

            return redirect()->route('departments.index')->with(['success' => 'تم إضافة الإدارة بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()])->withInput();
        }
    }
    
    //
    public function edit ($id) {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Department(), array('*'), array('com_code' => $com_code, 'id' => $id));
        
        if (empty($data)) {
            return redirect()->route("departments.index")->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }
        
        return view('admin.departments.edit', ['data' => $data]);
    }
    
    //
    public function update (DepartmentsRequest $request, $id) {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Department(), array('*'), array('com_code' => $com_code, 'id' => $id));
        
            if (empty($data)) {
                return redirect()->route("departments.index")->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }
            
            $checkExist = Department::select('id')->where(['com_code' => $com_code, 'name' => $request->name])->where('id', '!=', $id)->first();

            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً اسم الإدارة مسجل من قبل'])->withInput();
            }
            
            DB::beginTransaction();

            $dataToUpdate['name'] = $request->name;
            $dataToUpdate['phones'] = $request->phones;
            $dataToUpdate['notes'] = $request->notes;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] = auth()->user()->id;

            update(new Department(), $dataToUpdate, array("com_code" => $com_code, "id" => $id));
            
            DB::commit();

            return redirect()->route('departments.index')->with(['success' => 'تم تعديل البيانات بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()])->withInput();
        }
    }
    
    // 
    public function destroy ($id) {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Department(), array('*'), array('com_code' => $com_code, 'id' => $id));
        
            if (empty($data)) {
                return redirect()->route("departments.index")->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            DB::beginTransaction();

            destroy(new Department(), array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->route('departments.index')->with(['success' => 'تم تعديل البيانات بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()]);
        }
    }
}
