<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstallAuth
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
        $DB_HOST = env('DB_HOST');
        $DB_DATABASE = env('DB_DATABASE');
        $DB_USERNAME = env('DB_USERNAME');
        $DB_PASSWORD = env('DB_PASSWORD');
        if(isset($DB_HOST) && $DB_HOST != '' && isset($DB_DATABASE) && $DB_DATABASE != '' && isset($DB_USERNAME) && $DB_USERNAME != '' && isset($DB_PASSWORD)) {
            $api_data = DB::table('api')->get();
            if(count($api_data) > 0) {
                $api = DB::table('api')->first();
                if($api->api_email != null && $api->api_key != null) {

                } else {
                    return redirect('/install-app');
                }
            }
        } else {
            return redirect('/install-app');
        }
        return $next($request);
    }
}
