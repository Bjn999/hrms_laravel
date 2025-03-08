<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReligionsRequest;
use App\Models\Religion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReligionsController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Religion(), array('*'), array('com_code' => $com_code), "id", "DESC", P_C);

        return view('admin.religions.index', ['data' => $data]);
    }

    public function create()
    {
        return view('admin.religions.create');
    }

    public function store(ReligionsRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $checkExist = get_cols_where_row(new Religion(), array('id'), array('com_code' => $com_code, "name" => $request->name));

            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً هذه الديانة مسجلة من قبل']);
            }

            DB::beginTransaction();

            $dataToInsert['name'] = $request->name;
            $dataToInsert['active'] = $request->active;
            $dataToInsert['added_by'] = auth()->user()->id;
            $dataToInsert['com_code'] = $com_code;

            insert(new Religion(), $dataToInsert);

            DB::commit();

            return redirect()->route('religions.index')->with(['success' => 'تم اضافة الديانة بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما ' . $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Religion(), array('*'), array('com_code' => $com_code, 'id' => $id));

        if (empty($data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }

        return view('admin.religions.edit', ['data' => $data]);
    }

    public function update(ReligionsRequest $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Religion(), array('*'), array('com_code' => $com_code, 'id' => $id));
    
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            $checkExist = Religion::select('*')->where(['com_code' => $com_code, 'name' => $request->name])->where('id', '!=', $id)->first();
    
            if (!empty($checkExist)) {
                return redirect()->back()->with(['error' => 'عفواً هذه الديانة مسجلة من قبل']);
            }

            DB::beginTransaction();

            $dataToUpdate['name'] = $request->name;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] = auth()->user()->id;
            
            update(new Religion(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));
            
            DB::commit();
    
            return redirect()->route('religions.index')->with(['success' => 'تم تحديث البيانات بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما ' . $ex->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Religion(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('religions.index')->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            DB::beginTransaction();

            destroy(new Religion(), array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->route('religions.index')->with(['success' => 'تم حذف الديانة بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('religions.index')->with(['error' => 'عفواً حدث خطأ ما ' . $ex->getMessage()]);
        }
    }
}
