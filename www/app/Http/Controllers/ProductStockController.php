<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProductVariants;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\User;
use Carbon\Carbon;
use Session;
use Hash;
use Auth;
use DB;

class ProductStockController extends Controller
{
    public function stockInIndex(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'stock_management';
        $dataBag['sidebar_child'] = 'stock-in';
        $todayDate = date('Y-m-d');
        $todayData = StockIn::select(
            'stock_in.*',
            'product_variants.name as product_name',
            'product_variants.sku as product_sku',
            'unit_master.short_name as unit_name',
            'users.first_name as user_first_name',
            'users.last_name as user_last_name',
        )
        ->join('product_variants', 'product_variants.id', '=', 'stock_in.product_id')
        ->join('unit_master', 'unit_master.id', '=', 'stock_in.unit_id')
        ->join('users', 'users.id', '=', 'stock_in.user_id')
        ->where('stock_in.status', 1)
        ->whereDate('stock_in.created_at', $todayDate)
        ->orderBy('stock_in.id', 'desc')
        ->get();

        $dataBag['data'] = $todayData;
        return view('backend.product-stock.stock-in-index', $dataBag);
    }

    public function addStock(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'stock_management';
        $dataBag['sidebar_child'] = 'stock-in';
        
        $allUsers = User::with(['userRoles'])
            ->whereHas('userRoles', function($roleQry) {
                $roleQry->where('role_id', 8);
            })
            ->where('status', 1)
            ->orderBy('first_name', 'asc')
            ->get();
        
        $allProducts = ProductVariants::where('status', 1)->orderBy('name', 'asc')->get();
        
        $allUnitis = DB::table('unit_master')->where('status', 1)->orderBy('name', 'asc')->get(); 

        $userCategory = DB::table('user_categories')->where('status', 1)->whereIn('id', [2, 3])->get();
        
        $dataBag['all_users'] = $allUsers;
        $dataBag['all_products'] = $allProducts;
        $dataBag['all_units'] = $allUnitis;
        $dataBag['all_user_categories'] = $userCategory;

        return view('backend.product-stock.stock-in-add', $dataBag);
    }

