<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\CartItems;
use App\Models\Cart;
use App\Models\Procurement;
use App\Models\ProcurementItems;
use Session;
use Helper;
use Auth;
use DB;

class ProcurementController extends Controller
{
    public function index(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'procurement_management';
        $dataBag['sidebar_child'] = 'procurement_list';
        $data = Procurement::with([
                'procurementItems',
                'progressStatus'
            ])
            ->whereHas('procurementItems', function($query) {
                $query->where('status', 1);
            })
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();
        
        if (!empty($data) && count($data)) {
            foreach ($data as $v) {
                $associatesIds = ProcurementItems::select(
                        'procurement_associate_user_id'
                    )
                    ->where('procurement_id', $v->id)
                    ->where('status', 1)
                    ->pluck('procurement_associate_user_id')
                    ->unique()
                    ->toArray();
                
                if (!empty($associatesIds)) {
                    $v->associate_users = User::select(
                            DB::raw('CONCAT(first_name, " ", last_name) AS name'), 
                            'id'
                        )
                        ->whereIn('id', $associatesIds)
                        ->pluck('name', 'id')
                        ->toArray();
                }
            }
        } 

        $dataBag['data'] = $data;
        return view('backend.order.procurement-list', $dataBag);
    }

    public function itemList(Request $request, $procurementId)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'procurement_management';
        $dataBag['sidebar_child'] = 'procurement_list';
        $dataBag['procurement'] = Procurement::findOrFail($procurementId);
        $data = ProcurementItems::with([
                    'progressStatus',
                    'procurement',
                    'associates',
                    'product',
                    'unit'
                ])
                ->where('procurement_id', $procurementId)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->get();

        $dataBag['data'] = $data;
        $dataBag['associates'] = User::with(['userRoles'])
            ->whereHas('userRoles', function($roleQry) {
                $roleQry->where('role_id', 7);
            })
            ->where('status', 1)
            ->orderBy('first_name', 'asc')
            ->get();

        $dataBag['vendors'] = User::with(['userRoles'])
            ->whereHas('userRoles', function($roleQry) {
                $roleQry->where('role_id', 5);
            })
            ->where('status', 1)
            ->orderBy('first_name', 'asc')
            ->get();

