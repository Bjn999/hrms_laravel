<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResignationsRequest;
use App\Models\Resignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResignationsController extends Controller
{
    public function index() {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Resignation(), array('*'), array('com_code' => $com_code), "id", "DESC", P_C);

        return view("admin.resignations.index", ['data' => $data]);
    }

    public function create() {
        return view("admin.resignations.create");
    }

    public function store(ResignationsRequest $request) {
        try {
            $com_code = auth()->user()->com_code;
            $checkExist = get_cols_where_row(new Resignation(), array('id'), array('com_code' => $com_code, 'name' => $request->name));
            
            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً هذا النوع مسجل من قبل'])->withInput();
            }
            
            DB::beginTransaction();
            
            $dataToInsert['name'] = $request->name;
            $dataToInsert['active'] = $request->active;
            $dataToInsert['com_code'] = $com_code;
            $dataToInsert['added_by'] = auth()->user()->id;
            
            insert(new Resignation(), $dataToInsert);
            
            DB::commit();
            
            return redirect()->route('resignations.index')->with(['success' => 'تم إضافة النوع بنجاح']);
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()])->withInput();
        }
    }
    
    public function edit($id) {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Resignation(), array('*'), array('com_code' => $com_code, 'id' => $id));
        
        if (empty($data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }
        
        return view('admin.resignations.edit', ['data' => $data]);
    }

    public function update(ResignationsRequest $request, $id) {
        try {
            $com_code = auth()->user()->com_code;
            
            $data = get_cols_where_row(new Resignation(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }
            
            $checkExist = Resignation::select('id')->where(['com_code' => $com_code, 'name' => $request->name])->where('id' , '!=', $id)->first();
            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً هذا النوع مسجل من قبل'])->withInput();
            }
            
            DB::beginTransaction();
            
            $dataToUpdate['name'] = $request->name;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] = auth()->user()->id;
            
            update(new Resignation(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));
            
            DB::commit();
            
            return redirect()->route('resignations.index')->with(['success' => 'تم تحديث البيانات بنجاح']);
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()])->withInput();
        }
    }

    public function destroy($id) {
        try {
            $com_code = auth()->user()->com_code;
            
            $data = get_cols_where_row(new Resignation(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }
            
            DB::beginTransaction();
            
            destroy(new Resignation(), array('com_code' => $com_code, 'id' => $id));
            
            DB::commit();
            
            return redirect()->route('resignations.index')->with(['success' => 'تم حذف النوع بنجاح']);
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()])->withInput();
        }
    }
}
