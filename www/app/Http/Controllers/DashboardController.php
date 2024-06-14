<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Products;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Unit;
use Session;
use Hash;
use Auth;
use DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = '';
        $dataBag['sidebar_child'] = 'dashboard';
        return view('backend.dashboard.index', $dataBag);
    }
}
