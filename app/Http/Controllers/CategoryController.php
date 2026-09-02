<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Supplier;
use App\Models\Brand;
use App\Models\Status;
use App\Models\Color;
use App\Models\Company;
use App\Models\SizeMaseurment;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\ProductType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function departmentListApi(Request $request)
    {
        $query = Department::query();

        if ($request->filled('search')) {
            $query->where('department_name', 'LIKE', "%{$request->search}%");
        }

        $departments = $query
            ->orderBy('department_name', 'asc')
            ->get(['id', 'department_name']);

        return response()->json([
            'success' => true,
            'data' => $departments,
        ]);
    }

    public function departmentStoreApi(Request $request)
    {
        $request->validate([
            'department_name' => 'required|unique:departments,department_name',
        ]);

        $department = Department::create([
            'department_name' => $request->department_name,
        ]);

        $departments = Department::orderBy('department_name', 'asc')
            ->get(['id', 'department_name']);

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully',
            'data' => $departments,
        ], 201);
    }

    function department(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 13);

        $query = Department::query();

        if ($search) {
            $query->where('department_name', 'LIKE', "%{$search}%");
        }

        $query->orderBy('department_name', 'asc');

        $all_departments = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int)$perPage)->appends($request->except('page'));

        return view('admin.category.department', [
            'all_departments' => $all_departments,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    function department_store(Request $request)
    {
        $request->validate([
            'department_name' => 'required|unique:departments,department_name',
        ]);

        department::insert([
            'department_name' => $request->department_name,
            'created_at' => Carbon::now(),

        ]);
        return back()->with('department_add', 'Department added successfully');
    }

    function department_delete($department_id)
    {
        department::find($department_id)->delete();
        return back()->with('delete_department', 'Department delete success');
    }

    function department_edit($department_id)
    {
        $all_departments = Department::find($department_id);
        return view('admin.category.department_edit', [
            'all_departments' => $all_departments,
        ]);
    }

    function department_update(Request $request)
    {
        $request->validate([
            'department_name' => 'required|unique:departments,department_name,' . $request->department_id,
        ]);

        Department::find($request->department_id)->update([
            'department_name' => $request->department_name,
        ]);
        return redirect('department')->with('category_update', 'Department Update Successfull');
    }



    //designation
    public function designationListApi(Request $request)
    {
        $query = Designation::query();

        if ($request->filled('search')) {
            $query->where('designation_name', 'LIKE', "%{$request->search}%");
        }

        $designations = $query
            ->orderBy('designation_name', 'asc')
            ->get(['id', 'designation_name']);

        return response()->json([
            'success' => true,
            'data' => $designations,
        ]);
    }

    public function designationStoreApi(Request $request)
    {
        $request->validate([
            'designation_name' => 'required|unique:designations,designation_name',
        ]);

        Designation::create([
            'designation_name' => $request->designation_name,
        ]);

        $designations = Designation::orderBy('designation_name', 'asc')
            ->get(['id', 'designation_name']);

        return response()->json([
            'success' => true,
            'message' => 'Designation created successfully',
            'data' => $designations,
        ], 201);
    }

    function designation(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 13);

        $query = Designation::query();

        if ($search) {
            $query->where('designation_name', 'LIKE', "%{$search}%");
        }

        $query->orderBy('designation_name', 'asc');

        $all_designations = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int)$perPage)->appends($request->except('page'));

        return view('admin.category.designation', [
            'all_designations' => $all_designations,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    function designation_store(Request $request)
    {
        $request->validate([
            'designation_name' => 'required|unique:designations,designation_name',
        ]);

        Designation::create([
            'designation_name' => $request->designation_name,
        ]);
        return back()->with('add_designation', 'Designation added successfully');
    }
    function designation_delete($designation_id)
    {
        designation::find($designation_id)->delete();
        return back()->with('delete_designation', 'Designation delete success');
    }

    function designation_edit($designation_id)
    {
        $designation =  Designation::find($designation_id);
        return view('admin.category.designation_edit', [
            'designation' => $designation,
        ]);
    }

    function designation_update(Request $request)
    {
        $request->validate([
            'designation_name' => 'required|unique:designations,designation_name,' . $request->designation_id,
        ]);

        Designation::find($request->designation_id)->update([
            'designation_name' => $request->designation_name,
        ]);
        return redirect('designation')->with('designation_update', 'Designation Update Successfull');
    }

    //product Type
      function producttype(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 13);

        $query = ProductType::query();

        if ($search) {
            $query->where('product', 'LIKE', "%{$search}%");
        }

        $query->orderBy('product', 'asc');

        $all_producttypes = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int)$perPage)->appends($request->except('page'));

        return view('admin.category.producttype.producttype_list', [
            'all_producttypes' => $all_producttypes,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function productTypeListApi(Request $request)
    {
        $query = ProductType::query();

        if ($request->filled('search')) {
            $query->where('product', 'LIKE', "%{$request->search}%");
        }

        $productTypes = $query
            ->orderBy('product', 'asc')
            ->get(['id', 'product']);

        return response()->json([
            'success' => true,
            'data' => $productTypes,
        ]);
    }

    function productTypeStoreApi(Request $request)
    {
        $request->validate([
            'product' => 'required|unique:product_types,product',
        ]);

        ProductType::insert([
            'product' => $request->product,
            'created_at' => Carbon::now(),

        ]);
        return back()->with('add_producttype', 'Product Type added successfully');
    }

    
    function product_type(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 13);

        $query = ProductType::query();

        if ($search) {
            $query->where('product', 'LIKE', "%{$search}%");
        }

        $query->orderBy('product', 'asc');

        $all_producttypes = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int)$perPage)->appends($request->except('page'));

        return view('admin.category.producttype.producttype_list', [
            'all_producttypes' => $all_producttypes,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    function product_type_store(Request $request)
    {
        $request->validate([
            'product' => 'required|unique:product_types,product',
        ]);

        ProductType::insert([
            'product' => $request->product,
            'created_at' => Carbon::now(),
        ]);
        return back()->with('add_producttype', 'Product Type added successfully');
    }

    function product_type_delete($ProductType_id)
    {
        ProductType::find($ProductType_id)->delete();
        return back()->with('delete_producttype', 'ProductType delete success');
    }

    // Supplier
     public function supplierListApi(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('supplier_name', 'LIKE', "%{$request->search}%")
                  ->orWhere('phone', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%");
            });
        }

        $suppliers = $query
            ->orderBy('supplier_name', 'asc')
            ->get(['id', 'supplier_name', 'phone', 'email']);

        return response()->json([
            'success' => true,
            'data' => $suppliers,
        ]);
    }

    public function supplierStoreApi(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required',
            'email' => 'nullable|email',
        ]);

        Supplier::create($request->only(['supplier_name', 'address', 'phone', 'email', 'web', 'others1', 'others2']));

        $suppliers = Supplier::orderBy('supplier_name', 'asc')
            ->get(['id', 'supplier_name', 'phone', 'email']);

        return response()->json([
            'success' => true,
            'message' => 'Supplier created successfully',
            'data' => $suppliers,
        ], 201);
    }


    function supplier(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 13);

        $query = Supplier::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('supplier_name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $query->orderBy('supplier_name', 'asc');

        $all_supplier = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int)$perPage)->appends($request->except('page'));

        return view('admin.category.supplier', [
            'all_supplier' => $all_supplier,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    function supplier_store(Request $request)
    {
        Supplier::insert([
            'supplier_name' => $request->supplier_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'web' => $request->web,
            'others1' => $request->others1,
            'others2' => $request->others2,
        ]);
        return back()->with('suppler_add', 'Supplier add successful');
    }

    function supplier_delete($supplier_id)
    {
        Supplier::find($supplier_id)->delete();
        return back()->with('delete_supplier', 'Supplier delete success');
    }



    //brand

     public function brandListApi(Request $request)
    {
        $query = Brand::query();

        if ($request->filled('search')) {
            $query->where('brand_name', 'LIKE', "%{$request->search}%");
        }

        $brands = $query
            ->orderBy('brand_name', 'asc')
            ->get(['id', 'brand_name', 'others']);

        return response()->json([
            'success' => true,
            'data' => $brands,
        ]);
    }

    public function brandStoreApi(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|unique:brands,brand_name',
            'others' => 'nullable|string',
        ]);

        Brand::create($request->only(['brand_name', 'others']));

        $brands = Brand::orderBy('brand_name', 'asc')
            ->get(['id', 'brand_name', 'others']);

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully',
            'data' => $brands,
        ], 201);
    }

    function brand(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 13);

        $query = Brand::query();

        if ($search) {
            $query->where('brand_name', 'LIKE', "%{$search}%");
        }

        $query->orderBy('brand_name', 'asc');

        $all_brand = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int)$perPage)->appends($request->except('page'));

        return view('admin.category.brand.brand', [
            'all_brand' => $all_brand,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    function brand_store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|unique:brands,brand_name',
            'others' => 'nullable|string',
        ]);

        Brand::create($request->only(['brand_name', 'others']));
        return back()->with('brand_add', 'Brand added successfully');
    }

    function brand_delete($brand_id)
    {
        Brand::find($brand_id)->delete();
        return back()->with('brand_delete', 'brand delete success');
    }

    //status

     public function statusListApi(Request $request)
    {
        $query = Status::query();

        if ($request->filled('search')) {
            $query->where('status_name', 'LIKE', "%{$request->search}%");
        }

        $statuses = $query
            ->orderBy('status_name', 'asc')
            ->get(['id', 'status_name', 'description']);

        return response()->json([
            'success' => true,
            'data' => $statuses,
        ]);
    }

    public function statusStoreApi(Request $request)
    {
        $request->validate([
            'status_name' => 'required|unique:statuses,status_name',
        ]);

        Status::create([
            'status_name' => $request->status_name,
            'description' => $request->description,
        ]);

        $statuses = Status::orderBy('status_name', 'asc')
            ->get(['id', 'status_name', 'description']);

        return response()->json([
            'success' => true,
            'message' => 'Status created successfully',
            'data' => $statuses,
        ], 201);
    }


    function status()
    {
        $all_status = Status::paginate(13);
        return view('admin.category.status.status', [
            'all_status' => $all_status,
        ]);
    }

    function status_store(Request $request)
    {
        Status::insert([
            'status_name' => $request->status_name,
            'description' => $request->description,
            'created_at' => Carbon::now(),
        ]);
        return back()->with('status_delete', 'status added');
    }

    function status_delete($status_id)
    {
        Status::find($status_id)->delete();
        return back()->with('status_delete', 'status delete success');
    }

    //size_mesurment
     public function sizeMesurmentListApi(Request $request)
    {
        $query = SizeMaseurment::query();

        if ($request->filled('search')) {
            $query->where('size', 'LIKE', "%{$request->search}%");
        }

        $sizes = $query
            ->orderBy('size', 'asc')
            ->get(['id', 'size', 'description']);

        return response()->json([
            'success' => true,
            'data' => $sizes,
        ]);
    }

    public function sizeMesurmentStoreApi(Request $request)
    {
        $request->validate([
            'size' => 'required',
        ]);

        SizeMaseurment::create([
            'size' => $request->size,
            'description' => $request->description,
        ]);

        $sizes = SizeMaseurment::orderBy('size', 'asc')
            ->get(['id', 'size', 'description']);

        return response()->json([
            'success' => true,
            'message' => 'Size created successfully',
            'data' => $sizes,
        ], 201);
    }

    function size_mesurment()
    {
        $all_SizeMaseurment = SizeMaseurment::paginate(13);
        return view('admin.category.size_mesurment.size', [
            'all_SizeMaseurment' => $all_SizeMaseurment,
        ]);
    }

    function size_mesurment_store(Request $request)
    {
        SizeMaseurment::insert([
            'size' => $request->size,
            'description' => $request->description,
        ]);
        return back()->with('size_added', 'Size added');
    }

    function size_mesurment_delete($sizemesurment_id)
    {
        SizeMaseurment::find($sizemesurment_id)->delete();
        return back()->with('size_delete', 'Size delete success');
    }

    //color

      public function colorListApi(Request $request)
    {
        $query = Color::query();

        if ($request->filled('search')) {
            $query->where('color', 'LIKE', "%{$request->search}%");
        }

        $colors = $query
            ->orderBy('color', 'asc')
            ->get(['id', 'color', 'description']);

        return response()->json([
            'success' => true,
            'data' => $colors,
        ]);
    }

    public function colorStoreApi(Request $request)
    {
        $request->validate([
            'color' => 'required',
        ]);

        Color::create([
            'color' => $request->color,
            'description' => $request->description,
        ]);

        $colors = Color::orderBy('color', 'asc')
            ->get(['id', 'color', 'description']);

        return response()->json([
            'success' => true,
            'message' => 'Color created successfully',
            'data' => $colors,
        ], 201);
    }


    function color()
    {
        $all_color = Color::paginate(13);
        return view('admin.category.color.color', [
            'all_color' => $all_color,
        ]);
    }

    function color_store(Request $request)
    {
        Color::insert([
            'color' => $request->color,
            'description' => $request->description,
        ]);
        return back()->with('color_added', 'color added');
    }

    function color_delete($color_id)
    {
        Color::find($color_id)->delete();
        return back()->with('color_delte', 'Color delete success');
    }

    //company
    public function companyListApi(Request $request)
    {
        $query = Company::query();

        if ($request->filled('search')) {
            $query->where('company', 'LIKE', "%{$request->search}%");
        }

        $companies = $query
            ->orderBy('company', 'asc')
            ->get(['id', 'company', 'description', 'location']);

        return response()->json([
            'success' => true,
            'data' => $companies,
        ]);
    }

    public function companyStoreApi(Request $request)
    {
        $request->validate([
            'company' => 'required|unique:companies,company',
        ]);

        Company::create([
            'company' => $request->company,
            'description' => $request->description,
            'location' => $request->location,
        ]);

        $companies = Company::orderBy('company', 'asc')
            ->get(['id', 'company', 'description', 'location']);

        return response()->json([
            'success' => true,
            'message' => 'Company created successfully',
            'data' => $companies,
        ], 201);
    }

    
    function company_list()
    {
        $all_company = Company::paginate(13);
        return view('admin.category.company.company', [
            'all_company' => $all_company,
        ]);
    }

    function company_store(Request $request)
    {
        Company::insert([
            'company' => $request->company,
            'description' => $request->description,
            'location' => $request->location,
        ]);
        return back()->with('company_added', 'Company delete success');
    }

    function company_delete($company_id)
    {
        Company::find($company_id)->delete();
        return back()->with('comapny_delete', 'Company delete success');
    }
    


    function search_by_id($company_id)
    {
        $company = Company::find($company_id);
        $company_data = [
            'id' => $company->id,
            'company' => $company->company,
            'description' => $company->description,
        ];
        $company = Company::find($company_id);
        return response()->json(['data' => $company_data]);
    }


    //Department Asset
    public function departments_asset(Request $request, $department_id)
    {
        $role = auth()->user()->roles[0];
        $search = $request->input('search', '');
        $showAll = $request->input('show_all', false); // boolean or string
        $companies = [];

        // Build allowed company list from permissions
        if ($role->hasPermissionTo('view BHML INDUSTRIES LTD.')) $companies[] = 1;
        if ($role->hasPermissionTo('view BETTEX')) $companies[] = 2;
        if ($role->hasPermissionTo('view BETTEX PREMIUM')) $companies[] = 3;
        if ($role->hasPermissionTo('view BETTEX BRIDGE')) $companies[] = 4;

        // Query from view: employee_asset_summary
        $query = DB::table('employee_asset_summary')
            ->where('department_id', $department_id)
            ->whereIn('company', $companies);

        // Apply search if provided
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('emp_id', 'LIKE', "%{$search}%")
                    ->orWhere('emp_name', 'LIKE', "%{$search}%")
                    ->orWhere('designation_name', 'LIKE', "%{$search}%");
            });
        }

        // Show all or paginate
        if ($showAll) {
            $employees = $query->get(); // all results
        } else {
            $employees = $query->paginate(25); // paginated
        }

        return view('admin.department_asset', [
            'employees' => $employees,
            'showAll' => $showAll,
            'search' => $search
        ]);
    }

}
