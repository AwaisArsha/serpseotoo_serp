<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

function prx($arr)
{
    echo "<pre>";
    print_r($arr);
    die;
}

function total_backlinks($domain_id)
{
    $backlink = DB::table('backlinks')->where('domain_id', $domain_id)->first();
    if($backlink) {
        return $backlink->total_count;
    } else {
        return 0;
    }
}

function spam_score($domain_id)
{
    $UserPackageInfo = UserPackageInfo();
    $backlinks_lost_limit = round($UserPackageInfo->domain_backlinks_rows_limit * 0.3);
    $lost_backlinks = DB::table('backlinks')->where('user_id', Session::get('user_id'))->where('domain_id', $domain_id)->where('is_new', 1)->orWhere('is_lost', 1)->limit($backlinks_lost_limit)->get();
    $remaining_limit = $UserPackageInfo->domain_backlinks_rows_limit - (count($lost_backlinks));
    $remaining_backlinks = DB::table('backlinks')->where('user_id', Session::get('user_id'))->where('domain_id', $domain_id)->limit($remaining_limit)->get();

    $total_spam_score = 0;
    foreach($lost_backlinks as $backlink) {
        $total_spam_score += $backlink->spam_score;
    }
        
    foreach($remaining_backlinks as $backlink) {
        $total_spam_score += $backlink->spam_score;
    }

    $total_backlinks = 0;
    $total_backlinks = count($lost_backlinks)+ count($remaining_backlinks);
    if($total_spam_score > 0 && $total_backlinks > 0) {
        $final_spam_score = round($total_spam_score/(count($lost_backlinks) + count($remaining_backlinks)), 1);
    } else {
        $final_spam_score = 0;
    }
    return $final_spam_score;
}

function settings_data()
{
    $settings = DB::table('basic_settings')->first();
    return $settings;
}

function usefull_links()
{
    $usefull_links = DB::table('usefull_links')->where('status', 1)->orderBy('id', 'ASC')->get();
    return $usefull_links;
}

function recent_blogs()
{
    $recent_blog = DB::table('blogs')->where('status', 1)->where('purpose', 'blog')->orderBy('id', 'DESC')->limit(3)->get();
    return $recent_blog;
}

function domain_keywords_count($id)
{
    $count = 0;
    $domain_keywords = DB::table('domain_keywords')->where('domain_id', $id)->get();
    foreach ($domain_keywords as $keyword) {
        if ($keyword->platform == "desktop and mobile") {
            $count += 2;
        } else {
            $count++;
        }
    }
    return $count;
}

function all_domain_keywords_count()
{
    $count = 0;
    $domains = DB::table('domains')->where('user_id', Session::get('user_id'))->where('status', 1)->get();
    foreach ($domains as $dom) {
        $domain_keywords = DB::table('domain_keywords')->where('domain_id', $dom->id)->get();
        foreach ($domain_keywords as $keyword) {
            if ($keyword->platform == "desktop and mobile") {
                $count += 2;
            } else {
                $count++;
            }
        }
    }
    return $count;
}

function all_keywords_count()
{
    $all_domain_keywords = all_domain_keywords_count();
    $all_volume_keywords = DB::table('google_adwords_search_volume')->where(
        'user_id',
        Session::get('user_id')
    )->groupBy('search_volume_id')->get();
    $all_related_keywords = DB::table('related_keywords_data')->where(
        'user_id',
        Session::get('user_id')
    )->groupBy('related_keywords_id')->get();
    $keywords_count = $all_domain_keywords + count($all_volume_keywords) + count($all_related_keywords);
    return $keywords_count;
}


function domain_competitors_count($id)
{
    $domain_competitors = DB::table('competitors')->where('domain_id', $id)->get();
    return count($domain_competitors);
}

function UserPackageInfo()
{
    if (Session::has('user_id')) {
        $user_info = DB::table('users')->where('id', Session::get('user_id'))->first();
        $package_info = DB::table('packages')->where('id', $user_info->package_id)->first();
        return $package_info;
    } else {
        return redirect('/login-register');
    }
}

