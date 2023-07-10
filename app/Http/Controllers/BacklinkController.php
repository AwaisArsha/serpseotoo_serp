<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;


class BacklinkController extends Controller
{
    public function BacklinksDashboard()
    {
        $locations = DB::table('serp_google_locations')->where('status', 1)->orderBy('location_name', 'ASC')->get();
        $languages = DB::table('serp_google_languages')->where('status', 1)->orderBy('language_name', 'ASC')->get();
        $domains = DB::table('backlinks_domains')->where('user_id', Session::get('user_id'))->where('status',1)->orderBy('domain', 'ASC')->get();
        return view('seo.backlinks-dashboard', compact('locations', 'languages', 'domains'));
    }

    public function AddBacklink(Request $request)
    {
        // prx($request->post());
        $dataforseo_api = DB::table('api')->where('id', 1)->first();
        $current_user_data = DB::table('users')->where('id', Session::get('user_id'))->first();
        $user_package_data = DB::table('packages')->where('id', $current_user_data->package_id)->first();
        $domain_www_validation = substr($request->domain, 0, 4);
        $domain_http_validation = substr($request->domain, 0, 7);
        $domain_https_validation = substr($request->domain, 0, 8);
        $domain_httpwww_validation = substr($request->domain, 0, 11);
        $domain_httpswww_validation = substr($request->domain, 0, 12);
        if ($domain_httpswww_validation == "https://www.") {
            $request->domain = substr($request->domain, 12);
        } else if ($domain_httpwww_validation == "http://www.") {
            $request->domain = substr($request->domain, 11);
        } else if ($domain_www_validation == "www.") {
            $request->domain = substr($request->domain, 4);
        } elseif ($domain_http_validation == "http://") {
            $request->domain = substr($request->domain, 7);
        } elseif ($domain_https_validation == "https://") {
            $request->domain = substr($request->domain, 8);
        }

        $date = date('Y-m-d H:i:s');
        $domain_id = null;

        // prx($keywords);

        if(!backlinks_refreshes_exists()) {
            $request->session()->flash('message', 'Backlinks Workload Limit Reached.');
            $request->session()->flash('alert-type', 'error');
            return redirect()->back();
        }

        $domain_id = DB::table('backlinks_domains')->insertGetId([
            'user_id'   => $request->session()->get('user_id'),
            'domain'    =>  $request->domain,
            'date'          => $date,
        ]);

        DB::table('user_backlinks_refreshes')->insert([
            'user_id'   =>  Session::get('user_id'),
            'domain_id' =>  $domain_id,
            'date'  =>  date('Y-m-d H:i:s')
        ]);

        set_time_limit(2500);

        $api_url = 'https://api.dataforseo.com/';
        try {
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        } catch (RestClientException $e) {
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print "Message: {$e->getMessage()}\n";
            print  $e->getTraceAsString();
            echo "\n";
            exit();
        }


        //Backlinks Start
        if ($user_package_data->backlinks == 1) {
            $api_url = 'https://api.dataforseo.com/';
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);

            $post_array = array();
            $post_array[] = array(
                "target" => $request->domain,
                "limit" => 25,
                "internal_list_limit" => 10,
            );
            try {
                $result = $client->post('/v3/backlinks/anchors/live', $post_array);
                // prx($result);

                if ($result['status_message'] == "Ok.") {
                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                        $all_anchors = $result['tasks'][0]['result'][0]['items'];
                        if (isset($all_anchors) && count($all_anchors) > 0) {
                            foreach ($all_anchors as $anchor) {
                                // prx($anchor);
                                DB::table('anchors')->insert([
                                    'user_id'   =>  Session::get('user_id'),
                                    'domain_id'   =>  $domain_id,
                                    'anchor'    =>  $anchor['anchor'],
                                    'count'     =>  $anchor['referring_domains']
                                ]);
                            }
                        }
                    }
                }
                // do something with post result
            } catch (RestClientException $e) {
                echo "n";
                print "HTTP code: {$e->getHttpCode()}n";
                print "Error code: {$e->getCode()}n";
                print "Message: {$e->getMessage()}n";
                print  $e->getTraceAsString();
                echo "n";
            }
            $client = null;



