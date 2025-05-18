<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AllowancesRequest;
use App\Models\Allowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllowancesController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;

        $data = get_cols_where_p(new Allowance(), array('*'), array('com_code' => $com_code), "id", "DESC", P_C);

        return view('admin.allowances.index', ['data' => $data]);
    }

    public function create()
    {
        return view('admin.allowances.create');
    }

    public function store(AllowancesRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $chechExist = get_cols_where_row(new Allowance(), array('id'), array('com_code' => $com_code, 'name' => $request->name));

            if (!empty($chechExist)) {
                return redirect()->back()->with(['error' => 'عفواً هذه النوع مسجل من قبل'])->withInput();
            }

            DB::beginTransaction();

            $dataToInsert['name'] = $request->name;
            $dataToInsert['active'] = $request->active;
            $dataToInsert['com_code'] = $com_code;
            $dataToInsert['added_by'] = auth()->user()->id;

            insert(new Allowance(), $dataToInsert);

            DB::commit();

            return redirect()->route('allowances.index')->with(['success' => 'تم اضافة نوع إضافة الراتب بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما ' . $ex->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $com_code = auth()->user()->com_code;

        $data = get_cols_where_row(new Allowance(), array('*'), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }

        return view('admin.allowances.edit', ['data' => $data]);
    }

    public function update(AllowancesRequest $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;

            $data = get_cols_where_row(new Allowance(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة'])->withInput();
            }

            $chechExist = Allowance::select('*')->where(['com_code' => $com_code, 'name' => $request->name])->where('id', '!=', $id)->first();
            if (!empty($chechExist)) {
                return redirect()->back()->with(['error' => 'عفواً هذه الجنسية مسجلة من قبل'])->withInput();
            }

            DB::beginTransaction();

            $dataToUpdate['name'] = $request->name;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] =  auth()->user()->id;

            update(new Allowance(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->route('allowances.index')->with(['success' => 'تم تحديث البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما' . $ex->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $com_code = auth()->user()->com_code;

            $data = get_cols_where_row(new Allowance(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            DB::beginTransaction();

            destroy(new Allowance(), array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->route('allowances.index')->with(['success' => 'تم حذف نوع إضافة الراتب بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما' . $ex->getMessage()]);
        }
    }
}
