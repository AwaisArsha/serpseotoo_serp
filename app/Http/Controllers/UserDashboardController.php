<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\Hash;
use DateTime;
use Illuminate\Support\Facades\Mail;
use DateInterval;



class UserDashboardController extends Controller
{
    public function check_keyword_data(Request $request) {
        $keyword = DB::table('domain_keywords')->where('domain_id', $request->domain_id)->where('api_running', 'yes')->get();
        if(count($keyword) > 0) {
            // if($keyword->api_running == 'no' || $keyword->api_running == null) {
                return response()->json(['status' => 'successfull', 'count' => count($keyword)]);
            // } else {
                // return response()->json(['status' => 'not_yet']);
            // }
        } else {
            return response()->json(['status' => 'successfull', 'count' => 0]);
        }
    }

    public function set_api_manual_refresh($keyword_id)
    {
        set_time_limit(5000);
        $remaining_refreshers = refreshes();
        if ($remaining_refreshers <= 0) {
            Session::flash('message', 'You have reached the refresh limit.');
            Session::flash('alert-type', 'info');
            return redirect()->back();
        }

        $dataforseo_api = DB::table('api')->where('id', 1)->first();
        $keywords_everywhere_token = 'Bearer '.$dataforseo_api->keywords_everywhere_api;

        $api_url = 'https://api.dataforseo.com/';

        $keyword_data = DB::table('domain_keywords')->where('id', $keyword_id)->first();
        if(!$keyword_data) {
            return;
        }
        $domain_data = DB::table('domains')->where('id', $keyword_data->domain_id)->first();
        if(!$domain_data) {
            return;
        }
        $platform = $keyword_data->platform;
        // prx($keyword_data);
        if($platform == "desktop and mobile") {
            DB::table('user_refreshes')->insert([
                'keyword_id'    =>  $keyword_id,
                'user_id'    =>  Session::get('user_id'),
                'keyword_platform'  =>  $platform,
                'date'  =>  date('Y-m-d H:i:s')
            ]);

            try {
                $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            } catch (RestClientException $e) {
                return;
            }

            $ch = curl_init('https://api.keywordseverywhere.com/v1/get_keyword_data');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Authorization: '.$keywords_everywhere_token
            ));

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
            urldecode(http_build_query([
                "dataSource" => "gkp",
                "country" => "us",
                "kw" => [
                    $keyword_data->keyword
                ]
            ]))
            );

            $data = curl_exec($ch);
            $err = curl_error($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);
            $volume = 0;
            $monthly_trend = array();
            if($info['http_code'] == 200){
                $dataa = json_decode($data);
                $volume = $dataa->data[0]->vol;
                $monthly_trend = $dataa->data[0]->trend;
                
            }

            DB::table('domain_keywords')->where('id', $keyword_id)->update([
                'volume'  =>  $volume
            ]);
            
            $date = date('Y-m-d H:i:s');
            DB::table('domain_keywords_monthly_volume')->where('keyword_id', $keyword_id)->delete();

            foreach ($monthly_trend as $trend) {
                DB::table('domain_keywords_monthly_volume')->insertGetId([
                    'domain_id' =>  $keyword_data->domain_id,
                    'user_id'   => Session::get('user_id'),
                    'keyword'  =>  $keyword_data->keyword,
                    'keyword_id'    =>  $keyword_id,
                    'year'  =>  $trend->year,
                    'month'  =>  $trend->month,
                    'search_volume'    =>  $trend->value,
                    'date'      =>  $date
                ]);
            }

            $post_array[] = array(
                "language_code" => $domain_data->language_code,
                "location_code" => $domain_data->location_code,
                "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                "device" => "desktop",
                "priority" => 2
            );
            try {
                $result = $client->post('/v3/serp/google/organic/task_post', $post_array);
                // echo "<pre>";
                // print_r($result);
                if ($result['status_message'] = "Ok.") {
                    $reference_id = $result['tasks'][0]['id'];
                    if ($result['tasks'][0]['status_message'] == "Task Created.") {
                        DB::table('domain_keywords')->where('id', $keyword_id)->update([
                            'api_running'   =>  'yes',
                            'api_reference_desktop'   =>  $reference_id
                        ]);
                    } else {
                        return response()->json(['status' => 'Something went wrong']);
                    }
                } else {
                    return response()->json(['status' => 'Something went wrong']);
                }
            } catch (RestClientException $e) {
                return response()->json(['status' => 'Something went wrong']);
            }

            $client = null;


            try {
                $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            } catch (RestClientException $e) {
                return;
            }

            $post_array = array();
            $post_array[] = array(
                "language_code" => $domain_data->language_code,
                "location_code" => $domain_data->location_code,
                "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                "device" => "mobile",
                "priority" => 2
            );
            try {
                $result = $client->post('/v3/serp/google/organic/task_post', $post_array);
                // echo "<pre>";
                // print_r($result);
                if ($result['status_message'] = "Ok.") {
                    $reference_id = $result['tasks'][0]['id'];
                    if ($result['tasks'][0]['status_message'] == "Task Created.") {
                            DB::table('domain_keywords')->where('id', $keyword_id)->update([
                                'api_running'   =>  'yes',
                                'api_reference_mobile'   =>  $reference_id
                            ]);
                        Session::flash('message', 'Processing.');
                        Session::flash('alert-type', 'info');
                        return redirect()->back();
                    } else {
                        Session::flash('message', 'Something went wrong.');
                        Session::flash('alert-type', 'danger');
                        return redirect()->back();
                    }
                } else {
                    Session::flash('message', 'Something went wrong.');
                    Session::flash('alert-type', 'danger');
                    return redirect()->back();
                }

                $client = null;

            } catch (RestClientException $e) {
                Session::flash('message', 'Something went wrong.');
                Session::flash('alert-type', 'danger');
                return redirect()->back();
            }

            
        } else {
            $post_array[] = array(
                "language_code" => $domain_data->language_code,
                "location_code" => $domain_data->location_code,
                "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                "device" => $platform,
                "priority" => 2,
                "depth" =>  100
            );

            DB::table('user_refreshes')->insert([
                'keyword_id'    =>  $keyword_id,
                'user_id'    =>  Session::get('user_id'),
                'keyword_platform'  =>  $platform,
                'date'  =>  date('Y-m-d H:i:s')
            ]);
            try {
                $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            } catch (RestClientException $e) {
                return;
            }

            $ch = curl_init('https://api.keywordseverywhere.com/v1/get_keyword_data');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Authorization: '.$keywords_everywhere_token
            ));

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                urldecode(http_build_query([
                    "dataSource" => "gkp",
                    "country" => "us",
                    "kw" => [
                        $keyword_data->keyword
                    ]
                ]))
            );
            $data = curl_exec($ch);
            $err = curl_error($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);
            $volume = 0;
            $monthly_trend = array();
            if($info['http_code'] == 200){
                $dataa = json_decode($data);
                $volume = $dataa->data[0]->vol;
                $monthly_trend = $dataa->data[0]->trend;
                
            }

            DB::table('domain_keywords')->where('id', $keyword_id)->update([
                'volume'  =>  $volume
            ]);
            
            $date = date('Y-m-d H:i:s');
            DB::table('domain_keywords_monthly_volume')->where('keyword_id', $keyword_id)->delete();

            foreach ($monthly_trend as $trend) {
                DB::table('domain_keywords_monthly_volume')->insertGetId([
                    'domain_id' =>  $keyword_data->domain_id,
                    'user_id'   => Session::get('user_id'),
                    'keyword'  =>  $keyword_data->keyword,
                    'keyword_id'    =>  $keyword_id,
                    'year'  =>  $trend->year,
                    'month'  =>  $trend->month,
                    'search_volume'    =>  $trend->value,
                    'date'      =>  $date
                ]);
            }

            try {
    
                $result = $client->post('/v3/serp/google/organic/task_post', $post_array);
                // echo "<pre>";
                // print_r($result);
                if ($result['status_message'] = "Ok.") {
                    $reference_id = $result['tasks'][0]['id'];
                    if ($result['tasks'][0]['status_message'] == "Task Created.") {
                        if($result['tasks'][0]['data']['device'] == "desktop") {
                            DB::table('domain_keywords')->where('id', $keyword_id)->update([
                                'api_running'   =>  'yes',
                                'api_reference_desktop'   =>  $reference_id
                            ]);
                        } else if($result['tasks'][0]['data']['device'] == "mobile") {
                            DB::table('domain_keywords')->where('id', $keyword_id)->update([
                                'api_running'   =>  'yes',
                                'api_reference_mobile'   =>  $reference_id
                            ]);
                        }
                        Session::flash('message', 'Processing.');
                        Session::flash('alert-type', 'info');
                        return redirect()->back();
                    } else {
                        Session::flash('message', 'Semething went wrong.');
                        Session::flash('alert-type', 'danger');
                        return redirect()->back();
                    }
                } else {
                    Session::flash('message', 'Semething went wrong.');
                    Session::flash('alert-type', 'danger');
                    return redirect()->back();
                }
            } catch (RestClientException $e) {
                Session::flash('message', 'Semething went wrong.');
                Session::flash('alert-type', 'danger');
                return redirect()->back();
            }
        }
    }

    public function set_api_manual_all_refresh($domain_id)
    {
        set_time_limit(10000);
        $dataforseo_api = DB::table('api')->where('id', 1)->first();
        $keywords_everywhere_token = 'Bearer '.$dataforseo_api->keywords_everywhere_api;
        $api_url = 'https://api.dataforseo.com/';
        try {
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        } catch (RestClientException $e) {
            return redirect()->back();
        }

        $domain_data = DB::table('domains')->where('id', $domain_id)->first();
        if(!$domain_data) {
            return;
        }
        $all_keywords = DB::table('domain_keywords')->where('domain_id', $domain_id)->get();
        // prx($all_keywords);
        if(count($all_keywords) > 0) {
            foreach($all_keywords as $keyword_data) {
                $remaining_refreshers = refreshes();
                if ($remaining_refreshers <= 0) {
                    Session::flash('message', 'You have reached the refresh limit.');
                    Session::flash('alert-type', 'info');
                    return redirect()->back();
                }
                $platform = $keyword_data->platform;

                if($platform == "desktop and mobile") {
                    DB::table('user_refreshes')->insert([
                        'keyword_id'    =>  $keyword_data->id,
                        'user_id'    =>  Session::get('user_id'),
                        'keyword_platform'  =>  $platform,
                        'date'  =>  date('Y-m-d H:i:s')
                    ]);
                    $post_array = array();

                    $ch = curl_init('https://api.keywordseverywhere.com/v1/get_keyword_data');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Accept: application/json',
                    'Authorization: '.$keywords_everywhere_token
                    ));

                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,
                        urldecode(http_build_query([
                            "dataSource" => "gkp",
                            "country" => "us",
                            "kw" => [
                                $keyword_data->keyword
                            ]
                        ]))
                    );

                    $data = curl_exec($ch);
                    $err = curl_error($ch);
                    $info = curl_getinfo($ch);
                    curl_close($ch);
                    $volume = 0;
                    $monthly_trend = array();
                    if($info['http_code'] == 200){
                        $dataa = json_decode($data);
                        $volume = $dataa->data[0]->vol;
                        $monthly_trend = $dataa->data[0]->trend;
                    }

                    DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                        'volume'    =>  $volume,
                    ]);
                    DB::table('domain_keywords_monthly_volume')->where('keyword_id', $keyword_data->id)->delete();

                    foreach ($monthly_trend as $trend) {
                        DB::table('domain_keywords_monthly_volume')->insertGetId([
                            'domain_id' =>  $domain_id,
                            'user_id'   => Session::get('user_id'),
                            'keyword'  =>  $keyword_data->keyword,
                            'keyword_id'    =>  $keyword_data->id,
                            'year'  =>  $trend->year,
                            'month'  =>  $trend->month,
                            'search_volume'    =>  $trend->value,
                            'date'      =>  date('Y-m-d H:i:s')
                        ]);
                    }

                    $post_array[] = array(
                        "language_code" => $domain_data->language_code,
                        "location_code" => $domain_data->location_code,
                        "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                        "device" => "desktop",
                        "priority" => 2
                     );
            
                    try {
                        $result = $client->post('/v3/serp/google/organic/task_post', $post_array);
                        // echo "<pre>";
                        // print_r($result);
                        if ($result['status_message'] = "Ok.") {
                            $reference_id = $result['tasks'][0]['id'];
                            if ($result['tasks'][0]['status_message'] == "Task Created.") {
                                if($result['tasks'][0]['data']['device'] == "desktop") {
                                    DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                                        'api_running'   =>  'yes',
                                        'api_reference_desktop'   =>  $reference_id
                                    ]);
                                } else if($result['tasks'][0]['data']['device'] == "mobile") {
                                    DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                                        'api_running'   =>  'yes',
                                        'api_reference_mobile'   =>  $reference_id
                                    ]);
                                }
                            }
                        }
                    } catch (RestClientException $e) {
                        // return response()->json(['status' => 'failed']);
                    }

                    $post_array = array();
                    $post_array[] = array(
                        "language_code" => $domain_data->language_code,
                        "location_code" => $domain_data->location_code,
                        "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                        "device" => "mobile",
                        "priority" => 2
                     );
            
                    try {
                        $result = $client->post('/v3/serp/google/organic/task_post', $post_array);
                        // echo "<pre>";
                        // print_r($result);
                        if ($result['status_message'] = "Ok.") {
                            $reference_id = $result['tasks'][0]['id'];
                            if ($result['tasks'][0]['status_message'] == "Task Created.") {
                                if($result['tasks'][0]['data']['device'] == "desktop") {
                                    DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                                        'api_running'   =>  'yes',
                                        'api_reference_desktop'   =>  $reference_id
                                    ]);
                                } else if($result['tasks'][0]['data']['device'] == "mobile") {
                                    DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                                        'api_running'   =>  'yes',
                                        'api_reference_mobile'   =>  $reference_id
                                    ]);
                                }
                            }
                        }
                    } catch (RestClientException $e) {
                        // return response()->json(['status' => 'failed']);
                    }
                } else {
                    $post_array = array();
                    $post_array[] = array(
                        "language_code" => $domain_data->language_code,
                        "location_code" => $domain_data->location_code,
                        "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                        "device" => $platform,
                        "priority" => 2
                     );
            
                     DB::table('user_refreshes')->insert([
                         'keyword_id'    =>  $keyword_data->id,
                         'user_id'    =>  Session::get('user_id'),
                         'keyword_platform'  =>  $platform,
                         'date'  =>  date('Y-m-d H:i:s')
                     ]);
                    try {
                        $result = $client->post('/v3/serp/google/organic/task_post', $post_array);
                        // echo "<pre>";
                        // print_r($result);
                        if ($result['status_message'] = "Ok.") {
                            $reference_id = $result['tasks'][0]['id'];
                            if ($result['tasks'][0]['status_message'] == "Task Created.") {
                                if($result['tasks'][0]['data']['device'] == "desktop") {
                                    DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                                        'api_running'   =>  'yes',
                                        'api_reference_desktop'   =>  $reference_id
                                    ]);
                                } else if($result['tasks'][0]['data']['device'] == "mobile") {
                                    DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                                        'api_running'   =>  'yes',
                                        'api_reference_mobile'   =>  $reference_id
                                    ]);
                                }
                            }
                        }
                    } catch (RestClientException $e) {
                        // return response()->json(['status' => 'failed']);
                    }
                }
            }
        }

        Session::flash('message', 'Processing.');
        Session::flash('alert-type', 'info');
        return redirect()->back();

    }

  	public function get_api_maual_refresh_keyword()
    {
        set_time_limit(50000);

        $date= date('Y-m-d H:i:s');
        $dataforseo_api = DB::table('api')->where('id', 1)->first();
        $api_url = 'https://api.dataforseo.com/';
        try {
            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        } catch (RestClientException $e) {
            return;
        }

        try {
            $result = array();
            $tasks_ready = $client->get('/v3/serp/google/organic/tasks_ready');
            if (isset($tasks_ready['status_code']) AND $tasks_ready['status_code'] === 20000) {
                foreach ($tasks_ready['tasks'] as $task) {
                  if (isset($task['result'])) {
                    foreach ($task['result'] as $task_ready) {
                      if (isset($task_ready['endpoint_regular'])) {
                        $result[] = $client->get($task_ready['endpoint_regular']);
                      }
                      
                    }
                  }
                }
                foreach($result as $res) {
                    echo "done";

                    // echo "<pre>";
                    // print_r($res);
                    // echo "donedjf sdfkdsnfk sdf dfkl dsfkl sdfk";

                    if ($res['status_message'] = "Ok.") {
                        $reference_id = $res['tasks'][0]['id'];
                        $keyword_data = DB::table('domain_keywords')->where('api_reference_desktop', $reference_id)->orWhere('api_reference_mobile', $reference_id)->first();
                        if($keyword_data) {
                            $domain_id = $keyword_data->domain_id;
                            $domain_data = DB::table('domains')->where('id', $domain_id)->first();
                            $domain_url = $domain_data->domain;

                            if ($res['tasks'][0]['status_message'] == "Ok.") {
                                $all_competitors = $res['tasks'][0]['result'][0]['items'];
                                $platform = $res['tasks'][0]['data']['device'];
        
                                // prx($result);
                                if (isset($all_competitors) && count($all_competitors)  > 0) {
                                    DB::table('serp_competitors')->where('domain_id', $domain_id)->where('keyword_id', $keyword_data->id)->where("platform", $platform)->delete();

                                    foreach ($all_competitors as $competitor) {
                                        DB::table('serp_competitors')->insert([
                                            'user_id' => Session::get('user_id'),
                                            'domain_id' => $domain_id,
                                            'keyword_id'   =>  $keyword_data->id,
                                            'serp_competitor_id'    =>  $reference_id,
                                            'keyword'   =>  $keyword_data->keyword,
                                            'competitor'    =>  $competitor['url'],
                                            'avg_position'  =>  $competitor['rank_group'],
                                            "platform"      =>  $platform,
                                            'date'  =>  $date
                                        ]);
                                    }

                                    DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                                        'updated'   =>  $date,
                                        'api_running'   =>  'no'
                                    ]);

                                    DB::table('domains')->where('id', $domain_id)->update([
                                        'updated'    =>  $date
                                    ]);

                                    $competitors = DB::table('serp_competitors')->where('keyword_id', $keyword_data->id)->get();
                                    $desktop_rank = null;
                                    $mobile_rank = null;

                                    foreach ($competitors as $com) {
                                        if (strpos($com->competitor, $domain_url) !== false) {
                                            // if(strpos($com->competitor, $com->keyword !== false)) {
                                            if ($com->platform == "desktop" && $desktop_rank == null) {
                                                $desktop_rank = $com->avg_position;
                                            }
                                            if ($com->platform == "mobile" && $mobile_rank == null) {
                                                $mobile_rank = $com->avg_position;
                                            }
                                        }
                                    }
                                    if ($desktop_rank == null) {
                                        $desktop_rank = 0;
                                    }
                                    if ($mobile_rank == null) {
                                        $mobile_rank = 0;
                                    }

                                    DB::table('keywords_history')->insert([
                                        'user_id'   =>  Session::get('user_id'),
                                        'keyword_id'   =>  $keyword_data->id,
                                        'desktop_rank'   =>  $desktop_rank,
                                        'mobile_rank'   =>  $mobile_rank,
                                        'date'   =>  date('Y-m-d H:i:s')
                                    ]);
                                }
                                
                                if($platform == "desktop") {
                                    DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                                        'api_reference_desktop'   =>  null
                                    ]);
                                }
                                if($platform == "mobile") {
                                    DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                                        'api_reference_mobile'   =>  null
                                    ]);
                                }
                            }
                            DB::table('domain_keywords')->where('api_reference_desktop', null)->where('api_reference_mobile', null)->update([
                                'api_running'   =>   'no',
                            ]);
                        }

                    }
                }

                prx($result);


            }
        } catch (RestClientException $e) {
            
        }
        $client = null;

    }  
  
    public function testing()
    {
        $ch = curl_init('https://api.keywordseverywhere.com/v1/get_keyword_data');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Authorization: Bearer 00341172f4f6b166c623'
        ));

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
        urldecode(http_build_query([
            "dataSource" => "gkp",
            "country" => "us",
            "kw" => [
            "ranktools"
            ]
        ]))
        );

        $data = curl_exec($ch);
        $err = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $volume = 0;
        if($info['http_code'] == 200){
            $dataa = json_decode($data);
            prx($dataa);
            $volume = $dataa->data[0]->vol;
            $monthly_trend = $dataa->data[0]->trend;
            foreach ($monthly_trend as $trend) {
                prx($trend->month);
            }
        } else {
            prx($data);
        }        
    }

    public function UserDashboard()
    {
        $locations = DB::table('serp_google_locations')->where('status', 1)->orderBy('location_name', 'ASC')->get();
        $languages = DB::table('serp_google_languages')->where('status', 1)->orderBy('language_name', 'ASC')->get();
        $domains = DB::table('domains')->where('user_id', Session::get('user_id'))->where('status',1)->orderBy('domain', 'ASC')->get();
        return view('seo.dashboard', compact('locations', 'languages', 'domains'));
    }

    public function AddDomain(Request $request)
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();
        $keywords_everywhere_token = 'Bearer '.$dataforseo_api->keywords_everywhere_api;
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
        $visits = 0;
        $keywords = explode(",", $request->all_keywords);
        $platform    =    $request->platform;

        // prx($keywords);

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
        $post_array = array();
        $post_array[] = array(
            "target" => $request->domain
        );

        $domain_id = DB::table('domains')->insertGetId([
            'user_id'   => $request->session()->get('user_id'),
            'domain'    =>  $request->domain,
            'location_code'    =>  $request->location_code,
            'language_code'    =>  $request->language_code,
            'date'          => $date,
        ]);

        $client = null;

        $keywords_count = 0;
        $package_keywords_limit = $user_package_data->domain_keyword_limit;

        $all_keywords_count = all_domain_keywords_count();
        if ($package_keywords_limit == "Unlimited") {
            $package_keywords_limit = 9999999;
        } else {
            if ($all_keywords_count < $user_package_data->keywords_limit) {
                $remaining_overall_keywords = $user_package_data->keywords_limit - $all_keywords_count;
                if ($remaining_overall_keywords < $package_keywords_limit) {
                    $package_keywords_limit = $remaining_overall_keywords;
                }
            } else {
                $package_keywords_limit = 0;
            }
        }

        foreach ($keywords as $key) {
            $remaining_refreshers  = refreshes();
            if($remaining_refreshers > 0) {
                if ($key != null && $key != ' ' && $package_keywords_limit > 0) {
                    if ($platform == "desktop") {
                        $package_keywords_limit--;
    
                        // $total_search_volume = null;
    
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
                        $post_array = array();
                        $post_array[] = array(
                            "location_code" => $request->location_code,
                            "language_code" => $request->language_code,
                            "keywords" => array(
                                $key
                            )
                        );

                        $ch = curl_init('https://api.keywordseverywhere.com/v1/get_keyword_data');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Accept: application/json',
                        'Authorization: '.$keywords_everywhere_token
                        ));

                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS,
                        urldecode(http_build_query([
                            "dataSource" => "gkp",
                            "country" => "us",
                            "kw" => [
                                $key
                            ]
                        ]))
                        );

                        $data = curl_exec($ch);
                        $err = curl_error($ch);
                        $info = curl_getinfo($ch);
                        curl_close($ch);
                        $volume = 0;
                        $monthly_trend = array();
                        if($info['http_code'] == 200){
                            $dataa = json_decode($data);
                            $volume = $dataa->data[0]->vol;
                            $monthly_trend = $dataa->data[0]->trend;
                            
                        }

                        $database_keyword_id = DB::table('domain_keywords')->insertGetId([
                            'domain_id' =>  $domain_id,
                            'user_id'   => $request->session()->get('user_id'),
                            'keyword'  =>  $key,
                            'platform'  =>  $platform,
                            'volume'  =>  $volume,
                            'date'      =>  $date
                        ]);

                        foreach ($monthly_trend as $trend) {
                            DB::table('domain_keywords_monthly_volume')->insertGetId([
                                'domain_id' =>  $domain_id,
                                'user_id'   => $request->session()->get('user_id'),
                                'keyword'  =>  $key,
                                'keyword_id'    =>  $database_keyword_id,
                                'year'  =>  $trend->year,
                                'month'  =>  $trend->month,
                                'search_volume'    =>  $trend->value,
                                'date'      =>  $date
                            ]);
                        }
    
                        DB::table('user_refreshes')->insert([
                            'keyword_id'    =>  $database_keyword_id,
                            'user_id'    =>  Session::get('user_id'),
                            'keyword_platform'  =>  $platform,
                            'date'  =>  date('Y-m-d H:i:s')
                        ]);
    
    
                        $client = null;
    
    
                        // DB::table('domain_keywords')->where('id', $database_keyword_id)->update([
                        //     'volume'    =>  $total_search_volume,
                        // ]);
    
                        $keywords_count++;
    
                        $api_url = 'https://api.dataforseo.com/';
                        $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                        $post_array = array();
                        $post_array[] = array(
                            "keyword" => mb_convert_encoding($key, "UTF-8"),
                            "language_code" => $request->language_code,
                            "location_code" => $request->location_code,
                            "device" => "desktop"
                        );
                        try {
                            // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                            $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                            // prx($result);
                            if ($result['status_message'] = "Ok.") {
                                $reference_id = $result['tasks'][0]['id'];
                                if ($result['tasks'][0]['status_message'] == "Ok.") {
                                    $all_competitors = $result['tasks'][0]['result'][0]['items'];
    
                                    // prx($result);
                                    if (isset($all_competitors) && count($all_competitors)  > 0) {
                                        foreach ($all_competitors as $competitor) {
                                            DB::table('serp_competitors')->insert([
                                                'user_id' => Session::get('user_id'),
                                                'domain_id' => $domain_id,
                                                'keyword_id' => $database_keyword_id,
                                                'serp_competitor_id'    =>  $reference_id,
                                                'keyword'   =>  $key,
                                                'competitor'    =>  $competitor['url'],
                                                'avg_position'  =>  $competitor['rank_group'],
                                                "platform"      =>  "desktop",
                                                'date'  =>  $date
                                            ]);
                                        }
                                    }
                                }
                            }
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
    
                    if ($platform == "mobile") {
                        $package_keywords_limit--;
    
                        // $total_search_volume = null;
    
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
                        $post_array = array();
                        $post_array[] = array(
                            "location_code" => $request->location_code,
                            "language_code" => $request->language_code,
                            "keywords" => array(
                                $key
                            )
                        );

                        $ch = curl_init('https://api.keywordseverywhere.com/v1/get_keyword_data');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Accept: application/json',
                        'Authorization: '.$keywords_everywhere_token
                        ));

                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS,
                        urldecode(http_build_query([
                            "dataSource" => "gkp",
                            "country" => "us",
                            "kw" => [
                                $key
                            ]
                        ]))
                        );

                        $data = curl_exec($ch);
                        $err = curl_error($ch);
                        $info = curl_getinfo($ch);
                        curl_close($ch);
                        $volume = 0;
                        $monthly_trend = array();
                        if($info['http_code'] == 200){
                            $dataa = json_decode($data);
                            $volume = $dataa->data[0]->vol;
                            $monthly_trend = $dataa->data[0]->trend;
                            
                        }

                        $database_keyword_id = DB::table('domain_keywords')->insertGetId([
                            'domain_id' =>  $domain_id,
                            'user_id'   => $request->session()->get('user_id'),
                            'keyword'  =>  $key,
                            'platform'  =>  $platform,
                            'volume'  =>  $volume,
                            'date'      =>  $date
                        ]);

                        foreach ($monthly_trend as $trend) {
                            DB::table('domain_keywords_monthly_volume')->insertGetId([
                                'domain_id' =>  $domain_id,
                                'user_id'   => $request->session()->get('user_id'),
                                'keyword'  =>  $key,
                                'keyword_id'    =>  $database_keyword_id,
                                'year'  =>  $trend->year,
                                'month'  =>  $trend->month,
                                'search_volume'    =>  $trend->value,
                                'date'      =>  $date
                            ]);
                        }
    
                        DB::table('user_refreshes')->insert([
                            'keyword_id'    =>  $database_keyword_id,
                            'user_id'    =>  Session::get('user_id'),
                            'keyword_platform'  =>  $platform,
                            'date'  =>  date('Y-m-d H:i:s')
                        ]);
    
                        $client = null;
    
    
                        // DB::table('domain_keywords')->where('id', $database_keyword_id)->update([
                        //     'volume'    =>  $total_search_volume,
                        // ]);
    
                        $keywords_count++;
                        $api_url = 'https://api.dataforseo.com/';
                        $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                        $post_array = array();
                        $post_array[] = array(
                            "keyword" => mb_convert_encoding($key, "UTF-8"),
                            "language_code" => $request->language_code,
                            "location_code" => $request->location_code,
                            "device" => "mobile"
                        );
                        try {
                            // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                            $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                            // prx($result);
                            if ($result['status_message'] = "Ok.") {
                                $reference_id = $result['tasks'][0]['id'];
                                if ($result['tasks'][0]['status_message'] == "Ok.") {
                                    $all_competitors = $result['tasks'][0]['result'][0]['items'];
    
                                    // prx($result);
                                    if (isset($all_competitors) && count($all_competitors)  > 0) {
                                        foreach ($all_competitors as $competitor) {
                                            DB::table('serp_competitors')->insert([
                                                'user_id' => Session::get('user_id'),
                                                'domain_id' => $domain_id,
                                                'keyword_id' => $database_keyword_id,
                                                'serp_competitor_id'    =>  $reference_id,
                                                'keyword'   =>  $key,
                                                'competitor'    =>  $competitor['url'],
                                                'avg_position'  =>  $competitor['rank_group'],
                                                "platform"      =>  "mobile",
                                                'date'  =>  $date
                                            ]);
                                        }
                                    }
                                }
                            }
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
                    if ($platform == "desktop and mobile") {
                        
                        $package_keywords_limit -= 2;
    
                        $total_search_volume = null;
    
                        //Start key volume api
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
                        $post_array = array();
                        $post_array[] = array(
                            "location_code" => $request->location_code,
                            "language_code" => $request->language_code,
                            "keywords" => array(
                                $key
                            )
                        );

                        $ch = curl_init('https://api.keywordseverywhere.com/v1/get_keyword_data');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Accept: application/json',
                        'Authorization: '.$keywords_everywhere_token
                        ));

                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS,
                        urldecode(http_build_query([
                            "dataSource" => "gkp",
                            "country" => "us",
                            "kw" => [
                                $key
                            ]
                        ]))
                        );

                        $data = curl_exec($ch);
                        $err = curl_error($ch);
                        $info = curl_getinfo($ch);
                        curl_close($ch);
                        $volume = 0;
                        $monthly_trend = array();
                        if($info['http_code'] == 200){
                            $dataa = json_decode($data);
                            $volume = $dataa->data[0]->vol;
                            $monthly_trend = $dataa->data[0]->trend;
                            
                        }
    
                        $database_keyword_id = DB::table('domain_keywords')->insertGetId([
                            'domain_id' =>  $domain_id,
                            'user_id'   => $request->session()->get('user_id'),
                            'keyword'  =>  $key,
                            'platform'  =>  "desktop",
                            'volume'  =>  $volume,
                            'date'      =>  $date
                        ]);
    
                        foreach ($monthly_trend as $trend) {
                            DB::table('domain_keywords_monthly_volume')->insertGetId([
                                'domain_id' =>  $domain_id,
                                'user_id'   => $request->session()->get('user_id'),
                                'keyword'  =>  $key,
                                'keyword_id'    =>  $database_keyword_id,
                                'year'  =>  $trend->year,
                                'month'  =>  $trend->month,
                                'search_volume'    =>  $trend->value,
                                'date'      =>  $date
                            ]);
                        }

                        DB::table('user_refreshes')->insert([
                            'keyword_id'    =>  $database_keyword_id,
                            'user_id'    =>  Session::get('user_id'),
                            'keyword_platform'  =>  "desktop and mobile",
                            'date'  =>  date('Y-m-d H:i:s')
                        ]);
    
                        $client = null;
                        // End key volume api
    
                        // DB::table('domain_keywords')->where('id', $database_keyword_id)->update([
                        //    'volume'    =>  $total_search_volume,
                        // ]);
    
                        $keywords_count++;
    
                        $api_url = 'https://api.dataforseo.com/';
                        $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                        $post_array = array();
                        $post_array[] = array(
                            "keyword" => mb_convert_encoding($key, "UTF-8"),
                            "language_code" => $request->language_code,
                            "location_code" => $request->location_code,
                            "device" => "desktop"
                        );
                        try {
                            // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                            $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                            // prx($result);
                            if ($result['status_message'] = "Ok.") {
                                $reference_id = $result['tasks'][0]['id'];
                                if ($result['tasks'][0]['status_message'] == "Ok.") {
                                    $all_competitors = $result['tasks'][0]['result'][0]['items'];
    
                                    // prx($result);
                                    if (isset($all_competitors) && count($all_competitors)  > 0) {
                                        foreach ($all_competitors as $competitor) {
                                            DB::table('serp_competitors')->insert([
                                                'user_id' => Session::get('user_id'),
                                                'domain_id' => $domain_id,
                                                'keyword_id' => $database_keyword_id,
                                                'serp_competitor_id'    =>  $reference_id,
                                                'keyword'   =>  $key,
                                                'competitor'    =>  $competitor['url'],
                                                'avg_position'  =>  $competitor['rank_group'],
                                                "platform"      =>  "desktop",
                                                'date'  =>  $date
                                            ]);
                                        }
                                    }
                                }
                            }
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
    
                        if ($package_keywords_limit > 0) {
                            $keywords_count++;
    
                            DB::table('domain_keywords')->where('id', $database_keyword_id)->update([
                                'platform'  =>  "desktop and mobile"
                            ]);
                            $api_url = 'https://api.dataforseo.com/';
                            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                            $post_array = array();
                            $post_array[] = array(
                                "keyword" => mb_convert_encoding($key, "UTF-8"),
                                "language_code" => $request->language_code,
                                "location_code" => $request->location_code,
                                "device" => "mobile"
                            );
                            try {
                                // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                                $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                                if ($result['status_message'] = "Ok.") {
                                    $reference_id = $result['tasks'][0]['id'];
                                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                                        $all_competitors = $result['tasks'][0]['result'][0]['items'];
    
                                        if (isset($all_competitors) && count($all_competitors)  > 0) {
                                            foreach ($all_competitors as $competitor) {
                                                DB::table('serp_competitors')->insert([
                                                    'user_id' => Session::get('user_id'),
                                                    'domain_id' => $domain_id,
                                                    'keyword_id' => $database_keyword_id,
                                                    'serp_competitor_id'    =>  $reference_id,
                                                    'keyword'   =>  $key,
                                                    'competitor'    =>  $competitor['url'],
                                                    'avg_position'  =>  $competitor['rank_group'],
                                                    "platform"      =>  "mobile",
                                                    'date'  =>  $date
                                                ]);
                                            }
                                        }
                                    }
                                }
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
                }
            } else {
                Session::flash('message', 'Geschatte Dagelijkse Werklast gebruik wacht 24 uur of upgrade abonnement...');
                Session::flash('alert-type', 'info');
            }
        }

        //Backlinks Start
        // if ($user_package_data->backlinks == 1) {
        //     $api_url = 'https://api.dataforseo.com/';
        //     $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);

        //     $post_array = array();
        //     $post_array[] = array(
        //         "target" => $request->domain,
        //         "limit" => 25,
        //         "internal_list_limit" => 10,
        //     );
        //     try {
        //         $result = $client->post('/v3/backlinks/anchors/live', $post_array);
        //         // prx($result);

        //         if ($result['status_message'] == "Ok.") {
        //             if ($result['tasks'][0]['status_message'] == "Ok.") {
        //                 $all_anchors = $result['tasks'][0]['result'][0]['items'];
        //                 if (isset($all_anchors) && count($all_anchors) > 0) {
        //                     foreach ($all_anchors as $anchor) {
        //                         // prx($anchor);
        //                         DB::table('anchors')->insert([
        //                             'user_id'   =>  Session::get('user_id'),
        //                             'domain_id'   =>  $domain_id,
        //                             'anchor'    =>  $anchor['anchor'],
        //                             'count'     =>  $anchor['referring_domains']
        //                         ]);
        //                     }
        //                 }
        //             }
        //         }
        //         // do something with post result
        //     } catch (RestClientException $e) {
        //         echo "n";
        //         print "HTTP code: {$e->getHttpCode()}n";
        //         print "Error code: {$e->getCode()}n";
        //         print "Message: {$e->getMessage()}n";
        //         print  $e->getTraceAsString();
        //         echo "n";
        //     }
        //     $client = null;



        //     $api_url = 'https://api.dataforseo.com/';
        //     $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);

        //     $post_array = array();
        //     $post_array[] = array(
        //         "target" => $request->domain,
        //         "limit" => 100,
        //         "internal_list_limit" => 10,
        //     );
        //     try {
        //         $result = $client->post('/v3/backlinks/history/live', $post_array);
        //         // prx($result);

        //         if ($result['status_message'] == "Ok.") {
        //             if ($result['tasks'][0]['status_message'] == "Ok.") {
        //                 $all_results = $result['tasks'][0]['result'][0]['items'];
        //                 if (isset($all_results) && count($all_results) > 0) {
        //                     foreach ($all_results as $res) {
        //                         // prx($anchor);
        //                         DB::table('backlinks_history')->insert([
        //                             'user_id'   =>  Session::get('user_id'),
        //                             'domain_id'   =>  $domain_id,
        //                             'backlinks_count'    =>  $res['backlinks'],
        //                             'new_backlinks'    =>  $res['new_backlinks'],
        //                             'lost_backlinks'    =>  $res['lost_backlinks'],
        //                             'anchors_count'     =>  $res['referring_links_types']['anchor'],
        //                             'date'      =>  $res['date']
        //                         ]);
        //                     }
        //                 }
        //             }
        //         }
        //         // do something with post result
        //     } catch (RestClientException $e) {
        //         echo "n";
        //         print "HTTP code: {$e->getHttpCode()}n";
        //         print "Error code: {$e->getCode()}n";
        //         print "Message: {$e->getMessage()}n";
        //         print  $e->getTraceAsString();
        //         echo "n";
        //     }
        //     $client = null;


        //     $api_url = 'https://api.dataforseo.com/';
        //     $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        //     $post_array = array();
        //     $post_array[] = array(
        //         "target" => $request->domain,
        //         "limit" =>  50,
        //         "mode" => "as_is",
        //         "filters" => ["dofollow", "=", false]
        //     );
        //     try {
        //         $result = $client->post('/v3/backlinks/backlinks/live', $post_array);
        //         if ($result['status_message'] == "Ok.") {
        //             if ($result['tasks'][0]['status_message'] == "Ok.") {
        //                 $total_count = $result['tasks'][0]['result'][0]['total_count'];
        //                 $all_backlinks = $result['tasks'][0]['result'][0]['items'];
        //                 if (isset($all_backlinks) && count($all_backlinks) > 0) {
        //                     foreach ($all_backlinks as $backlink) {
        //                         $already_exists = DB::table('backlinks')->where('domain_id', $domain_id)->where('user_id', Session::get('user_id'))->where('url_from', $backlink['url_from'])->first();
        //                         if (!$already_exists) {
        //                             DB::table('backlinks')->insert([
        //                                 'user_id'   =>  Session::get('user_id'),
        //                                 'domain_id'   =>  $domain_id,
        //                                 'total_count'   =>  $total_count,
        //                                 'url_from'   =>  $backlink['url_from'],
        //                                 'title'   =>  $backlink['page_from_title'],
        //                                 'domain_to'   =>  $backlink['domain_to'],
        //                                 'is_new'   =>  $backlink['is_new'],
        //                                 'is_lost'   =>  $backlink['is_lost'],
        //                                 'do_follow'   =>  $backlink['dofollow'],
        //                                 'p_a'   =>  $backlink['page_from_rank'],
        //                                 'd_a'   =>  $backlink['domain_from_rank'],
        //                                 'domain_from_rank'  =>  $backlink['domain_from_rank'],
        //                                 'date'   =>  date('Y-m-d H:i:s')
        //                             ]);
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     } catch (RestClientException $e) {
        //     }
        //     $client = null;





        //     $api_url = 'https://api.dataforseo.com/';
        //     $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        //     $post_arrayy = array();
        //     if ($user_package_data->domain_backlinks_rows_limit == "Unlimited") {
        //         $post_arrayy[] = array(
        //             "target" => $request->domain,
        //             "limit" =>  300
        //         );
        //     } else {
        //         $post_arrayy[] = array(
        //             "target" => $request->domain,
        //             "limit" =>  $user_package_data->domain_backlinks_rows_limit
        //         );
        //     }
        //     try {
        //         $result = $client->post('/v3/backlinks/backlinks/live', $post_arrayy);
        //         // prx($result);
        //         if ($result['status_message'] == "Ok.") {
        //             if ($result['tasks'][0]['status_message'] == "Ok.") {
        //                 $total_count = $result['tasks'][0]['result'][0]['total_count'];
        //                 $all_backlinks = $result['tasks'][0]['result'][0]['items'];
        //                 if (isset($all_backlinks) && count($all_backlinks) > 0) {
        //                     foreach ($all_backlinks as $backlink) {
        //                         $already_exists = DB::table('backlinks')->where('domain_id', $domain_id)->where('user_id', Session::get('user_id'))->where('url_from', $backlink['url_from'])->first();
        //                         if (!$already_exists) {
        //                             DB::table('backlinks')->insert([
        //                                 'user_id'   =>  Session::get('user_id'),
        //                                 'domain_id'   =>  $domain_id,
        //                                 'total_count'   =>  $total_count,
        //                                 'url_from'   =>  $backlink['url_from'],
        //                                 'title'   =>  $backlink['page_from_title'],
        //                                 'domain_to'   =>  $backlink['domain_to'],
        //                                 'is_new'   =>  $backlink['is_new'],
        //                                 'is_lost'   =>  $backlink['is_lost'],
        //                                 'do_follow'   =>  $backlink['dofollow'],
        //                                 'p_a'   =>  $backlink['page_from_rank'],
        //                                 'd_a'   =>  $backlink['domain_from_rank'],
        //                                 'domain_from_rank'  =>  $backlink['domain_from_rank'],
        //                                 'date'   =>  date('Y-m-d H:i:s')
        //                             ]);
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //         // prx($result);

        //         // do something with post result
        //     } catch (RestClientException $e) {
        //     }
        //     $client = null;
        // }
        //Backlinks End






        $domain_data = DB::table('domains')->where('id', $domain_id)->first();
        $platform = $domain_data->platform;


        $keywords_data = DB::table('domain_keywords')->where('domain_id', $domain_id)->get();
        foreach ($keywords_data as $keyword) {
            $competitors = DB::table('serp_competitors')->where('domain_id', $domain_id)->where('keyword', $keyword->keyword)->get();
            // echo "<pre>";
            // print_r($competitors);
            $keyword->desktop_rank = array();
            $keyword->mobile_rank = array();
            foreach ($competitors as $com) {
                if (strpos($com->competitor, $domain_data->domain) !== false) {
                    // if(strpos($com->competitor, $com->keyword !== false)) {
                    if ($com->platform == "desktop" && $keyword->desktop_rank == null) {
                        $keyword->desktop_rank = $com->avg_position;
                    }
                    if ($com->platform == "mobile" && $keyword->mobile_rank == null) {
                        $keyword->mobile_rank = $com->avg_position;
                    }
                }
            }
            $keyword->competitor = array();
            array_push($keyword->competitor, $competitors);
        }

        $total = 0;
        $keyword_index = 0;
        foreach ($keywords_data as $keyword) {
            if ($keyword->platform == "desktop and mobile") {
                $total += 2;
            } else {
                $total++;
            }
            if (isset($keyword->desktop_rank)) {
                if ($keyword->desktop_rank != null) {
                    $keyword_index += $keyword->desktop_rank;
                }
            }
            if (isset($keyword->mobile_rank)) {
                if ($keyword->mobile_rank != null) {
                    $keyword_index += $keyword->mobile_rank;
                }
            }
        }
        $new_avg_position = 0;
        if ($total > 0 && $keyword_index > 0) {
            $new_avg_position = round($keyword_index / $total);
            DB::table('domains')->where('id', $domain_id)->update([
                'avg_position'  =>  $new_avg_position
            ]);
        } else {
            DB::table('domains')->where('id', $domain_id)->update([
                'avg_position'  =>  0
            ]);
        }

        return response()->json(['status' => 'successfull', 'keywords' => $keywords_count, 'visits' => $visits, 'domain_id' => $domain_id, 'new_avg_position' => $new_avg_position, 'last_updated' => "0 hours"]);
        // return redirect()->back();
    }

    public function AddKeyword(Request $request)
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();
        $keywords_everywhere_token = 'Bearer '.$dataforseo_api->keywords_everywhere_api;
        $current_user_data = DB::table('users')->where('id', Session::get('user_id'))->first();
        $user_package_data = DB::table('packages')->where('id', $current_user_data->package_id)->first();

        $domain_id = $request->domain_id;
        $keywords = explode(",", $request->all_keywords);
        $platform    =    $request->platform;
        $date = date('Y-m-d H:i:S');

        DB::table('domains')->where('id', $domain_id)->update([
            'updated'    =>  $date
        ]);
        set_time_limit(1500);
        $keywords_count = 0;
        $package_keywords_limit = $user_package_data->domain_keyword_limit;

        if ($package_keywords_limit == "Unlimited") {
            $package_keywords_limit = 999999;
        } else {
            $all_keywords_count = all_domain_keywords_count();
            $domain_existing_keywords = domain_keywords_count($domain_id);
            if ($domain_existing_keywords > 0) {
                $package_keywords_limit -= $domain_existing_keywords;
            }

            if ($all_keywords_count < $user_package_data->keywords_limit) {
                $remaining_overall_keywords = $user_package_data->keywords_limit - $all_keywords_count;
                if ($remaining_overall_keywords < $package_keywords_limit) {
                    $package_keywords_limit = $remaining_overall_keywords;
                }
            } else {
                $request->session()->flash('message', 'Keywords Limit Reached.');
                $request->session()->flash('alert-type', 'error');
            }
        }

        // prx($user_package_data);
        foreach ($keywords as $key) {
            $remaining_refreshers = refreshes();
            if($remaining_refreshers > 0) {
                if ($key != null && $key != ' ' && $package_keywords_limit > 0) {
                    if ($platform == "desktop") {
                        $package_keywords_limit--;
    
                        // $total_search_volume = null;
    
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
                        $post_array = array();
                        $post_array[] = array(
                            "location_code" => $request->location_code,
                            "language_code" => $request->language_code,
                            "keywords" => array(
                                $key
                            )
                        );
    
                        $ch = curl_init('https://api.keywordseverywhere.com/v1/get_keyword_data');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Accept: application/json',
                        'Authorization: '.$keywords_everywhere_token
                        ));

                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS,
                        urldecode(http_build_query([
                            "dataSource" => "gkp",
                            "country" => "us",
                            "kw" => [
                                $key
                            ]
                        ]))
                        );

                        $data = curl_exec($ch);
                        $err = curl_error($ch);
                        $info = curl_getinfo($ch);
                        curl_close($ch);
                        $volume = 0;
                        $monthly_trend = array();
                        if($info['http_code'] == 200){
                            $dataa = json_decode($data);
                            $volume = $dataa->data[0]->vol;
                            $monthly_trend = $dataa->data[0]->trend;
                            
                        }

                        $database_keyword_id = DB::table('domain_keywords')->insertGetId([
                            'domain_id' =>  $domain_id,
                            'user_id'   => $request->session()->get('user_id'),
                            'keyword'  =>  $key,
                            'volume'    =>  $volume,
                            'platform'  =>  $platform,
                            'date'      =>  $date
                        ]);

                        foreach ($monthly_trend as $trend) {
                            DB::table('domain_keywords_monthly_volume')->insertGetId([
                                'domain_id' =>  $domain_id,
                                'user_id'   => $request->session()->get('user_id'),
                                'keyword'  =>  $key,
                                'keyword_id'    =>  $database_keyword_id,
                                'year'  =>  $trend->year,
                                'month'  =>  $trend->month,
                                'search_volume'    =>  $trend->value,
                                'date'      =>  $date
                            ]);
                        }
    
                        DB::table('user_refreshes')->insert([
                            'keyword_id'    =>  $database_keyword_id,
                            'user_id'    =>  Session::get('user_id'),
                            'keyword_platform'  =>  $platform,
                            'date'  =>  date('Y-m-d H:i:s')
                        ]);
    
                        
    
                        // try {
                        //     $result = $client->post('/v3/keywords_data/google/search_volume/live', $post_array);
                        //     $result = json_decode(json_encode($result), false);
                        //     if ($result->status_message == "Ok.") {
                        //         // $search_volume_id = $result->tasks[0]->id;
                        //         if ($result->tasks[0]->status_message == "Ok.") {
                        //             $final_result = $result->tasks[0]->result[0];
                        //             $total_search_volume = $final_result->search_volume;
                        //             $monthlySearches = $final_result->monthly_searches;
                        //             if (isset($monthlySearches) && count($monthlySearches) > 0) {
                        //                 foreach ($monthlySearches as $search) {
                        //                     DB::table('domain_keywords_monthly_volume')->insert([
                        //                         'user_id' =>    Session::get('user_id'),
                        //                         'domain_id' =>    $domain_id,
                        //                         'keyword_id' =>    $database_keyword_id,
                        //                         'keyword' =>    $key,
                        //                         'year' =>    $search->year,
                        //                         'month' =>    $search->month,
                        //                         'search_volume' =>    $search->search_volume,
                        //                         'date' =>    $date
                        //                     ]);
                        //                 }
                        //             }
                        //         }
                        //     }
                        // } catch (RestClientException $e) {
                        //     echo "\n";
                        //     print "HTTP code: {$e->getHttpCode()}\n";
                        //     print "Error code: {$e->getCode()}\n";
                        //     print "Message: {$e->getMessage()}\n";
                        //     print  $e->getTraceAsString();
                        //     echo "\n";
                        // }
                        $client = null;
                        // End key volume api
    
    
                        // DB::table('domain_keywords')->where('id', $database_keyword_id)->update([
                        //     'volume'    =>  $total_search_volume,
                        // ]);
    
                        $keywords_count++;
    
                        $api_url = 'https://api.dataforseo.com/';
                        $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                        $post_array = array();
                        $post_array[] = array(
                            "keyword" => mb_convert_encoding($key, "UTF-8"),
                            "language_code" => $request->language_code,
                            "location_code" => $request->location_code,
                            "device" => "desktop"
                        );
    
                        try {
                            // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                            $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                            // prx($result);
                            if ($result['status_message'] = "Ok.") {
                                $reference_id = $result['tasks'][0]['id'];
                                if ($result['tasks'][0]['status_message'] == "Ok.") {
                                    $all_competitors = $result['tasks'][0]['result'][0]['items'];
                                    if (isset($all_competitors) && count($all_competitors)  > 0) {
                                        foreach ($all_competitors as $competitor) {
                                            DB::table('serp_competitors')->insert([
                                                'user_id' => Session::get('user_id'),
                                                'domain_id' => $domain_id,
                                                'keyword_id' => $database_keyword_id,
                                                'serp_competitor_id'    =>  $reference_id,
                                                'keyword'   =>  $key,
                                                'competitor'    =>  $competitor['url'],
                                                'avg_position'  =>  $competitor['rank_group'],
                                                "platform"      =>  "desktop",
                                                'date'  =>  $date
                                            ]);
                                        }
                                    }
                                }
                            }
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
    
                    if ($platform == "mobile") {
                        $package_keywords_limit--;
    
    
                        // $total_search_volume = null;
    
                        $api_url = 'https://api.dataforseo.com/';
                        try {
                            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                        } catch (RestClientException $e) {
                        }
                        $post_array = array();
                        $post_array[] = array(
                            "location_code" => $request->location_code,
                            "language_code" => $request->language_code,
                            "keywords" => array(
                                $key
                            )
                        );

                        $ch = curl_init('https://api.keywordseverywhere.com/v1/get_keyword_data');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Accept: application/json',
                        'Authorization: '.$keywords_everywhere_token
                        ));

                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS,
                        urldecode(http_build_query([
                            "dataSource" => "gkp",
                            "country" => "us",
                            "kw" => [
                                $key
                            ]
                        ]))
                        );

                        $data = curl_exec($ch);
                        $err = curl_error($ch);
                        $info = curl_getinfo($ch);
                        curl_close($ch);
                        $volume = 0;
                        $monthly_trend = array();
                        if($info['http_code'] == 200){
                            $dataa = json_decode($data);
                            $volume = $dataa->data[0]->vol;
                            $monthly_trend = $dataa->data[0]->trend;
                            
                        }
    
                        $database_keyword_id = DB::table('domain_keywords')->insertGetId([
                            'domain_id' =>  $domain_id,
                            'user_id'   => $request->session()->get('user_id'),
                            'keyword'  =>  $key,
                            'platform'  =>  $platform,
                            'volume'  =>  $volume,
                            'date'      =>  $date
                        ]);

                        foreach ($monthly_trend as $trend) {
                            DB::table('domain_keywords_monthly_volume')->insertGetId([
                                'domain_id' =>  $domain_id,
                                'user_id'   => $request->session()->get('user_id'),
                                'keyword'  =>  $key,
                                'keyword_id'    =>  $database_keyword_id,
                                'year'  =>  $trend->year,
                                'month'  =>  $trend->month,
                                'search_volume'    =>  $trend->value,
                                'date'      =>  $date
                            ]);
                        }
    
                        DB::table('user_refreshes')->insert([
                            'keyword_id'    =>  $database_keyword_id,
                            'user_id'    =>  Session::get('user_id'),
                            'keyword_platform'  =>  $platform,
                            'date'  =>  date('Y-m-d H:i:s')
                        ]);
    
                        // try {
                        //     $result = $client->post('/v3/keywords_data/google/search_volume/live', $post_array);
                        //     $result = json_decode(json_encode($result), false);
                        //     if ($result->status_message == "Ok.") {
                        //         // $search_volume_id = $result->tasks[0]->id;
                        //         if ($result->tasks[0]->status_message == "Ok.") {
                        //             $final_result = $result->tasks[0]->result[0];
                        //             $total_search_volume = $final_result->search_volume;
                        //             $monthlySearches = $final_result->monthly_searches;
                        //             // prx($monthlySearches);
                        //             if (isset($monthlySearches) && count($monthlySearches) > 0) {
                        //                 foreach ($monthlySearches as $search) {
                        //                     DB::table('domain_keywords_monthly_volume')->insert([
                        //                         'user_id' =>    Session::get('user_id'),
                        //                         'domain_id' =>    $domain_id,
                        //                         'keyword_id' =>    $database_keyword_id,
                        //                         'keyword' =>    $key,
                        //                         'year' =>    $search->year,
                        //                         'month' =>    $search->month,
                        //                         'search_volume' =>    $search->search_volume,
                        //                         'date' =>    $date
                        //                     ]);
                        //                 }
                        //             }
                        //         }
                        //     }
                        // } catch (RestClientException $e) {
                        //     echo "\n";
                        //     print "HTTP code: {$e->getHttpCode()}\n";
                        //     print "Error code: {$e->getCode()}\n";
                        //     print "Message: {$e->getMessage()}\n";
                        //     print  $e->getTraceAsString();
                        //     echo "\n";
                        // }
                        $client = null;
    
    
                        // DB::table('domain_keywords')->where('id', $database_keyword_id)->update([
                        //     'volume'    =>  $total_search_volume,
                        // ]);
    
    
                        $keywords_count++;
                        $api_url = 'https://api.dataforseo.com/';
                        $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                        $post_array = array();
                        $post_array[] = array(
                            "keyword" => mb_convert_encoding($key, "UTF-8"),
                            "language_code" => $request->language_code,
                            "location_code" => $request->location_code,
                            "device" => "mobile"
                        );
                        try {
                            // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                            $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                            // prx($result);
                            if ($result['status_message'] = "Ok.") {
                                $reference_id = $result['tasks'][0]['id'];
                                if ($result['tasks'][0]['status_message'] == "Ok.") {
                                    $all_competitors = $result['tasks'][0]['result'][0]['items'];
    
                                    // prx($result);
                                    if (isset($all_competitors) && count($all_competitors)  > 0) {
                                        foreach ($all_competitors as $competitor) {
                                            DB::table('serp_competitors')->insert([
                                                'user_id' => Session::get('user_id'),
                                                'domain_id' => $domain_id,
                                                'keyword_id' => $database_keyword_id,
                                                'serp_competitor_id'    =>  $reference_id,
                                                'keyword'   =>  $key,
                                                'competitor'    =>  $competitor['url'],
                                                'avg_position'  =>  $competitor['rank_group'],
                                                "platform"      =>  "mobile",
                                                'date'  =>  $date
                                            ]);
                                        }
                                    }
                                }
                            }
                            // do something with post result
                        } catch (RestClientException $e) {
                        }
                        $client = null;
                    }
    
                    if ($platform == "desktop and mobile") {
                        $package_keywords_limit -= 2;
    
    
                        // $total_search_volume = null;
    
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
                        $post_array = array();
                        $post_array[] = array(
                            "location_code" => $request->location_code,
                            "language_code" => $request->language_code,
                            "keywords" => array(
                                $key
                            )
                        );

                        $ch = curl_init('https://api.keywordseverywhere.com/v1/get_keyword_data');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Accept: application/json',
                        'Authorization: '.$keywords_everywhere_token
                        ));

                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS,
                        urldecode(http_build_query([
                            "dataSource" => "gkp",
                            "country" => "us",
                            "kw" => [
                                $key
                            ]
                        ]))
                        );

                        $data = curl_exec($ch);
                        $err = curl_error($ch);
                        $info = curl_getinfo($ch);
                        curl_close($ch);
                        $volume = 0;
                        $monthly_trend = array();
                        if($info['http_code'] == 200){
                            $dataa = json_decode($data);
                            $volume = $dataa->data[0]->vol;
                            $monthly_trend = $dataa->data[0]->trend;
                            
                        }
    
                        $database_keyword_id = DB::table('domain_keywords')->insertGetId([
                            'domain_id' =>  $domain_id,
                            'user_id'   => $request->session()->get('user_id'),
                            'keyword'  =>  $key,
                            'platform'  =>  "desktop",
                            'volume'  =>  $volume,
                            'date'      =>  $date
                        ]);

                        foreach ($monthly_trend as $trend) {
                            DB::table('domain_keywords_monthly_volume')->insertGetId([
                                'domain_id' =>  $domain_id,
                                'user_id'   => $request->session()->get('user_id'),
                                'keyword'  =>  $key,
                                'keyword_id'    =>  $database_keyword_id,
                                'year'  =>  $trend->year,
                                'month'  =>  $trend->month,
                                'search_volume'    =>  $trend->value,
                                'date'      =>  $date
                            ]);
                        }
    
                        DB::table('user_refreshes')->insert([
                            'keyword_id'    =>  $database_keyword_id,
                            'user_id'    =>  Session::get('user_id'),
                            'keyword_platform'  =>  "desktop and mobile",
                            'date'  =>  date('Y-m-d H:i:s')
                        ]);
    
                        // try {
                        //     $result = $client->post('/v3/keywords_data/google/search_volume/live', $post_array);
                        //     $result = json_decode(json_encode($result), false);
                        //     if ($result->status_message == "Ok.") {
                        //         // $search_volume_id = $result->tasks[0]->id;
                        //         if ($result->tasks[0]->status_message == "Ok.") {
                        //             $final_result = $result->tasks[0]->result[0];
                        //             $total_search_volume = $final_result->search_volume;
                        //             $monthlySearches = $final_result->monthly_searches;
                        //             // prx($monthlySearches);
                        //             if (isset($monthlySearches) && count($monthlySearches) > 0) {
                        //                 foreach ($monthlySearches as $search) {
                        //                     DB::table('domain_keywords_monthly_volume')->insert([
                        //                         'user_id' =>    Session::get('user_id'),
                        //                         'domain_id' =>    $domain_id,
                        //                         'keyword_id' =>    $database_keyword_id,
                        //                         'keyword' =>    $key,
                        //                         'year' =>    $search->year,
                        //                         'month' =>    $search->month,
                        //                         'search_volume' =>    $search->search_volume,
                        //                         'date' =>    $date
                        //                     ]);
                        //                 }
                        //             }
                        //         }
                        //     }
                        // } catch (RestClientException $e) {
                        //     echo "\n";
                        //     print "HTTP code: {$e->getHttpCode()}\n";
                        //     print "Error code: {$e->getCode()}\n";
                        //     print "Message: {$e->getMessage()}\n";
                        //     print  $e->getTraceAsString();
                        //     echo "\n";
                        // }
                        $client = null;
    
    
                        // DB::table('domain_keywords')->where('id', $database_keyword_id)->update([
                        //     'volume'    =>  $total_search_volume,
                        // ]);
    
    
                        $keywords_count++;
    
                        $api_url = 'https://api.dataforseo.com/';
                        $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                        $post_array = array();
                        $post_array[] = array(
                            "keyword" => mb_convert_encoding($key, "UTF-8"),
                            "language_code" => $request->language_code,
                            "location_code" => $request->location_code,
                            "device" => "desktop"
                        );
                        try {
                            // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                            $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                            // prx($result);
                            if ($result['status_message'] = "Ok.") {
                                $reference_id = $result['tasks'][0]['id'];
                                if ($result['tasks'][0]['status_message'] == "Ok.") {
                                    $all_competitors = $result['tasks'][0]['result'][0]['items'];
    
                                    // prx($result);
                                    if (isset($all_competitors) && count($all_competitors)  > 0) {
                                        foreach ($all_competitors as $competitor) {
                                            DB::table('serp_competitors')->insert([
                                                'user_id' => Session::get('user_id'),
                                                'domain_id' => $domain_id,
                                                'keyword_id' => $database_keyword_id,
                                                'serp_competitor_id'    =>  $reference_id,
                                                'keyword'   =>  $key,
                                                'competitor'    =>  $competitor['url'],
                                                'avg_position'  =>  $competitor['rank_group'],
                                                "platform"      =>  "desktop",
                                                'date'  =>  $date
                                            ]);
                                        }
                                    }
                                }
                            }
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
    
                        if ($package_keywords_limit > 0) {
                            $keywords_count++;
    
                            DB::table('domain_keywords')->where('id', $database_keyword_id)->update([
                                'platform'  =>  "desktop and mobile"
                            ]);
                            $api_url = 'https://api.dataforseo.com/';
                            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                            $post_array = array();
                            $post_array[] = array(
                                "keyword" => mb_convert_encoding($key, "UTF-8"),
                                "language_code" => $request->language_code,
                                "location_code" => $request->location_code,
                                "device" => "mobile"
                            );
                            try {
                                // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                                $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                                // prx($result);
                                if ($result['status_message'] = "Ok.") {
                                    $reference_id = $result['tasks'][0]['id'];
                                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                                        $all_competitors = $result['tasks'][0]['result'][0]['items'];
    
                                        // prx($result);
                                        if (isset($all_competitors) && count($all_competitors)  > 0) {
                                            foreach ($all_competitors as $competitor) {
                                                DB::table('serp_competitors')->insert([
                                                    'user_id' => Session::get('user_id'),
                                                    'domain_id' => $domain_id,
                                                    'keyword_id' => $database_keyword_id,
                                                    'serp_competitor_id'    =>  $reference_id,
                                                    'keyword'   =>  $key,
                                                    'competitor'    =>  $competitor['url'],
                                                    'avg_position'  =>  $competitor['rank_group'],
                                                    "platform"      =>  "mobile",
                                                    'date'  =>  $date
                                                ]);
                                            }
                                        }
                                    }
                                }
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
                }
            } else {
                Session::flash('message', 'Geschatte Dagelijkse Werklast gebruik wacht 24 uur of upgrade abonnement...');
                Session::flash('alert-type', 'info');
            }
        }

        return redirect()->back();
    }

    public function RefreshAllKeywords($domain_id)
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();

        $all_keywords_data = DB::table('domain_keywords')->where('domain_id', $domain_id)->get();
        $date = date('Y-m-d H:i:S');

        DB::table('domains')->where('id', $domain_id)->update([
            'updated'    =>  $date
        ]);
        foreach ($all_keywords_data as $selected_keyword) {
            $remaining_refreshers = refreshes();
            if ($remaining_refreshers <= 0) {
                Session::flash('message', 'You have reached the refresh limit.');
                Session::flash('alert-type', 'info');
                return redirect()->back();
            }
            $keyword_data = $selected_keyword;
            $keyword_id = $keyword_data->id;

            $keyword_data = DB::table('domain_keywords')->where('id', $keyword_id)->first();
            $domain_data = DB::table('domains')->where('id', $domain_id)->first();
            $domain_url = $domain_data->domain;
            $platform = $keyword_data->platform;
            $date = date('Y-m-d H:i:S');

            DB::table('user_refreshes')->insert([
                'keyword_id'    =>  $keyword_id,
                'user_id'    =>  Session::get('user_id'),
                'keyword_platform'  =>  $platform,
                'date'  =>  date('Y-m-d H:i:s')
            ]);

            DB::table('domain_keywords_monthly_volume')->where('keyword_id', $keyword_id)->delete();
            DB::table('serp_competitors')->where('domain_id', $domain_id)->where('keyword_id', $keyword_id)->delete();
            DB::table('domain_keywords')->where('id', $keyword_id)->update([
                'updated' => $date
            ]);
            set_time_limit(2500);


            if ($platform == "desktop") {

                $total_search_volume = null;

                $api_url = 'https://api.dataforseo.com/';
                try {
                    $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                } catch (RestClientException $e) {
                    Session::flash('message', 'Something went wrong. Plz try later');
                    Session::flash('alert-type', 'error');
                    return redirect()->back();
                }
                $post_array = array();
                $post_array[] = array(
                    "location_code" => $domain_data->location_code,
                    "language_code" => $domain_data->language_code,
                    "keywords" => array(
                        $keyword_data->keyword
                    )
                );
                $client = null;
                // End key volume api


                $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                $post_array = array();
                $post_array[] = array(
                    "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                    "language_code" => $domain_data->language_code,
                    "location_code" => $domain_data->location_code,
                    "device" => "desktop"
                );
                try {
                    $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                    if ($result['status_message'] = "Ok.") {
                        $reference_id = $result['tasks'][0]['id'];
                        if ($result['tasks'][0]['status_message'] == "Ok.") {
                            $all_competitors = $result['tasks'][0]['result'][0]['items'];
                            if (isset($all_competitors) && count($all_competitors)  > 0) {
                                foreach ($all_competitors as $competitor) {
                                    DB::table('serp_competitors')->insert([
                                        'user_id' => Session::get('user_id'),
                                        'domain_id' => $domain_id,
                                        'keyword_id' =>    $keyword_data->id,
                                        'serp_competitor_id'    =>  $reference_id,
                                        'keyword'   =>  $keyword_data->keyword,
                                        'competitor'    =>  $competitor['url'],
                                        'avg_position'  =>  $competitor['rank_group'],
                                        "platform"      =>  "desktop",
                                        'date'  =>  $date
                                    ]);
                                }


                                $competitors = DB::table('serp_competitors')->where('keyword_id', $keyword_data->id)->get();
                                $desktop_rank = null;
                                $mobile_rank = null;

                                foreach ($competitors as $com) {
                                    if (strpos($com->competitor, $domain_url) !== false) {
                                        // if(strpos($com->competitor, $com->keyword !== false)) {
                                        if ($com->platform == "desktop" && $desktop_rank == null) {
                                            $desktop_rank = $com->avg_position;
                                        }
                                        if ($com->platform == "mobile" && $mobile_rank == null) {
                                            $mobile_rank = $com->avg_position;
                                        }
                                    }
                                }
                                if ($desktop_rank == null) {
                                    $desktop_rank = 0;
                                }
                                if ($mobile_rank == null) {
                                    $mobile_rank = 0;
                                }

                                DB::table('keywords_history')->insert([
                                    'user_id'   =>  Session::get('user_id'),
                                    'keyword_id'   =>  $keyword_data->id,
                                    'desktop_rank'   =>  $desktop_rank,
                                    'mobile_rank'   =>  $mobile_rank,
                                    'date'   =>  date('Y-m-d H:i:s')
                                ]);
                            }
                        }
                    }
                } catch (RestClientException $e) {
                }
                $client = null;
            }

            if ($platform == "mobile") {

                $total_search_volume = null;

                $api_url = 'https://api.dataforseo.com/';
                try {
                    $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                } catch (RestClientException $e) {
                    Session::flash('message', 'Something went wrong. Plz try later');
                    Session::flash('alert-type', 'error');
                    return redirect()->back();
                }
                $post_array = array();
                $post_array[] = array(
                    "location_code" => $domain_data->location_code,
                    "language_code" => $domain_data->language_code,
                    "keywords" => array(
                        $keyword_data->keyword
                    )
                );
                $client = null;


                DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                    'updated'   =>  $date
                ]);

                $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                $post_array = array();
                $post_array[] = array(
                    "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                    "language_code" => $domain_data->language_code,
                    "location_code" => $domain_data->location_code,
                    "device" => "mobile"
                );
                try {
                    // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                    $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                    // prx($result);
                    if ($result['status_message'] = "Ok.") {
                        $reference_id = $result['tasks'][0]['id'];
                        if ($result['tasks'][0]['status_message'] == "Ok.") {
                            $all_competitors = $result['tasks'][0]['result'][0]['items'];

                            // prx($result);
                            if (isset($all_competitors) && count($all_competitors)  > 0) {
                                foreach ($all_competitors as $competitor) {
                                    DB::table('serp_competitors')->insert([
                                        'user_id' => Session::get('user_id'),
                                        'domain_id' => $domain_id,
                                        'keyword'   =>  $keyword_data->id,
                                        'serp_competitor_id'    =>  $reference_id,
                                        'keyword'   =>  $keyword_data->keyword,
                                        'competitor'    =>  $competitor['url'],
                                        'avg_position'  =>  $competitor['rank_group'],
                                        "platform"      =>  "mobile",
                                        'date'  =>  $date
                                    ]);
                                }


                                $competitors = DB::table('serp_competitors')->where('keyword_id', $keyword_data->id)->get();
                                $desktop_rank = null;
                                $mobile_rank = null;

                                foreach ($competitors as $com) {
                                    if (strpos($com->competitor, $domain_url) !== false) {
                                        // if(strpos($com->competitor, $com->keyword !== false)) {
                                        if ($com->platform == "desktop" && $desktop_rank == null) {
                                            $desktop_rank = $com->avg_position;
                                        }
                                        if ($com->platform == "mobile" && $mobile_rank == null) {
                                            $mobile_rank = $com->avg_position;
                                        }
                                    }
                                }
                                if ($desktop_rank == null) {
                                    $desktop_rank = 0;
                                }
                                if ($mobile_rank == null) {
                                    $mobile_rank = 0;
                                }

                                DB::table('keywords_history')->insert([
                                    'user_id'   =>  Session::get('user_id'),
                                    'keyword_id'   =>  $keyword_data->id,
                                    'desktop_rank'   =>  $desktop_rank,
                                    'mobile_rank'   =>  $mobile_rank,
                                    'date'   =>  date('Y-m-d H:i:s')
                                ]);
                            }
                        }
                    }
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

            if ($platform == "desktop and mobile") {

                $total_search_volume = null;

                $api_url = 'https://api.dataforseo.com/';
                try {
                    $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                } catch (RestClientException $e) {
                    Session::flash('message', 'Something went wrong. Plz try later');
                    Session::flash('alert-type', 'error');
                    return redirect()->back();
                }
                $post_array = array();
                $post_array[] = array(
                    "location_code" => $domain_data->location_code,
                    "language_code" => $domain_data->language_code,
                    "keywords" => array(
                        $keyword_data->keyword
                    )
                );
                $client = null;


                DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                    'updated'   =>  $date
                ]);

                $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                $post_array = array();
                $post_array[] = array(
                    "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                    "language_code" => $domain_data->language_code,
                    "location_code" => $domain_data->location_code,
                    "device" => "desktop"
                );
                try {
                    // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                    $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                    // prx($result);
                    if ($result['status_message'] = "Ok.") {
                        $reference_id = $result['tasks'][0]['id'];
                        if ($result['tasks'][0]['status_message'] == "Ok.") {
                            $all_competitors = $result['tasks'][0]['result'][0]['items'];

                            // prx($result);
                            if (isset($all_competitors) && count($all_competitors)  > 0) {
                                foreach ($all_competitors as $competitor) {
                                    DB::table('serp_competitors')->insert([
                                        'user_id' => Session::get('user_id'),
                                        'domain_id' => $domain_id,
                                        'keyword_id'   =>  $keyword_data->id,
                                        'serp_competitor_id'    =>  $reference_id,
                                        'keyword'   =>  $keyword_data->keyword,
                                        'competitor'    =>  $competitor['url'],
                                        'avg_position'  =>  $competitor['rank_group'],
                                        "platform"      =>  "desktop",
                                        'date'  =>  $date
                                    ]);
                                }
                            }
                        }
                    }
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


                $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                $post_array = array();
                $post_array[] = array(
                    "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                    "language_code" => $domain_data->language_code,
                    "location_code" => $domain_data->location_code,
                    "device" => "mobile"
                );
                try {
                    // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                    $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                    // prx($result);
                    if ($result['status_message'] = "Ok.") {
                        $reference_id = $result['tasks'][0]['id'];
                        if ($result['tasks'][0]['status_message'] == "Ok.") {
                            $all_competitors = $result['tasks'][0]['result'][0]['items'];

                            // prx($result);
                            if (isset($all_competitors) && count($all_competitors)  > 0) {
                                foreach ($all_competitors as $competitor) {
                                    DB::table('serp_competitors')->insert([
                                        'user_id' => Session::get('user_id'),
                                        'domain_id' => $domain_id,
                                        'serp_competitor_id'    =>  $reference_id,
                                        'keyword_id'   =>  $keyword_data->id,
                                        'keyword'   =>  $keyword_data->keyword,
                                        'competitor'    =>  $competitor['url'],
                                        'avg_position'  =>  $competitor['rank_group'],
                                        "platform"      =>  "mobile",
                                        'date'  =>  $date
                                    ]);
                                }
                            }
                        }
                    }
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

                $competitors = DB::table('serp_competitors')->where('keyword_id', $keyword_data->id)->get();
                $desktop_rank = null;
                $mobile_rank = null;

                foreach ($competitors as $com) {
                    if (strpos($com->competitor, $domain_url) !== false) {
                        // if(strpos($com->competitor, $com->keyword !== false)) {
                        if ($com->platform == "desktop" && $desktop_rank == null) {
                            $desktop_rank = $com->avg_position;
                        }
                        if ($com->platform == "mobile" && $mobile_rank == null) {
                            $mobile_rank = $com->avg_position;
                        }
                    }
                }
                if ($desktop_rank == null) {
                    $desktop_rank = 0;
                }
                if ($mobile_rank == null) {
                    $mobile_rank = 0;
                }

                DB::table('keywords_history')->insert([
                    'user_id'   =>  Session::get('user_id'),
                    'keyword_id'   =>  $keyword_data->id,
                    'desktop_rank'   =>  $desktop_rank,
                    'mobile_rank'   =>  $mobile_rank,
                    'date'   =>  date('Y-m-d H:i:s')
                ]);
            }
        }

        return redirect()->back();
        // return redirect()->back();
    }

    public function RefreshKeyword($domain_id, $keyword_id)
    {
        $remaining_refreshers = refreshes();
        if ($remaining_refreshers <= 0) {
            Session::flash('message', 'You have reached the refresh limit.');
            Session::flash('alert-type', 'info');
            return redirect()->back();
        }

        $dataforseo_api = DB::table('api')->where('id', 1)->first();
        $keywords_everywhere_token = 'Bearer '.$dataforseo_api->keywords_everywhere_api;

        $date = date('Y-m-d h:i:s');

        DB::table('domains')->where('id', $domain_id)->update([
            'updated'    =>  $date
        ]);

        $keyword_data = DB::table('domain_keywords')->where('id', $keyword_id)->first();
        $domain_data = DB::table('domains')->where('id', $domain_id)->first();
        $domain_url = $domain_data->domain;
        $platform = $keyword_data->platform;
        $date = date('Y-m-d H:i:S');

        DB::table('domain_keywords_monthly_volume')->where('keyword_id', $keyword_id)->delete();
        DB::table('serp_competitors')->where('domain_id', $domain_id)->where('keyword_id', $keyword_id)->delete();
        DB::table('domain_keywords')->where('id', $keyword_id)->update([
            'updated' => $date
        ]);
        set_time_limit(3500);


        if ($platform == "desktop") {
            DB::table('user_refreshes')->insert([
                'keyword_id'    =>  $keyword_id,
                'user_id'    =>  Session::get('user_id'),
                'keyword_platform'  =>  $platform,
                'date'  =>  date('Y-m-d H:i:s')
            ]);

            $api_url = 'https://api.dataforseo.com/';
            try {
                $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            } catch (RestClientException $e) {
                Session::flash('message', 'Something went wrong. Plz try later');
                Session::flash('alert-type', 'error');
                return redirect()->back();
            }


            $post_array = array();
            $post_array[] = array(
                "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                "language_code" => $domain_data->language_code,
                "location_code" => $domain_data->location_code,
                "device" => "desktop"
            );

            $ch = curl_init('https://api.keywordseverywhere.com/v1/get_keyword_data');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Authorization: '.$keywords_everywhere_token
            ));

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                urldecode(http_build_query([
                    "dataSource" => "gkp",
                    "country" => "us",
                    "kw" => [
                        $keyword_data->keyword
                    ]
                ]))
            );

            $data = curl_exec($ch);
            $err = curl_error($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);
            $volume = 0;
            $monthly_trend = array();
            if($info['http_code'] == 200){
                $dataa = json_decode($data);
                $volume = $dataa->data[0]->vol;
                $monthly_trend = $dataa->data[0]->trend;
            }

            DB::table('domain_keywords')->where('id', $keyword_id)->update([
                'volume'    =>  $volume,
            ]);
            DB::table('domain_keywords_monthly_volume')->where('keyword_id', $keyword_id)->delete();

            foreach ($monthly_trend as $trend) {
                DB::table('domain_keywords_monthly_volume')->insertGetId([
                    'domain_id' =>  $domain_id,
                    'user_id'   => Session::get('user_id'),
                    'keyword'  =>  $keyword_data->keyword,
                    'keyword_id'    =>  $keyword_id,
                    'year'  =>  $trend->year,
                    'month'  =>  $trend->month,
                    'search_volume'    =>  $trend->value,
                    'date'      =>  $date
                ]);
            }

            try {
                $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                if ($result['status_message'] = "Ok.") {
                    $reference_id = $result['tasks'][0]['id'];
                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                        $all_competitors = $result['tasks'][0]['result'][0]['items'];
                        if (isset($all_competitors) && count($all_competitors)  > 0) {
                            foreach ($all_competitors as $competitor) {
                                DB::table('serp_competitors')->insert([
                                    'user_id' => Session::get('user_id'),
                                    'domain_id' => $domain_id,
                                    'keyword_id' =>    $keyword_data->id,
                                    'serp_competitor_id'    =>  $reference_id,
                                    'keyword'   =>  $keyword_data->keyword,
                                    'competitor'    =>  $competitor['url'],
                                    'avg_position'  =>  $competitor['rank_group'],
                                    "platform"      =>  "desktop",
                                    'date'  =>  $date
                                ]);
                            }

                            $competitors = DB::table('serp_competitors')->where('keyword_id', $keyword_data->id)->get();
                            $desktop_rank = null;
                            $mobile_rank = null;

                            foreach ($competitors as $com) {
                                if (strpos($com->competitor, $domain_url) !== false) {
                                    // if(strpos($com->competitor, $com->keyword !== false)) {
                                    if ($com->platform == "desktop" && $desktop_rank == null) {
                                        $desktop_rank = $com->avg_position;
                                    }
                                    if ($com->platform == "mobile" && $mobile_rank == null) {
                                        $mobile_rank = $com->avg_position;
                                    }
                                }
                            }
                            if ($desktop_rank == null) {
                                $desktop_rank = 0;
                            }
                            if ($mobile_rank == null) {
                                $mobile_rank = 0;
                            }

                            DB::table('keywords_history')->insert([
                                'user_id'   =>  Session::get('user_id'),
                                'keyword_id'   =>  $keyword_data->id,
                                'desktop_rank'   =>  $desktop_rank,
                                'mobile_rank'   =>  $mobile_rank,
                                'date'   =>  date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                }
            } catch (RestClientException $e) {
            }
            $client = null;
        }

        if ($platform == "mobile") {
            DB::table('user_refreshes')->insert([
                'keyword_id'    =>  $keyword_id,
                'user_id'    =>  Session::get('user_id'),
                'keyword_platform'  =>  $platform,
                'date'  =>  date('Y-m-d H:i:s')
            ]);

            $api_url = 'https://api.dataforseo.com/';
            try {
                $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            } catch (RestClientException $e) {
                Session::flash('message', 'Something went wrong. Plz try later');
                Session::flash('alert-type', 'error');
                return redirect()->back();
            }
            $post_array = array();
            $post_array[] = array(
                "location_code" => $domain_data->location_code,
                "language_code" => $domain_data->language_code,
                "keywords" => array(
                    $keyword_data->keyword
                )
            );
            $client = null;


            DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                'updated'   =>  $date
            ]);

            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            $post_array = array();
            $post_array[] = array(
                "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                "language_code" => $domain_data->language_code,
                "location_code" => $domain_data->location_code,
                "device" => "mobile"
            );
            try {
                // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                // prx($result);
                if ($result['status_message'] = "Ok.") {
                    $reference_id = $result['tasks'][0]['id'];
                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                        $all_competitors = $result['tasks'][0]['result'][0]['items'];

                        // prx($result);
                        if (isset($all_competitors) && count($all_competitors)  > 0) {
                            foreach ($all_competitors as $competitor) {
                                DB::table('serp_competitors')->insert([
                                    'user_id' => Session::get('user_id'),
                                    'domain_id' => $domain_id,
                                    'keyword'   =>  $keyword_data->id,
                                    'serp_competitor_id'    =>  $reference_id,
                                    'keyword'   =>  $keyword_data->keyword,
                                    'competitor'    =>  $competitor['url'],
                                    'avg_position'  =>  $competitor['rank_group'],
                                    "platform"      =>  "mobile",
                                    'date'  =>  $date
                                ]);
                            }

                            $competitors = DB::table('serp_competitors')->where('keyword_id', $keyword_data->id)->get();
                            $desktop_rank = null;
                            $mobile_rank = null;

                            foreach ($competitors as $com) {
                                if (strpos($com->competitor, $domain_url) !== false) {
                                    // if(strpos($com->competitor, $com->keyword !== false)) {
                                    if ($com->platform == "desktop" && $desktop_rank == null) {
                                        $desktop_rank = $com->avg_position;
                                    }
                                    if ($com->platform == "mobile" && $mobile_rank == null) {
                                        $mobile_rank = $com->avg_position;
                                    }
                                }
                            }
                            if ($desktop_rank == null) {
                                $desktop_rank = 0;
                            }
                            if ($mobile_rank == null) {
                                $mobile_rank = 0;
                            }

                            DB::table('keywords_history')->insert([
                                'user_id'   =>  Session::get('user_id'),
                                'keyword_id'   =>  $keyword_data->id,
                                'desktop_rank'   =>  $desktop_rank,
                                'mobile_rank'   =>  $mobile_rank,
                                'date'   =>  date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                }
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

        if ($platform == "desktop and mobile") {
            DB::table('user_refreshes')->insert([
                'keyword_id'    =>  $keyword_id,
                'user_id'    =>  Session::get('user_id'),
                'keyword_platform'  =>  $platform,
                'date'  =>  date('Y-m-d H:i:s')
            ]);

            $api_url = 'https://api.dataforseo.com/';
            try {
                $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            } catch (RestClientException $e) {
                Session::flash('message', 'Something went wrong. Plz try later');
                Session::flash('alert-type', 'error');
                return redirect()->back();
            }
            $post_array = array();
            $post_array[] = array(
                "location_code" => $domain_data->location_code,
                "language_code" => $domain_data->language_code,
                "keywords" => array(
                    $keyword_data->keyword
                )
            );
            $ch = curl_init('https://api.keywordseverywhere.com/v1/get_keyword_data');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Authorization: '.$keywords_everywhere_token
            ));

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                urldecode(http_build_query([
                    "dataSource" => "gkp",
                    "country" => "us",
                    "kw" => [
                        $keyword_data->keyword
                    ]
                ]))
            );

            $data = curl_exec($ch);
            $err = curl_error($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);
            $volume = 0;
            $monthly_trend = array();
            if($info['http_code'] == 200){
                $dataa = json_decode($data);
                $volume = $dataa->data[0]->vol;
                $monthly_trend = $dataa->data[0]->trend;
            }

            DB::table('domain_keywords_monthly_volume')->where('keyword_id', $keyword_id)->delete();

            foreach ($monthly_trend as $trend) {
                DB::table('domain_keywords_monthly_volume')->insertGetId([
                    'domain_id' =>  $domain_id,
                    'user_id'   => Session::get('user_id'),
                    'keyword'  =>  $keyword_data->keyword,
                    'keyword_id'    =>  $keyword_id,
                    'year'  =>  $trend->year,
                    'month'  =>  $trend->month,
                    'search_volume'    =>  $trend->value,
                    'date'      =>  $date
                ]);
            }

            $client = null;


            DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                'updated'   =>  $date,
                'volume'    =>  $volume
            ]);

            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            $post_array = array();
            $post_array[] = array(
                "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                "language_code" => $domain_data->language_code,
                "location_code" => $domain_data->location_code,
                "device" => "desktop"
            );
            try {
                // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                // prx($result);
                if ($result['status_message'] = "Ok.") {
                    $reference_id = $result['tasks'][0]['id'];
                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                        $all_competitors = $result['tasks'][0]['result'][0]['items'];

                        // prx($result);
                        if (isset($all_competitors) && count($all_competitors)  > 0) {
                            foreach ($all_competitors as $competitor) {
                                DB::table('serp_competitors')->insert([
                                    'user_id' => Session::get('user_id'),
                                    'domain_id' => $domain_id,
                                    'keyword_id'   =>  $keyword_data->id,
                                    'serp_competitor_id'    =>  $reference_id,
                                    'keyword'   =>  $keyword_data->keyword,
                                    'competitor'    =>  $competitor['url'],
                                    'avg_position'  =>  $competitor['rank_group'],
                                    "platform"      =>  "desktop",
                                    'date'  =>  $date
                                ]);
                            }
                        }
                    }
                }
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


            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
            $post_array = array();
            $post_array[] = array(
                "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                "language_code" => $domain_data->language_code,
                "location_code" => $domain_data->location_code,
                "device" => "mobile"
            );
            try {
                // $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
                $result = $client->post('/v3/serp/google/organic/live/regular', $post_array);
                // prx($result);
                if ($result['status_message'] = "Ok.") {
                    $reference_id = $result['tasks'][0]['id'];
                    if ($result['tasks'][0]['status_message'] == "Ok.") {
                        $all_competitors = $result['tasks'][0]['result'][0]['items'];

                        // prx($result);
                        if (isset($all_competitors) && count($all_competitors)  > 0) {
                            foreach ($all_competitors as $competitor) {
                                DB::table('serp_competitors')->insert([
                                    'user_id' => Session::get('user_id'),
                                    'domain_id' => $domain_id,
                                    'serp_competitor_id'    =>  $reference_id,
                                    'keyword_id'   =>  $keyword_data->id,
                                    'keyword'   =>  $keyword_data->keyword,
                                    'competitor'    =>  $competitor['url'],
                                    'avg_position'  =>  $competitor['rank_group'],
                                    "platform"      =>  "mobile",
                                    'date'  =>  $date
                                ]);
                            }
                        }
                    }
                }
                // do something with post result
            } catch (RestClientException $e) {
                echo "\n";
                print "HTTP code: {$e->getHttpCode()}\n";
                print "Error code: {$e->getCode()}\n";
                print "Message: {$e->getMessage()}\n";
                print  $e->getTraceAsString();
                echo "\n";
            }

            $competitors = DB::table('serp_competitors')->where('keyword_id', $keyword_data->id)->get();
            $desktop_rank = null;
            $mobile_rank = null;

            foreach ($competitors as $com) {
                if (strpos($com->competitor, $domain_url) !== false) {
                    // if(strpos($com->competitor, $com->keyword !== false)) {
                    if ($com->platform == "desktop" && $desktop_rank == null) {
                        $desktop_rank = $com->avg_position;
                    }
                    if ($com->platform == "mobile" && $mobile_rank == null) {
                        $mobile_rank = $com->avg_position;
                    }
                }
            }
            if ($desktop_rank == null) {
                $desktop_rank = 0;
            }
            if ($mobile_rank == null) {
                $mobile_rank = 0;
            }

            DB::table('keywords_history')->insert([
                'user_id'   =>  Session::get('user_id'),
                'keyword_id'   =>  $keyword_data->id,
                'desktop_rank'   =>  $desktop_rank,
                'mobile_rank'   =>  $mobile_rank,
                'date'   =>  date('Y-m-d H:i:s')
            ]);
            $client = null;
        }

        return redirect()->back();
        // return redirect()->back();
    }

    public function DeleteDomain($id)
    {
        $domain = DB::table('domains')->where('id', $id)->first();
        $user_info = DB::table('users')->where('id', $domain->user_id)->first();
      //prx($user_info);
        

        $basic_settings = DB::table('basic_settings')->where('id',1)->first();
        $data = ['domain' => $domain->domain, 'logo' => $basic_settings->site_logo];
        $user['to'] = $user_info->email;
        $user['from'] = $basic_settings->for_emails_email;
        try {
            Mail::send('mail.user_domain_deleted', $data, function ($message) use ($user) {
                $message->from($user['from'], 'Domain Deleted');
                $message->sender($user['from'], 'Domain Deleted');
                $message->to($user['to']);
                $message->subject('Domain Deleted');
                $message->priority(3);
            });
        } catch(Exception $e) {

        }

        DB::table('domains')->where('id', $id)->update([
            'status'    =>  0
        ]);

        DB::table('serp_competitors')->where('domain_id', $id)->delete();
        DB::table('domains')->where('id', $id)->delete();
        $domain_keywords = DB::table('domain_keywords')->where('domain_id', $id)->get();
        foreach ($domain_keywords as $key) {
           DB::table('keywords_history')->where('keyword_id', $key->id)->delete();
        }
        DB::table('domain_keywords')->where('domain_id', $id)->delete();
        DB::table('traffic_api_domain_top_keywords')->where('domain_id', $id)->delete();
        DB::table('traffic_api_domain_monthly_volume')->where('domain_id', $id)->delete();
        DB::table('backlinks')->where('domain_id', $id)->delete();
        DB::table('anchors')->where('domain_id', $id)->delete();
        DB::table('domain_keywords_monthly_volume')->where('domain_id', $id)->delete();

        return redirect()->back();
    }

    public function DeleteKeyword($keyword_id)
    {
        DB::table('domain_keywords')->where('id', $keyword_id)->delete();
        DB::table('serp_competitors')->where('keyword_id', $keyword_id)->delete();
        DB::table('domain_keywords_monthly_volume')->where('keyword_id', $keyword_id)->delete();
        DB::table('keywords_history')->where('keyword_id', $keyword_id)->delete();

        return redirect()->back();
    }

    public function DomainDetail($id)
    {
        $final_avg_desktop_change = 0;
        $final_avg_mobile_change = 0;
        $locations = DB::table('serp_google_locations')->orderBy('location_name', 'ASC')->get();
        $languages = DB::table('serp_google_languages')->orderBy('language_name', 'ASC')->get();
        $main_rank = 999999999;
        $competitors_data = array();
        $domain_data = DB::table('domains')->where('id', $id)->first();
        $platform = $domain_data->platform;
        $monthly_tarffic = DB::table('traffic_api_domain_monthly_volume')->where('domain_id', $id)->orderby('traffic_month', 'ASC')->get();
        $top_keywords_tarffic = DB::table('traffic_api_domain_top_keywords')->where('domain_id', $id)->orderby('value', 'DESC')->get();
        // prx($domain_data);

        $keywords_data = DB::table('domain_keywords')->where('domain_id', $id)->orderBy('id', 'ASC')->get();
        $api_running_data = DB::table('domain_keywords')->where('domain_id', $id)->where('api_running', 'yes')->get();
        if(count($api_running_data) > 0) {
            $api_running_count = count($api_running_data);
        } else {
            $api_running_count = 0;
        }
        $total_keywords_for_avg = 0;
        $total_ranks_for_avg_position = 0;
        foreach ($keywords_data as $keyword) {
            $competitors = DB::table('serp_competitors')->where('domain_id', $id)->where('keyword_id', $keyword->id)->orderBy('id', 'ASC')->get();
            $keywords_history_data = DB::table('keywords_history')->where('keyword_id', $keyword->id)->orderBy('date', 'DESC')->get();

            $refer1 = 0;
            $refer2 = 0;

            $keyword->desktop_rank = null;
            $keyword->mobile_rank = null;
            foreach ($competitors as $com) {
                if (strpos($com->competitor, $domain_data->domain) !== false) {
                    // if(strpos($com->competitor, $com->keyword !== false)) {
                    if ($com->platform == "desktop" && $keyword->desktop_rank == null) {
                        $keyword->desktop_rank = $com->avg_position;
                    }
                    if ($com->platform == "mobile" && $keyword->mobile_rank == null) {
                        $keyword->mobile_rank = $com->avg_position;
                    }
                    if (isset($com->avg_position) && $com->avg_position < $main_rank) {
                        $main_rank = $com->avg_position;
                    }
                }
            }
            if($keyword->platform == "desktop and mobile") {
                $total_keywords_for_avg += 2;
                if($keyword->desktop_rank == null || $keyword->desktop_rank == 0) {
                    $total_ranks_for_avg_position += 101; 
                } else {
                    $total_ranks_for_avg_position += $keyword->desktop_rank;
                }
    
                if($keyword->mobile_rank == null || $keyword->mobile_rank == 0) {
                    $total_ranks_for_avg_position += 101; 
                } else {
                    $total_ranks_for_avg_position += $keyword->mobile_rank;
                }
            }else if($keyword->platform == "desktop") {
                $total_keywords_for_avg++;
                if($keyword->desktop_rank == null || $keyword->desktop_rank == 0) {
                    $total_ranks_for_avg_position += 101; 
                } else {
                    $total_ranks_for_avg_position += $keyword->desktop_rank;
                }
            }else if($keyword->platform == "mobile") {
                $total_keywords_for_avg++;
                if($keyword->mobile_rank == null || $keyword->mobile_rank == 0) {
                    $total_ranks_for_avg_position += 101; 
                } else {
                    $total_ranks_for_avg_position += $keyword->mobile_rank;
                }
            }


            $keyword->competitor = array();
            $keyword->final_avg_desktop_change = 0;
            $keyword->final_avg_mobile_change = 0;
            array_push($keyword->competitor, $competitors);

            if (count($keywords_history_data) > 0) {
                // prx($keywords_history_data);
                $array_length = sizeof($keywords_history_data);
                $keyword->start_desktop = $keywords_history_data[$array_length - 1]->desktop_rank;
                $keyword->start_mobile = $keywords_history_data[$array_length - 1]->mobile_rank;

                foreach ($keywords_history_data as $history_data) {
                    $refer1 += $history_data->desktop_rank;
                    $refer2 += $history_data->mobile_rank;
                }

                if ($refer1 > 0) {
                    $desktop_change = round($refer1 / $array_length);
                    if ($keyword->start_desktop == 0 && $desktop_change > 0) {
                        $keyword->final_avg_desktop_change = 101 - $desktop_change;
                    }
                    if ($keyword->start_desktop == 0 && $desktop_change == 0) {
                        $keyword->final_avg_desktop_change = 0;
                    }
                    if ($keyword->start_desktop > 0 && $desktop_change > 0) {
                        $keyword->final_avg_desktop_change = ($keyword->start_desktop) - ($desktop_change);
                    }
                    if ($keyword->start_desktop > 0 && $desktop_change == 0) {
                        $keyword->final_avg_desktop_change = 100 - $keyword->start_desktop;
                    }
                } else {
                    $keyword->final_avg_desktop_change = 0;
                }

                if ($refer2 > 0) {
                    $mobile_change = round($refer2 / $array_length);
                    if ($keyword->start_mobile == 0 && $mobile_change > 0) {
                        $keyword->final_avg_mobile_change = 101 - $mobile_change;
                    }
                    if ($keyword->start_mobile == 0 && $mobile_change == 0) {
                        $keyword->final_avg_mobile_change = 0;
                    }
                    if ($keyword->start_mobile > 0 && $mobile_change > 0) {
                        $keyword->final_avg_mobile_change = $keyword->start_mobile - $mobile_change;
                    }
                    if ($keyword->start_mobile > 0 && $mobile_change == 0) {
                        $keyword->final_avg_mobile_change = 100 - $keyword->start_mobile;
                    }
                } else {
                    $keyword->final_avg_mobile_change = 0;
                }





                if (count($keywords_history_data) >= 7) {
                    $desktop_count = 0;
                    $mobile_count = 0;
                    for ($i = 0; $i < 7; $i++) {
                        $desktop_count += $keywords_history_data[$i]->desktop_rank;
                        $mobile_count += $keywords_history_data[$i]->mobile_rank;
                    }
                    if ($desktop_count > 0) {
                        $keyword->seven_days_desktop = round($desktop_count / 7);
                    } else {
                        $keyword->seven_days_desktop = 0;
                    }
                    if ($mobile_count > 0) {
                        $keyword->seven_days_mobile = round($mobile_count / 7);
                    } else {
                        $keyword->seven_days_mobile = 0;
                    }
                } else {
                    $keyword->seven_days_desktop = null;
                    $keyword->seven_days_mobile = null;
                }

                if (count($keywords_history_data) >= 30) {
                    $desktop_count = 0;
                    $mobile_count = 0;
                    for ($i = 0; $i < 30; $i++) {
                        $desktop_count += $keywords_history_data[$i]->desktop_rank;
                        $mobile_count += $keywords_history_data[$i]->mobile_rank;
                    }
                    if ($desktop_count > 0) {
                        $keyword->thirty_days_desktop = round($desktop_count / 30);
                    } else {
                        $keyword->thirty_days_desktop = 0;
                    }
                    if ($mobile_count > 0) {
                        $keyword->thirty_days_mobile = round($mobile_count / 30);
                    } else {
                        $keyword->thirty_days_mobile = 0;
                    }
                } else {
                    $keyword->thirty_days_desktop = null;
                    $keyword->thirty_days_mobile = null;
                }
            } else {
                if ($keyword->desktop_rank == null) {
                    $keywords_start_desktop_data = 0;
                } else {
                    $keywords_start_desktop_data = $keyword->desktop_rank;
                }
                if ($keyword->mobile_rank == null) {
                    $keywords_start_mobile_data = 0;
                } else {
                    $keywords_start_mobile_data = $keyword->mobile_rank;
                }
                DB::table('keywords_history')->insert([
                    'user_id'   =>  Session::get('user_id'),
                    'keyword_id'   =>  $keyword->id,
                    'desktop_rank'   =>  $keywords_start_desktop_data,
                    'mobile_rank'   =>  $keywords_start_mobile_data,
                    'date'   =>  date('Y-m-d H:i:s')
                ]);
                $keyword->start_desktop = $keywords_start_desktop_data;
                $keyword->start_mobile = $keywords_start_mobile_data;
            }
        }
        // prx($keywords_data);
        if ($main_rank == 999999999) {
            $main_rank = "Not in 100";
        }
        $total = 0;
        $keyword_index = 0;
        foreach ($keywords_data as $keyword) {
            if ($keyword->platform == "desktop and mobile") {
                $total += 2;
            } else {
                $total++;
            }
            if (isset($keyword->desktop_rank)) {
                if ($keyword->desktop_rank != null) {
                    $keyword_index += $keyword->desktop_rank;
                }
            }
            if (isset($keyword->mobile_rank)) {
                if ($keyword->mobile_rank != null) {
                    $keyword_index += $keyword->mobile_rank;
                }
            }
        }
        if ($total > 0 && $keyword_index > 0) {
            $new_avg_position = round($keyword_index / $total);
            DB::table('domains')->where('id', $id)->update([
                'avg_position'  =>  $new_avg_position
            ]);
        } else {
            DB::table('domains')->where('id', $id)->update([
                'avg_position'  =>  0
            ]);
        }

        if($total_ranks_for_avg_position != 0 && $total_ranks_for_avg_position > 0 && $total_keywords_for_avg > 0) {
            $updated_domain_avg_position = round($total_ranks_for_avg_position/$total_keywords_for_avg);
        } else {
            $updated_domain_avg_position = 0;
        }

        DB::table('domains')->where('id', $id)->update([
            'avg_position' => $updated_domain_avg_position
        ]);

        // prx($keywords_data);

        return view('seo.domain-detail', compact('domain_data', 'keywords_data', 'monthly_tarffic', 'top_keywords_tarffic', 'main_rank', 'platform', 'locations', 'languages', 'final_avg_mobile_change', 'final_avg_desktop_change', 'api_running_count'));
    }


    public function Competitors()
    {
        $main_rank = 999999999;
        $competitors_data = array();
        $domain_data = DB::table('domains')->where('user_id', Session::get('user_id'))->where('status',1)->paginate(5);
        // prx($domain_data);

        foreach ($domain_data as $domain) {
            $domain->competitors = DB::table('competitors')->where('domain_id', $domain->id)->get();
            $all_competitors = $domain->competitors;
            $all_keywords = DB::table('domain_keywords')->where('domain_id', $domain->id)->get();
            $domain->keywords = $all_keywords;
            // prx($all_keywords);
            foreach ($all_competitors as $competitor) {
                $competitor->keywords = $all_keywords;

                foreach ($all_keywords as $key) {
                    $key->desktop_rank = array();
                    $key->mobile_rank = array();
                    $key->competitors = array();
                }
            }
            // prx($domain);

            foreach ($all_keywords as $key) {
                // $keyword_url = DB::table('serp_competitors')->where('domain_id', $domain->id)->where('keyword', $key->keyword)->get();
                // $key->competitors = $keyword_url;
                // foreach($keyword_url as $url) {
                //     if(strpos($url->competitor, $competitor->competitor)) {
                //         if ($url->platform == "desktop" && $key->desktop_rank == null) {
                //             $key->desktop_rank = $url->avg_position;
                //         }
                //         if ($url->platform == "mobile" && $key->mobile_rank == null) {
                //             $key->mobile_rank = $url->avg_position;
                //         }  
                //     }
                // }
            }
            // prx($domain);
        }
        // prx($domain_data);
        return view('seo.competitors', compact('domain_data'));
    }

    public function CompetitorsSearch(Request $request)
    {
        $main_rank = 999999999;
        $competitors_data = array();
        $domain_data = DB::table('domains')->where('user_id', Session::get('user_id'))->where('domain', 'like', '%'.$request->s.'%')->paginate(5);
        // prx($domain_data);

        foreach ($domain_data as $domain) {
            $domain->competitors = DB::table('competitors')->where('domain_id', $domain->id)->get();
            $all_competitors = $domain->competitors;
            $all_keywords = DB::table('domain_keywords')->where('domain_id', $domain->id)->get();
            $domain->keywords = $all_keywords;
            // prx($all_keywords);
            foreach ($all_competitors as $competitor) {
                $competitor->keywords = $all_keywords;

                foreach ($all_keywords as $key) {
                    $key->desktop_rank = array();
                    $key->mobile_rank = array();
                    $key->competitors = array();
                }
            }
            // prx($domain);

            foreach ($all_keywords as $key) {
                // $keyword_url = DB::table('serp_competitors')->where('domain_id', $domain->id)->where('keyword', $key->keyword)->get();
                // $key->competitors = $keyword_url;
                // foreach($keyword_url as $url) {
                //     if(strpos($url->competitor, $competitor->competitor)) {
                //         if ($url->platform == "desktop" && $key->desktop_rank == null) {
                //             $key->desktop_rank = $url->avg_position;
                //         }
                //         if ($url->platform == "mobile" && $key->mobile_rank == null) {
                //             $key->mobile_rank = $url->avg_position;
                //         }  
                //     }
                // }
            }
            // prx($domain);
        }
        // prx($domain_data);
        return view('seo.competitors', compact('domain_data'));
    }

    public function CompetitorsQuery(Request $request)
    {
        $domain_www_validation = substr($request->competitor, 0, 4);
        $domain_http_validation = substr($request->competitor, 0, 7);
        $domain_https_validation = substr($request->competitor, 0, 8);
        $domain_httpwww_validation = substr($request->competitor, 0, 11);
        $domain_httpswww_validation = substr($request->competitor, 0, 12);
        if ($domain_httpswww_validation == "https://www.") {
            $request->competitor = substr($request->competitor, 12);
        } else if ($domain_httpwww_validation == "http://www.") {
            $request->competitor = substr($request->competitor, 11);
        } else if ($domain_www_validation == "www.") {
            $request->competitor = substr($request->competitor, 4);
        } elseif ($domain_http_validation == "http://") {
            $request->competitor = substr($request->competitor, 7);
        } elseif ($domain_https_validation == "https://") {
            $request->competitor = substr($request->competitor, 8);
        }

        DB::table('competitors')->insert([
            'user_id' =>  Session::get('user_id'),
            'domain_id' =>  $request->domain_id,
            'competitor' =>  $request->competitor
        ]);
        return redirect('/user/competitors');
    }

    public function DeleteCompetitor($id)
    {
        DB::table('competitors')->where('id', $id)->delete();
        return redirect()->back();
    }

    public function SerpCompetitors()
    {
        $dataforseo_api = DB::table('api')->where('id', 1)->first();

        $api_url = 'https://api.dataforseo.com/';
        $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
        $post_array = array();
        $post_arrayy = array();
        // simple way to set a task

        $post_array[] = array(
            "keywords" => [
                "phone",
                "watch"
            ],
            "language_name" => "English",
            "location_code" => 2840
        );
        array_push($post_array[0]['keywords'], "usama");
        // prx($post_array);
        try {
            // POST /v3/dataforseo_labs/serp_competitors/live
            $result = $client->post('/v3/dataforseo_labs/serp_competitors/live', $post_array);
            prx($result);
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

    public function Profile()
    {
        $user_data = DB::table('users')->where('id', Session::get('user_id'))->first();
        return view('seo.profile', compact('user_data'));
    }

    public function ProfileUpdate(Request $request)
    {
        // prx($request->post());
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;

            DB::table('users')->where('id', Session::get('user_id'))->update([
                'Name'   =>  $request->name,
                'image' =>  $request->img,
                'email'   =>  $request->email,
                'country'   =>  $request->country,
            ]);
        } else {
            DB::table('users')->where('id', Session::get('user_id'))->update([
                'Name'   =>  $request->name,
                'email'   =>  $request->email,
                'country'   =>  $request->country,
            ]);
        }

        $request->session()->put('user_email', $request->email);
        $request->session()->put('user_name', $request->name);

        if ($request->password != null && $request->password != ' ') {
            $password = Hash::make($request->password);
            DB::table('users')->where('id', Session::get('user_id'))->update([
                'password'   =>  $password
            ]);
        }
        return redirect()->back();
    }

    public function Subscription()
    {
      	if(!Session::has('user_id')) {
            return redirect('/user/logout');
        }
        $user_data = DB::table('users')->where('id', Session::get('user_id'))->first();
        $payment_method_data = DB::table('basic_settings')->first();

        $package_info = DB::table('packages')->where('id', $user_data->package_id)->first();

        return view('seo.subscription', compact('user_data', 'package_info', 'payment_method_data'));
    }

    public function NotificationUpdate(Request $request)
    {
        DB::table('domains')->where('id', $request->domain_id)->update([
            'notification' => $request->frequency
        ]);
        return response()->json(['status' => 'successfull', 'message' => 'Notification Frequency Updated.']);
    }

    public function CancelSubscription()
    {
        DB::table('users')->where('id', Session::get('user_id'))->update([
            'subscription'   =>  0
        ]);
        Session::forget('user_id');
        Session::forget('user_name');
        Session::forget('user_email');
        return redirect('/');
    }

    public function ReSubscribe()
    {
        return view('front.re_subscribe');
    }

    public function ReSubscribeUpdate()
    {
        DB::table('users')->where('id', Session::get('user_id'))->update([
            'subscription'   =>  1
        ]);
        return redirect('/user/dashboard');
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