            $api_url = 'https://api.dataforseo.com/';
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            $post_array = array();
            $post_array[] = array(
                "target" => $request->domain,
                "limit" => 100,
                "internal_list_limit" => 10,
            );
            try {
                $result = $client->post('/v3/backlinks/history/live', $post_array);
                // prx($result);

                if ($result['status_message'] == "Ok.") {
                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                        $all_results = $result['tasks'][0]['result'][0]['items'];
                        if (isset($all_results) && count($all_results) > 0) {
                            foreach ($all_results as $res) {
                                // prx($anchor);
                                DB::table('backlinks_history')->insert([
                                    'user_id'   =>  Session::get('user_id'),
                                    'domain_id'   =>  $domain_id,
                                    'backlinks_count'    =>  $res['backlinks'],
                                    'new_backlinks'    =>  $res['new_backlinks'],
                                    'lost_backlinks'    =>  $res['lost_backlinks'],
                                    'anchors_count'     =>  $res['referring_links_types']['anchor'],
                                    'date'      =>  $res['date']
                                ]);
                            }
                        }
                    }
                }
                // do something with post result
            } catch (RestClientException $e) {
                echo "n";
                print "HTTP code: {$e->getHttpCode()}n";
                print "Error code: {$e->getCode()}n";
                print "Message: {$e->getMessage()}n";
                print  $e->getTraceAsString();
                echo "n";
            }
            $client = null;


            $api_url = 'https://api.dataforseo.com/';
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            $post_array = array();
            $post_array[] = array(
                "target" => $request->domain,
                "limit" =>  50,
                'backlinks_status_type' =>  'all',
                "mode" => "as_is",
                "filters" => ["dofollow", "=", false]
            );
            try {
                $result = $client->post('/v3/backlinks/backlinks/live', $post_array);
                if ($result['status_message'] == "Ok.") {
                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                        $total_count = $result['tasks'][0]['result'][0]['total_count'];
                        $all_backlinks = $result['tasks'][0]['result'][0]['items'];
                        if (isset($all_backlinks) && count($all_backlinks) > 0) {
                            foreach ($all_backlinks as $backlink) {
                                $already_exists = DB::table('backlinks')->where('domain_id', $domain_id)->where('user_id', Session::get('user_id'))->where('url_from', $backlink['url_from'])->first();
                                if (!$already_exists) {
                                    DB::table('backlinks')->insert([
                                        'user_id'   =>  Session::get('user_id'),
                                        'domain_id'   =>  $domain_id,
                                        'total_count'   =>  $total_count,
                                        'url_from'   =>  $backlink['url_from'],
                                        'title'   =>  $backlink['page_from_title'],
                                        'domain_to'   =>  $backlink['url_to'],
                                        'spam_score'   =>  $backlink['backlink_spam_score'],
                                        'is_new'   =>  $backlink['is_new'],
                                        'is_lost'   =>  $backlink['is_lost'],
                                        'do_follow'   =>  $backlink['dofollow'],
                                        'p_a'   =>  $backlink['page_from_rank'],
                                        'd_a'   =>  $backlink['domain_from_rank'],
                                        'domain_from_rank'  =>  $backlink['domain_from_rank'],
                                        'date'   =>  date('Y-m-d H:i:s')
                                    ]);
                                }
                            }
                        }
                    }
                }
            } catch (RestClientException $e) {
            }
            $client = null;





            $api_url = 'https://api.dataforseo.com/';
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            $post_arrayy = array();
            if ($user_package_data->domain_backlinks_rows_limit == "Unlimited") {
                $post_arrayy[] = array(
                    "target" => $request->domain,
                    "limit" =>  1000,
                    "mode" => "as_is",
                    'backlinks_status_type' =>  'all',
                );
            } else {
                $post_arrayy[] = array(
                    "target" => $request->domain,
                    "limit" =>  $user_package_data->domain_backlinks_rows_limit,
                    "mode" => "as_is",
                    'backlinks_status_type' =>  'all'
                );
            }
            try {
                $result = $client->post('/v3/backlinks/backlinks/live', $post_arrayy);
                if ($result['status_message'] == "Ok.") {
                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                        $total_count = $result['tasks'][0]['result'][0]['total_count'];
                        $all_backlinks = $result['tasks'][0]['result'][0]['items'];
                        if (isset($all_backlinks) && count($all_backlinks) > 0) {
                            foreach ($all_backlinks as $backlink) {
                                $already_exists = DB::table('backlinks')->where('domain_id', $domain_id)->where('user_id', Session::get('user_id'))->where('url_from', $backlink['url_from'])->first();
                                if (!$already_exists) {
                                    DB::table('backlinks')->insert([
                                        'user_id'   =>  Session::get('user_id'),
                                        'domain_id'   =>  $domain_id,
                                        'total_count'   =>  $total_count,
                                        'url_from'   =>  $backlink['url_from'],
                                        'title'   =>  $backlink['page_from_title'],
                                        'domain_to'   =>  $backlink['url_to'],
                                        'spam_score'   =>  $backlink['backlink_spam_score'],
                                        'is_new'   =>  $backlink['is_new'],
                                        'is_lost'   =>  $backlink['is_lost'],
                                        'do_follow'   =>  $backlink['dofollow'],
                                        'p_a'   =>  $backlink['page_from_rank'],
                                        'd_a'   =>  $backlink['domain_from_rank'],
                                        'domain_from_rank'  =>  $backlink['domain_from_rank'],
                                        'date'   =>  date('Y-m-d H:i:s')
                                    ]);
                                }
                            }
                        }
                    }
                }
            } catch (RestClientException $e) {
            }
            $client = null;
        }

        return redirect()->back();
    }
    public function UserBacklinksRefresh($domain_id)
    {
        // prx($request->post());
        if(!backlinks_refreshes_exists()) {
            Session::flash('message', 'Backlinks Workload Limit Reached.');
            Session::flash('alert-type', 'error');
            return redirect()->back();
        }
        $dataforseo_api = DB::table('api')->where('id', 1)->first();
        $current_user_data = DB::table('users')->where('id', Session::get('user_id'))->first();
        $user_package_data = DB::table('packages')->where('id', $current_user_data->package_id)->first();

        $domain_info = DB::table('backlinks_domains')->where('id', $domain_id)->first();
        if(!$domain_info) {
            return;
        }
        $user_id = $domain_info->user_id;

        DB::table('backlinks_domains')->where('id', $domain_id)->update([
            'date'  =>  date('Y-m-d H:i:s')
        ]);
        DB::table('user_backlinks_refreshes')->insert([
            'user_id'   =>  $user_id,
            'domain_id' =>  $domain_id,
            'date'  =>  date('Y-m-d H:i:s')
        ]);
        
        DB::table('backlinks')->where('domain_id', $domain_id)->delete();
        DB::table('backlinks_history')->where('domain_id', $domain_id)->delete();
        DB::table('anchors')->where('domain_id', $domain_id)->delete();

        set_time_limit(2500);

        $api_url = 'https://api.dataforseo.com/';
        try {
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        } catch (RestClientException $e) {
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print "Message: {$e->getMessage()}\n";
            print  $e->getTraceAsString();
            echo "\n";
            exit();
        }


        //Backlinks Start
        if ($user_package_data->backlinks == 1) {
            $api_url = 'https://api.dataforseo.com/';
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);

            $post_array = array();
            $post_array[] = array(
                "target" => $domain_info->domain,
                "limit" => 25,
                "internal_list_limit" => 10,
            );
            try {
                $result = $client->post('/v3/backlinks/anchors/live', $post_array);
                // prx($result);

                if ($result['status_message'] == "Ok.") {
                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                        $all_anchors = $result['tasks'][0]['result'][0]['items'];
                        if (isset($all_anchors) && count($all_anchors) > 0) {
                            foreach ($all_anchors as $anchor) {
                                // prx($anchor);
                                DB::table('anchors')->insert([
                                    'user_id'   =>  $user_id,
                                    'domain_id'   =>  $domain_id,
                                    'anchor'    =>  $anchor['anchor'],
                                    'count'     =>  $anchor['referring_domains']
                                ]);
                            }
                        }
                    }
                }
                // do something with post result
            } catch (RestClientException $e) {
                echo "n";
                print "HTTP code: {$e->getHttpCode()}n";
                print "Error code: {$e->getCode()}n";
                print "Message: {$e->getMessage()}n";
                print  $e->getTraceAsString();
                echo "n";
            }
            $client = null;



            $api_url = 'https://api.dataforseo.com/';
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            $post_array = array();
            $post_array[] = array(
                "target" => $domain_info->domain,
                "limit" => 100,
                "internal_list_limit" => 10,
            );
            try {
                $result = $client->post('/v3/backlinks/history/live', $post_array);
                // prx($result);

                if ($result['status_message'] == "Ok.") {
                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                        $all_results = $result['tasks'][0]['result'][0]['items'];
                        if (isset($all_results) && count($all_results) > 0) {
                            foreach ($all_results as $res) {
                                // prx($anchor);
                                DB::table('backlinks_history')->insert([
                                    'user_id'   =>  $user_id,
                                    'domain_id'   =>  $domain_id,
                                    'backlinks_count'    =>  $res['backlinks'],
                                    'new_backlinks'    =>  $res['new_backlinks'],
                                    'lost_backlinks'    =>  $res['lost_backlinks'],
                                    'anchors_count'     =>  $res['referring_links_types']['anchor'],
                                    'date'      =>  $res['date']
                                ]);
                            }
                        }
                    }
                }
                // do something with post result
            } catch (RestClientException $e) {
                echo "n";
                print "HTTP code: {$e->getHttpCode()}n";
                print "Error code: {$e->getCode()}n";
                print "Message: {$e->getMessage()}n";
                print  $e->getTraceAsString();
                echo "n";
            }
            $client = null;


            $api_url = 'https://api.dataforseo.com/';
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            $post_array = array();
            $post_array[] = array(
                "target" => $domain_info->domain,
                "limit" =>  50,
                'backlinks_status_type' =>  'all',
                "mode" => "as_is",
                "filters" => ["dofollow", "=", false]
            );
            try {
                $result = $client->post('/v3/backlinks/backlinks/live', $post_array);
                if ($result['status_message'] == "Ok.") {
                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                        $total_count = $result['tasks'][0]['result'][0]['total_count'];
                        $all_backlinks = $result['tasks'][0]['result'][0]['items'];
                        if (isset($all_backlinks) && count($all_backlinks) > 0) {
                            foreach ($all_backlinks as $backlink) {
                                $already_exists = DB::table('backlinks')->where('domain_id', $domain_id)->where('user_id', Session::get('user_id'))->where('url_from', $backlink['url_from'])->first();
                                if (!$already_exists) {
                                    DB::table('backlinks')->insert([
                                        'user_id'   =>  $user_id,
                                        'domain_id'   =>  $domain_id,
                                        'total_count'   =>  $total_count,
                                        'url_from'   =>  $backlink['url_from'],
                                        'title'   =>  $backlink['page_from_title'],
                                        'domain_to'   =>  $backlink['url_to'],
                                        'spam_score'   =>  $backlink['backlink_spam_score'],
                                        'is_new'   =>  $backlink['is_new'],
                                        'is_lost'   =>  $backlink['is_lost'],
                                        'do_follow'   =>  $backlink['dofollow'],
                                        'p_a'   =>  $backlink['page_from_rank'],
                                        'd_a'   =>  $backlink['domain_from_rank'],
                                        'domain_from_rank'  =>  $backlink['domain_from_rank'],
                                        'date'   =>  date('Y-m-d H:i:s')
                                    ]);
                                }
                            }
                        }
                    }
                }
            } catch (RestClientException $e) {
            }
            $client = null;

            $api_url = 'https://api.dataforseo.com/';
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            $post_arrayy = array();
            if ($user_package_data->domain_backlinks_rows_limit == "Unlimited") {
                $post_arrayy[] = array(
                    "target" => $domain_info->domain,
                    "limit" =>  1000,
                    "mode" => "as_is",
                    'backlinks_status_type' =>  'all',
                );
            } else {
                $post_arrayy[] = array(
                    "target" => $domain_info->domain,
                    "limit" =>  $user_package_data->domain_backlinks_rows_limit,
                    "mode" => "as_is",
                    'backlinks_status_type' =>  'all'
                );
            }
            try {
                $result = $client->post('/v3/backlinks/backlinks/live', $post_arrayy);
                if ($result['status_message'] == "Ok.") {
                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                        $total_count = $result['tasks'][0]['result'][0]['total_count'];
                        $all_backlinks = $result['tasks'][0]['result'][0]['items'];
                        if (isset($all_backlinks) && count($all_backlinks) > 0) {
                            foreach ($all_backlinks as $backlink) {
                                $already_exists = DB::table('backlinks')->where('domain_id', $domain_id)->where('user_id', Session::get('user_id'))->where('url_from', $backlink['url_from'])->first();
                                if (!$already_exists) {
                                    DB::table('backlinks')->insert([
                                        'user_id'   =>  $user_id,
                                        'domain_id'   =>  $domain_id,
                                        'total_count'   =>  $total_count,
                                        'url_from'   =>  $backlink['url_from'],
                                        'title'   =>  $backlink['page_from_title'],
                                        'domain_to'   =>  $backlink['url_to'],
                                        'spam_score'   =>  $backlink['backlink_spam_score'],
                                        'is_new'   =>  $backlink['is_new'],
                                        'is_lost'   =>  $backlink['is_lost'],
                                        'do_follow'   =>  $backlink['dofollow'],
                                        'p_a'   =>  $backlink['page_from_rank'],
                                        'd_a'   =>  $backlink['domain_from_rank'],
                                        'domain_from_rank'  =>  $backlink['domain_from_rank'],
                                        'date'   =>  date('Y-m-d H:i:s')
                                    ]);
                                }
                            }
                        }
                    }
                }
            } catch (RestClientException $e) {
            }
            $client = null;
        }

        return redirect()->back();
    }

    public function DeleteBacklinks($domain_id)
    {
        DB::table('backlinks_domains')->where('id', $domain_id)->delete();
        DB::table('backlinks')->where('domain_id', $domain_id)->delete();
        DB::table('backlinks_history')->where('domain_id', $domain_id)->delete();
        DB::table('anchors')->where('domain_id', $domain_id)->delete();
        return redirect()->back();
    }

    public function Backlinks()
    {
        $total_backlinks = null;
        $domains_exists = null;
        $first_domain = null;
        $anchors = null;
        $backlinks = null;
        $total_backlinks = null;
        $backlinks_history = null;
        $UserPackageInfo = UserPackageInfo();
        $total_backlinks = null;
        $domain_data = DB::table('backlinks_domains')->where('user_id', Session::get('user_id'))->where('status',1)->orderBy('domain', 'ASC')->get();
        if (count($domain_data) > 0) {
            $domains_exists = "yes";
            $first_domain = $domain_data[0];
            $anchors = DB::table('anchors')->where('domain_id', $first_domain->id)->orderBy('count', 'DESC')->get();

            if ($UserPackageInfo->domain_backlinks_rows_limit == "Unlimited") {
                $backlinks = DB::table('backlinks')->where('user_id', Session::get('user_id'))->where('domain_id', $first_domain->id)->inRandomOrder()->paginate(30);
            } else {

                $backlinks = DB::table('backlinks')->where('user_id', Session::get('user_id'))->where('domain_id', $first_domain->id)->inRandomOrder()->limit($UserPackageInfo->domain_backlinks_rows_limit)->paginate(30);
            }


            // $backlinks = DB::table('backlinks')->where('user_id', Session::get('user_id'))->where('domain_id', $first_domain->id)->inRandomOrder()->limit($UserPackageInfo->domain_backlinks_rows_limit)->get();
            if (isset($backlinks[0])) {
                $total_backlinks = $backlinks[0]->total_count;
            }
            // prx($first_domain);
            // prx($backlinks);
            $backlinks_histor = DB::table('backlinks_history')->where('domain_id', $first_domain->id)->orderBy('date', 'DESC')->limit(5)->get();
            $backlinks_history = $backlinks_histor->reverse()->values();
        }
        // prx($backlinks_history);
        return view('seo.backlinks', compact('anchors', 'backlinks', 'domain_data', 'total_backlinks', 'first_domain', 'domains_exists', 'backlinks_history'));
    }

    public function NotificationUpdate(Request $request)
    {
        DB::table('backlinks_domains')->where('id', $request->domain_id)->update([
            'notification' => $request->frequency
        ]);
        return response()->json(['status' => 'successfull', 'message' => 'Notification Frequency Updated.']);
    }

    public function DomainIdBacklink($id)
    {
        $total_backlinks = null;
        $domains_exists = null;
        $UserPackageInfo = UserPackageInfo();
        $total_backlinks_count = 0;
        $first_domain= null;
        $backlinks_history = null;
        $backlinks = null;
        $anchors = null;
        $arrayResult = null;
      

        $domain_data = DB::table('backlinks_domains')->where('user_id', Session::get('user_id'))->orderBy('domain', 'ASC')->get();
        if (count($domain_data) > 0) {
            $domains_exists = "yes";
        }
        $first_domain = DB::table('backlinks_domains')->where('user_id', Session::get('user_id'))->where('id', $id)->first();
        // prx($first_domain);
        $anchors = DB::table('anchors')->where('domain_id', $first_domain->id)->orderBy('count', 'DESC')->get();
        if ($UserPackageInfo->domain_backlinks_rows_limit == "Unlimited") {
            $backlinks = DB::table('backlinks')->where('user_id', Session::get('user_id'))->where('domain_id', $first_domain->id)->inRandomOrder()->paginate(30);
        } else {
            $backlinks_lost_limit = round($UserPackageInfo->domain_backlinks_rows_limit * 0.3);

            $lost_backlinks = DB::table('backlinks')->where('user_id', Session::get('user_id'))->where('domain_id', $first_domain->id)->where('is_new', 1)->orWhere('is_lost', 1)->limit($backlinks_lost_limit)->get();
            $remaining_limit = $UserPackageInfo->domain_backlinks_rows_limit - (count($lost_backlinks));
            $remaining_backlinks = DB::table('backlinks')->where('user_id', Session::get('user_id'))->where('domain_id', $first_domain->id)->limit($remaining_limit)->get();
            $after_shuffling = json_decode(json_encode($remaining_backlinks), true);
            shuffle($after_shuffling);

            $arrayResult = array_map(function($array){
                return (object)$array;
            }, $after_shuffling);

        }
        if(isset($remaining_backlinks[0])) {
            $total_backlinks_count = $remaining_backlinks[0]->total_count;
        } else if(isset($lost_backlinks[0])) {
            $total_backlinks_count = $lost_backlinks[0]->total_count;
        }

        $backlinks_histor = DB::table('backlinks_history')->where('domain_id', $first_domain->id)->orderBy('date', 'DESC')->limit(5)->get();
        $backlinks_history = $backlinks_histor->reverse()->values();
        // prx($backlinks);
        

        //spam_score_calculation
        $total_spam_score = 0;
        $thirty_count = 0;
        $sixty_count = 0;
        $hundered_count = 0;
        foreach($lost_backlinks as $backlink) {
            $total_spam_score += $backlink->spam_score;
            if($backlink->spam_score < 31) {
                $thirty_count++;
            } else if($backlink->spam_score < 61) {
                $sixty_count++;
            } else if($backlink->spam_score <= 100) {
                $hundered_count++;
            }
        }
        
        foreach($remaining_backlinks as $backlink) {
            $total_spam_score += $backlink->spam_score;
            if($backlink->spam_score < 31) {
                $thirty_count++;
            } else if($backlink->spam_score < 61) {
                $sixty_count++;
            } else if($backlink->spam_score <= 100) {
                $hundered_count++;
            }
        }

        $total_backlinks = count($lost_backlinks)+ count($remaining_backlinks);
        if($total_spam_score > 0 && $total_backlinks > 0) {
            $final_spam_score = round($total_spam_score/(count($lost_backlinks) + count($remaining_backlinks)), 1);
        } else {
            $final_spam_score = 0;
        }

        if($thirty_count > 0 && $total_backlinks > 0) {
            $final_thirty_score = round($thirty_count/$total_backlinks, 1);
        } else {
            $final_thirty_score = 0;
        }
        if($sixty_count > 0 && $total_backlinks > 0) {
            $final_sixty_score = round($sixty_count/$total_backlinks, 1);
        } else {
            $final_sixty_score = 0;
        }
        if($hundered_count > 0 && $total_backlinks > 0) {
            $final_hundered_score = round($hundered_count/$total_backlinks, 1);
        } else {
            $final_hundered_score = 0;
        }


        $remaining_backlinks = (object)$arrayResult;
        // prx($remaining_backlinks);

        
        return view('seo.backlinks', compact('anchors', 'lost_backlinks', 'remaining_backlinks', 'domain_data', 'total_backlinks_count', 'first_domain', 'domains_exists', 'backlinks_history', 'final_spam_score', 'final_hundered_score', 'final_thirty_score', 'final_sixty_score'));
    }

    public function AnchorQuery()
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();

        $api_url = 'https://api.dataforseo.com/';
        // Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
        $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);

        $post_array = array();
        // simple way to get a result
        $post_array[] = array(
            "target" => "amazon.com",
            "limit" => 100,
            "internal_list_limit" => 10,
            // "order_by" => ["backlinks,desc"]
        );
        try {
            // POST /v3/backlinks/anchors/live
            // the full list of possible parameters is available in documentation
            $result = $client->post('/v3/backlinks/anchors/live', $post_array);
            // prx($result);

            if ($result['status_message'] == "Ok.") {
                if ($result['tasks'][0]['status_message'] == "Ok.") {
                    $all_anchors = $result['tasks'][0]['result'][0]['items'];
                    if (isset($all_anchors) && count($all_anchors) > 0) {
                        foreach ($all_anchors as $anchor) {
                            // prx($anchor);
                            DB::table('anchors')->insert([
                                'user_id'   =>  Session::get('user_id'),
                                'domain_id'   =>  43,
                                'anchor'    =>  $anchor['anchor'],
                                'count'     =>  $anchor['referring_domains']
                            ]);
                        }
                    }
                }
            }
            // do something with post result
        } catch (RestClientException $e) {
            echo "n";
            print "HTTP code: {$e->getHttpCode()}n";
            print "Error code: {$e->getCode()}n";
            print "Message: {$e->getMessage()}n";
            print  $e->getTraceAsString();
            echo "n";
        }
        $client = null;
    }
    public function BacklinkQuery()
    {
        $basic_settings = DB::table('basic_settings')->where('id',1)->first();
            $date = date('Y-m-d H:i:s');
            $all_users = DB::table('users')->where('payment', 1)->where('id', 1)->where('package_id', '!=', null)->get();
            foreach ($all_users as $user) {
                $UserPackageInfo = DB::table('packages')->where('id', $user->package_id)->first();
                $user_id = $user->id;
                $notification_domains = [];
                $user_all_domains = DB::table('backlinks_domains')->where('user_id', $user->id)->where('status',1)->get();
                if(count($user_all_domains) > 0) {
                    foreach($user_all_domains as $domain) {
                        if ($domain->last_notification == null && $domain->notification != null && $domain->notification != '0') {
                            $backlinks_lost_limit = round($UserPackageInfo->domain_backlinks_rows_limit * 0.3);
                            $lost_backlinks = DB::table('backlinks')->where('user_id', $user_id)->where('domain_id', $domain->id)->where('is_new', 1)->orWhere('is_lost', 1)->limit($backlinks_lost_limit)->get();
                            $remaining_limit = $UserPackageInfo->domain_backlinks_rows_limit - (count($lost_backlinks));
                            $remaining_backlinks = DB::table('backlinks')->where('user_id', $user_id)->where('domain_id', $domain->id)->limit($remaining_limit)->get();
                            $total_spam_score = 0;
                            foreach($lost_backlinks as $backlink) {
                                $total_spam_score += $backlink->spam_score;
                            }
                            
                            foreach($remaining_backlinks as $backlink) {
                                $total_spam_score += $backlink->spam_score;
                            }
                            $final_spam_score = 0;
                            $total_backlinks = count($lost_backlinks)+ count($remaining_backlinks);
                            if($total_spam_score > 0 && $total_backlinks > 0) {
                                $final_spam_score = round($total_spam_score/(count($lost_backlinks) + count($remaining_backlinks)), 1);
                            }
                            if($total_backlinks > 0) {
                                $domain->total_backlinks = $remaining_backlinks[0]->total_count;
                            } else {
                                $domain->total_backlinks = 0;
                            }
                            $domain->spam_score = $final_spam_score;
                            array_push($notification_domains, $domain);
                            DB::table('backlinks_domains')->where('id', $domain->id)->update([
                                'last_notification' => $date
                            ]);
                        } elseif ($domain->notification == "daily") {
                            $backlinks_lost_limit = round($UserPackageInfo->domain_backlinks_rows_limit * 0.3);
                            $lost_backlinks = DB::table('backlinks')->where('user_id', $user_id)->where('domain_id', $domain->id)->where('is_new', 1)->orWhere('is_lost', 1)->limit($backlinks_lost_limit)->get();
                            $remaining_limit = $UserPackageInfo->domain_backlinks_rows_limit - (count($lost_backlinks));
                            $remaining_backlinks = DB::table('backlinks')->where('user_id', $user_id)->where('domain_id', $domain->id)->limit($remaining_limit)->get();
                            $total_spam_score = 0;
                            foreach($lost_backlinks as $backlink) {
                                $total_spam_score += $backlink->spam_score;
                            }
                            
                            foreach($remaining_backlinks as $backlink) {
                                $total_spam_score += $backlink->spam_score;
                            }
                            $final_spam_score = 0;
                            $total_backlinks = count($lost_backlinks)+ count($remaining_backlinks);
                            if($total_spam_score > 0 && $total_backlinks > 0) {
                                $final_spam_score = round($total_spam_score/(count($lost_backlinks) + count($remaining_backlinks)), 1);
                            }
                            if($total_backlinks > 0) {
                                $domain->total_backlinks = $remaining_backlinks[0]->total_count;
                            } else {
                                $domain->total_backlinks = 0;
                            }
                            $domain->spam_score = $final_spam_score;
                            array_push($notification_domains, $domain);
                            DB::table('backlinks_domains')->where('id', $domain->id)->update([
                                'last_notification' => $date
                            ]);
                        } elseif ($domain->notification == "weekly") {
                            $days = "7 days";
                            $new_notification_date = date_add(date_create($user->last_notification), date_interval_create_from_date_string($days));
                            if ($new_notification_date <= date('Y-m-d H:i:s')) {
                                $backlinks_lost_limit = round($UserPackageInfo->domain_backlinks_rows_limit * 0.3);
                                $lost_backlinks = DB::table('backlinks')->where('user_id', $user_id)->where('domain_id', $domain->id)->where('is_new', 1)->orWhere('is_lost', 1)->limit($backlinks_lost_limit)->get();
                                $remaining_limit = $UserPackageInfo->domain_backlinks_rows_limit - (count($lost_backlinks));
                                $remaining_backlinks = DB::table('backlinks')->where('user_id', $user_id)->where('domain_id', $domain->id)->limit($remaining_limit)->get();
                                $total_spam_score = 0;
                                foreach($lost_backlinks as $backlink) {
                                    $total_spam_score += $backlink->spam_score;
                                }
                                
                                foreach($remaining_backlinks as $backlink) {
                                    $total_spam_score += $backlink->spam_score;
                                }
                                $final_spam_score = 0;
                                $total_backlinks = count($lost_backlinks)+ count($remaining_backlinks);
                                if($total_spam_score > 0 && $total_backlinks > 0) {
                                    $final_spam_score = round($total_spam_score/(count($lost_backlinks) + count($remaining_backlinks)), 1);
                                }
                                if($total_backlinks > 0) {
                                    $domain->total_backlinks = $remaining_backlinks[0]->total_count;
                                } else {
                                    $domain->total_backlinks = 0;
                                }
                                $domain->spam_score = $final_spam_score;
                                array_push($notification_domains, $domain);
                                DB::table('backlinks_domains')->where('id', $domain->id)->update([
                                    'last_notification' => $date
                                ]);
                            }
                        } elseif ($domain->notification == "monthly") {
                            $days = "30 days";
                            $new_notification_date = date_add(date_create($user->last_notification), date_interval_create_from_date_string($days));
                            if ($new_notification_date <= date('Y-m-d H:i:s')) {
                                $backlinks_lost_limit = round($UserPackageInfo->domain_backlinks_rows_limit * 0.3);
                                $lost_backlinks = DB::table('backlinks')->where('user_id', $user_id)->where('domain_id', $domain->id)->where('is_new', 1)->orWhere('is_lost', 1)->limit($backlinks_lost_limit)->get();
                                $remaining_limit = $UserPackageInfo->domain_backlinks_rows_limit - (count($lost_backlinks));
                                $remaining_backlinks = DB::table('backlinks')->where('user_id', $user_id)->where('domain_id', $domain->id)->limit($remaining_limit)->get();
                                $total_spam_score = 0;
                                foreach($lost_backlinks as $backlink) {
                                    $total_spam_score += $backlink->spam_score;
                                }
                                
                                foreach($remaining_backlinks as $backlink) {
                                    $total_spam_score += $backlink->spam_score;
                                }
                                $final_spam_score = 0;
                                $total_backlinks = count($lost_backlinks)+ count($remaining_backlinks);
                                if($total_spam_score > 0 && $total_backlinks > 0) {
                                    $final_spam_score = round($total_spam_score/(count($lost_backlinks) + count($remaining_backlinks)), 1);
                                }
                                if($total_backlinks > 0) {
                                    $domain->total_backlinks = $remaining_backlinks[0]->total_count;
                                } else {
                                    $domain->total_backlinks = 0;
                                }
                                $domain->spam_score = $final_spam_score;
                                array_push($notification_domains, $domain);
                                DB::table('backlinks_domains')->where('id', $domain->id)->update([
                                    'last_notification' => $date
                                ]);
                            }
                        }
                    }

                    if($notification_domains != null) {
                        $subscriber['to'] = $user->email;
                        $subscriber['from'] = $basic_settings->for_emails_email;
                        $data = [
                                'notification_domains' => $notification_domains,
                                'logo' => $basic_settings->site_logo
                        ];
                        $date = date('Y-m-d');
                        Mail::send('mail.user_backlink_notification', $data, function ($message) use ($subscriber) {
                            $message->from($subscriber['from'], 'Rank Checker');
                            $message->sender($subscriber['from'], 'Rank Checker');
                            $message->to($subscriber['to']);
                            $message->subject('Domains Backlink Info');
                            $message->priority(3);
                        });
                    }


                }
            }

            return redirect()->back();
        // $dataforseo_api = DB::table('api')->where('id', 1)->first();

        // $api_url = 'https://api.dataforseo.com/';
        // $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        // $post_array = array();
        // // simple way to get a result
        // $post_array[] = array(
        //     "target" => "unsplash.com",
        //     "limit" => 300,
        //     "mode" => "as_is",
        //     // "filters" => ["anchor", "like", "%news%"],
        //     // "order_by" => ["backlinks,desc"]
        // );
        // try {
        //     // POST /v3/backlinks/anchors/live
        //     // the full list of possible parameters is available in documentation
        //     $result = $client->post('/v3/backlinks/backlinks/live', $post_array);
        //     prx($result);
        //     if ($result['status_message'] == "Ok.") {
        //         if ($result['tasks'][0]['status_message'] == "Ok.") {
        //             $total_count = $result['tasks'][0]['result'][0]['total_count'];
        //             $all_backlinks = $result['tasks'][0]['result'][0]['items'];
        //             foreach ($all_backlinks as $backlink) {
        //                 $already_exists = DB::table('backlinks')->where('domain_id', 43)->where('user_id', Session::get('user_id'))->where('url_from', $backlink['url_from'])->first();
        //                 if (!$already_exists) {
        //                     DB::table('backlinks')->insert([
        //                         'user_id'   =>  Session::get('user_id'),
        //                         'domain_id'   =>  43,
        //                         'total_count'   =>  $total_count,
        //                         'url_from'   =>  $backlink['url_from'],
        //                         'title'   =>  $backlink['page_from_title'],
        //                         'domain_to'   =>  $backlink['domain_to'],
        //                         'is_new'   =>  $backlink['is_new'],
        //                         'is_lost'   =>  $backlink['is_lost'],
        //                         'do_follow'   =>  $backlink['dofollow'],
        //                         'domain_from_rank'  =>  $backlink['domain_from_rank'],
        //                         'date'   =>  date('Y-m-d H:i:s')
        //                     ]);
        //                 }
        //             }
        //         }
        //     }
        //     prx($result);

        //     // do something with post result
        // } catch (RestClientException $e) {
        //     echo "n";
        //     print "HTTP code: {$e->getHttpCode()}n";
        //     print "Error code: {$e->getCode()}n";
        //     print "Message: {$e->getMessage()}n";
        //     print  $e->getTraceAsString();
        //     echo "n";
        // }
        // $client = null;
    }

    public function RankedKeywords()
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();

        $api_url = 'https://api.dataforseo.com/';
        $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        $post_array = array();
        // simple way to set a task
        $post_array[] = array(
            "target" => "youtube.com",
            "limit" =>  50,
            "mode" => "as_is",
            "filters" => ["dofollow", "=", false]
        );
        try {
            // POST /v3/dataforseo_labs/ranked_keywords/live
            $first_result = $client->post('/v3/backlinks/backlinks/live', $post_array);
            if ($first_result['status_message'] == "Ok.") {
                if ($first_result['tasks'][0]['status_message'] == "Ok.") {
                    $total_count = $first_result['tasks'][0]['result'][0]['total_count'];
                    $all_backlinks = $first_result['tasks'][0]['result'][0]['items'];
                    if (isset($backlinks) && count($backlinks) > 0) {
                        
                        prx($backlinks);
                    }
                }
            }
            $post_arrayy[] = array(
                "target" => "forbes.com",
                "filters" => ["dofollow", "=", true]
            );

            $second_result = $client->post('/v3/backlinks/backlinks/live', $post_arrayy);

            $reference_first = $first_result['tasks'][0]['result'][0]['items'];
            $reference_second = $second_result['tasks'][0]['result'][0]['items'];

            foreach ($reference_first as $reference) {
                $new_result[] = $reference;
            }
            foreach ($reference_second as $ref) {
                $new_result[] = $ref;
            }

            prx($new_result);
            // do something with post result
        } catch (RestClientException $e) {
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print "Message: {$e->getMessage()}\n";
            print  $e->getTraceAsString();
            echo "\n";
        }
        $client = null;
    }
}







