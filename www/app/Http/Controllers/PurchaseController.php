<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProductVariants;
use App\Models\Batch;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\BatchProducts;
use App\Models\User;
use App\Models\Unit;
use Helper;

class PurchaseController extends Controller
{

    public function __construct()
    {
        
    }

    public function index(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'purchase_management';
        $dataBag['sidebar_child'] = 'all-purchases';
        $pagination = !empty($request->get('pagination')) ? $request->get('pagination') : 25; 
        $dataBag['data'] = Purchase::with(['purchaseProducts'])
            ->where('status', '!=', 3)
            ->orderBy('id', 'desc')
            ->paginate($pagination);

        return view('backend.purchase.index', $dataBag);
    }

    public function add(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'purchase_management';
        $dataBag['sidebar_child'] = 'add-purchase';

        $dataBag['batches'] = Batch::where('status', '!=', 3)->orderBy('id', 'desc')->get();
        $dataBag['vendors'] = User::where('user_category', 2)->where('status', '!=', 3)->orderBy('first_name', 'asc')->get();
        $dataBag['units'] = Unit::where('status', '!=', 3)->orderBy('id', 'desc')->get();
        $dataBag['productVariants'] = ProductVariants::select(
                'id', 
                'name', 
                'sku', 
                'barcode_no', 
                'unit_id', 
                'price', 
                'old_price', 
                'hsn_code', 
                'gst_rate'
            )
            ->where('status', '!=', 3)
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.purchase.add', $dataBag);
    }

    public function save(Request $request)
    {
        $productId = $request->input('product_id');
        $batchId = $request->input('batch_id');
        $vendorId = $request->input('vendor_id');
        $billNo = $request->input('bill_no');
        $receivedDate = date('Y-m-d', strtotime($request->input('received_date')));

        $isPurchaseExist = Purchase::where('batch_id', $batchId)
            ->where('vendor_id', $vendorId)
            ->where('bill_no', $billNo)
            ->whereDate('received_date', $receivedDate)
            ->first();

        $purchaseId = self::doPurchase($request, $isPurchaseExist);
        if (!empty($purchaseId)) {
            $purchaseProduct = new PurchaseProduct();
            $purchaseProduct->purchase_id = $purchaseId;
            $purchaseProduct->batch_id = $request->input('batch_id');
            $purchaseProduct->vendor_id = $request->input('vendor_id');
            $purchaseProduct->product_id = $request->input('product_id');
            $purchaseProduct->product_qty = $request->input('product_qty');
            $purchaseProduct->unit_id = $request->input('unit_id');
            $purchaseProduct->purchase_price = $request->input('purchase_price');
            $purchaseProduct->gst_rate = $request->input('gst_rate');
            $purchaseProduct->gst_amount = $request->input('gst_amount');
            $purchaseProduct->total_amount = $request->input('total_amount');
            if ($purchaseProduct->save()) {
                $isBatchProductsExist = BatchProducts::where('batch_id', $batchId)
                    ->where('product_id', $productId)
                    ->first();

                $batchProductsId = self::batchWiseProduct($request, $isBatchProductsExist);

                return redirect()->back()
                    ->with('message_type', 'success')
                    ->with('message_title', 'Done!')
                    ->with('message_text', 'New purchase entry has been created successfully');
            }
        }

        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');

    }

    public static function doPurchase($requestObj, $purchase)
    {
        if (!empty($purchase)) {
            $purchase->bill_amount = $purchase->bill_amount + $requestObj->input('total_amount');
            $purchase->due_amount = ($purchase->bill_amount > $purchase->due_amount) ? ($purchase->bill_amount - $purchase->due_amount) : 0;
            if ($purchase->due_amount > 0) {
                $purchase->payment_status = 0;
            }
            $purchase->save();
            return $purchase->id;
        }

        $purchase = new Purchase();
        $purchase->hash_id = (string) Str::uuid();
        $purchase->batch_id = $requestObj->input('batch_id');
        $purchase->vendor_id = $requestObj->input('vendor_id');
        $purchase->bill_amount = $requestObj->input('total_amount');
        $purchase->due_amount = $requestObj->input('total_amount');
        $purchase->bill_no = $requestObj->input('bill_no');
        $purchase->received_date = date('Y-m-d', strtotime($requestObj->input('received_date')));
        $purchase->save();
        return $purchase->id;
    }

    public static function batchWiseProduct($requestObj, $batchProducts) {

        if (!empty($batchProducts)) {
            $batchProducts->product_qty = $batchProducts->product_qty + $requestObj->input('product_qty');
            $batchProducts->purchase_price = $requestObj->input('purchase_price');
            $batchProducts->sale_price = $requestObj->input('sale_price');
            $batchProducts->save();
            return $batchProducts->id;
        }

        $batchProductsEntry = new BatchProducts();
        $batchProductsEntry->batch_id = $requestObj->input('batch_id');
        $batchProductsEntry->product_id = $requestObj->input('product_id');
        $batchProductsEntry->product_qty = $requestObj->input('product_qty');
        $batchProductsEntry->purchase_price = $requestObj->input('purchase_price');
        $batchProductsEntry->sale_price = $requestObj->input('sale_price');
        $batchProductsEntry->save();
        return $batchProductsEntry->id;

    }

    /**
     * Batches
     */
    public function batchIndex(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'purchase_management';
        $dataBag['sidebar_child'] = 'all-batches';
        $pagination = !empty($request->get('pagination')) ? $request->get('pagination') : 25; 
        $batchSearchText = ($request->has('batch_search_text') && !empty($request->get('batch_search_text'))) ? $request->get('batch_search_text') : null; 
        $dataBag['data'] = Batch::where('status', '!=', 3)
            ->when(!empty($batchSearchText), function ($query) use ($batchSearchText) {
                return $query->where('batch_no', $batchSearchText)
                    ->orWhere('name', 'like', '%' . $batchSearchText . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($pagination);
        return view('backend.purchase.all-batches', $dataBag);
    }

    public function addBatch(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'purchase_management';
        $dataBag['sidebar_child'] = 'all-batches';
        $dataBag['batch_no'] = Helper::createBatchNo(); 
        return view('backend.purchase.add-batch', $dataBag);
    }

    public function saveBatch(Request $request)
    {
        $batchNo = $request->input('batch_no');
        $checkBatch = Batch::where('batch_no', $batchNo)->where('status', '!=', 3)->exists();

        if ($checkBatch) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Batch No is already exist');
        }

        $batch = new Batch();
        $batch->batch_no = $request->input('batch_no');
        $batch->name = $request->input('name') ?? '';
        $batch->description = $request->input('description') ?? null;
        $batch->status = $request->input('status');
        if ($batch->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'New Batch has been created successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function editBatch(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'purchase_management';
        $dataBag['sidebar_child'] = 'all-batches';
        $dataBag['data'] = $batch; 
        return view('backend.purchase.edit-batch', $dataBag);
    }

    public function updateBatch(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);

        $batchNo = $request->input('batch_no');
        $checkBatch = Batch::where('batch_no', $batchNo)
            ->where('id', '!=', $id)
            ->where('status', '!=', 3)
            ->exists();

        if ($checkBatch) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Batch No is already exist');
        }

        //$batch->batch_no = $request->input('batch_no');
        $batch->name = $request->input('name') ?? '';
        $batch->description = $request->input('description') ?? null;
        $batch->status = $request->input('status');
        if ($batch->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Batch has been updated successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function deleteBatch(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);
        $batch->status = 3;
        $batch->save();
        return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Batch has been deleted successfully');
    }
}
