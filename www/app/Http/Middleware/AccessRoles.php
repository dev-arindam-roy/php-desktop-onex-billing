<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class AccessRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roles = null)
    {
        if (!Auth::check()) {
            abort('403', 'Access Denied');
        }
        $explodeRoles = [];
        if (!empty($roles)) {
            $explodeRoles = explode('|', $roles);
        }
        if (empty($explodeRoles)) {
            abort('403', 'Access Denied');
        }
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
        $isAccessGranted = false;
        foreach ($loggedInUserRoles as $v) {
            if (in_array($v, $explodeRoles)) {
                $isAccessGranted = true;
            }
        }
        if (!$isAccessGranted) {
            abort('403', 'Access Denied');
        }
        return $next($request);
    }
}