function remaining_refreshes($id = null)
{
    $percent = null;
    // $date = date('Y-m-d');
    $today_refreshes = 0;
    // $refreshes = DB::table('user_refreshes')->where('user_id', Session::get('user_id'))->where('date', $date)->get();
    if(isset($id) && $id!= null && $id!= 0) {
        $refreshes = DB::table('user_refreshes')->where('user_id', $id)->get();
    } else {    
        $refreshes = DB::table('user_refreshes')->where('user_id', Session::get('user_id'))->get();
    }

    foreach ($refreshes as $ref) {
        $day = date("d",strtotime($ref->date));
        $month = date("m",strtotime($ref->date));
        $year = date("Y",strtotime($ref->date));
        if($day == date('d') && $month == date('m') && $year == date('Y')) {
            if ($ref->keyword_platform == "desktop and mobile") {
                $today_refreshes += 2;
            } else {
                $today_refreshes++;
            }
        }
    }
    if(isset($id) && $id!= null && $id!= 0) {
        $user_info = DB::table('users')->where('id', $id)->first();
    } else {
        $user_info = DB::table('users')->where('id', Session::get('user_id'))->first();
    }
    $package_info = DB::table('packages')->where('id', $user_info->package_id)->first();
    if($package_info->keywords_limit == 'Unlimited') {
        return 'Unlimited';
    }

    if ($today_refreshes != null && $today_refreshes > 0) {
        $percent = $package_info->keywords_limit - $today_refreshes;
        if($percent < 0) {
            $percent = 0;
        }
    } else {
        $percent = $package_info->keywords_limit;
    }
    $string = $percent.'/'.$package_info->keywords_limit;
    return $string;
}

function total_backlinks_refreshes($id = null)
{
    $today_refreshes = 0;
    if(isset($id) && $id!= null && $id!= 0) {
        $refreshes = DB::table('user_backlinks_refreshes')->where('user_id', $id)->get();
    } else {    
        $refreshes = DB::table('user_backlinks_refreshes')->where('user_id', Session::get('user_id'))->get();
    }

    foreach ($refreshes as $ref) {
        $day = date("d",strtotime($ref->date));
        $month = date("m",strtotime($ref->date));
        $year = date("Y",strtotime($ref->date));
        if($day == date('d') && $month == date('m') && $year == date('Y')) {
            $today_refreshes++;
        }
    }
    return $today_refreshes;
}

function remaining_backlinks_refreshes($id = null)
{
    $percent = null;
    // $date = date('Y-m-d');
    $today_refreshes = 0;
    // $refreshes = DB::table('user_refreshes')->where('user_id', Session::get('user_id'))->where('date', $date)->get();
    if(isset($id) && $id!= null && $id!= 0) {
        $refreshes = DB::table('user_backlinks_refreshes')->where('user_id', $id)->get();
    } else {    
        $refreshes = DB::table('user_backlinks_refreshes')->where('user_id', Session::get('user_id'))->get();
    }

    foreach ($refreshes as $ref) {
        $day = date("d",strtotime($ref->date));
        $month = date("m",strtotime($ref->date));
        $year = date("Y",strtotime($ref->date));
        if($day == date('d') && $month == date('m') && $year == date('Y')) {
            $today_refreshes++;
        }
    }
    if(isset($id) && $id!= null && $id!= 0) {
        $user_info = DB::table('users')->where('id', $id)->first();
    } else {
        $user_info = DB::table('users')->where('id', Session::get('user_id'))->first();
    }
    $package_info = DB::table('packages')->where('id', $user_info->package_id)->first();
    if($package_info->backlinks_workload_limit == 'Unlimited') {
        return 'Unlimited';
    }

    // $all_keywords_count = $package_info->backlinks_workload_limit;
    if ($today_refreshes != null && $today_refreshes > 0) {
        // if ($all_keywords_count > 100) {
        //     $all_keywords_count = 100;
        // }
        // $done_refrehes = ($today_refreshes / $all_keywords_count) * 100;
        // $percent = round(100 - $done_refrehes);
        $percent = $package_info->backlinks_workload_limit - $today_refreshes;
        if($percent < 0) {
            $percent = 0;
        }
    } else {
        $percent = $package_info->backlinks_workload_limit;
    }
    $string = $percent.'/'.$package_info->backlinks_workload_limit;
    return $string;
}

function backlinks_refreshes_exists($id = null)
{
    $today_refreshes = 0;
    if(isset($id) && $id!= null && $id!= 0) {
        $refreshes = DB::table('user_backlinks_refreshes')->where('user_id', $id)->get();
    } else {    
        $refreshes = DB::table('user_backlinks_refreshes')->where('user_id', Session::get('user_id'))->get();
    }

    foreach ($refreshes as $ref) {
        $day = date("d",strtotime($ref->date));
        $month = date("m",strtotime($ref->date));
        $year = date("Y",strtotime($ref->date));
        if($day == date('d') && $month == date('m') && $year == date('Y')) {
            $today_refreshes++;
        }
    }
    if(isset($id) && $id!= null && $id!= 0) {
        $user_info = DB::table('users')->where('id', $id)->first();
    } else {
        $user_info = DB::table('users')->where('id', Session::get('user_id'))->first();
    }
    $package_info = DB::table('packages')->where('id', $user_info->package_id)->first();
    if($package_info->keywords_limit == 'Unlimited') {
        return true;
    }

    if ($today_refreshes != null && $today_refreshes > 0) {
        $percent = $package_info->backlinks_workload_limit - $today_refreshes;
        if($percent > 0) {
            return true;
        }
    } else if( $package_info->backlinks_workload_limit > 0){
        return true;
    } else {
        return false;
    }
}

