<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Models\SizeMaseurment;
use App\Models\Store;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // User role company permission check (same logic as instock_list)
        $role = auth()->user()->roles[0];

        $isAdminManager = in_array($role->name, ['Admin', 'Manager']);
        $hasGlobalAttire = false;
        $globalAttireCompanyId = Company::where('company', 'Global Attire')->value('id');

        if ($isAdminManager) {
            $companies = Company::pluck('id')->all();
        } else {
            $companies = [];
            $globalPermissions = [
                'view global attire',
                'view global attire ltd.',
                'view global attire ltd',
                'view global_attire',
            ];

            foreach ($globalPermissions as $permissionName) {
                if ($role->hasPermissionTo($permissionName)) {
                    $hasGlobalAttire = true;
                    $companies = $globalAttireCompanyId ? [$globalAttireCompanyId] : [];
                    break;
                }
            }

            if (empty($companies)) {
                $companyPermissions = [
                    'view BHML INDUSTRIES LTD.' => 1,
                    'view BETTEX' => 2,
                    'view BETTEX PREMIUM' => 3,
                    'view BETTEX BRIDGE' => 4,
                ];

                foreach ($companyPermissions as $permissionName => $companyId) {
                    if ($role->hasPermissionTo($permissionName)) {
                        $companies[] = $companyId;
                    }
                }
            }
        }

        if ($isAdminManager) {
            $query = Store::query();
        } else {
            $query = empty($companies)
                ? Store::whereRaw('1 = 0')
                : Store::whereIn('company_id', $companies);
        }

        if ($hasGlobalAttire) {
            $query->where('created_by', auth()->id());
        }

        if ($request->filled('asset_type')) {
            $query->where('asset_type', $request->asset_type);
        }

        $stores = $query->get();

        // counts for dashboard tiles (company filtered; Admin/Manager sees all)
        if ($isAdminManager) {
            $assetCount = Store::whereNotIn('checkstatus', ['DELETE', 'ARCHIVE'])->count();
            $laptopCount = Store::where('asset_type', 1)->whereNotIn('checkstatus', ['DELETE', 'ARCHIVE'])->count();
            $desktopCount = Store::where('asset_type', 2)->whereNotIn('checkstatus', ['DELETE', 'ARCHIVE'])->count();
            $printerCount = Store::where('asset_type', 4)->whereNotIn('checkstatus', ['DELETE', 'ARCHIVE'])->count();

            $employeeCount = DB::table('employees')->where('status', 'Active')->count();
            $userCount = DB::table('users')->count();
        } else {
            $assetQuery = Store::query()->whereNotIn('checkstatus', ['DELETE', 'ARCHIVE']);
            $laptopQuery = Store::query()->where('asset_type', 1)->whereNotIn('checkstatus', ['DELETE', 'ARCHIVE']);
            $desktopQuery = Store::query()->where('asset_type', 2)->whereNotIn('checkstatus', ['DELETE', 'ARCHIVE']);
            $printerQuery = Store::query()->where('asset_type', 4)->whereNotIn('checkstatus', ['DELETE', 'ARCHIVE']);

            if ($hasGlobalAttire) {
                $assetQuery->where('created_by', auth()->id());
                $laptopQuery->where('created_by', auth()->id());
                $desktopQuery->where('created_by', auth()->id());
                $printerQuery->where('created_by', auth()->id());
            } else {
                $assetQuery->whereIn('company_id', $companies);
                $laptopQuery->whereIn('company_id', $companies);
                $desktopQuery->whereIn('company_id', $companies);
                $printerQuery->whereIn('company_id', $companies);
            }

            $assetCount = empty($companies) ? 0 : $assetQuery->count();
            $laptopCount = empty($companies) ? 0 : $laptopQuery->count();
            $desktopCount = empty($companies) ? 0 : $desktopQuery->count();
            $printerCount = empty($companies) ? 0 : $printerQuery->count();

            $employeeCount = empty($companies)
                ? 0
                : DB::table('employees')->whereIn('company', $companies)->where('status', 'Active')->count();
            $userCount = DB::table('users')->count();
        }

        if ($isAdminManager) {
            $desktops = DB::table('stores')->where('asset_type', 2)->get();
            $laptops = DB::table('stores')->where('asset_type', 1)->get();
            $printers = DB::table('stores')->where('asset_type', 3)->get();
        } else {
            if (empty($companies)) {
                $desktops = [];
                $laptops = [];
                $printers = [];
            } else {
                $desktops = DB::table('stores')
                    ->where('asset_type', 2)
                    ->whereIn('company_id', $companies)
                    ->when($hasGlobalAttire, function ($query) {
                        return $query->where('created_by', auth()->id());
                    })
                    ->get();
                $laptops = DB::table('stores')
                    ->where('asset_type', 1)
                    ->whereIn('company_id', $companies)
                    ->when($hasGlobalAttire, function ($query) {
                        return $query->where('created_by', auth()->id());
                    })
                    ->get();
                $printers = DB::table('stores')
                    ->where('asset_type', 3)
                    ->whereIn('company_id', $companies)
                    ->when($hasGlobalAttire, function ($query) {
                        return $query->where('created_by', auth()->id());
                    })
                    ->get();
            }
        }
        $product_summary_global_attire = [];
        if ($hasGlobalAttire && $globalAttireCompanyId) {
            $product_summary_global_attire = DB::select(
                'SELECT asset_type, units_id, COUNT(*) as TotalAssets, SUM(CASE WHEN checkstatus = ? THEN 1 ELSE 0 END) as IssueQty, SUM(CASE WHEN checkstatus = ? THEN 1 ELSE 0 END) as WastProduct, SUM(CASE WHEN checkstatus = ? THEN 1 ELSE 0 END) as StockQty FROM stores WHERE company_id = ? AND created_by = ? AND checkstatus NOT IN (?, ?) GROUP BY asset_type, units_id',
                ['ISSUED', 'Wast Products', 'INSTOCK', $globalAttireCompanyId, auth()->id(), 'DELETE', 'ARCHIVE']
            );

            foreach ($product_summary_global_attire as $product_summary) {
                $product_summary->asset_type = ProductType::find($product_summary->asset_type);
                $product_summary->units = SizeMaseurment::find($product_summary->units_id);
            }
        }
        $product_summary_bt = $isAdminManager || in_array(2, $companies)
            ? DB::select("CALL sp_product_summary_bt()") : [];
        $product_summary_bhml = $isAdminManager || in_array(1, $companies)
            ? DB::select("CALL sp_product_summary_bhml()") : [];
        $product_summary_bp = $isAdminManager || in_array(3, $companies)
            ? DB::select("CALL sp_product_summary_bp()") : [];
        $product_summary_bt_ind = $isAdminManager || in_array(4, $companies)
            ? DB::select("CALL sp_product_summary_bt_ind()") : [];
        //dd($product_summary_bhml);


        foreach ($product_summary_bt as $product_summary) {
            //dd($product_summary->asset_type);
            $product_type = ProductType::find($product_summary->asset_type);
            $units = SizeMaseurment::find($product_summary->units_id);
            //dd($units );

            $product_summary->asset_type = $product_type;
            $product_summary->units = $units;
        }

        foreach ($product_summary_bhml as $product_summary) {
            $product_type = ProductType::find($product_summary->asset_type);
            $units = SizeMaseurment::find($product_summary->units_id);

            $product_summary->asset_type = $product_type;
            $product_summary->units = $units;
        }

        foreach ($product_summary_bp as $product_summary) {
            $product_type = ProductType::find($product_summary->asset_type);
            $units = SizeMaseurment::find($product_summary->units_id);

            $product_summary->asset_type = $product_type;
            $product_summary->units = $units;
        }

        foreach ($product_summary_bt_ind as $product_summary) {
            $product_type = ProductType::find($product_summary->asset_type);
            $units = SizeMaseurment::find($product_summary->units_id);

            $product_summary->asset_type = $product_type;
            $product_summary->units = $units;
        }



        return view('home', [
            'stores' => $stores,
            'desktops' => $desktops,
            'laptops' => $laptops,
            'printers' => $printers,
            'product_summary_bt' => $product_summary_bt,
            'product_summary_bhml' => $product_summary_bhml,
            'product_summary_bp' => $product_summary_bp,
            'product_summary_bt_ind' => $product_summary_bt_ind,
            'product_summary_global_attire' => $product_summary_global_attire,
            'globalAttireCompanyId' => $globalAttireCompanyId,
            'assetCount' => $assetCount,
            'laptopCount' => $laptopCount,
            'desktopCount' => $desktopCount,
            'printerCount' => $printerCount,
            'employeeCount' => $employeeCount,
            'userCount' => $userCount,
        ]);
    }

    public function master()
    {
        return view('master');
    }
}
