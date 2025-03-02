<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Jobs_categoriesRequest;
use App\Models\Jobs_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class jobs_categoriesController extends Controller
{
    //
    public function index () {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Jobs_categories(), array('*'), array('com_code' => $com_code), "id", "DESC", P_C);

        return view('admin.jobs_categories.index', ['data' => $data]);
    }
    
    //
    public function create () {
        return view('admin.jobs_categories.create');

    }
    
    //
    public function store (Jobs_categoriesRequest $request) {
        try {
            $com_code = auth()->user()->com_code;
            $checkExist = get_cols_where_row(new Jobs_categories(), array('id'), array('com_code' => $com_code, 'name' => $request->name));
            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً اسم الوظيفة مسجل من قبل '])->withInput();
            }

            DB::beginTransaction();

            $dataToInsert['name'] = $request->name;
            $dataToInsert['active'] = $request->active;
            $dataToInsert['com_code'] = $com_code;
            $dataToInsert['added_by'] = auth()->user()->id;

            insert(new Jobs_categories(), $dataToInsert);

            DB::commit();

            return redirect()->route('jobs_categories.index')->with(['success' => 'تم إضافة الوظيفة بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()])->withInput();
        }

    }
    
    //
    public function edit ($id) {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Jobs_categories(), array('*'), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }
        
        return view('admin.jobs_categories.edit', ['data' => $data]);
    }
    
    //
    public function update (Jobs_categoriesRequest $request, $id) {
        try {
            $com_code = auth()->user()->com_code;
            
            $checkExist = Jobs_categories::select('id')->where(['com_code' => $com_code, 'name' => $request->name])->where('id', '!=', $id)->first();
            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً اسم الوظيفة مسجل من قبل '])->withInput();
            }

            $data = get_cols_where_row(new Jobs_categories(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            DB::beginTransaction();

            $dataToUpdate['name'] = $request->name;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] = auth()->user()->id;

            update(new Jobs_categories(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));

            DB::commit();
            
            return redirect()->route('jobs_categories.index')->with(['success' => 'تم تعديل بيانات الوظيفة بنجاح']);
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()]);
        }
    }
    
    //
    public function destroy ($id) {
        try {
            //code...
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Jobs_categories(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route("jobs_categories.index")->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }
            
            DB::beginTransaction();
            
            destroy(new Jobs_categories(), array('com_code' => $com_code, 'id' => $id));
            
            DB::commit();
            
            return redirect()->route("jobs_categories.index")->with(['success' => 'تم حذف الوظيفة بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route("jobs_categories.index")->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()]);
        }
    }
}