function refreshes($id = null)
{
    $percent = null;
    // $date = date('Y-m-d');
    $today_refreshes = 0;
    if(isset($id) && $id!= null && $id!= 0) {
        $refreshes = DB::table('user_refreshes')->where('user_id', $id)->get();
    } else {
        $refreshes = DB::table('user_refreshes')->where('user_id', Session::get('user_id'))->get();
    }
    // $refreshes = DB::table('user_refreshes')->where('user_id', Session::get('user_id'))->where('date', $date)->get();
    foreach ($refreshes as $ref) {
        $day = date("d",strtotime($ref->date));
        $month = date("m",strtotime($ref->date));
        $year = date("Y",strtotime($ref->date));
        if($day == date('d') && $month == date('m') && $year == date('Y')) {
            if ($ref->keyword_platform == "desktop and mobile") {
                $today_refreshes += 2;
            } else {
                $today_refreshes++;
            }
        }
    }
    if(isset($id) && $id!= null && $id!= 0) {
        $user_info = DB::table('users')->where('id', $id)->first();
    } else {
        $user_info = DB::table('users')->where('id', Session::get('user_id'))->first();
    }
    $package_info = DB::table('packages')->where('id', $user_info->package_id)->first();
    if($package_info->keywords_limit == 'Unlimited') {
        return 1000;
    }

    // $all_keywords_count = $package_info->keywords_limit;
    if ($today_refreshes != null && $today_refreshes > 0) {
        // if ($all_keywords_count > 100) {
        //     $all_keywords_count = 100;
        // }
        // $done_refrehes = ($today_refreshes / $all_keywords_count) * 100;
        // $percent = round(100 - $done_refrehes);
        $percent = $package_info->keywords_limit - $today_refreshes;
        if($percent < 0) {
            $percent = 0;
        }
    } else {
        $percent = $package_info->keywords_limit;
    }
    return $percent;
}

function serp_keywords_count() {
    $count = 0;
    $serp_data = DB::table('serp_data')->where('user_id', Session::get('user_id'))->groupBy('serp_id')->orderBy('date', 'DESC')->get();
    foreach ($serp_data as $ref) {
        $month = date("m",strtotime($ref->date));
        $year = date("Y",strtotime($ref->date));
        if($month == date('m') && $year == date('Y')) {
            $count++;
        }
    }
    return $count;
}

function volume_keywords_count() {
    $count = 0;
    $all_volume_keywords = DB::table('google_adwords_search_volume')->where(
        'user_id',
        Session::get('user_id')
    )->groupBy('search_volume_id')->get();
    foreach ($all_volume_keywords as $ref) {
        $month = date("m",strtotime($ref->date));
        $year = date("Y",strtotime($ref->date));
        if($month == date('m') && $year == date('Y')) {
            $count++;
        }
    }
    return $count;
}

function keyword_planner_keywords_count($id = null) {
    $count = 0;
    if(isset($id) && $id!= null && $id!= 0) {
        $all_keywords = DB::table('related_keywords_data')->where('user_id', $id)->groupBy('related_keywords_id')->get();
    } else {    
        $all_keywords = DB::table('related_keywords_data')->where('user_id', Session::get('user_id'))->groupBy('related_keywords_id')->get();
    }

    foreach ($all_keywords as $ref) {
        $month = date("m",strtotime($ref->date));
        $year = date("Y",strtotime($ref->date));
        if($month == date('m') && $year == date('Y')) {
            $count++;
        }
    }
    return $count;
}

function get_currency () {
    $result = DB::table('basic_settings')->where('id',1)->first();
    Session::put('currency', $result->currency);
}

function check_subscribed() {
    $user_info = DB::table('users')->where('id', Session::get('user_id'))->first();
    if($user_info->payment == 1 && $user_info->status == 1) {
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
        if($package_expiry_date < date('Y-m-d H:i:s')) {
            return false;
        } else {
            return true;
        }
    }
}