<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ProductVariants;
use Session;
use Helper;
use Auth;
use DB;

class AjaxController extends Controller
{
    public function removeTableImage(Request $request)
    {
        $tabName = $request->input('tab_name');
        $tabField = $request->input('tab_field');
        $tabId = $request->input('tab_id');
        $result = DB::table($tabName)->where('id', $tabId)->first();
        if($result) {
            $imageName = $result->$tabField;
            DB::table($tabName)->where('id', $tabId)->update([$tabField => null]);
            Helper::unlinkFiles($tabName, $imageName);
            return response()->json(['is_success' => true]);
        }
        return response()->json(['is_success' => false]);
    }

    public function getUsers(Request $request)
    {
        $dataBag = [];

        $perPage = 10;
        $search = $request->get('search');
        $roleId = $request->get('role_id');
        $page = $request->get('page');
        $offset = ($page - 1) * $perPage;
        
        $authId = Auth::user()->id;
        $authRoles = Helper::authRoles();
        
        $data = User::select(
                'users.id as user_id',
                'users.hash_id as hash_id',
                DB::raw("CONCAT(`users`.`first_name`, ' ', `users`.`last_name`) as name"),
                'users.first_name as fname',
                'users.last_name as lname',
                'users.unique_id as unique_id',
                'users.login_id as login_id',
                'users.phone_number as phone_number'
            )
            ->with(['userRoles'])
            ->whereHas('userRoles', function($q) use($roleId) {
                $q->where('role_id', $roleId);
            })
            ->where('id', '!=', $authId)
            ->where('status', 1)
            ->where(function($q) use ($search) {
                $q->where(DB::raw("CONCAT(`users`.`first_name`, ' ', `users`.`last_name`)"), 'LIKE', '%' . $search . '%');
                $q->orWhere('unique_id', 'LIKE', '%' . $search . '%');
                $q->orWhere('login_id', 'LIKE', '%' . $search . '%');
                $q->orWhere('phone_number', 'LIKE', '%' . $search . '%');
            })
            ->skip($offset)
            ->take($perPage)
            ->orderBy('first_name', 'asc')
            ->get();

        $dataCount = User::select(
                'users.id as user_id'
            )
            ->with(['userRoles'])
            ->whereHas('userRoles', function($q) use($roleId) {
                $q->where('role_id', $roleId);
            })
            ->where('id', '!=', $authId)
            ->where('status', 1)
            ->where(function($q) use ($search) {
                $q->where(DB::raw("CONCAT(`users`.`first_name`, ' ', `users`.`last_name`)"), 'LIKE', '%' . $search . '%');
                $q->orWhere('users.unique_id', 'LIKE', '%' . $search . '%');
                $q->orWhere('users.login_id', 'LIKE', '%' . $search . '%');
                $q->orWhere('users.phone_number', 'LIKE', '%' . $search . '%');
            })
            ->count();
        
        $dataBag['restlt_data'] = $data;
        $dataBag['pagination'] = ['more' => true];
        $dataBag['data_count'] = $dataCount;
        return response()->json($dataBag);
    }

    public function getProducts(Request $request)
    {
        $dataBag = [];

        $perPage = 10;
        $search = $request->get('search');
        $page = $request->get('page');
        $offset = ($page - 1) * $perPage;
        
        $data = ProductVariants::select(
                'product_variants.id as variant_id',
                'product_variants.product_id as base_product_id',
                'product_variants.brand_id as product_brand_id',
                'brands.name as brand_name',
                'product_variants.name as variant_product_name',
                'product_variants.image as variant_product_image',
                'product_variants.price as variant_product_price',
                'unit_master.name as unit_name',
                'unit_master.short_name as unit_short_name'
            )
            ->join('brands', 'product_variants.brand_id', '=', 'brands.id')
            ->join('unit_master', 'product_variants.unit_id', '=', 'unit_master.id')
            ->where('product_variants.status', 1)
            ->where(function($q) use ($search) {
                $q->where('product_variants.name', 'LIKE', '%' . $search . '%');
                $q->orWhere('brands.name', 'LIKE', '%' . $search . '%');
                $q->orWhere('unit_master.name', 'LIKE', '%' . $search . '%');
                $q->orWhere('unit_master.short_name', 'LIKE', '%' . $search . '%');
            })
            ->skip($offset)
            ->take($perPage)
            ->orderBy('product_variants.name', 'asc')
            ->get();

        $dataCount = ProductVariants::select(
                'product_variants.id as variant_id'
            )
            ->join('brands', 'product_variants.brand_id', '=', 'brands.id')
            ->join('unit_master', 'product_variants.unit_id', '=', 'unit_master.id')
            ->where('product_variants.status', 1)
            ->where(function($q) use ($search) {
                $q->where('product_variants.name', 'LIKE', '%' . $search . '%');
                $q->orWhere('brands.name', 'LIKE', '%' . $search . '%');
            })
            ->count();
        
        $dataBag['restlt_data'] = $data;
        $dataBag['pagination'] = ['more' => true];
        $dataBag['data_count'] = $dataCount;
        $dataBag['product_image_path'] = asset('public/uploads/images/products/thumbnail');
        return response()->json($dataBag);
    }
}
