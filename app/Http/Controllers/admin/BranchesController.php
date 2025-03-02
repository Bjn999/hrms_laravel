<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branche;
use App\Models\Admin_panel_settings;
use Illuminate\Http\Request;
use App\Http\Requests\BranchesRequest;
use Illuminate\Support\Facades\DB;

class BranchesController extends Controller
{
    // Show all branches of the company 
    public function index() {
        //
        $com_code = auth()->user()->com_code;
        // $data = Branche::select("*")->where("com_code", $com_code)->orderby("id", "DESC")->paginate(P_C);
        $data = get_cols_where_p(new Branche(), array("*"), array("com_code" => $com_code), "id", "DESC", P_C);
        return view('admin.branches.index', ['data' => $data]);
        // return $data;
    }

    // Show Add view of Branch 
    public function create() {
        return view("admin.branches.create");
    }
    
    // Insert new branch in table 
    public function store(BranchesRequest $request) {
        try {
            $com_code = auth()->user()->com_code;
            $checkExist = get_cols_where_row(new Branche(), array("id"), array("com_code" => $com_code, "name" => $request->name));
            
            DB::beginTransaction();

            $dataToInsert['name'] = $request->name;
            $dataToInsert['phones'] = $request->phones;
            $dataToInsert['address'] = $request->address;
            $dataToInsert['email'] = $request->email;
            $dataToInsert['active'] = $request->active;
            $dataToInsert['added_by'] = auth()->user()->id;
            $dataToInsert['com_code'] = auth()->user()->com_code;
            insert(new Branche(), $dataToInsert);

            DB::commit();

            return redirect()->route('branches.index')->with(['success' => 'تمت إضافة الفرع بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ ' . $ex->getMessage()])->withInput();
        }
    }

    // Show edit page of branches 
    public function edit($id) {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Branche(), array("*"), array("id" => $id, "com_code" => $com_code));
        if (empty($data)) {
            return redirect()->route("branches.index")->with(["error" => "عفواً غير قادر على الوصول إلى البيانات المطلوبة"]);
        }
        return view('admin.branches.edit', ['data' => $data]);
    }
    
    // Update a specific branche 
    public function update(BranchesRequest $request, $id) {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Branche(), array("*"), array("id" => $id, "com_code" => $com_code));
            if (empty($data)) {
                return redirect()->route("branches.index")->with(["error" => "عفواً غير قادر على الوصول إلى البيانات المطلوبة"]);
            }

            DB::beginTransaction();

            $dataToUpdate['name'] = $request->name;
            $dataToUpdate['phones'] = $request->phones;
            $dataToUpdate['address'] = $request->address;
            $dataToUpdate['email'] = $request->email;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] = auth()->user()->id;
            update(new Branche(), $dataToUpdate, array("id" => $id, "com_code" => $com_code));
            
            DB::commit();

            return redirect()->route("branches.index")->with(["success" => "تم تعديل البيانات بنجاح"]);

        } catch (\Exception $ex) {

            DB::rollBack();
            return redirect()->back()->with(["error" => "عفواً حدث خطأ".$ex->getMessage()])->withInput();

        }

    }
    
    // Delete a specific branche 
    public function destroy($id) {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Branche(), array("*"), array("id" => $id, "com_code" => $com_code));
            if (empty($data)) {
                return redirect()->route("branches.index")->with(["error" => "عفواً غير قادر على الوصول إلى البيانات المطلوبة"]);
            }

            destroy(new Branche(), array("id" => $id, "com_code" => $com_code));

            DB::commit();

            return redirect()->route("branches.index")->with(["success" => "تم حذف الفرع بنجاح"]);

        } catch (\Exception $ex) {
            
            DB::rollBack();
            return redirect()->route("branches.index")->with(["error" => "عفواً حدث خطأ".$ex->getMessage()])->withInput();

        }

    }
}
