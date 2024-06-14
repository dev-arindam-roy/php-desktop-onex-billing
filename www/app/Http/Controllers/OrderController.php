<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\User;
use App\Models\CartItems;
use App\Models\Cart;
use App\Models\Procurement;
use App\Models\ProcurementItems;
use App\Models\DeliveryTimeline;
use App\Models\StatusMaster;
use App\Models\ProductVariants;
use Session;
use Helper;
use Auth;
use DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'order_management';
        $dataBag['sidebar_child'] = 'orders';
        $dataBag['data'] = Cart::with([
                'cartItems', 
                'customer', 
                'orderStatus', 
                'procurementBtach',
                'deliveryMan',
                'deliveryStatus'
            ])
            ->where('status', '!=', 3)
            ->orWhere(function($query) {
                $query->whereNull('order_image');
                $query->where('order_status_id', 16);
                $query->whereHas('cartItems', function($cartQry) {
                    $cartQry->where('status', 1);
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(25);
        
        $dataBag['delivery_mans'] = User::with(['userRoles'])
            ->whereHas('userRoles', function($roleQry) {
                $roleQry->where('role_id', 8);
            })
            ->where('status', 1)
            ->orderBy('first_name', 'asc')
            ->get();

        if ($request->ajax()) {
            return view('backend.order.all-orders-rander', $dataBag);
        }

        return view('backend.order.index', $dataBag);
    }

    public function deleteOrder(Request $request, $id) 
    {
        $cart = Cart::findOrFail($id);
        $cart->status = 3;
        $cart->save();
        //code for reset procurement - item, qty
        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'Order has been deleted successfully');
    }

    public function procurementInit(Request $request)
    {
        $dataBag = [];
        $dataBag['isSuccess'] = false;
        $orderIds = !empty($request->input('order_ids')) ? $request->input('order_ids') : [];
        if (empty($orderIds)) {
            return response()->json($dataBag);
        }
        $checkData = Cart::select('id', 'order_status_id', 'procurement_id')->whereIn('id', $orderIds)->get();
        if (count($checkData) == 0) {
            return response()->json($dataBag);
        }
        $isCorrectStatus = true;
        foreach ($checkData as $v) {
            if ($v->order_status_id != 1 || !empty($v->procurement_id)) {
                $isCorrectStatus = false;
            }
        }
        if (!$isCorrectStatus) {
            return response()->json($dataBag);
        }

        $batchNumber = count($orderIds) . rand(111, 999);
        $batchNumber .= '/' . date('d/m/Y');
        
        $calculate = CartItems::select(
                DB::raw('SUM(product_quantity) AS total_quantity'),
                'product_id',
                'unit_id'
            )
            ->whereIn('cart_id', $orderIds)
            ->where('status', 1)
            ->groupBy(['product_id', 'unit_id'])
            ->get();

        if (count($calculate)) {
            $procurementId = Procurement::insertGetId(['batch_number' => $batchNumber]);
            if ($procurementId) {
                foreach ($calculate as $v) {
                    ProcurementItems::insert([
                        'procurement_id' => $procurementId,
                        'order_product_id' => $v->product_id,
                        'order_product_unit_id' => $v->unit_id,
                        'order_product_quantity' => $v->total_quantity
                    ]);
                }
                foreach ($orderIds as $v) {
                    Cart::whereNull('procurement_id')
                        ->whereIn('id', $orderIds)
                        ->update([
                            'procurement_id' => $procurementId,
                            'order_status_id' => 3
                        ]);
                }
            }
            $dataBag['isSuccess'] = true;
            $dataBag['batch_no'] = $batchNumber;
            $dataBag['batch_id'] = $procurementId;
        }
        return response()->json($dataBag);
    }

    public function deliveryInit(Request $request)
    {
        $dataBag = [];
        $dataBag['isSuccess'] = false;
        $orderIds = !empty($request->input('order_ids')) ? $request->input('order_ids') : [];
        $deliveryMan = $request->input('delivery_man') ?? null;
        if (empty($orderIds) || empty($deliveryMan)) {
            return response()->json($dataBag);
        }
        $checkData = Cart::select('id', 'order_status_id')->whereIn('id', $orderIds)->get();
        if (count($checkData) == 0) {
            return response()->json($dataBag);
        }
        $isCorrectStatus = true;
        foreach ($checkData as $v) {
            if ($v->order_status_id != 4 && $v->order_status_id != 15) {
                $isCorrectStatus = false;
            }
        }
        if (!$isCorrectStatus) {
            return response()->json($dataBag);
        }
        foreach ($orderIds as $v) {
            $cart = Cart::find($v);
            if (!empty($cart)) {
                $cart->order_status_id = 15;
                $cart->delivery_status_id = !empty($cart->delivery_status_id) ? $cart->delivery_status_id : 15; 
                $cart->delivery_man_user_id = $deliveryMan;
                if ($cart->save()) {
                    DeliveryTimeline::insert([
                        'order_id' => $cart->id,
                        'delivery_man_id' => $deliveryMan,
                        'progress_status_id' => $cart->delivery_status_id
                    ]);
                    $dataBag['isSuccess'] = true;
                }
            }
        }
        return response()->json($dataBag);
    }

    public function allDeliveries(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'delivery_management';
        $dataBag['sidebar_child'] = 'delivery';
        $dataBag['data'] = Cart::with([
                'cartItems', 
                'customer', 
                'orderStatus', 
                'procurementBtach',
                'deliveryMan',
                'deliveryStatus'
            ])
            ->whereHas('cartItems', function($query) {
                $query->where('status', 1);
            })
            ->where('status', '!=', 3)
            ->where('order_status_id', '!=', 9)
            ->whereNotNull('delivery_status_id')
            ->orderBy('id', 'desc')
            ->get();

        $dataBag['delivery_mans'] = User::with(['userRoles'])
            ->whereHas('userRoles', function($roleQry) {
                $roleQry->where('role_id', 8);
            })
            ->where('status', 1)
            ->orderBy('first_name', 'asc')
            ->get();

        return view('backend.order.delivery-index', $dataBag);
    }

    public function orderDetails(Request $request, $orderNo)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'order_management';
        $dataBag['sidebar_child'] = 'orders';

        $order = Cart::with([
            'cartItems', 
            'customer', 
            'orderStatus', 
            'procurementBtach',
            'deliveryMan',
            'deliveryStatus',
            'deliveryTimeline'
        ])
        ->whereHas('cartItems', function($query) {
            $query->where('status', 1);
        })
        ->where('cart_number', $orderNo)
        ->where('status', '!=', 3)
        ->orderBy('id', 'desc')
        ->first();

        if (empty($order)) {
            abort(404);
        }

        $dataBag['data'] = $order;
        $dataBag['delivery_mans'] = User::with(['userRoles'])
            ->whereHas('userRoles', function($roleQry) {
                $roleQry->where('role_id', 8);
            })
            ->where('status', 1)
            ->orderBy('first_name', 'asc')
            ->get();

        return view('backend.order.order-details', $dataBag);
    }

    public function deliveryDetails(Request $request, $orderNo)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'delivery_management';
        $dataBag['sidebar_child'] = 'delivery';
        
        $order = Cart::with([
            'cartItems' => function($itemQry) {
                $itemQry->with(['productVariant', 'unit']);
            }, 
            'customer', 
            'orderStatus', 
            'procurementBtach',
            'deliveryMan',
            'deliveryStatus',
            'deliveryTimeline' => function($deliQry) {
                $deliQry->with(['deliveryStatus', 'deliveryMan']);
                $deliQry->where('status', 1)->orderBy('id', 'asc');
            }
        ])
        ->whereHas('cartItems', function($query) {
            $query->where('status', 1);
        })
        ->where('cart_number', $orderNo)
        ->where('status', '!=', 3)
        ->orderBy('id', 'desc')
        ->first();

        if (empty($order)) {
            abort(404);
        }

        $dataBag['data'] = $order;
        $dataBag['delivery_mans'] = User::with(['userRoles'])
            ->whereHas('userRoles', function($roleQry) {
                $roleQry->where('role_id', 8);
            })
            ->where('status', 1)
            ->orderBy('first_name', 'asc')
            ->get();

        $dataBag['status'] = StatusMaster::whereIn('id', [2, 6, 7, 8, 9, 10, 11, 17, 18])->get();

        return view('backend.order.order-details', $dataBag);
    }

    public function updateDelivery(Request $request)
    {
        $orderId = $request->input('order_id');
        $cart = Cart::findOrFail($orderId);
        $deliveryTimeline = new DeliveryTimeline();
        $deliveryTimeline->order_id = $cart->id;
        $deliveryTimeline->delivery_man_id = $cart->delivery_man_user_id;
        $deliveryTimeline->progress_status_id = $request->input('delivery_status');
        $deliveryTimeline->note = $request->input('delivery_note') ?? null;
        $deliveryTimeline->save();
        $cart->delivery_status_id = $deliveryTimeline->progress_status_id;
        if ($deliveryTimeline->progress_status_id == 9) {
            $cart->order_status_id = 9;
        }
        $cart->save();
        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'Delivery timeline has been updated successfully');
    }

    public function getImageOrderItem(Request $request)
    {
        $dataBag = [];
        $cartId = $request->input('order_id');
        $cartItems = CartItems::with([
                'productVariant',
                'unit'
            ])
            ->where('cart_id', $cartId)
            ->orderBy('id', 'asc')
            ->get();

        $cartUser = Cart::with(['customer'])->where('id', $cartId)->first();

        $dataBag['cart_items'] = $cartItems;
        $dataBag['cart_user'] = $cartUser;
        $dataBag['isSuccess'] = true;
        
        return response()->json($dataBag);
    }

    public function saveImageOrderItem(Request $request)
    {
        $id = $request->input('order_id');
        $itemArr = $request->input('product_variant_id') ?? [];
        $qtyArr = $request->input('item_qty') ?? [];
        
        $cart = Cart::findOrFail($id);
        if (empty($itemArr) || empty($qtyArr) || (count($itemArr) != count($qtyArr))) {
            return redirect()->back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Something went wrong, Try again');
        }
        $orderItems = [];
        foreach ($itemArr as $k => $v) {
            $orderItems[$v] = array('quantity' => $qtyArr[$k]);
        }
        $cartAmount = json_decode(self::totalCartAmount($orderItems), true);
        $cart->total_cart_amount = $cartAmount['total_cart_amount'];
        $cart->total_shipping_amount = $cartAmount['total_shipping_amount'];
        if ($request->has('progress_status_id')) {
            $cart->order_status_id = $request->input('progress_status_id') ?? 1;
        }
        if ($cart->save()) {
            self::saveCartItems($id, $orderItems);
            if ($request->has('progress_status_id') && $request->input('progress_status_id') == 1) {
                return redirect()->back()
                    ->with('message_type', 'success')
                    ->with('message_title', 'Done!')
                    ->with('message_text', 'Order items has been updated successfully')
                    ->with('message_header', "Let's Process the Order")
                    ->with('message_content', 'All order items has been listed successfully');
            }
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Order items has been updated successfully');
        }
        return redirect()->back()
            ->with('message_type', 'error')
            ->with('message_title', 'Sorry!')
            ->with('message_text', 'Something went wrong, Try again');
        
    }

    public static function saveCartItems($id, $orderItems)
    {
        $insertDataArr = [];
        $productIds = [];
        if (!empty($orderItems)) {
            $productIds = array_keys($orderItems);
            if (!empty($productIds)) {
                $cartProducts = ProductVariants::with(['baseProduct', 'productUnit', 'productBrand'])->whereIn('id', $productIds)->get();
                if (!empty($cartProducts) && count($cartProducts)) {
                    foreach ($cartProducts as $product) {
                        $itemsArr = array();
                        $itemsArr['cart_id'] = $id;
                        $itemsArr['product_id'] = $product->id;
                        $itemsArr['product_quantity'] = !empty($orderItems[$product->id]['quantity']) ? $orderItems[$product->id]['quantity'] : 1;
                        $itemsArr['name'] = $product->name;
                        $itemsArr['brand_id'] = $product->brand_id;
                        $itemsArr['sku'] = $product->sku;
                        $itemsArr['short_description'] = $product->short_description;
                        $itemsArr['offer_text'] = $product->offer_text;
                        $itemsArr['unit_id'] = $product->unit_id;
                        $itemsArr['price'] = $product->price;
                        $itemsArr['old_price'] = $product->old_price;
                        $itemsArr['percentage_discount'] = $product->percentage_discount;
                        $itemsArr['flat_discount'] = $product->flat_discount;
                        $itemsArr['hsn_code'] = $product->hsn_code;
                        $itemsArr['gst_rate'] = $product->gst_rate;
                        $itemsArr['image'] = $product->image; 
                        $itemsArr['is_bundle_product'] = $product->is_bundle_product; 
                        $itemsArr['have_free_product'] = $product->have_free_product; 
                        array_push($insertDataArr, $itemsArr);
                    }
                }
            }
        }
        if (!empty($insertDataArr)) {
            CartItems::where('cart_id', $id)->delete();
            CartItems::insert($insertDataArr);
            return true;
        }
        return false;
    }

    public static function totalCartAmount($orderItems)
    {
        $total = 0;
        $payable = 0;
        $shipping = 0;
        $productIds = [];
        $dataBag = [];
        $dataBag['total_cart_amount'] = $payable;
        $dataBag['total_shipping_amount'] = $shipping;
        if (!empty($orderItems)) {
            $productIds = array_keys($orderItems);
            if (!empty($productIds)) {
                $cartProducts = ProductVariants::with(['baseProduct', 'productUnit', 'productBrand'])->whereIn('id', $productIds)->get();
                if (!empty($cartProducts) && count($cartProducts)) {
                    foreach ($cartProducts as $product) {
                        $qty = !empty($orderItems[$product->id]['quantity']) ? $orderItems[$product->id]['quantity'] : 1; 
                        $itemTotal = $qty * $product->price;
                        if (!empty($product->gst_rate) && $product->gst_rate) {
                            $itemTotal = $itemTotal + (($itemTotal * $product->gst_rate) / 100);
                        }
                        $total = $total + $itemTotal;
                    }
                    $shipping = self::caculateShippingAmount($total);
                    $payable = $total + $shipping;
                    $dataBag['total_cart_amount'] = $payable;
                    $dataBag['total_shipping_amount'] = $shipping;
                }
            }
        }
        return json_encode($dataBag);
    }

    public static function caculateShippingAmount($totalAmount)
    {
        return 0;
    }

    public function createOrder(Request $request)
    {
        $requestData = $request->all();
        $customerId = $requestData['customer_id'];
        $orderProductIds = $requestData['order_product_id'];
        $orderProductQty = $requestData['order_product_qty'];
        $orderId = $requestData['order_id'];
        //$orderItems = array_combine($orderProductIds, $orderProductQty);

        if (empty($orderProductIds) || empty($orderProductQty) || (count($orderProductIds) != count($orderProductQty))) {
            return redirect()->back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Something went wrong, Try again');
        }
        
        $customer = User::with(['userProfile'])->findOrFail($customerId);
        
        $orderItems = [];
        foreach ($orderProductIds as $k => $v) {
            $orderItems[$v] = array('quantity' => $orderProductQty[$k]);
        }
        
        if (!empty($orderId)) {
            $cart = Cart::findOrFail($orderId);
        } else {
            $cart = new Cart();
            $cart->cart_number = Helper::cartUniqueId();
        }
        $cart->customer_id = $customerId;
        $cart->cart_name = $customer->first_name . ' ' . $customer->last_name;
        $cart->house_name = !empty($customer->userProfile->house_name) ? $customer->userProfile->house_name : NULL;
        $cart->street_name = !empty($customer->userProfile->house_name) ? $customer->userProfile->house_name : NULL;
        $cart->full_address = !empty($customer->userProfile->full_address) ? $customer->userProfile->full_address : NULL;
        $cart->city = !empty($customer->userProfile->city) ? $customer->userProfile->city : NULL;
        $cart->pincode = !empty($customer->userProfile->pincode) ? $customer->userProfile->pincode : NULL;
        $cart->landmark = !empty($customer->userProfile->land_mark) ? $customer->userProfile->land_mark : NULL;
        $cart->state = !empty($customer->userProfile->state) ? $customer->userProfile->state : NULL;
        $cartAmount = json_decode(self::totalCartAmount($orderItems), true);
        $cart->total_cart_amount = $cartAmount['total_cart_amount'];
        $cart->total_shipping_amount = $cartAmount['total_shipping_amount'];
        $cart->order_status_id = ($request->has('progress_status_id') && !empty($request->input('progress_status_id'))) ? $request->input('progress_status_id') : 16;
        if ($cart->save()) {
            if (empty($orderId)) {
                self::cartQrCode($cart->cart_number);
            }
            self::saveCartItems($cart->id, $orderItems);
            if ($request->has('progress_status_id') && $request->input('progress_status_id') == 1) {
                return redirect()->back()
                    ->with('message_type', 'success')
                    ->with('message_title', 'Done!')
                    ->with('message_text', 'Order items has been updated successfully')
                    ->with('message_header', "Let's Process the Order")
                    ->with('message_content', 'All order items has been listed successfully');
            }
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Order items has been updated successfully');
        }
        return redirect()->back()
            ->with('message_type', 'error')
            ->with('message_title', 'Sorry!')
            ->with('message_text', 'Something went wrong, Try again');
    }

    public static function cartQrCode($data)
    {
        $savePath = public_path('uploads/images/qr-codes/' . $data . '.svg');
        $image = QrCode::eyeColor(0, 115, 103, 240, 0, 0, 0)->format('svg')->size(300)->generate($data, $savePath);
    }

    public function orderStatement(Request $request, $orderNo)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'order_management';
        $dataBag['sidebar_child'] = 'orders';

        $order = Cart::with([
            'cartItems', 
            'customer', 
            'orderStatus', 
            'procurementBtach',
            'deliveryMan',
            'deliveryStatus',
            'deliveryTimeline'
        ])
        ->whereHas('cartItems', function($query) {
            $query->where('status', 1);
        })
        ->where('cart_number', $orderNo)
        ->where('status', '!=', 3)
        ->first();

        if (empty($order)) {
            abort(404);
        }

        $dataBag['data'] = $order;
        return view('backend.order.order-statement', $dataBag);
    }
}