class RestClient
{
    public string $host; // the url to the rest server
    public ?int $port = null;
    public string $scheme;
    public string $post_type = 'json';
    public int $timeout = 60;
    public int $connection_timeout = 10;
    private ?string $token; // Auth token
    private ?string $ba_user;
    private ?string $ba_password;
    private ?string $ba_ua;
    public string $last_url = '';
    public $last_response = null;
    public $last_http_code = null;

    public function __construct(
        string $host,
        string $token = null,
        string $ba_user = null,
        string $ba_password = null,
        string $ba_user_agent = null
    ) {
        $arr_h = parse_url($host);
        if (isset($arr_h['port'])) {
            $this->port = (int)$arr_h['port'];
            $this->host = str_replace(":" . $this->port, "", $host);
        } else {
            $this->port = null;
            $this->host = $host;
        }
        if (isset($arr_h['scheme'])) {
            $this->scheme = $arr_h['scheme'];
        }
        $this->token = $token;
        $this->ba_user = $ba_user;
        $this->ba_password = $ba_password;
        $this->ba_ua = $ba_user_agent;
    }

    /**
     * Returns the absolute URL
     *
     * @param string $raw_headers
     */
    private function http_parse_headers(string $raw_headers): array
    {
        $headers = array();
        $key = '';

        foreach (explode("\n", $raw_headers) as $h) {
            $h = explode(':', $h, 2);
            if (isset($h[1])) {
                if (!isset($headers[$h[0]])) {
                    $headers[$h[0]] = trim($h[1]);
                } elseif (is_array($headers[$h[0]])) {
                    $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
                } else {
                    $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
                }
                $key = $h[0];
            } else {
                if (substr($h[0], 0, 1) == "\t") {
                    $headers[$key] .= "\r\n\t" . trim($h[0]);
                } elseif (!$key) {
                    $headers[0] = trim($h[0]);
                }
            }
        }

        return $headers;
    }

