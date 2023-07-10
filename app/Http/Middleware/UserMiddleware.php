<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserMiddleware
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
        if($request->session()->has('user_id') && $request->session()->has('user_email') && $request->session()->has('user_name')) {
            $user_info = DB::table('users')->where('id', $request->session()->get('user_id'))->first();
          
            if($user_info->payment == 1 && $user_info->status == 1) {
              if($user_info->subscription == 0 && $user_info->subscription != null) {
                    return redirect('/re-subscribe');
                }
                $package_info = DB::table('packages')->where('id', $user_info->package_id)->first();
              
                if($package_info->subscription == "monthly") {
                    $days = "30 days";
                } else if($package_info->subscription == "yearly") {
                    $days = "365 days";
                } else {
                    $days = $package_info->subscription." days";
                }
                $package_expiry_date = date_add(date_create($user_info->payment_date), date_interval_create_from_date_string($days));
              

                if($user_info->expire_date != null) {
                    $package_expiry_date = $user_info->expire_date;
                }
                //echo $package_expiry_date;
              //echo "<pre>";
              //print_r(date('Y-m-d H:i:s'));
              //die;
                if($package_expiry_date < date('Y-m-d H:i:s')) {
                   // $request->session()->forget('user_id');
                   // $request->session()->forget('user_email');
                   // $request->session()->forget('user_name');
                   // $request->session()->flash('error','Your subscription has been expired');
                  //echo 'pricing';
                  //die;
                  return redirect('/user/subscription');
                   // return redirect('/pricing');
                } else {
                 // echo 'else';
                  //die;

                }
            } else {
                $request->session()->forget('user_id');
                $request->session()->forget('user_email');
                $request->session()->forget('user_name');

                $request->session()->flash('message', 'Access Denied');
                $request->session()->flash('alert-type', 'error');
                return redirect('/pricing');    
            }
        } else {
            $request->session()->forget('user_id');
            $request->session()->forget('user_email');
            $request->session()->forget('user_name');
    
            $request->session()->flash('message', 'Access Denied');
            $request->session()->flash('alert-type', 'error');
            return redirect('/login-register');
        }
        return $next($request);
    }
}
