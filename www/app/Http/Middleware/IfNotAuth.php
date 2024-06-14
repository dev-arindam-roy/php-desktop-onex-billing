<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Session;
use Auth;
use DB;

class IfNotAuth
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
            return redirect()
                ->route('dashboard.index');
        } else {
            $theme = DB::table('theme_settings')->where('is_active', 1)->first();
            $crm = DB::table('crm_settings')->where('id', 1)->first();
            $defaultShareData = [];
            $defaultShareData['theme'] = $theme;
            $defaultShareData['crm'] = $crm;
            View::share('defaultShareData', $defaultShareData);
            $response = $next($request);
            return $response
                ->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
                ->header('Pragma','no-cache')
                ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        }
    }
}
