<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Session;
use Hash;
use Auth;
use DB;

class UserSigninController extends Controller
{
    public function index(Request $request)
    {
        $dataBag = [];
        return view('backend.signin.index', $dataBag);
    }

    public function signin(Request $request)
    {
        $loginId = $request->input('login_id');
        $loginPassword = $request->input('login_password');
        $query = User::where('email_id', $loginId)
            ->orWhere('phone_number', $loginId)
            ->orWhere('user_name', $loginId)
            ->first();
        
        if (empty($query)) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Oops!')
                ->with('message_text', 'Wrong login id');
        }

        if ($query->is_crm_access != 1) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Oops!')
                ->with('message_text', 'Sorry! Access Denied');
        }

        if ($query->status != 1) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Your account has been inactivated');
        }

        if (!Hash::check($loginPassword, $query->password)) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Oops!')
                ->with('message_text', 'Incorrect password');
        }
        
        Auth::login($query);
        return redirect()
            ->route('dashboard.index')
            ->with('message_type', 'success')
            ->with('message_title', 'Hey, hi! ' . $query->first_name)
            ->with('message_text', 'Welcome to your account');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        Auth::logout();
        return redirect()
            ->route('signin.index')
            ->with('message_type', 'success')
            ->with('message_title', 'Hi! ' . $user->first_name)
            ->with('message_text', 'You have securly logged out');
    }
}