        $isAllAssigned = true;
        if (count($data)) {
            foreach ($data as $v) {
                if (empty($v->procurement_associate_user_id)) {
                    $isAllAssigned = false;
                }
            }
        }
        $dataBag['is_all_assigned'] = $isAllAssigned;
        return view('backend.order.procurement-list-items', $dataBag);
    }

    public function assignItems(Request $request, $procurementId)
    {
        $procurement = Procurement::findOrFail($procurementId);
        $assignUserId = $request->input('assign_user_id');
        $assignProductIds = explode(',', $request->input('assign_product_ids'));
        if (!empty($assignProductIds)) {
            foreach ($assignProductIds as $id) {
                ProcurementItems::where('id', $id)
                    ->where('procurement_id', $procurementId)
                    ->update([
                        'procurement_associate_user_id' => $assignUserId,
                        'progress_status_id' => 2
                    ]);
            }
            $procurement->progress_status_id = 13;
            $procurement->save();
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Associate has been assigned successfully');
        }

        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function purchaseItems(Request $request, $procurementId)
    {
        if (!empty($request->input('assign_item_row_id'))) {
            
            $id = $request->input('assign_item_row_id');
            $itemsPurchase = ProcurementItems::findOrFail($id);
            $itemsPurchase->vendor_id = $request->input('vendor_user_id');
            $itemsPurchase->purchase_price = $request->input('item_price');
            if ($request->has('progress_status_id') && !empty($request->input('progress_status_id'))) {
                $itemsPurchase->progress_status_id = $request->input('progress_status_id');
            }
            $itemsPurchase->save();
            self::OrderProcurementStatus($procurementId);
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Purchase entry has been updated successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function allCompletedItems(Request $request, $procurementId)
    {
        $dataBag = [];
        $dataBag['isSuccess'] = false;
        $procurement = Procurement::find($procurementId);
        $itemIds = $request->input('item_ids') ?? [];
        if (!empty($procurement)) {
            ProcurementItems::where('procurement_id', $procurementId)
                ->whereIn('id', $itemIds)
                ->update(['progress_status_id' => 14]);
            $procurement->progress_status_id = 14;
            $procurement->save();
            $dataBag['batch_no'] = $procurement->batch_number;
            $dataBag['isSuccess'] = true;
            self::OrderProcurementStatus($procurementId);
        }
        return response()->json($dataBag);
    }

    public function deleteItem(Request $request, $procurementId, $id)
    {
        $procurement = Procurement::findOrFail($procurementId);
        $data = ProcurementItems::findOrFail($id);
        $data->status = 3;
        $data->save();
        self::OrderProcurementStatus($procurementId);

        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'Item has been removed from the procurement list');
    }

    public function deleteProcurement(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);
        ProcurementItems::where('procurement_id', $id)->update(['status' => 3]);
        self::OrderProcurementStatus($id);

        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'Procurement has been deleted successfully');
    }

    public function editProcurement(Request $request, $id)
    {
        $dataBag = [];
        $procurement = Procurement::find($id);
        $dataBag['isSuccess'] = true;
        $dataBag['procurement'] = $procurement;
        return response()->json($dataBag);
    }

    public function updateProcurement(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);
        $procurement->note = $request->input('note') ?? NULL;
        $procurement->comment = $request->input('comment') ?? NULL;
        $procurement->progress_status_id = 13;
        if ($request->has('progress_status_id') && !empty($request->input('progress_status_id'))) {
            $procurement->progress_status_id = $request->input('progress_status_id');
        }
        $procurement->save();
        self::OrderProcurementStatus($id);

        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'Procurement has been saved successfully');
    }

    public function bulkUpdateProcurement(Request $request)
    {
        $dataBag = [];
        $dataBag['isSuccess'] = false;
        $ids = $request->input('ids') ?? [];
        if (empty($ids)) {
            return response()->json($dataBag);
        }
        foreach ($ids as $v) {
            ProcurementItems::where('procurement_id', $v)->update(['progress_status_id' => 14]);
            Procurement::where('id', $v)->update(['progress_status_id' => 14]);
            Cart::where('procurement_id', $v)->update(['order_status_id' => 4]);
            $dataBag['isSuccess'] = true;
        }
        return response()->json($dataBag);
    }

    public static function OrderProcurementStatus($procurementId)
    {
        $anyItems = ProcurementItems::where('procurement_id', $procurementId)->where('status', 1)->count();
        if ($anyItems == 0) {
            Procurement::where('id', $procurementId)->update(['status' => 3]);
            Cart::where('procurement_id', $procurementId)->update([
                'order_status_id' => 1,
                'procurement_id' => NULL
            ]);
        } else {
            $completedCount = ProcurementItems::where('procurement_id', $procurementId)
                ->where('status', 1)
                ->where('progress_status_id', 14)
                ->count();

            $allItemCount = ProcurementItems::where('procurement_id', $procurementId)
                ->where('status', 1)
                ->count();
            
            if ($completedCount >= $allItemCount) {
                Procurement::where('id', $procurementId)->update(['progress_status_id' => 14]);
                Cart::where('procurement_id', $procurementId)->update(['order_status_id' => 4]);
            }
            $procurement = Procurement::where('id', $procurementId)->first();
            if ($procurement->progress_status_id == 14) {
                ProcurementItems::where('procurement_id', $procurementId)->where('status', 1)->update(['progress_status_id' => 14]);
                Cart::where('procurement_id', $procurementId)->update(['order_status_id' => 4]);
            }
        }
    }

    public function pdfProcurement(Request $request, $id)
    {
        $procurement = Procurement::with([
                'procurementItems' => function($itemQry) {
                    $itemQry->where('status', 1);
                    $itemQry->with([
                        'progressStatus',
                        'procurement',
                        'associates',
                        'product',
                        'unit'
                    ]);
                },
                'progressStatus',
                'cartOrders'
            ])
            ->where('id', $id)
            ->where('status', 1)
            ->first();
        
        if (empty($procurement)) {
            return back();
        }
        $pdfData = array();
        $pdfData['procurement'] = $procurement;
        $pdf = PDF::loadView('backend.order.procurement-pdf', $pdfData);
        $fileName = 'procurement_' . $procurement->batch_number . '.pdf';
        return $pdf->download($fileName);
    }
}