    /**
     * Returns the absolute URL
     *
     * @param string|null $url
     * @return string
     */
    private function url(string $url = null): string
    {
        $_host = rtrim($this->host, '/');
        $_url = ltrim($url, '/');

        return "{$_host}:{$this->port}/{$_url}";
    }

    /**
     * Returns the URL with encoded query string params
     *
     * @param string $url
     * @param array|null $params
     * @return string
     */
    private function urlQueryString(string $url, array $params = null): string
    {
        $qs = array();
        if ($params) {
            foreach ($params as $key => $value) {
                $qs[] = "{$key}=" . urlencode($value);
            }
        }

        $url = explode('?', $url);
        if (isset($url[1])) {
            $url_qs = $url[1];
        }
        $url = $url[0];
        if (isset($url_qs)) {
            $url = "{$url}?{$url_qs}";
        }

        if (count($qs)) {
            return "{$url}?" . implode('&', $qs);
        } else {
            return $url;
        }
    }

    /**
     * Make an HTTP request using cURL
     *
     * @param string $verb
     * @param string $url
     * @param array $params
     */
    private function request(string $verb, string $url, array $params = array())
    {

        $ch = curl_init(); // the cURL handler
        $url = $this->url($url); // the absolute URL
        $request_headers = array();
        if (!empty($this->token)) {
            $request_headers[] = "Authorization: {$this->token}";
        }

        if ((!empty($this->ba_user)) and (!empty($this->ba_password))) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->ba_user . ":" . $this->ba_password);
        }

        // encoded query string on GET
        switch (true) {
            case 'GET' == $verb:
                $url = $this->urlQueryString($url, $params);
                break;
            case in_array($verb, array(
                'POST',
                'PUT',
                'DELETE'
            ), false):
                if ($this->post_type == 'json') {
                    $request_headers[] = 'Content-Type: application/json';
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                }
        }

        // set the URL
        curl_setopt($ch, CURLOPT_URL, $url);
        $this->last_url = $url;

        // set the HTTP verb for the request
        switch ($verb) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case 'PUT':
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
        }

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connection_timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        if (!empty($this->ba_ua)) {
            curl_setopt($ch, CURLOPT_USERAGENT, $this->ba_ua);
        }
        if (!empty($this->port)) {
            curl_setopt($ch, CURLOPT_PORT, $this->port);
        }
        if ((!empty($this->scheme)) and ($this->scheme == 'https')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = $this->http_parse_headers(substr($response, 0, $header_size));
        $response = substr($response, $header_size);
        $http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $content_error = curl_error($ch);
        //var_dump($content_error);

        curl_close($ch);

        if (strpos($content_type, 'json')) {
            $response = json_decode($response, true);
        }

        $this->last_response = $response;
        $this->last_http_code = $http_code;

        switch (true) {
            case 'GET' == $verb:
                if ($http_code !== 200) {
                    if (is_array($response)) {
                        $this->throw_error($response, $http_code);
                    } else {
                        $this->throw_error(trim($content_error . "\n" . $response), $http_code);
                    }
                }
                return $response;
            case in_array($verb, array(
                'POST',
                'PUT',
                'DELETE'
            ), false):
                if (($http_code !== 303) and ($http_code !== 200)) {
                    if (is_array($response)) {
                        $this->throw_error($response, $http_code);
                    } else {
                        $this->throw_error(trim($content_error . "\n" . $response), $http_code);
                    }
                }
                if ($http_code === 200) {
                    return $response;
                } else {
                    return str_replace(rtrim($this->host, '/') . '/', '', $headers['Location']);
                }
        }
    }

    private function throw_error($response, $http_code)
    {
        if (is_array($response) && array_key_exists('error', $response)) {
            if ((isset($response['error']['message'])) and (isset($response['error']['code']))) {
                if (is_array($response['error']['message'])) {
                    throw new RestClientException(
                        implode("; ", $response['error']['message']),
                        (int)$response['error']['code'],
                        $http_code
                    );
                } else {
                    throw new RestClientException($response['error']['message'], (int)$response['error']['code'], $http_code);
                }
            } else {
                throw new RestClientException(implode("; ", $response), 0, $http_code);
            }
        } else {
            if (is_string($response)) {
                throw new RestClientException($response, 0, $http_code);
            } else {
                throw new RestClientException(json_encode($response), 0, $http_code);
            }
        }
    }

    /**
     * Make an HTTP GET request
     *
     * @param string $url
     * @param array $params
     */
    public function get($url, $params = array())
    {
        return $this->request('GET', $url, $params);
    }

    /**
     * Make an HTTP POST request
     *
     * @param string $url
     * @param array $params
     */
    public function post($url, $params = array())
    {
        return $this->request('POST', $url, $params);
    }

    /**
     * Make an HTTP PUT request
     *
     * @param string $url
     * @param array $params
     */
    public function put($url, $params = array())
    {
        return $this->request('PUT', $url, $params);
    }

    /**
     * Make an HTTP DELETE request
     *
     * @param string $url
     * @param array $params
     */
    public function delete($url, $params = array())
    {
        return $this->request('DELETE', $url, $params);
    }
}

class RestClientException extends Exception
{
    protected $http_code;

    public function __construct(string $message, int $code = 0, int $http_code = 0, Exception $previous = null)
    {
        $this->http_code = $http_code;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int the http code error representation of the exception.
     */
    public function getHttpCode()
    {
        return $this->http_code;
    }

    /**
     * @return string the string representation of the exception.
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message} (HTTP status code: {$this->http_code})\n";
    }
}