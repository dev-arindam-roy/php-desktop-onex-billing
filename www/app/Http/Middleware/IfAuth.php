<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Session;
use Auth;
use DB;

class IfAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $loggedInUserRoles = [];
            if (!empty(Auth::user()->userRoles)) {
                foreach (Auth::user()->userRoles as $eachRole) {
                    if (!empty($eachRole->role) && !empty($eachRole->role->key_name)) {
                        array_push($loggedInUserRoles, $eachRole->role->key_name);    
                    }
                }
            }
            if (empty($loggedInUserRoles)) {
                abort('403', 'Access Denied');
            }
            if (in_array('customer', $loggedInUserRoles)) {
                abort('403', 'Access Denied');
            }
            $theme = DB::table('theme_settings')->where('is_active', 1)->first();
            $crm = DB::table('crm_settings')->where('id', 1)->first();
            $defaultShareData = [];
            $defaultShareData['theme'] = $theme;
            $defaultShareData['crm'] = $crm;
            $request->request->add(['pagination' => $crm->list_per_page]);
            View::share('defaultShareData', $defaultShareData);
            $response = $next($request);
            return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
                ->header('Pragma','no-cache')
                ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        } else {
            return redirect()
                ->route('signin.index')
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'UnAuthorized Access');
        }
    }
}
