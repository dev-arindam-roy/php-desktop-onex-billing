<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Brand;
use Session;
use Hash;
use Auth;
use DB;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'brands';
        $dataBag['all_brands'] = Brand::where('status', '!=', 3)->orderBy('name', 'asc')->get();
        return view('backend.brand.index', $dataBag);
    }

    public function addBrand(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'brands';
        return view('backend.brand.add', $dataBag);
    }

    public function saveBrand(Request $request)
    {
        $name = $request->input('name');
        $checkName = Brand::where('name', $name)->where('status', '!=', 3)->exists();

        if ($checkName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Brand name is already exist');
        }

        $obj = new Brand();
        $obj->name = $name;
        $obj->description = $request->input('description');
        $obj->status = $request->input('status');
        if ($obj->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'New brand has been created successfully');
        }

        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function deleteBrand(Request $request, $id)
    {
        $obj = Brand::findOrFail($id);
        $obj->status = 3;
        $obj->save();
        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'Brand has been deleted successfully');
    }

    public function editBrand(Request $request, $id)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'brands';
        $dataBag['brand'] = Brand::findOrFail($id);
        return view('backend.brand.edit', $dataBag);
    }

    public function updateBrand(Request $request, $id)
    {
        $name = $request->input('name');
        $checkName = Brand::where('name', $name)->where('status', '!=', 3)->where('id', '!=', $id)->exists();

        if ($checkName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Brand name is already exist');
        }

        $obj = Brand::findOrFail($id);
        $obj->name = $name;
        $obj->status = $request->input('status');
        $obj->description = $request->input('description');
        if ($obj->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Brand has been updated successfully');
        }

        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }
}
