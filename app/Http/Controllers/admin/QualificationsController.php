<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QualificationsRequest;
use Illuminate\Http\Request;
use App\Models\Qualification;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Empty_;

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
    
    //
    public function store (QualificationsRequest $request) {
        try {
            $com_code = auth()->user()->com_code;
            $checkExists = get_cols_where_row(new Qualification(), array('id'), array('com_code' => $com_code, 'name' => $request->name));
            if (!empty($checkExists)) {
                return redirect()->back()->with(['error' => 'عفواً هذا الاسم مسجل من قبل'])->withInput();
            }

            DB::beginTransaction();

            $dataToInsert['name']= $request->name;
            $dataToInsert['active']= $request->active;
            $dataToInsert['added_by']= auth()->user()->id;
            $dataToInsert['com_code']= $com_code;

            insert(new Qualification(), $dataToInsert);

            DB::commit();

            return redirect()->route('qualifications.index')->with(['success' => 'تم إدخال البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()])->withInput();
        }
    }

    public function edit ($id) {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Qualification(), array('*'), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->route('qualifications.index')->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }
        return view('admin.qualifications.edit', ['data' => $data]);
    }

    public function update (QualificationsRequest $request, $id) {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Qualification(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('qualifications.index')->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }
            
            $checkExists = Qualification::select('id')->where(['com_code' => $com_code, 'name' => $request->name])->where('id', '!=', $id)->first();
            if (!empty($checkExists)) {
                return redirect()->back()->with(['error' => 'عفواً المؤهل المدخل موجود مسبقاً'])->withInput();
            }

            DB::beginTransaction();

            $dataToUpdate['name'] = $request->name;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] = auth()->user()->id;

            update(new Qualification(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));

            DB::commit();
            
            return redirect()->route('qualifications.index')->with(['success' => 'تم تحديث البيانات بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()])->withInput();
        }
    }

    public function destroy ($id) {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Qualification(), array('id'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('qualifications.index')->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            DB::beginTransaction();

            destroy(new Qualification(), array('com_code' => $com_code, 'id' => $id));
            
            DB::commit();

            return redirect()->route('qualifications.index')->with(['success' => 'تم حذف المؤهل بنجاح']);
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما '.$ex->getMessage()]);
        }
    }
    
}
