<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstallAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $admin_data = DB::table('admin')->first();
        if($admin_data->username != null && $admin_data->password != null) {
            
        } else {
            return redirect('/admin-configuration');
        }
        return $next($request);
    }
}