    public function saveStock(Request $request)
    {
        $stockIn = new StockIn();
        $stockIn->transaction_id = md5(Str::uuid(36)->toString());
        $stockIn->batch_no = null;
        $stockIn->challan_no = $request->input('challan_no');
        $stockIn->user_id = $request->input('user_id');
        $stockIn->product_id = $request->input('product_id');
        $stockIn->product_quantity = $request->input('product_quantity');
        $stockIn->unit_id = $request->input('unit_id');
        $stockIn->unit_price = $request->input('unit_price');
        $stockIn->stock_received_date = self::formatDate($request->input('stock_received_date'));
        $stockIn->unit_total = !(empty($request->input('unit_total'))) ? $request->input('unit_total') : 0;
        if ($stockIn->save()) {
            self::updateProductMaster($stockIn->product_id, $stockIn->product_quantity, $stockIn->unit_id, 'stock_in');
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Stock has been added successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public static function formatDate($date) {
        if (empty($date)) {
            return null;
        }
        $dateArr = explode('/', $date);
        if (empty($dateArr) || count($dateArr) <= 0) {
            return null;
        }
        return $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0];
    }

    public static function updateProductMaster($productId, $quantity, $unitId, $action)
    {
        if (empty($productId) || empty($quantity) || empty($unitId) || empty($action)) {
            return 0;
        }
        $data = DB::table('product_variants')->where('id', $productId)->first();
        if (empty($data)) {
            return 0;
        }
        if ($action == 'stock_in') {
            $newStock = $data->available_stock + $quantity;
        }
        if ($action == 'stock_out') {
            $newStock = $data->available_stock - $quantity;
            if ($newStock < 0) {
                $newStock = 0;
            }
        }
        DB::table('product_variants')->where('id', $productId)->update(['available_stock' => $newStock, 'unit_id' => $unitId]);
        return 1;
    }

    public function stockOutIndex(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'stock_management';
        $dataBag['sidebar_child'] = 'stock-out';
        $todayDate = date('Y-m-d');
        $todayData = StockOut::select(
            'stock_out.*',
            'product_variants.name as product_name',
            'product_variants.sku as product_sku',
            'unit_master.short_name as unit_name',
            'users.first_name as user_first_name',
            'users.last_name as user_last_name',
        )
        ->join('product_variants', 'product_variants.id', '=', 'stock_out.product_id')
        ->join('unit_master', 'unit_master.id', '=', 'stock_out.unit_id')
        ->join('users', 'users.id', '=', 'stock_out.user_id')
        ->where('stock_out.status', 1)
        ->whereDate('stock_out.created_at', $todayDate)
        ->orderBy('stock_out.id', 'desc')
        ->get();

        $dataBag['data'] = $todayData;
        return view('backend.product-stock.stock-out-index', $dataBag);
    }

    public function outStock(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'stock_management';
        $dataBag['sidebar_child'] = 'stock-out';
        
        $allUsers = User::with(['userRoles'])
            ->whereHas('userRoles', function($roleQry) {
                $roleQry->where('role_id', 8);
            })
            ->where('status', 1)
            ->orderBy('first_name', 'asc')
            ->get();
        
        $allProducts = ProductVariants::where('status', 1)->orderBy('name', 'asc')->get();
        
        $allUnitis = DB::table('unit_master')->where('status', 1)->orderBy('name', 'asc')->get(); 

        $userCategory = DB::table('user_categories')->where('status', 1)->whereIn('id', [4, 5, 6])->get();
        
        $dataBag['all_users'] = $allUsers;
        $dataBag['all_products'] = $allProducts;
        $dataBag['all_units'] = $allUnitis;
        $dataBag['all_user_categories'] = $userCategory;

        return view('backend.product-stock.stock-out-add', $dataBag);
    }

    public function saveOutStock(Request $request)
    {
        $stockOut = new StockOut();
        $stockOut->transaction_id = md5(Str::uuid(36)->toString());
        $stockOut->batch_no = null;
        $stockOut->challan_no = $request->input('challan_no');
        $stockOut->user_id = $request->input('user_id');
        $stockOut->product_id = $request->input('product_id');
        $stockOut->product_quantity = $request->input('product_quantity');
        $stockOut->unit_id = $request->input('unit_id');
        $stockOut->unit_price = $request->input('unit_price');
        $stockOut->stock_issued_date = self::formatDate($request->input('stock_issued_date'));
        $stockOut->unit_total = !(empty($request->input('unit_total'))) ? $request->input('unit_total') : 0;
        if ($stockOut->save()) {
            self::updateProductMaster($stockOut->product_id, $stockOut->product_quantity, $stockOut->unit_id, 'stock_out');
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Stock has been dispatched successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function stockReport(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'stock_management';
        $dataBag['sidebar_child'] = 'stock-report';
        $startDate = Carbon::now()->subDays(29)->format('d/m/Y');
        $endDate = Carbon::now()->format('d/m/Y');
        $dateRange = $startDate . ' - ' . $endDate;

        $reportDateType = !empty($request->get('report_date_type')) ? $request->get('report_date_type') : 'created_at';
        $stockReportDateRange = !empty($request->get('stock_report_date_range')) ? $request->get('stock_report_date_range') : $dateRange;
        $dateExplode = explode(' - ', $stockReportDateRange);
        $startDate = self::formatDate($dateExplode[0]);
        $endDate = self::formatDate($dateExplode[1]);

        $stockIn = StockIn::select(
            'stock_in.*',
            'product_variants.name as product_name',
            'product_variants.sku as product_sku',
            'unit_master.short_name as unit_name',
            'users.first_name as user_first_name',
            'users.last_name as user_last_name',
        )
        ->join('product_variants', 'product_variants.id', '=', 'stock_in.product_id')
        ->join('unit_master', 'unit_master.id', '=', 'stock_in.unit_id')
        ->join('users', 'users.id', '=', 'stock_in.user_id')
        ->where('stock_in.status', 1)
        ->when((!empty($reportDateType) && $reportDateType == 'created_at'), function($query) use ($startDate, $endDate) {
            return $query->whereDate('stock_in.created_at', '>=', $startDate)->whereDate('stock_in.created_at', '<=', $endDate);
        })
        ->when((!empty($reportDateType) && $reportDateType == 'issue_receive_date'), function($query) use ($startDate, $endDate) {
            return $query->whereDate('stock_in.stock_received_date', '>=', $startDate)->whereDate('stock_in.stock_received_date', '<=', $endDate);
        })
        ->orderBy('stock_in.id', 'desc')
        ->get();

        $stockOut = StockOut::select(
            'stock_out.*',
            'product_variants.name as product_name',
            'product_variants.sku as product_sku',
            'unit_master.short_name as unit_name',
            'users.first_name as user_first_name',
            'users.last_name as user_last_name',
        )
        ->join('product_variants', 'product_variants.id', '=', 'stock_out.product_id')
        ->join('unit_master', 'unit_master.id', '=', 'stock_out.unit_id')
        ->join('users', 'users.id', '=', 'stock_out.user_id')
        ->where('stock_out.status', 1)
        ->when((!empty($reportDateType) && $reportDateType == 'created_at'), function($query) use ($startDate, $endDate) {
            return $query->whereDate('stock_out.created_at', '>=', $startDate)->whereDate('stock_out.created_at', '<=', $endDate);
        })
        ->when((!empty($reportDateType) && $reportDateType == 'issue_receive_date'), function($query) use ($startDate, $endDate) {
            return $query->whereDate('stock_out.stock_issued_date', '>=', $startDate)->whereDate('stock_out.stock_issued_date', '<=', $endDate);
        })
        ->orderBy('stock_out.id', 'desc')
        ->get();

        $dataBag['stockIn'] = $stockIn;
        $dataBag['stockOut'] = $stockOut;

        return view('backend.product-stock.stock-report', $dataBag);
    }
}
