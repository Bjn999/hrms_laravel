<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shifts_typesRequest;
use Illuminate\Http\Request;
use App\Models\shifts_type;
use App\Models\User;
// use App\Requests\Shifts_typesRequest;
use Illuminate\Support\Facades\DB;

class Shifts_typeController extends Controller
{
    // show all shifts
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new shifts_type(), array("*"), array("com_code" => $com_code), "id", "DESC", P_C);
        return view("admin.shifts_types.index", ["data" => $data]);
    }

    // show page of insert new shift
    public function create()
    {
        return view("admin.shifts_types.create");
    }

    // insert new shift in DB 
    public function store(Shifts_typesRequest $request)
    {
        try {
            $dataToInsert['type'] = $request->type;
            $dataToInsert['from_time'] = $request->from_time;
            $dataToInsert['to_time'] = $request->to_time;
            $dataToInsert['total_hour'] = $request->total_hour;
            $dataToInsert['com_code'] = auth()->user()->com_code;
            $dataToInsert['added_by'] = auth()->user()->id;

            $checkExistData = get_cols_where_row(new shifts_type(), array("*"), $dataToInsert);

            if (!empty($checkExistData)) {
                return redirect()->back()->with(["error" => "عفواً هذه البيانات موجودة من قبل"])->withInput();
            }

            $dataToInsert['active'] = $request->active;

            DB::beginTransaction();
            insert(new shifts_type(), $dataToInsert);
            DB::commit();

            return redirect()->route("shiftstypes.index")->with(["success" => "تم حفظ البيانات بنجاح"]);
        } catch (\Exception $ex) {

            DB::rollBack();
            return redirect()->back()->with(["error" => "عفواً حدث خطأ" . $ex->getMessage()])->withInput();
        }
    }

    // show edit page of a specific shift
    public function edit($id)
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new shifts_type(), array("*"), array("id" => $id, "com_code" => $com_code));
        if (empty($data)) {
            return redirect()->route("shiftstypes.index")->with(["error" => "عفواً غير قادر على الوصول للبيانات المطلوبة"]);
        }
        return view("admin.shifts_types.edit", ["data" => $data]);
    }

    // edit a specific shift in DB
    public function update(Shifts_typesRequest $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new shifts_type(), array("*"), array("id" => $id, "com_code" => $com_code));
            if (empty($data)) {
                return redirect()->route("shiftstypes.index")->with(["error" => "عفواً غير قادر على الوصول للبيانات المطلوبة"]);
            }

            $checkExistData = shifts_type::select("id")->where("type", "=", $request->type)->where("from_time", "=", $request->from_time)->where("to_time", "=", $request->to_time)->where("total_hour", "=", $request->total_hour)->where('id', '!=', $id)->first();

            if (!empty($checkExistData)) {
                return redirect()->back()->with(["error" => "عفواً هذه البيانات موجودة من قبل"])->withInput();
            }

            DB::beginTransaction();

            $dataToUpdate['type'] = $request->type;
            $dataToUpdate['from_time'] = $request->from_time;
            $dataToUpdate['to_time'] = $request->to_time;
            $dataToUpdate['total_hour'] = $request->total_hour;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] = auth()->user()->id;
            update(new shifts_type(), $dataToUpdate, array("com_code" => $com_code, "id" => $id));

            DB::commit();

            return redirect()->route('shiftstypes.index')->with(['success' => 'تم تعديل البيانات بنجاح']);
        } catch (\Exception $ex) {

            DB::rollBack();
            return redirect()->back()->with(["error" => "عفواً حدث خطأ" . $ex->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new shifts_type(), array("id"), array("id" => $id, "com_code" => $com_code));
            if (empty($data)) {
                return redirect()->route("shiftstypes.index")->with(["error" => "عفواً غير قادر على الوصول للبيانات المطلوبة"]);
            }
            DB::beginTransaction();

            destroy(new shifts_type(), array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->route('shiftstypes.index')->with(["success" => "تم حذف الشفت بنجاح"]);
        } catch (\Exception $ex) {

            DB::rollBack();
            return redirect()->back()->with(["error" => "عفواً حدث خطأ" . $ex->getMessage()])->withInput();
        }
    }

    public function ajax_search(Request $request) {
        if ($request->ajax()) {
            $type_search = $request->type_search;
            $hour_from_range = $request->hour_from_range;
            $hour_to_range = $request->hour_to_range;

            // ShiftTypes 
            if ($type_search == "all") {
                # Here is a condition always is enabled 
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = "type";
                $operator1 = "=";
                $value1 = $type_search;
            }

            // HourFrom 
            if ($hour_from_range == "") {
                # Here is a condition always is enabled 
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "total_hour";
                $operator2 = ">=";
                $value2 = $hour_from_range;
            }

            // HourTo 
            if ($hour_to_range == "") {
                # Here is a condition always is enabled 
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            } else {
                $field3 = "total_hour";
                $operator3 = "<=";
                $value3 = $hour_to_range;
            }

            $data = shifts_type::select('*')->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->orderBy('id', 'DESC')->paginate(P_C);

            return view("admin.shifts_types.ajax_search", ['data' => $data]);
        }
    }
}
