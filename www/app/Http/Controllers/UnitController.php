<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Unit;
use Session;
use Hash;
use Auth;
use DB;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'units';
        $dataBag['all_units'] = Unit::with(['subUnit' => function ($qry) {
            $qry->select('id', 'name', 'short_name');
        }])->where('status', '!=', 3)->orderBy('name', 'asc')->get();
        return view('backend.unit.index', $dataBag);
    }

    public function addUnit(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'units';
        $dataBag['all_units'] = Unit::select('id','name','short_name')->where('status', '!=', 3)->orderBy('name', 'asc')->get();
        return view('backend.unit.add', $dataBag);
    }

    public function saveUnit(Request $request)
    {
        $name = $request->input('name');
        $shortName = str_replace(' ', '-', $request->input('short_name'));
        $checkName = Unit::where('name', $name)->where('status', '!=', 3)->exists();
        $checkShortName = Unit::where('short_name', $shortName)->where('status', '!=', 3)->exists();

        if ($checkName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Unit name is already exist');
        }
        if ($checkShortName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Unit short name is already exist');
        }

        $unit = new Unit();
        $unit->name = $name;
        $unit->short_name = $shortName;
        $unit->description = $request->input('description');
        $unit->child_unit_value = !empty($request->child_unit_value) ? $request->child_unit_value : null;
        $unit->child_unit_id = !empty($request->child_unit_id) ? $request->child_unit_id : null;
        if ($unit->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'New unit has been created successfully');
        }

        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function deleteUnit(Request $request, $id)
    {
        $obj = Unit::findOrFail($id);
        $obj->status = 3;
        $obj->save();
        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'Unit has been deleted successfully');
    }

    public function editUnit(Request $request, $id)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'units';
        $dataBag['unit'] = Unit::findOrFail($id);
        $dataBag['all_units'] = Unit::select('id','name','short_name')->where('status', '!=', 3)->orderBy('name', 'asc')->get();
        return view('backend.unit.edit', $dataBag);
    }

    public function updateUnit(Request $request, $id)
    {
        $name = $request->input('name');
        $shortName = str_replace(' ', '-', $request->input('short_name'));
        $checkName = Unit::where('name', $name)->where('status', '!=', 3)->where('id', '!=', $id)->exists();
        $checkShortName = Unit::where('short_name', $shortName)->where('status', '!=', 3)->where('id', '!=', $id)->exists();

        if ($checkName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Unit name is already exist');
        }
        if ($checkShortName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Unit short name is already exist');
        }

        $unit = Unit::findOrFail($id);
        $unit->name = $name;
        $unit->short_name = $shortName;
        $unit->status = $request->input('status');
        $unit->description = $request->input('description');
        $unit->child_unit_value = !empty($request->child_unit_value) ? $request->child_unit_value : null;
        $unit->child_unit_id = !empty($request->child_unit_id) ? $request->child_unit_id : null;
        if ($unit->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Unit has been updated successfully');
        }

        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }
}
