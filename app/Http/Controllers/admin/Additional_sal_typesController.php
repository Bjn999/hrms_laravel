<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Additional_sal_typesRequest;
use App\Models\Additional_sal_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Additional_sal_typesController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;

        $data = get_cols_where_p(new Additional_sal_type(), array('*'), array('com_code' => $com_code), "id", "DESC", P_C);

        return view('admin.additional_sal_types.index', ['data' => $data]);
    }

    public function create()
    {
        return view('admin.additional_sal_types.create');
    }

    public function store(Additional_sal_typesRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $chechExist = get_cols_where_row(new Additional_sal_type(), array('id'), array('com_code' => $com_code, 'name' => $request->name));

            if (!empty($chechExist)) {
                return redirect()->back()->with(['error' => 'عفواً هذه النوع مسجل من قبل'])->withInput();
            }

            DB::beginTransaction();

            $dataToInsert['name'] = $request->name;
            $dataToInsert['active'] = $request->active;
            $dataToInsert['com_code'] = $com_code;
            $dataToInsert['added_by'] = auth()->user()->id;

            insert(new Additional_sal_type(), $dataToInsert);

            DB::commit();

            return redirect()->route('additionalsaltypes.index')->with(['success' => 'تم اضافة نوع إضافة الراتب بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما ' . $ex->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $com_code = auth()->user()->com_code;

        $data = get_cols_where_row(new Additional_sal_type(), array('*'), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
        }

        return view('admin.additional_sal_types.edit', ['data' => $data]);
    }

    public function update(Additional_sal_typesRequest $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;

            $data = get_cols_where_row(new Additional_sal_type(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة'])->withInput();
            }

            $chechExist = Additional_sal_type::select('*')->where(['com_code' => $com_code, 'name' => $request->name])->where('id', '!=', $id)->first();
            if (!empty($chechExist)) {
                return redirect()->back()->with(['error' => 'عفواً هذه الجنسية مسجلة من قبل'])->withInput();
            }

            DB::beginTransaction();

            $dataToUpdate['name'] = $request->name;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] =  auth()->user()->id;

            update(new Additional_sal_type(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->route('additionalsaltypes.index')->with(['success' => 'تم تحديث البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما' . $ex->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $com_code = auth()->user()->com_code;

            $data = get_cols_where_row(new Additional_sal_type(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
            }

            DB::beginTransaction();

            destroy(new Additional_sal_type(), array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->route('additionalsaltypes.index')->with(['success' => 'تم حذف نوع إضافة الراتب بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ما' . $ex->getMessage()]);
        }
    }
}
