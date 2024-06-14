<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Session;
use Hash;
use Auth;
use DB;

class CrmSettingsController extends Controller
{
    public function index(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'settings';
        $dataBag['sidebar_child'] = 'crm';
        $dataBag['crm_settings'] = DB::table('crm_settings')->first();
        return view('backend.crm.index', $dataBag);
    }

    public function saveChanges(Request $request)
    {
        DB::table('crm_settings')->where('id', 1)->update([
            'name' => $request->input('name'),
            'list_per_page' => $request->input('list_per_page') ?? 25
        ]);
        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'CRM settings has been changed successfully');
    }
}
