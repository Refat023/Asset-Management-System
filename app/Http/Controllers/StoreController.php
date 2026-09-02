<?php

namespace App\Http\Controllers;

use App\Exports\HistoryExport;
use App\Exports\StockSummaryExport;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\ProductType;
use App\Models\Store;
use App\Models\Brand;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Company;
use App\Models\issue;
use App\Models\SizeMaseurment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Image;
use App\Models\Desktop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StoreExport;
use App\Exports\TransferExport;
use App\Models\Transfer;
use App\Models\TransferRequest;
use App\Models\Maintenance;
use App\Exports\MaintenanceExport;
use App\Models\WastProduct;
use App\Models\SizeMeasurement;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\stores_file;
use App\Exports\WastProductExport;
use App\Imports\StoreImport;

class StoreController extends Controller
{
    private function hasGlobalAttirePermission($role): bool
    {
        if (!$role) {
            return false;
        }

        $globalPermissions = [
            'view global attire',
            'view global attire ltd.',
            'view global attire ltd',
            'view global_attire',
        ];

        foreach ($globalPermissions as $permissionName) {
            if ($role->hasPermissionTo($permissionName)) {
                return true;
            }
        }

        return false;
    }

    private function getAllowedCompanyIdsForRole($role): array
    {
        if ($this->hasGlobalAttirePermission($role)) {
            return [];
        }

        $companies = [];
        if ($role->hasPermissionTo('view BHML INDUSTRIES LTD.')) $companies[] = 1;
        if ($role->hasPermissionTo('view BETTEX')) $companies[] = 2;
        if ($role->hasPermissionTo('view BETTEX PREMIUM')) $companies[] = 3;
        if ($role->hasPermissionTo('view BETTEX BRIDGE')) $companies[] = 4;

        return $companies;
    }

public function store(Request $request)
{
    $role = auth()->user()->roles[0];

    $search         = $request->input('search');
    $productSearch  = $request->input('product_search');
    $assetTag       = $request->input('asset_tag'); // from employee asset click
    $companyFilter  = $request->input('company_filter');
    $perPage        = $request->input('per_page', session('asset_per_page', 10));
    session(['asset_per_page' => $perPage]);
    $showDeleted    = $request->input('show_deleted', false);

    // Determine which companies the user can view
    $companies = $this->getAllowedCompanyIdsForRole($role);

    // Base query
    $query = Store::query()
        ->join('brands', 'brands.id', '=', 'stores.brand_id')
        ->join('product_types', 'product_types.id', '=', 'stores.asset_type')
        ->whereNotIn('stores.checkstatus', ['DELETE', 'ARCHIVE']);

    if ($this->hasGlobalAttirePermission($role)) {
        if (Schema::hasColumn('stores', 'created_by')) {
            $query->where('stores.created_by', auth()->id());
        } else {
            $query->whereRaw('1 = 0');
        }
    } else {
        $query->whereIn('stores.company_id', $companies);
    }

    // Company filter
    if ($companyFilter) {
        $query->where('stores.company_id', $companyFilter);
    }

    // Product type filter
    if ($productSearch) {
        $query->where('product_types.id', $productSearch);
    }

    // Asset tag filter (from employee asset page)
    if ($assetTag) {
        $query->where('stores.asset_tag', $assetTag);
    }

    // General search
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('stores.asset_tag', 'LIKE', "%{$search}%")
                ->orWhere('brands.brand_name', 'LIKE', "%{$search}%")
                ->orWhere('stores.vendor', 'LIKE', "%{$search}%")
                ->orWhere('stores.checkstatus', 'LIKE', "%{$search}%")
                ->orWhere('stores.asset_sl_no', 'LIKE', "%{$search}%");
        });
    }

    // Order newest first
    $query->orderBy('stores.id', 'desc');

    // Pagination
    if ($perPage === 'all') {
        $stores = $query->select('stores.*')->get();
    } else {
        $stores = $query->select('stores.*')
            ->paginate((int) $perPage)
            ->appends($request->except('page'));
    }

    return view('admin.store.store_list', [
        'stores'             => $stores,
        'search'             => $search,
        'perPage'            => $perPage,
        'productSearch'      => $productSearch,
        'companyFilter'      => $companyFilter,
        'showDeleted'        => $showDeleted,
        'all_product_types'  => ProductType::all(),
        'all_departments'    => Department::all(),
        'all_brands'         => Brand::all(),
        'all_SizeMeasurement'=> SizeMaseurment::all(),
        'all_status'         => Status::all(),
        'all_supplier'       => Supplier::all(),
        'all_company'        => Company::all(),
        'employee'           => Employee::where('status', 'Active')->get(),
        'all_issue'          => Issue::all(),
    ]);
}


    function add_product()
    {
        $all_product_types = ProductType::all();
        $all_departments = Department::all();
        $all_brands = Brand::all();
        $all_SizeMaseurment = SizeMaseurment::all();
        $all_status = Status::all();
        $all_supplier = Supplier::all();
        $all_company = Company::all();
        return view('admin.store.add_product', [
            'all_product_types' => $all_product_types,
            'all_departments' => $all_departments,
            'all_brands' => $all_brands,
            'all_SizeMaseurment' => $all_SizeMaseurment,
            'all_status' => $all_status,
            'all_supplier' => $all_supplier,
            'all_company' => $all_company,
        ]);
    }

    function store_store(Request $request)
    {
        $picture_id = Store::insertGetId([
            'asset_tag' => $request->asset_tag,
            'asset_type' => $request->asset_type,
            'model' => $request->model,
            'brand_id' => $request->brand_id,
            'description' => $request->description,
            'asset_sl_no' => $request->asset_sl_no,
            'qty' => $request->qty,
            'units_id' => $request->units_id,
            'warrenty' => $request->warrenty,
            'durablity' => $request->durablity,
            'cost' => $request->cost,
            'currency' => $request->currency,
            'vendor' => $request->vendor,
            'purchase_date' => $request->purchase_date,
            'challan_no' => $request->challan_no,
            'status_id' => $request->status_id,
            'location' => $request->department_id,
            'company_id' => $request->company_id,
            'others' => $request->others,
            'checkstatus' => $request->checkstatus ?? 'INSTOCK',
            'others2' => $request->others2,
            'created_by' => auth()->id(),
            'created_at' => Carbon::now(),
        ]);
        if ($request->file('picture')) {
            $imageName = $picture_id . '.' . $request->picture->extension();
            $request->picture->move(public_path('uploads/store/'), $imageName);

            Store::where('id', $picture_id)->update([
                'picture' => $imageName,
            ]);
        }

        return redirect()->route('instock_list')->with('success', 'Product added...!');
    }

    public function storeListApi(Request $request)
    {
        $query = Store::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('asset_tag', 'LIKE', "%{$request->search}%")
                  ->orWhere('model', 'LIKE', "%{$request->search}%")
                  ->orWhere('vendor', 'LIKE', "%{$request->search}%")
                  ->orWhere('asset_sl_no', 'LIKE', "%{$request->search}%");
            });
        }

        $stores = $query
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $stores,
        ]);
    }

    public function storeStoreApi(Request $request)
    {
        $request->validate([
            'asset_tag' => 'required|unique:stores,asset_tag',
        ]);

        $payload = $request->only([
            'asset_tag',
            'asset_type',
            'model',
            'brand_id',
            'description',
            'asset_sl_no',
            'qty',
            'units_id',
            'warrenty',
            'durablity',
            'cost',
            'currency',
            'vendor',
            'purchase_date',
            'challan_no',
            'status_id',
            'company_id',
            'others',
            'others2',
        ]);

        $payload['location'] = $request->input('location', $request->input('department_id'));
        $payload['checkstatus'] = $request->input('checkstatus', 'INSTOCK');

        $store = Store::create($payload);

        return response()->json([
            'success' => true,
            'message' => 'Store created successfully',
            'data' => $store,
        ], 201);
    }

    //Store INSTOCK list start
    function instock_list(Request $request)
    {
        $role = auth()->user()->roles[0];

        $search = $request->input('search', '');
        $productSearch = $request->input('product_search');
        $companyFilter = $request->input('company_filter');
        $perPage = $request->input('per_page', session('asset_per_page', 10));
        session(['asset_per_page' => $perPage]);

        $companies = $this->getAllowedCompanyIdsForRole($role);

        $query = Store::query()
            ->join('brands', 'brands.id', '=', 'stores.brand_id')
            ->join('product_types', 'product_types.id', '=', 'stores.asset_type')
            ->where('stores.checkstatus', 'INSTOCK');

        if ($this->hasGlobalAttirePermission($role)) {
            if (Schema::hasColumn('stores', 'created_by')) {
                $query->where('stores.created_by', auth()->id());
            } else {
                $query->whereRaw('1 = 0');
            }
        } else {
            $query->whereIn('stores.company_id', $companies);
        }

        if ($companyFilter) {
            $query->where('stores.company_id', $companyFilter);
        }

        if ($search || $productSearch) {
            $query->where(function ($q) use ($search, $productSearch) {
                if ($productSearch) {
                    $q->where('product_types.id', '=', $productSearch);
                }
                if ($search) {
                    $q->where(function ($sq) use ($search) {
                        $sq->where('stores.asset_tag', 'LIKE', "%{$search}%")
                            ->orWhere('brands.brand_name', 'LIKE', "%{$search}%")
                            ->orWhere('stores.vendor', 'LIKE', "%{$search}%")
                            ->orWhere('stores.asset_sl_no', 'LIKE', "%{$search}%")
                            ->orWhere('stores.checkstatus', 'LIKE', "%{$search}%");
                    });
                }
            });
        }

        $query->orderBy('stores.id', 'desc');

        $stores = $perPage === 'all'
            ? $query->select('stores.*')->get()
            : $query->select('stores.*')->paginate((int)$perPage)->appends($request->except('page'));

        return view('admin.store.store_list', [
            'stores' => $stores,
            'search' => $search,
            'perPage' => $perPage,
            'productSearch' => $productSearch,
            'companyFilter' => $companyFilter,
            'showDeleted' => true, // ✅ Flag to detect deleted page
            'all_product_types' => ProductType::all(),
            'all_brands' => Brand::all(),
            'all_company' => Company::all(),
            'employee' => Employee::where('status', 'Active')->get(),
            'all_issue' => Issue::all(),
        ]);
    }
    //Store INSTOCK list end

    //store issue_list start
    function issue_list(Request $request)
    {
        $role = auth()->user()->roles[0];

        $search = $request->input('search', '');
        $productSearch = $request->input('product_search');
        $companyFilter = $request->input('company_filter');
        $perPage = $request->input('per_page', session('asset_per_page', 10));
        session(['asset_per_page' => $perPage]);

        $companies = [];
        if ($role->hasPermissionTo('view BHML INDUSTRIES LTD.')) $companies[] = 1;
        if ($role->hasPermissionTo('view BETTEX')) $companies[] = 2;
        if ($role->hasPermissionTo('view BETTEX PREMIUM')) $companies[] = 3;
        if ($role->hasPermissionTo('view BETTEX BRIDGE')) $companies[] = 4;

        $query = Store::query()
            ->join('brands', 'brands.id', '=', 'stores.brand_id')
            ->join('product_types', 'product_types.id', '=', 'stores.asset_type')
            ->whereIn('stores.company_id', $companies)
            ->whereNotIn('stores.checkstatus', ['INSTOCK', 'MAINTENANCE', 'Wast Products', 'DELETE']);
        // Exclude INSTOCK

        if ($companyFilter) {
            $query->where('stores.company_id', $companyFilter);
        }

        if ($search || $productSearch) {
            $query->where(function ($q) use ($search, $productSearch) {
                if ($productSearch) {
                    $q->where('product_types.id', '=', $productSearch);
                }
                if ($search) {
                    $q->where(function ($sq) use ($search) {
                        $sq->where('stores.asset_tag', 'LIKE', "%{$search}%")
                            ->orWhere('brands.brand_name', 'LIKE', "%{$search}%")
                            ->orWhere('stores.vendor', 'LIKE', "%{$search}%")
                            ->orWhere('stores.asset_sl_no', 'LIKE', "%{$search}%")
                            ->orWhere('stores.checkstatus', 'LIKE', "%{$search}%");
                    });
                }
            });
        }

        $query->orderBy('stores.id', 'desc');

        $stores = $perPage === 'all'
            ? $query->select('stores.*')->get()
            : $query->select('stores.*')->paginate((int)$perPage)->appends($request->except('page'));

        return view('admin.store.store_list', [
            'stores' => $stores,
            'search' => $search,
            'perPage' => $perPage,
            'productSearch' => $productSearch,
            'companyFilter' => $companyFilter,
            'showDeleted' => true, // ✅ Flag to detect deleted page
            'all_product_types' => ProductType::all(),
            'all_brands' => Brand::all(),
            'all_company' => Company::all(),
            'employee' => Employee::where('status', 'Active')->get(),
            'all_issue' => Issue::all(),
        ]);
    }
    //store issue_list end


    //delete start
    public function store_delete(Request $request, $stores_id)
    {
        $store = Store::find($stores_id);

        if ($store) {
            $comment = $request->input('delete_comment', '');
            $store->update([
                'checkstatus' => 'DELETE',
                'others' => $comment
            ]);
            return back()->with('delete_success', 'Store record marked as deleted.');
        }

        return back()->with('error', 'Store record not found.');
    }

    public function store_delete_list(Request $request)
    {
        $role = auth()->user()->roles[0];

        $search = $request->input('search', '');
        $productSearch = $request->input('product_search');
        $companyFilter = $request->input('company_filter');
        $perPage = $request->input('per_page', session('asset_per_page', 10));
        session(['asset_per_page' => $perPage]);

        $companies = [];
        if ($role->hasPermissionTo('view BHML INDUSTRIES LTD.')) $companies[] = 1;
        if ($role->hasPermissionTo('view BETTEX')) $companies[] = 2;
        if ($role->hasPermissionTo('view BETTEX PREMIUM')) $companies[] = 3;
        if ($role->hasPermissionTo('view BETTEX BRIDGE')) $companies[] = 4;

        $query = Store::query()
            ->join('brands', 'brands.id', '=', 'stores.brand_id')
            ->join('product_types', 'product_types.id', '=', 'stores.asset_type')
            ->whereIn('stores.company_id', $companies)
            ->where('stores.checkstatus', 'DELETE'); // Only deleted

        if ($companyFilter) {
            $query->where('stores.company_id', $companyFilter);
        }

        if ($search || $productSearch) {
            $query->where(function ($q) use ($search, $productSearch) {
                if ($productSearch) {
                    $q->where('product_types.id', '=', $productSearch);
                }
                if ($search) {
                    $q->where(function ($sq) use ($search) {
                        $sq->where('stores.asset_tag', 'LIKE', "%{$search}%")
                            ->orWhere('brands.brand_name', 'LIKE', "%{$search}%")
                            ->orWhere('stores.vendor', 'LIKE', "%{$search}%")
                            ->orWhere('stores.asset_sl_no', 'LIKE', "%{$search}%")
                            ->orWhere('stores.checkstatus', 'LIKE', "%{$search}%");
                    });
                }
            });
        }

        $query->orderBy('stores.id', 'desc');

        $stores = $perPage === 'all'
            ? $query->select('stores.*')->get()
            : $query->select('stores.*')->paginate((int)$perPage)->appends($request->except('page'));

        return view('admin.store.store_list', [
            'stores' => $stores,
            'search' => $search,
            'perPage' => $perPage,
            'productSearch' => $productSearch,
            'companyFilter' => $companyFilter,
            'showDeleted' => true, // ✅ Flag to detect deleted page
            'all_product_types' => ProductType::all(),
            'all_brands' => Brand::all(),
            'all_company' => Company::all(),
            'employee' => Employee::where('status', 'Active')->get(),
            'all_issue' => Issue::all(),
        ]);
    }

    public function store_archive(Request $request, $stores_id)
    {
        $store = Store::find($stores_id);

        if ($store) {
            $comment = $request->input('archive_comment', '');
            $store->update([
                'checkstatus' => 'ARCHIVE',
                'others' => $comment
            ]);
            return back()->with('archive_success', 'Store record archived successfully.');
        }

        return back()->with('error', 'Store record not found.');
    }

    public function store_archive_list(Request $request)
    {
        $role = auth()->user()->roles[0];

        $search = $request->input('search', '');
        $productSearch = $request->input('product_search');
        $companyFilter = $request->input('company_filter');
        $perPage = $request->input('per_page', session('asset_per_page', 10));
        session(['asset_per_page' => $perPage]);

        $companies = [];
        if ($role->hasPermissionTo('view BHML INDUSTRIES LTD.')) $companies[] = 1;
        if ($role->hasPermissionTo('view BETTEX')) $companies[] = 2;
        if ($role->hasPermissionTo('view BETTEX PREMIUM')) $companies[] = 3;
        if ($role->hasPermissionTo('view BETTEX BRIDGE')) $companies[] = 4;

        $query = Store::query()
            ->join('brands', 'brands.id', '=', 'stores.brand_id')
            ->join('product_types', 'product_types.id', '=', 'stores.asset_type')
            ->whereIn('stores.company_id', $companies)
            ->where('stores.checkstatus', 'ARCHIVE'); // Only archived

        if ($companyFilter) {
            $query->where('stores.company_id', $companyFilter);
        }

        if ($search || $productSearch) {
            $query->where(function ($q) use ($search, $productSearch) {
                if ($productSearch) {
                    $q->where('product_types.id', '=', $productSearch);
                }
                if ($search) {
                    $q->where(function ($sq) use ($search) {
                        $sq->where('stores.asset_tag', 'LIKE', "%{$search}%")
                            ->orWhere('brands.brand_name', 'LIKE', "%{$search}%")
                            ->orWhere('stores.vendor', 'LIKE', "%{$search}%")
                            ->orWhere('stores.asset_sl_no', 'LIKE', "%{$search}%")
                            ->orWhere('stores.checkstatus', 'LIKE', "%{$search}%");
                    });
                }
            });
        }

        $query->orderBy('stores.id', 'desc');

        $stores = $perPage === 'all'
            ? $query->select('stores.*')->get()
            : $query->select('stores.*')->paginate((int)$perPage)->appends($request->except('page'));

        return view('admin.store.store_list', [
            'stores' => $stores,
            'search' => $search,
            'perPage' => $perPage,
            'productSearch' => $productSearch,
            'companyFilter' => $companyFilter,
            'showDeleted' => true,
            'showArchived' => true,
            'all_product_types' => ProductType::all(),
            'all_brands' => Brand::all(),
            'all_company' => Company::all(),
            'employee' => Employee::where('status', 'Active')->get(),
            'all_issue' => Issue::all(),
        ]);
    }

    //delete end


    //Edit start
    function store_edit($stores_id)
    {
        $all_product_types = ProductType::all();
        $all_brands = Brand::all();
        $all_supplier = Supplier::all();
        $all_company = Company::all();
        $all_status = Status::all();
        $all_departments = Department::all();
        $all_SizeMaseurment = SizeMaseurment::all();
        $all_store = Store::find($stores_id);
        return view('admin.store.store_edit', [
            'all_store' => $all_store,
            'all_product_types' => $all_product_types,
            'all_brands' => $all_brands,
            'all_supplier' => $all_supplier,
            'all_company' => $all_company,
            'all_status' => $all_status,
            'all_departments' => $all_departments,
            'all_SizeMaseurment' => $all_SizeMaseurment,
        ]);
    }

    //update
    function store_update(Request $request)

    {
        if ($request->picture == '') {
            Store::find($request->stores_id)->update([
                'asset_tag' => $request->asset_tag,
                'asset_type' => $request->asset_type,
                'model' => $request->model,
                'brand_id' => $request->brand_id,
                'description' => $request->description,
                'asset_sl_no' => $request->asset_sl_no,
                'qty' => $request->qty,
                'units_id' => $request->units_id,
                'warrenty' => $request->warrenty,
                'durablity' => $request->durablity,
                'cost' => $request->cost,
                'currency' => $request->currency,
                'vendor' => $request->vendor,
                'purchase_date' => $request->purchase_date,
                'challan_no' => $request->challan_no,
                'status_id' => $request->status_id,
                'location' => $request->location,
                'company_id' => $request->company_id,
                'others' => $request->others,
                'others2' => $request->others2,
            ]);
            return back();
        } else {
            $store = Store::find($request->stores_id);
            $delete_from = public_path('/uploads/store/' . $store->picture);
            if (file_exists($delete_from)) {
                unlink($delete_from);
            }

            $imageName = null;
            if ($request->file('picture')) {
                $imageName = $request->stores_id . '.' . $request->picture->extension();
                $request->picture->move(public_path('uploads/store/'), $imageName);

                Store::where('id', $request->stores_id)->update([
                    'picture' => $imageName,
                ]);
                return back();
            }
        }
    }
    //Edit end


    //view start
    function store_view($stores_id)
    {
        $stores_info = Store::find($stores_id);
        return view('admin.store.store_printview', [
            'stores_info' => $stores_info,
        ]);
    }
    //view end


    //issue start...
    public function issue($stores_id)
    {
        // get store info
        $store = Store::with([
            'rel_to_ProductType',
            'rel_to_brand',
            'rel_to_Company',
            'rel_to_Department',
            'rel_to_Designation'
        ])->findOrFail($stores_id);

        // previous data
        $issued_products = DB::select("CALL sp_issued_products()");
        $product_types = ProductType::all();
        $departments = Department::all();
        $designation = Designation::all();
        $stores = Store::all();
        $employee = Employee::where('status', 'Active')->get();

        return view('admin.store.issue', [
            'store' => $store, // 👈 single store data
            'issued_products' => $issued_products,
            'stores' => $stores,
            'departments' => $departments,
            'designation' => $designation,
            'product_types' => $product_types,
            'employee' => $employee,
        ]);
    }


    public function issue_store(Request $request)
    {
        // Insert issue record
        Issue::insertGetId([
            'asset_tag' => $request->asset_tag,
            'asset_type' => $request->asset_type,
            'model' => $request->model,
            'description' => $request->description,
            'emp_id' => $request->emp_id,
            'emp_name' => $request->emp_name,
            'designation_id' => $request->designation_id,
            'department_id' => $request->department_id,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'others' => $request->others,
            'issue_date' => $request->issue_date,
            'return_date' => $request->return_date,
            'created_at' => Carbon::now(),
        ]);

        // Redirect to the instock list page
        return redirect()->route('instock_list')
            ->with('issue_success', 'Product issued successfully!');
    }
    //issue end

    //autofill start...
    function search_by_id($store_id)
    {
        $issued_products = Store::find($store_id);
        $issued_products_data = [
            'asset_tag' => $issued_products->asset_tag,
            'asset_type' => $issued_products->rel_to_ProductType->product,
            'model' => $issued_products->model,
            'purchase_date' => $issued_products->purchase_date,
            'asset_sl_no' => $issued_products->asset_sl_no,
            'others' => $issued_products->others, // using `others` instead of company_id
            'company_id' => $issued_products->company_id
        ];
        $issued_products = Store::find($store_id);
        return response()->json(['data' => $issued_products_data]);
    }

    function store_export(Request $request)
    {

        if ($request->type == "xlsx") {
            $extension = "xlsx";
            $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        } elseif ($request->type == "csv") {
            $extension = "csv";
            $exportFormat = \Maatwebsite\Excel\Excel::CSV;
        } elseif ($request->type == "xls") {
            $extension = "xls";
            $exportFormat = \Maatwebsite\Excel\Excel::XLS;
        } else {
            $extension = "xlsx";
            $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        }


        $Filename = "assetlist-data.$extension";
        return Excel::download(new StoreExport($request->input("search")), $Filename, $exportFormat);
    }
    //store Import start...
    function store_import()
    {
        return view('admin.store.store_import');
    }

    function store_importexceldata(Request $request)
    {
        $request->validate([
            'asset_import' => [
                'required',
                'file'
            ],
        ]);
        Excel::import(new StoreImport, $request->file('asset_import'));

        return back()->with('asset_data', 'Data has been imported');
    }

    //store Import start...


    //invoice start...
    function invoice($stores_id)
    {
        $stores_info = Store::find($stores_id);

        $issue_info = DB::table('issues')->select('asset_tag', 'asset_type', 'model', 'emp_id', 'emp_name', 'phone_number', 'email', 'designation_id', 'issue_date')->where('asset_tag', $stores_info->asset_tag)->first();

        return view('admin.store.invoice', [
            'stores_info' => $stores_info,
            'issue_info' => $issue_info,
        ]);
    }

    function qr_code($stores_id)
    {
        $qrCode = Store::find($stores_id);
        return view('admin.store.qr_code', [
            'qrCode' => $qrCode,
        ]);
    }

    function qr_code_view($stores_id)
    {
        $stores_info = Store::find($stores_id);
        $issue_info = DB::table('issues')->select('asset_tag', 'asset_type', 'model', 'emp_id', 'emp_name', 'phone_number', 'email', 'designation_id', 'issue_date')->where('asset_tag', $stores_info->asset_tag)->orderBy('issue_date', 'desc')->first();
        return view('admin.store.qr_code_view', [
            'stores_info' => $stores_info,
            'issue_info' => $issue_info,
        ]);
    }

    //invoice end.



    //return start...
    public function return_form(Request $request)
    {
        return view('admin.store.return', [
            'asset_tag' => $request->asset_tag,
            'asset_type' => $request->asset_type,
            'model' => $request->model,
            'emp_id' => $request->emp_id,
            'emp_name' => $request->emp_name,
            'issue_date' => $request->issue_date,
        ]);
    }


    public function return_update(Request $request)
    {
        // Validate
        $request->validate([
            'asset_tag' => 'required|string',
            'return_date' => 'required|date',
        ]);

        // Update only return_date in issues table
        $updated = DB::table('issues')
            ->where('asset_tag', $request->asset_tag)
            ->update(['return_date' => $request->return_date]);

        if ($updated) {
            // Redirect to the specific asset’s history page
            return redirect()->route('history', ['asset_tag' => $request->asset_tag])
                ->with('return_success', 'Product return successful!');
        }

        return redirect()->back()->with('error', 'No matching record found for this asset.');
    }

    // Autofill return modal
    public function return_search_by_id($issue_id)
    {
        $return_product = DB::table('issues')
            ->select('asset_tag', 'asset_type', 'model', 'emp_id', 'emp_name', 'designation_id', 'issue_date', 'company_id')
            ->where('id', $issue_id)   // must be issue id
            ->first();
        return response()->json(['data' => $return_product]);
    }

    function history_export(Request $request)
    {
        if ($request->type == "xlsx") {
            $extension = "xlsx";
            $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        } elseif ($request->type == "csv") {
            $extension = "csv";
            $exportFormat = \Maatwebsite\Excel\Excel::CSV;
        } elseif ($request->type == "xls") {
            $extension = "xls";
            $exportFormat = \Maatwebsite\Excel\Excel::XLS;
        } else {
            $extension = "xlsx";
            $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        }

        $Filename = "history-data.$extension";
        return Excel::download(new HistoryExport($request->input("search")), $Filename, $exportFormat);
    }

    function history_generatePDF(Request $request)
    {
        $search = $request->search;
        $issue_info = Issue::where('asset_tag', 'LIKE', "%$search%")->orwhere('asset_type', 'LIKE', "%$search%")->orwhere('emp_id', 'LIKE', "%$search%")->orwhere('emp_name', 'LIKE', "%$search%")->orwhere('emp_name', 'LIKE', "%$search%")->orwhere('others', 'LIKE', "%$search%")->get();
        // Fetch all issues
        $employee_info = Issue::where('asset_tag', 'LIKE', "%$search%")->orwhere('asset_type', 'LIKE', "%$search%")->orwhere('emp_id', 'LIKE', "%$search%")->orwhere('emp_name', 'LIKE', "%$search%")->orwhere('emp_name', 'LIKE', "%$search%")->orwhere('others', 'LIKE', "%$search%")->get();
        // Fetch store information based on each issue
        $store_info = [];
        foreach ($issue_info as $issue) {
            $store = DB::table('stores')
                ->select('asset_tag', 'asset_type', 'model', 'brand', 'description', 'asset_sl_no', 'qty', 'units', 'picture')
                ->where('asset_tag', $issue->asset_tag) // Make sure 'asset_tag' exists in 'issues' table
                ->first();

            $store_info[] = $store; // Store each record
        }

        $data = [
            'title' => 'BETTEX HK Ltd',
            'date' => date('Y-m-d'),
            'content' => 'Acknowledgement Form.',
            'issue_info' => $issue_info,
            'store_info' => $store_info,
            'employee_info' => $employee_info,
        ];

        $pdf = Pdf::loadView('admin.store.pdf_history', $data)->setPaper('a4', 'portrait');
        return $pdf->download('history.pdf'); // Display PDF in browser
    }
    //autofill end

    //store search start...

    function store_search(Request $request)
    {
        $data = $request->input('search');
        $all_product_types = DB::table('stores')->where('asset_tag', 'LIKE', '%' . $data . '%')
            //->orwhere('asset_type', 'LIKE', '%' . $data . '%')
            ->get();

        return view('admin.store.store_list',  compact('all_product_types'));
    }
    //store search end.


    public function history(Request $request, $asset_tag = null)
    {
        $companies = [];
        $role = auth()->user()->roles[0];
        $role->hasPermissionTo('view BHML INDUSTRIES LTD.') ? array_push($companies, 'BHML INDUSTRIES LTD') : '';
        $role->hasPermissionTo('view BETTEX') ? array_push($companies, 'BETTEX HK LTD') : '';
        $role->hasPermissionTo('view BETTEX PREMIUM') ? array_push($companies, 'BETTEX PREMIUM') : '';
        $role->hasPermissionTo('view BETTEX BRIDGE') ? array_push($companies, 'BETTEX INDIA') : '';

        $search  = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        $query = Issue::whereIn('others', $companies);

        $store = null; // single store
        $maintenances = collect(); // default empty collection

        if ($asset_tag) {
            $query->where('asset_tag', $asset_tag);

            $issue = Issue::where('asset_tag', $asset_tag)->first();
            $stores = Store::where('asset_tag', $asset_tag)->first();

            if ($issue && isset($issue->store_id)) {
                $store = Store::with([
                    'rel_to_ProductType',
                    'rel_to_brand',
                    'rel_to_SizeMaseurment',
                    'rel_to_Supplier',
                    'rel_to_Status',
                    'rel_to_Company',
                    'rel_to_Department',
                    'rel_to_Designation'
                ])->find($issue->store_id);

                $maintenances = Maintenance::where('asset_tag', $store->asset_tag)->get();
            }
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('asset_tag', 'LIKE', "%$search%")
                    ->orWhere('asset_type', 'LIKE', "%$search%")
                    ->orWhere('emp_id', 'LIKE', "%$search%")
                    ->orWhere('emp_name', 'LIKE', "%$search%")
                    ->orWhere('others', 'LIKE', "%$search%");
            });
        }

        $issue_info = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int)$perPage)->appends($request->all());

        return view('admin.store.history', [
            'issue_info'   => $issue_info,
            'search'       => $search,
            'perPage'      => $perPage,
            'asset_tag'    => $asset_tag,
            'store'        => $store,
            'stores'        => $stores,
            'maintenances' => $maintenances,
        ]);
    }

    //Stock Summary
    function stock_summary(Request $request)
    {
        $assetName = $request->input('asset_name');

        $companies = [];
        $role = auth()->user()->roles[0];
        $role->hasPermissionTo('view BHML INDUSTRIES LTD.') ? array_push($companies, 1) : '';
        $role->hasPermissionTo('view BETTEX') ? array_push($companies, 2) : '';
        $role->hasPermissionTo('view BETTEX PREMIUM') ? array_push($companies, 3) : '';
        $role->hasPermissionTo('view BETTEX BRIDGE') ? array_push($companies, 4) : '';

        $stock_summary = Store::select('asset_type', 'company_id', DB::raw('COUNT(*) as total'))
            ->whereIn('company_id', $companies)
            ->groupBy('asset_type', 'company_id')
            ->with('rel_to_ProductType', 'rel_to_Company')
            ->get();

        // Get product summaries from stored procedures for each company
        $product_summary_bhml = [];
        $product_summary_bt = [];
        $product_summary_bp = [];
        $product_summary_bt_ind = [];

        if (in_array(1, $companies)) {
            $product_summary_bhml = DB::select("CALL sp_product_summary_bhml()");
            foreach ($product_summary_bhml as $product_summary) {
                $product_type = ProductType::find($product_summary->asset_type);
                $units = SizeMaseurment::find($product_summary->units_id);
                $product_summary->asset_type = $product_type;
                $product_summary->units = $units;
            }
            if ($assetName) {
                $product_summary_bhml = array_values(array_filter($product_summary_bhml, function ($product_summary) use ($assetName) {
                    return stripos($product_summary->asset_type->product ?? '', $assetName) !== false;
                }));
            }
        }

        if (in_array(2, $companies)) {
            $product_summary_bt = DB::select("CALL sp_product_summary_bt()");
            foreach ($product_summary_bt as $product_summary) {
                $product_type = ProductType::find($product_summary->asset_type);
                $units = SizeMaseurment::find($product_summary->units_id);
                $product_summary->asset_type = $product_type;
                $product_summary->units = $units;
            }
            if ($assetName) {
                $product_summary_bt = array_values(array_filter($product_summary_bt, function ($product_summary) use ($assetName) {
                    return stripos($product_summary->asset_type->product ?? '', $assetName) !== false;
                }));
            }
        }

        if (in_array(3, $companies)) {
            $product_summary_bp = DB::select("CALL sp_product_summary_bp()");
            foreach ($product_summary_bp as $product_summary) {
                $product_type = ProductType::find($product_summary->asset_type);
                $units = SizeMaseurment::find($product_summary->units_id);
                $product_summary->asset_type = $product_type;
                $product_summary->units = $units;
            }
            if ($assetName) {
                $product_summary_bp = array_values(array_filter($product_summary_bp, function ($product_summary) use ($assetName) {
                    return stripos($product_summary->asset_type->product ?? '', $assetName) !== false;
                }));
            }
        }

        if (in_array(4, $companies)) {
            $product_summary_bt_ind = DB::select("CALL sp_product_summary_bt_ind()");
            foreach ($product_summary_bt_ind as $product_summary) {
                $product_type = ProductType::find($product_summary->asset_type);
                $units = SizeMaseurment::find($product_summary->units_id);
                $product_summary->asset_type = $product_type;
                $product_summary->units = $units;
            }
            if ($assetName) {
                $product_summary_bt_ind = array_values(array_filter($product_summary_bt_ind, function ($product_summary) use ($assetName) {
                    return stripos($product_summary->asset_type->product ?? '', $assetName) !== false;
                }));
            }
        }

        return view('admin.store.stock_summary.index', [
            'stock_summary' => $stock_summary,
            'product_summary_bhml' => $product_summary_bhml,
            'product_summary_bt' => $product_summary_bt,
            'product_summary_bp' => $product_summary_bp,
            'product_summary_bt_ind' => $product_summary_bt_ind,
        ]);
    }

    // Export Stock Summary to Excel
    public function stock_summary_export_excel(Request $request)
    {
        $company = $request->input('company');
        $assetName = $request->input('asset_name');
        
        $companies = [];
        $role = auth()->user()->roles[0];
        $role->hasPermissionTo('view BHML INDUSTRIES LTD.') ? array_push($companies, 1) : '';
        $role->hasPermissionTo('view BETTEX') ? array_push($companies, 2) : '';
        $role->hasPermissionTo('view BETTEX PREMIUM') ? array_push($companies, 3) : '';
        $role->hasPermissionTo('view BETTEX BRIDGE') ? array_push($companies, 4) : '';

        $data = [];

        if (!$company || $company == 1) {
            if (in_array(1, $companies)) {
                $product_summary_bhml = DB::select("CALL sp_product_summary_bhml()");
                foreach ($product_summary_bhml as $product) {
                    $product_type = ProductType::find($product->asset_type);
                    $units = SizeMaseurment::find($product->units_id);
                    $product->asset_type = $product_type;
                    $product->units = $units;
                }
                if ($assetName) {
                    $product_summary_bhml = array_values(array_filter($product_summary_bhml, function ($product) use ($assetName) {
                        return stripos($product->asset_type->product ?? '', $assetName) !== false;
                    }));
                }
                $data['BHML INDUSTRIES LTD.'] = $product_summary_bhml;
            }
        }

        if (!$company || $company == 2) {
            if (in_array(2, $companies)) {
                $product_summary_bt = DB::select("CALL sp_product_summary_bt()");
                foreach ($product_summary_bt as $product) {
                    $product_type = ProductType::find($product->asset_type);
                    $units = SizeMaseurment::find($product->units_id);
                    $product->asset_type = $product_type;
                    $product->units = $units;
                }
                if ($assetName) {
                    $product_summary_bt = array_values(array_filter($product_summary_bt, function ($product) use ($assetName) {
                        return stripos($product->asset_type->product ?? '', $assetName) !== false;
                    }));
                }
                $data['BETTEX'] = $product_summary_bt;
            }
        }

        if (!$company || $company == 3) {
            if (in_array(3, $companies)) {
                $product_summary_bp = DB::select("CALL sp_product_summary_bp()");
                foreach ($product_summary_bp as $product) {
                    $product_type = ProductType::find($product->asset_type);
                    $units = SizeMaseurment::find($product->units_id);
                    $product->asset_type = $product_type;
                    $product->units = $units;
                }
                if ($assetName) {
                    $product_summary_bp = array_values(array_filter($product_summary_bp, function ($product) use ($assetName) {
                        return stripos($product->asset_type->product ?? '', $assetName) !== false;
                    }));
                }
                $data['BETTEX PREMIUM'] = $product_summary_bp;
            }
        }

        if (!$company || $company == 4) {
            if (in_array(4, $companies)) {
                $product_summary_bt_ind = DB::select("CALL sp_product_summary_bt_ind()");
                foreach ($product_summary_bt_ind as $product) {
                    $product_type = ProductType::find($product->asset_type);
                    $units = SizeMaseurment::find($product->units_id);
                    $product->asset_type = $product_type;
                    $product->units = $units;
                }
                if ($assetName) {
                    $product_summary_bt_ind = array_values(array_filter($product_summary_bt_ind, function ($product) use ($assetName) {
                        return stripos($product->asset_type->product ?? '', $assetName) !== false;
                    }));
                }
                $data['BETTEX BRIDGE'] = $product_summary_bt_ind;
            }
        }

        return Excel::download(new StockSummaryExport($data), 'stock-summary-' . date('Y-m-d-H-i-s') . '.xlsx');
    }

    // Export Stock Summary to PDF
    public function stock_summary_export_pdf(Request $request)
    {
        $company = $request->input('company');
        $assetName = $request->input('asset_name');
        
        $companies = [];
        $role = auth()->user()->roles[0];
        $role->hasPermissionTo('view BHML INDUSTRIES LTD.') ? array_push($companies, 1) : '';
        $role->hasPermissionTo('view BETTEX') ? array_push($companies, 2) : '';
        $role->hasPermissionTo('view BETTEX PREMIUM') ? array_push($companies, 3) : '';
        $role->hasPermissionTo('view BETTEX BRIDGE') ? array_push($companies, 4) : '';

        $data = [];

        if (!$company || $company == 1) {
            if (in_array(1, $companies)) {
                $product_summary_bhml = DB::select("CALL sp_product_summary_bhml()");
                foreach ($product_summary_bhml as $product) {
                    $product_type = ProductType::find($product->asset_type);
                    $units = SizeMaseurment::find($product->units_id);
                    $product->asset_type = $product_type;
                    $product->units = $units;
                }
                if ($assetName) {
                    $product_summary_bhml = array_values(array_filter($product_summary_bhml, function ($product) use ($assetName) {
                        return stripos($product->asset_type->product ?? '', $assetName) !== false;
                    }));
                }
                $data['BHML INDUSTRIES LTD.'] = $product_summary_bhml;
            }
        }

        if (!$company || $company == 2) {
            if (in_array(2, $companies)) {
                $product_summary_bt = DB::select("CALL sp_product_summary_bt()");
                foreach ($product_summary_bt as $product) {
                    $product_type = ProductType::find($product->asset_type);
                    $units = SizeMaseurment::find($product->units_id);
                    $product->asset_type = $product_type;
                    $product->units = $units;
                }
                if ($assetName) {
                    $product_summary_bt = array_values(array_filter($product_summary_bt, function ($product) use ($assetName) {
                        return stripos($product->asset_type->product ?? '', $assetName) !== false;
                    }));
                }
                $data['BETTEX'] = $product_summary_bt;
            }
        }

        if (!$company || $company == 3) {
            if (in_array(3, $companies)) {
                $product_summary_bp = DB::select("CALL sp_product_summary_bp()");
                foreach ($product_summary_bp as $product) {
                    $product_type = ProductType::find($product->asset_type);
                    $units = SizeMaseurment::find($product->units_id);
                    $product->asset_type = $product_type;
                    $product->units = $units;
                }
                if ($assetName) {
                    $product_summary_bp = array_values(array_filter($product_summary_bp, function ($product) use ($assetName) {
                        return stripos($product->asset_type->product ?? '', $assetName) !== false;
                    }));
                }
                $data['BETTEX PREMIUM'] = $product_summary_bp;
            }
        }

        if (!$company || $company == 4) {
            if (in_array(4, $companies)) {
                $product_summary_bt_ind = DB::select("CALL sp_product_summary_bt_ind()");
                foreach ($product_summary_bt_ind as $product) {
                    $product_type = ProductType::find($product->asset_type);
                    $units = SizeMaseurment::find($product->units_id);
                    $product->asset_type = $product_type;
                    $product->units = $units;
                }
                if ($assetName) {
                    $product_summary_bt_ind = array_values(array_filter($product_summary_bt_ind, function ($product) use ($assetName) {
                        return stripos($product->asset_type->product ?? '', $assetName) !== false;
                    }));
                }
                $data['BETTEX BRIDGE'] = $product_summary_bt_ind;
            }
        }

        $pdf = PDF::loadView('admin.store.stock_summary.export_pdf', ['data' => $data])
            ->setPaper('a4', 'portrait');

        return $pdf->download('stock-summary-' . date('Y-m-d-H-i-s') . '.pdf');
    }


    //Transfer Start

    public function store_transfer(Request $request)
    {
        // Get the selected company or fallback to logged-in user's company
        $company_id = $request->input('company_id', auth()->user()->company_id ?? null);

        // Get all companies for dropdown
        $companies = Company::all();

        // Filter issued products only for the current user's or selected company
        $issued_products = Store::with('rel_to_ProductType')
            ->when($company_id, fn($query) => $query->where('company_id', $company_id))
            ->get();

        return view('admin.store.transfer', [
            'issued_products'   => $issued_products,
            'companies'         => $companies,
            'selected_company'  => $company_id,
        ]);
    }



    function transfer_store(Request $request)
    {
        $store = Store::where('asset_tag', $request->asset_tag)->first();

        $toCompanyName = Company::where('id', $request->company)->value('id');

        TransferRequest::create([
            'asset_tag' => $request->asset_tag,
            'asset_type' => $request->asset_type,
            'model' => $request->model,
            'from_company' => $request->oldcompany,
            'to_company' => $toCompanyName,
            'description' => $request->description,
            'note' => $request->note,
            'transfer_date' => $request->transfer_date,
            'status' => 'pending',
            'item_status' => $request->item_status,
            'requested_by' => auth()->user()->name ?? 'System',
            'store_id' => $store ? $store->id : null,
        ]);

        return redirect()->route('transfer_list')
            ->with('success', 'Transfer request submitted successfully!');
    }

    function transfer_list(Request $request)
    {
        $search  = $request->input('search', '');
        $role = auth()->user()->roles[0];

        // Define companies allowed by role permissions
        $companies = [];
        if ($role->hasPermissionTo('view BHML INDUSTRIES LTD.')) $companies[] = 1;
        if ($role->hasPermissionTo('view BETTEX')) $companies[] = 2;
        if ($role->hasPermissionTo('view BETTEX PREMIUM')) $companies[] = 3;
        if ($role->hasPermissionTo('view BETTEX BRIDGE')) $companies[] = 4;

        // Filter inputs
        $assetType   = $request->input('asset_type', '');
        $company     = $request->input('company', '');
        $fromCompany = $request->input('from_company', ''); // ✅ new filter
        $dateFrom    = $request->input('date_from', '');
        $dateTo      = $request->input('date_to', '');
        $status      = $request->input('status', '');
        $perPage     = $request->input('per_page', 10);

        // Base query
        $query = Transfer::where(function ($q) use ($companies) {
            $q->whereIn('company', $companies)
                ->orWhereIn('oldcompany', $companies);
        });

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('asset_tag', 'LIKE', "%$search%")
                    ->orWhere('asset_type', 'LIKE', "%$search%")
                    ->orWhere('model', 'LIKE', "%$search%")
                    ->orWhere('company', 'LIKE', "%$search%")
                    ->orWhere('oldcompany', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%")
                    ->orWhere('note', 'LIKE', "%$search%");
            });
        }

        // Asset Type filter
        if ($assetType) {
            $query->where('asset_type', 'LIKE', "%$assetType%");
        }

        // Company (To Company) filter
        if ($company) {
            $query->where('company', $company);
        }

        // ✅ From Company filter (oldcompany)
        if ($fromCompany) {
            $query->where('oldcompany', $fromCompany);
        }

        // Date range filter
        if ($dateFrom) {
            $query->whereDate('transfer_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('transfer_date', '<=', $dateTo);
        }

        // Status filter
        if ($status === 'returned') {
            $query->whereNotNull('return_date');
        } elseif ($status === 'active') {
            $query->whereNull('return_date');
        }

        // Pagination
        if ($perPage === 'all') {
            $transer_data = $query->orderBy('transfer_date', 'desc')->get();
        } else {
            $transer_data = $query->orderBy('transfer_date', 'desc')
                ->paginate((int)$perPage)
                ->appends($request->except('page'));
        }

        // Dropdown data
        $assetTypes   = Transfer::distinct()->pluck('asset_type')->filter()->sort()->values();
        $companiesTo  = Transfer::distinct()->pluck('company')->filter()->sort()->values();
        $companiesFrom = Company::whereIn(
            'id',
            Transfer::distinct()->pluck('oldcompany')->filter()
        )->orderBy('company', 'asc')->get();


        // Statistics
        $stats = [
            'total' => Transfer::count(),
            'active' => Transfer::whereNull('return_date')->count(),
            'returned' => Transfer::whereNotNull('return_date')->count(),
            'this_month' => Transfer::whereMonth('transfer_date', now()->month)
                ->whereYear('transfer_date', now()->year)
                ->count(),
        ];

        // Return to view
        return view('admin.store.transfer.transfer_list', [
            'transer_data'   => $transer_data,
            'search'         => $search,
            'assetType'      => $assetType,
            'company'        => $company,
            'fromCompany'    => $fromCompany, // ✅ keep selected
            'dateFrom'       => $dateFrom,
            'dateTo'         => $dateTo,
            'status'         => $status,
            'perPage'        => $perPage,
            'assetTypes'     => $assetTypes,
            'companies'      => $companiesTo,
            'fromCompanies'  => $companiesFrom, // ✅ pass list for dropdown
            'stats'          => $stats,
        ]);
    }


    function transfer_export(Request $request)
    {
        if ($request->type == "xlsx") {
            $extension = "xlsx";
            $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        } elseif ($request->type == "csv") {
            $extension = "csv";
            $exportFormat = \Maatwebsite\Excel\Excel::CSV;
        } elseif ($request->type == "xls") {
            $extension = "xls";
            $exportFormat = \Maatwebsite\Excel\Excel::XLS;
        } else {
            $extension = "xlsx";
            $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        }


        $Filename = "transer-data.$extension";
        return Excel::download(new TransferExport($request->input("search")), $Filename, $exportFormat);
    }

    function transfer_edit($id)
    {
        $transfer_data = Transfer::find($id);
        $companys = Company::all();
        return view('admin.store.transfer.transfer_edit', [
            'transfer_data' => $transfer_data,
            'companys' => $companys,
        ]);
    }

    function transfer_update(Request $request)
    {
        Transfer::find($request->id)->update([
            'company' => $request->companys,
            'note' => $request->note,
            'transfer_date' => $request->transfer_date,
        ]);

        return redirect()->route('transfer_list');
    }

    function transfer_return()
    {
        $transfer_return = DB::select("CALL sp_transfer_return()");
        return view('admin.store.transfer.transfer_return', [
            'transfer_return' => $transfer_return,
        ]);
    }

    function transfer_return_search_id($id)
    {
        $transfer_data = DB::select("CALL sp_transfer_return()", [$id]);

        $transfer_return_data = [
            'asset_tag' => $transfer_data[0]->asset_tag,
            'asset_type' => $transfer_data[0]->asset_type,
            'oldcompany' => $transfer_data[0]->oldcompany,
            'company' => $transfer_data[0]->company,
            'transfer_date' => $transfer_data[0]->transfer_date,
        ];

        return response()->json(['data' => $transfer_return_data]);
    }

    function transfer_return_update(Request $request)
    {
        DB::table("transfers")
            ->where(['asset_tag' => $request->asset_tag])
            ->update(['return_date' => $request->return_date]);

        return back();
    }

    //transfer end


    //maintenance start...

    public function maintenance_form($stores_id)
    {
        // Load the store with its relationships
        $stores = Store::with([
            'rel_to_ProductType',
            'rel_to_brand',
            'rel_to_SizeMaseurment',
            'rel_to_Supplier',
            'rel_to_Status',
            'rel_to_Company',
            'rel_to_Department',
            'rel_to_Designation'
        ])->findOrFail($stores_id);

        // Find the latest maintenance record
        $maintenance_data = Maintenance::where('asset_tag', $stores->asset_tag)
            ->orderByDesc('id')
            ->first();

        // If no maintenance record exists, use store info
        if (!$maintenance_data) {
            $maintenance_data = new Maintenance([
                'asset_tag'      => $stores->asset_tag,
                'asset_type'     => $stores->asset_type,
                'model'          => $stores->model,
                'purchase_date'  => $stores->purchase_date,
                'description'    => $stores->description,
            ]);
        }

        // ✅ Check maintenance button visibility
        // Show button only if store checkstatus is 'INSTOCK'
        $canShowMaintenanceButton = strtoupper(trim($stores->checkstatus)) === 'INSTOCK';

        return view('admin.store.maintenance.maintenance_form', compact(
            'maintenance_data',
            'stores',
            'canShowMaintenanceButton'
        ));
    }


    function maintenance_store(Request $request)
    {
        Maintenance::insertGetId([
            'asset_tag' => $request->asset_tag,
            'asset_type' => $request->asset_type,
            'model' => $request->model,
            'purchase_date' => $request->purchase_date,
            'description' => $request->description,
            'strat_date' => $request->strat_date,
            'end_date' => $request->end_date,
            'note' => $request->note,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'vendor' => $request->vendor,
            'others' => $request->others,
            'created_at' => Carbon::now(),

        ]);
        return back()->with('maintenance_success', 'Maintenance record added successfully!');
    }

    // maintenance view show all data start
    function maintenance_view(Request $request)
    {
        $role = auth()->user()->roles[0];

        $search = $request->input('search', '');
        $productSearch = $request->input('product_search');
        $companyFilter = $request->input('company_filter');
        $perPage = $request->input('per_page', session('asset_per_page', 10));
        session(['asset_per_page' => $perPage]);

        $companies = [];
        if ($role->hasPermissionTo('view BHML INDUSTRIES LTD.')) $companies[] = 1;
        if ($role->hasPermissionTo('view BETTEX')) $companies[] = 2;
        if ($role->hasPermissionTo('view BETTEX PREMIUM')) $companies[] = 3;
        if ($role->hasPermissionTo('view BETTEX BRIDGE')) $companies[] = 4;

        $query = Store::query()
            ->join('brands', 'brands.id', '=', 'stores.brand_id')
            ->join('product_types', 'product_types.id', '=', 'stores.asset_type')
            ->whereIn('stores.company_id', $companies)
            ->where('stores.checkstatus', 'Maintenance'); // Only deleted

        if ($companyFilter) {
            $query->where('stores.company_id', $companyFilter);
        }

        if ($search || $productSearch) {
            $query->where(function ($q) use ($search, $productSearch) {
                if ($productSearch) {
                    $q->where('product_types.id', '=', $productSearch);
                }
                if ($search) {
                    $q->where(function ($sq) use ($search) {
                        $sq->where('stores.asset_tag', 'LIKE', "%{$search}%")
                            ->orWhere('brands.brand_name', 'LIKE', "%{$search}%")
                            ->orWhere('stores.vendor', 'LIKE', "%{$search}%")
                            ->orWhere('stores.asset_sl_no', 'LIKE', "%{$search}%")
                            ->orWhere('stores.checkstatus', 'LIKE', "%{$search}%");
                    });
                }
            });
        }

        $query->orderBy('stores.id', 'desc');

        $stores = $perPage === 'all'
            ? $query->select('stores.*')->get()
            : $query->select('stores.*')->paginate((int)$perPage)->appends($request->except('page'));

        return view('admin.store.store_list', [
            'stores' => $stores,
            'search' => $search,
            'perPage' => $perPage,
            'productSearch' => $productSearch,
            'companyFilter' => $companyFilter,
            'showDeleted' => true, // ✅ Flag to detect deleted page
            'all_product_types' => ProductType::all(),
            'all_brands' => Brand::all(),
            'all_company' => Company::all(),
            'employee' => Employee::where('status', 'Active')->get(),
            'all_issue' => Issue::all(),
        ]);
    }
    // maintenance view show all data end


    // maintenance list show only stored_id data

    public function maintenance_list(Request $request, $store_id = null)
    {
        $search = $request->input('search', "");

        // Fetch all stores for the dropdown/filter
        $stores_list = Store::all();
        $stores = Store::with(['rel_to_ProductType', 'rel_to_brand', 'rel_to_SizeMaseurment', 'rel_to_Supplier', 'rel_to_Status', 'rel_to_Company', 'rel_to_Department', 'rel_to_Designation'])->findOrFail($store_id);

        $query = Maintenance::query();
        $store = null;

        if ($store_id) {
            $store = Store::findOrFail($store_id);
            if ($store->asset_tag) {
                $query->where('asset_tag', $store->asset_tag);
            }
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('asset_tag', 'LIKE', "%{$search}%")
                    ->orWhere('asset_type', 'LIKE', "%{$search}%")
                    ->orWhere('vendor', 'LIKE', "%{$search}%")
                    ->orWhere('others', 'LIKE', "%{$search}%");
            });
        }

        // Get all results without pagination
        $maintenance_data = $query->orderBy('id', 'desc')->get();

        // Pass $stores_list to the view
        return view(
            'admin.store.maintenance.maintenance_list',
            compact('maintenance_data', 'search', 'store', 'stores_list', 'stores')
        );
    }




    function maintenance_search_id($store_id)
    {
        $issued_products = Maintenance::find($store_id);

        $issued_products_data = [
            'asset_tag' => $issued_products->asset_tag,
            'asset_type' => $issued_products->asset_type,
            'model' => $issued_products->model,
            'purchase_date' => $issued_products->purchase_date,
            'asset_sl_no' => $issued_products->asset_sl_no,
        ];
        $issued_products = Maintenance::find($store_id);
        return response()->json(['data' => $issued_products_data]);
    }

    function maintenance_return()
    {
        $issued_products = DB::select("CALL sp_maintenence_issue_list()");
        $all_supplier = Supplier::all();
        return view('admin.store.maintenance.maintenance_return', [
            'issued_products' => $issued_products,
            'all_supplier' => $all_supplier,
        ]);
    }



    function ma_return_update(Request $request)
    {
        DB::table("maintenances")
            ->where('asset_tag', $request->asset_tag)
            ->update([
                'end_date' => $request->end_date,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'vendor' => $request->vendor,
            ]);

        return redirect()->route('maintenance_list');
    }


    function maintenance_export(Request $request)
    {
        //dd($request->all());
        if ($request->type == "xlsx") {
            $extension = "xlsx";
            $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        } elseif ($request->type == "csv") {
            $extension = "csv";
            $exportFormat = \Maatwebsite\Excel\Excel::CSV;
        } elseif ($request->type == "xls") {
            $extension = "xls";
            $exportFormat = \Maatwebsite\Excel\Excel::XLS;
        } else {
            $extension = "xlsx";
            $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        }

        $Filename = "maintenance-data.$extension";
        return Excel::download(new MaintenanceExport($request->input("search")), $Filename, $exportFormat);
    }

    // Show the edit form
    public function maintenance_edit($id)
    {
        $maintenance_data = Maintenance::findOrFail($id);

        // Get the store for this asset_tag
        $store = Store::where('asset_tag', $maintenance_data->asset_tag)->first();

        return view('admin.store.maintenance.maintenance_edit', [
            'maintenance_data' => $maintenance_data,
            'store' => $store,
        ]);
    }

    // Update the maintenance record
    public function maintenance_update(Request $request, $id)
    {
        $request->validate([
            'description' => 'nullable|string|max:255',
            'strat_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:strat_date',
            'note' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:10',
            'vendor' => 'nullable|string|max:255',
            'others' => 'nullable|string|max:255',
        ]);

        $maintenance = Maintenance::findOrFail($id);

        $maintenance->update([
            'description' => $request->description,
            'strat_date' => $request->strat_date,
            'end_date' => $request->end_date,
            'note' => $request->note,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'vendor' => $request->vendor,
            'others' => $request->others,
        ]);

        return redirect()->route('maintenance_list')
            ->with('success', 'Maintenance record updated successfully!');
    }



    //wastproduct start..
        public function wastproduct()
        {
            $role = auth()->user()->roles[0];

            // Get allowed companies based on role permissions
            $allowedCompanyIds = [];
            if ($role->hasPermissionTo('view BHML INDUSTRIES LTD.')) $allowedCompanyIds[] = 1;
            if ($role->hasPermissionTo('view BETTEX')) $allowedCompanyIds[] = 2;
            if ($role->hasPermissionTo('view BETTEX PREMIUM')) $allowedCompanyIds[] = 3;
            if ($role->hasPermissionTo('view BETTEX BRIDGE')) $allowedCompanyIds[] = 4;

            $issued_products = Store::where('checkstatus', 'INSTOCK')
                ->whereIn('company_id', $allowedCompanyIds)
                ->get();

            $companies = Company::whereIn('id', $allowedCompanyIds)->pluck('company', 'id');

            return view('admin.store.wastproduct.wastproduct', compact(
                'issued_products',
                'companies'
            ));
        }

    function wastproduct_store(Request $request)
    {
        WastProduct::insertGetId([
            'asset_tag' => $request->asset_tag,
            'asset_type' => $request->asset_type,
            'model' => $request->model,
            'purchase_date' => $request->purchase_date,
            'description' => $request->description,
            'asset_sl_no' => $request->asset_sl_no,
            'date' => $request->date,
            'note' => $request->note,
            'others' => $request->others,
            'others1' => $request->others1,
            'created_at' => Carbon::now(),
        ]);

        if (!empty($request->asset_tag)) {
            Store::where('asset_tag', $request->asset_tag)
                ->whereIn('checkstatus', ['INSTOCK', 'MAINTENANCE', 'Wast Products'])
                ->update(['checkstatus' => 'Wast Products']);
        }

        return redirect()->back()->with('success_wast', 'Waste product saved successfully.');
    }

 public function wastproduct_list(Request $request)
{
    $role = auth()->user()->roles[0];

    $search = $request->input('search', '');
    $assetType = $request->input('asset_type', '');
    $company = $request->input('company', '');

    // Per page handling (supports "all")
    $perPageInput = $request->input('per_page', 15);

    // Get allowed companies based on role permissions
    $companies = [];
    if ($role->hasPermissionTo('view BHML INDUSTRIES LTD.')) $companies[] = 'BHML INDUSTRIES LTD';
    if ($role->hasPermissionTo('view BETTEX')) $companies[] = 'BETTEX HK LTD';
    if ($role->hasPermissionTo('view BETTEX PREMIUM')) $companies[] = 'BETTEX PREMIUM';
    if ($role->hasPermissionTo('view BETTEX BRIDGE')) $companies[] = 'BETTEX INDIA';

    $query = WastProduct::query();

    // Filter by allowed companies (role-based)
    $query->whereIn('others', $companies);

    // Search
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('asset_tag', 'LIKE', "%{$search}%")
              ->orWhere('asset_type', 'LIKE', "%{$search}%")
              ->orWhere('model', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhere('asset_sl_no', 'LIKE', "%{$search}%")
              ->orWhere('others', 'LIKE', "%{$search}%")
              ->orWhere('note', 'LIKE', "%{$search}%");
        });
    }

    // Filters
    if (!empty($assetType)) {
        $query->where('asset_type', $assetType);
    }

    if (!empty($company)) {
        $query->where('others', $company);
    }

    // ✅ Handle "All" safely while keeping paginator
    if ($perPageInput === 'all') {
        $perPage = $query->count(); // show all records
    } else {
        $perPage = max((int) $perPageInput, 1);
    }

    // Pagination (always paginator)
    $wastproduct = $query
        ->orderBy('date', 'desc')
        ->paginate($perPage)
        ->appends($request->query()); // keep filters on pagination links

    // Dropdown data (only from allowed companies)
    $assetTypes = WastProduct::whereIn('others', $companies)
        ->whereNotNull('asset_type')
        ->distinct()
        ->orderBy('asset_type')
        ->pluck('asset_type');

    $companya = WastProduct::whereIn('others', $companies)
        ->whereNotNull('others')
        ->distinct()
        ->orderBy('others')
        ->pluck('others');

    // Statistics (only from allowed companies)
    $statistics = [
        'total' => WastProduct::whereIn('others', $companies)->count(),
        'this_month' => WastProduct::whereIn('others', $companies)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count(),
        'this_year' => WastProduct::whereIn('others', $companies)
            ->whereYear('date', now()->year)->count(),
        'total_value' => 0,
    ];

    return view('admin.store.wastproduct.wastproduct_list', compact(
        'wastproduct',
        'search',
        'assetTypes',
        'companya',
        'statistics'
    ));
}


    function wastproduct_edit($id)
    {
        $wastproduct = WastProduct::find($id);
        return view('admin.store.wastproduct.wastproduct_edit', [
            'wastproduct' => $wastproduct,
        ]);
    }
    //wastproduct end..
    function wastproduct_update(Request $request)
    {

        WastProduct::find($request->id)->update([
            'description' => $request->description,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        return redirect()->route('wastproduct_list')->with('update_success', 'Wast Product updated successfully!');
    }

    function wastproduct_delete($id)
    {
        $wasteRecord = WastProduct::findOrFail($id);

        if (!empty($wasteRecord->asset_tag)) {
            Store::where('asset_tag', $wasteRecord->asset_tag)
                ->where('checkstatus', 'Wast Products')
                ->update(['checkstatus' => 'INSTOCK']);
        }

        $wasteRecord->delete();
        return back()->with('delete_success', 'Wast Product deleted successfully!');
    }

    // function wastproduct_export(Request $request){
    //     if ($request->type == "xlsx") {
    //         $extension = "xlsx";
    //         $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
    //     } elseif ($request->type == "csv") {
    //         $extension = "csv";
    //         $exportFormat = \Maatwebsite\Excel\Excel::CSV;
    //     } elseif ($request->type == "xls") {
    //         $extension = "xls";
    //         $exportFormat = \Maatwebsite\Excel\Excel::XLS;
    //     } else {
    //         $extension = "xlsx";
    //         $exportFormat = \Maatwebsite\Excel\Excel::XLSX;
    //     }


    //     $Filename = "wastproduct-data.$extension";
    //     return Excel::download(new WastProductExport, $Filename, $exportFormat);
    // }


    public function wastproduct_export(Request $request)
    { {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            return Excel::download(new WastProductExport($startDate, $endDate), 'filtered_data.xlsx');
        }
    }



    //store Info
    public function store_info($stores_id)
{
    // Get the store with all relations
    $stores = Store::with([
        'rel_to_ProductType', 
        'rel_to_brand', 
        'rel_to_SizeMaseurment', 
        'rel_to_Supplier', 
        'rel_to_Status', 
        'rel_to_Company', 
        'rel_to_Department', 
        'rel_to_Designation'
    ])->findOrFail($stores_id);

    // Get all issues for this asset
    $issues = Issue::where('asset_tag', $stores->asset_tag)->get();

    // Get maintenances for this asset
    $maintenances = Maintenance::where('asset_tag', $stores->asset_tag)->get();

    $showDeleted = false; // default

    // 🔹 Get employee info from issues table where return_date is null
    $employeeFromIssue = Issue::where('asset_tag', $stores->asset_tag)
                                ->whereNull('return_date')
                                ->first(); // get the first active issue

    // Optional: if no active issue, it will be null
    $employee = null;
    if ($employeeFromIssue) {
        $employee = [
            'emp_id' => $employeeFromIssue->emp_id,
            'emp_name' => $employeeFromIssue->emp_name,
        ];
    }

    // QR code placeholder (same as your code)
    $qrCode = $stores;

    return view('admin.store.store_info', compact(
        'stores',
        'issues',
        'maintenances',
        'showDeleted',
        'qrCode',
        'employee' // pass to view
    ));
}


    //store Clone - Show edit page with cloned data
    function store_clone($stores_id)
    {
        $originalAsset = Store::findOrFail($stores_id);

        // Get all the necessary data for the edit form
        $all_product_types = ProductType::all();
        $all_brands = Brand::all();
        $all_supplier = Supplier::all();
        $all_company = Company::all();
        $all_status = Status::all();
        $all_departments = Department::all();
        $all_SizeMaseurment = SizeMaseurment::all();

        return view('admin.store.store_edit', [
            'all_store' => $originalAsset,
            'all_product_types' => $all_product_types,
            'all_brands' => $all_brands,
            'all_supplier' => $all_supplier,
            'all_company' => $all_company,
            'all_status' => $all_status,
            'all_departments' => $all_departments,
            'all_SizeMaseurment' => $all_SizeMaseurment,
            'is_clone' => true // Flag to indicate this is a clone operation
        ]);
    }

    //store Clone Save - Actually create the new asset
    function store_clone_save(Request $request)
    {
        $newAssetId = Store::insertGetId([
            'asset_tag' => $request->asset_tag,
            'asset_type' => $request->asset_type,
            'model' => $request->model,
            'brand_id' => $request->brand_id,
            'description' => $request->description,
            'asset_sl_no' => $request->asset_sl_no,
            'qty' => $request->qty,
            'units_id' => $request->units_id,
            'warrenty' => $request->warrenty,
            'durablity' => $request->durablity,
            'cost' => $request->cost,
            'currency' => $request->currency,
            'vendor' => $request->vendor,
            'purchase_date' => $request->purchase_date,
            'challan_no' => $request->challan_no,
            'status_id' => $request->status_id,
            'location' => $request->location,
            'company_id' => $request->company_id,
            'others' => $request->others,
            'checkstatus' => $request->checkstatus,
            'others2' => $request->others2,
            'created_at' => Carbon::now(),
        ]);

        if ($request->file('picture')) {
            $imageName = $newAssetId . '.' . $request->picture->extension();
            $request->picture->move(public_path('uploads/store/'), $imageName);
            Store::find($newAssetId)->update(['picture' => $imageName]);
        }

        return redirect()->route('store')->with('success', 'Asset cloned and saved successfully!');
    }

    //store file upload
    public function store_file($stores_id)
    {
        $store = Store::findOrFail($stores_id);
        $file_info = stores_file::all();
        return view('admin.store.store_file', compact('store', 'file_info'));
    }

    public function store_file_save(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'file' => 'required|file|max:2048',
        ]);

        $store = Store::findOrFail($request->store_id);

        if ($request->hasFile('file')) {
            $fileName = $store->asset_tag . '_' . time() . '.' . $request->file('file')->extension();
            $request->file('file')->move(public_path('uploads/store_files/'), $fileName);

            stores_file::create([
                'store_id' => $store->id,
                'file' => $fileName,
                'note' => $request->input('note'),
                'created_by' => auth()->id(),
            ]);
        }

        return back()->with('save_success', 'File uploaded successfully!');
    }

    public function store_file_delete($file_id)
    {
        // Find file by its id (fail if not found)
        $file = stores_file::findOrFail($file_id);

        // Optionally delete the physical file from disk too
        $filePath = public_path('uploads/store_files/' . $file->file);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $file->delete();

        return back()->with('delete_success', 'File deleted successfully!');
    }

    // Transfer Request Management Methods

    public function transfer_requests(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $company = $request->input('company', '');
        $perPage = $request->input('per_page', 15);
        $query = TransferRequest::query();

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('asset_tag', 'like', "%{$search}%")
                    ->orWhere('asset_type', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('from_company', 'like', "%{$search}%")
                    ->orWhere('to_company', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status) {
            $query->where('status', $status);
        }

        // Company filter
        if ($company) {
            $query->where(function ($q) use ($company) {
                $q->where('from_company_id', $company)
                    ->orWhere('to_company_id', $company);
            });
        }

        $transferRequests = $query->orderBy('created_at', 'desc')->paginate($perPage);
        $companies = Company::all();

        return view('admin.store.transfer_requests', compact('transferRequests', 'companies'));
    }

    public function pending_transfer_requests(Request $request)
    {
        
        // Get current logged-in user's company ID
        $userCompany = auth()->user()->company_id; // Make sure your users table has this column

        // Base query for pending requests
        $query = TransferRequest::where('status', 'pending');

        // If user belongs to a specific company, show only related requests
        if ($userCompany) {
            $query->where(function ($q) use ($userCompany) {
                $q->where('to_company', $userCompany)
                    ->orWhere('from_company', $userCompany);
            });
        }

        $pendingRequests = $query->orderBy('created_at', 'desc')->get();

        return view('admin.store.pending_transfer_requests', compact('pendingRequests'));
    }



    public function approve_transfer_request(Request $request, $id)
    {
        $transferRequest = TransferRequest::findOrFail($id);

        $transferRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->user()->name ?? 'System',
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
            'item_status' => $request->item_status ?? $transferRequest->item_status,
        ]);

        // If it's a borrowed item, don't create a permanent transfer record
        if ($transferRequest->item_status !== 'borrowed') {
            // Create the actual transfer record for permanent transfers
            Transfer::create([
                'asset_tag' => $transferRequest->asset_tag,
                'asset_type' => $transferRequest->asset_type,
                'asset_sl_no' => $transferRequest->asset_sl_no,
                'model' => $transferRequest->model,
                'company' => $transferRequest->to_company,
                'description' => $transferRequest->description,
                'note' => $transferRequest->note,
                'transfer_date' => $transferRequest->transfer_date,
                'oldcompany' => $transferRequest->from_company,
            ]);

            // Update the store record company for permanent transfers
            if ($transferRequest->store_id) {
                Store::where('id', $transferRequest->store_id)->update([
                    'others' => $transferRequest->to_company
                ]);
            }
        }

        return back()->with('transfer_success', 'Transfer request approved successfully!');
    }

    public function reject_transfer_request(Request $request, $id)
    {
        $transferRequest = TransferRequest::findOrFail($id);

        $transferRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->user()->name ?? 'System',
            'approved_at' => now(),
            'approval_notes' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Transfer request rejected.');
    }

    public function borrowed_items(Request $request)
    {
        $search = $request->input('search', '');
        $company = $request->input('company', '');
        $perPage = $request->input('per_page', 15);

        $query = TransferRequest::borrowedItems();

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('asset_tag', 'like', "%{$search}%")
                    ->orWhere('asset_type', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Company filter - show borrowed items for user's company
        $userCompany = auth()->user()->company ?? $company;
        if ($userCompany) {
            $query->where('to_company', $userCompany);
        }

        $borrowedItems = $query->orderBy('approved_at', 'desc')->paginate($perPage);
        $companies = Company::all();

        return view('admin.store.borrowed_items', compact('borrowedItems', 'companies'));
    }
}
