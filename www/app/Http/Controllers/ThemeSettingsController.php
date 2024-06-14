<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Session;
use Hash;
use Auth;
use DB;

class ThemeSettingsController extends Controller
{
    public function index(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'settings';
        $dataBag['sidebar_child'] = 'theme';
        $dataBag['theme_settings'] = DB::table('theme_settings')->where('status', 1)->get();
        return view('backend.theme.index', $dataBag);
    }

    public function saveChanges(Request $request)
    {
        DB::table('theme_settings')->where('id', $request->input('name'))->update(['is_active' => 1]);
        DB::table('theme_settings')->where('id', '!=', $request->input('name'))->update(['is_active' => 0]);
        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'Theme settings has been changed successfully');
    }
}
