<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Exception;
use DateTime;
use DateInterval;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        set_time_limit(60000);
        // $schedule->call(function () {
        //     DB::table('basic_settings')->where('id', 1)->update([
        //         'for_emails_email' =>  'info@serpseotools.com',
        //     ]);
        // })->everyMinute();;

      
        $schedule->call(function () {
      DB::table('refresh_cron')->where('id', 1)->update(['test' => 1]);
          

            $basic_settings = DB::table('basic_settings')->where('id',1)->first();
            
            $all_users = DB::table('users')->where('payment', 1)->get();
            foreach ($all_users as $user) {
                $user_id = $user->id;
                if ($user->subscription != null && $user->subscription == 0 && $user->expire_date < date('Y-m-d H:i:s')) {
                    DB::table('domains')->where([
                        'user_id'   => $user_id
                    ])->delete();
                    DB::table('domain_keywords')->where([
                        'user_id', $user_id
                    ])->delete();
                    DB::table('serp_competitors')->where([
                        'user_id'   =>  $user_id
                    ])->delete();
                    DB::table('keywords_history')->where([
                        'user_id'   =>  $user_id,
                    ])->delete();
                    DB::table('anchors')->where([
                        'user_id'   =>  $user_id
                    ])->delete();
                    DB::table('backlinks_history')->where([
                        'user_id'   =>  $user_id
                    ])->delete();
                    DB::table('backlinks')->where([
                        'user_id'   =>  $user_id
                    ])->delete();
                    DB::table('users')->where([
                        'id'    =>   $user_id
                    ])->delete();
                }
            }

            $cron_refresh = DB::table('refresh_cron')->first();
            $cron_get_refresh = DB::table('get_refresh_cron')->first();
            if($cron_refresh->status == 1) {
                $all_users = DB::table('users')->where('payment', 1)->inRandomOrder()->where('package_id', '!=', null)->get();
                DB::table('refresh_cron')->where('id', 1)->update(['status' =>   0]);
                foreach ($all_users as $user) {
                    $user_id = $user->id;
                    $user_all_domains = DB::table('domains')->inRandomOrder()->where('status',1)->where('user_id', $user->id)->get();
                    if ($user_all_domains != null && count($user_all_domains) > 0) {
                        foreach ($user_all_domains as $domain) {
                            $domain_id = $domain->id;
                            $domain_url = $domain->domain;
    
                            $domain_all_keywords = DB::table('domain_keywords')->where('domain_id', $domain_id)->inRandomOrder()->get();
                            foreach ($domain_all_keywords as $keyword) {
                              //  $days = "1 day";
                                  $remaining_refreshers = refreshes($user_id);
    
                                $diff1Day = new DateInterval('P1D');
                                $date_time = new DateTime();
                                $current_time = $date_time->getTimestamp();
                                if($keyword->updated != null) {
                                    $d1 = new DateTime($keyword->updated);
                                } else {
                                    $d1 = new DateTime($keyword->date);
                                }
                                $d1->add($diff1Day);
                                $new_updated_time = $d1->getTimestamp();
                              
                              
                               // $new_notification_date = date_add(date_create($keyword->updated), date_interval_create_from_date_string($days));
                                if ($remaining_refreshers > 0 && $new_updated_time < $current_time) {
                                    $dataforseo_api = DB::table('api')->where('id', 1)->first();
                                    $keywords_everywhere_token = 'Bearer '.$dataforseo_api->keywords_everywhere_api;
    
                                    $date = date('Y-m-d H:i:s');
    
                                    DB::table('domains')->where('id', $domain_id)->update([
                                        'updated'    =>  $date
                                    ]);
    
                                    $keyword_data = DB::table('domain_keywords')->where('id', $keyword->id)->first();
                                    $domain_data = DB::table('domains')->where('id', $domain_id)->first();
                                    $domain_url = $domain_data->domain;
                                    $platform = $keyword_data->platform;
    
                                    DB::table('domain_keywords_monthly_volume')->where('keyword_id', $keyword->id)->delete();
                                   // DB::table('serp_competitors')->where('domain_id', $domain_id)->where('keyword_id', $keyword->id)->delete();
                                    DB::table('domain_keywords')->where('id', $keyword->id)->update([
                                        'updated' => $date
                                    ]);
                                    // set_time_limit(2500);

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
                                        'volume'  =>  $volume
                                    ]);
                                    
                                    $date = date('Y-m-d H:i:s');
                                    DB::table('domain_keywords_monthly_volume')->where('keyword_id', $keyword_data->id)->delete();

                                    foreach ($monthly_trend as $trend) {
                                        DB::table('domain_keywords_monthly_volume')->insertGetId([
                                            'domain_id' =>  $keyword_data->domain_id,
                                            'user_id'   => $user_id,
                                            'keyword'  =>  $keyword_data->keyword,
                                            'keyword_id'    =>  $keyword_data->id,
                                            'year'  =>  $trend->year,
                                            'month'  =>  $trend->month,
                                            'search_volume'    =>  $trend->value,
                                            'date'      =>  $date
                                        ]);
                                    }
    
    
                                    if ($platform == "desktop") {
                                        DB::table('user_refreshes')->insert([
                                            'keyword_id'    =>  $keyword->id,
                                            'user_id'    =>  $user_id,
                                            'keyword_platform'  =>  $platform,
                                            'date'  =>  date('Y-m-d H:i:s')
                                        ]);
    
                                        $api_url = 'https://api.dataforseo.com/';
                                        try {
                                            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                                        } catch (RestClientException $e) {
                                            
                                        }
    
    
                                        $post_array = array();
                                        $post_array[] = array(
                                            "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                                            "language_code" => $domain_data->language_code,
                                            "location_code" => $domain_data->location_code,
                                            "device" => $platform
                                        );

                                        try {
                                            $result = $client->post('/v3/serp/google/organic/task_post', $post_array);
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
                                        }
                                        $client = null;
                                    }
                                    if ($platform == "mobile") {
                                        DB::table('user_refreshes')->insert([
                                            'keyword_id'    =>  $keyword->idate,
                                            'user_id'    =>  $user_id,
                                            'keyword_platform'  =>  $platform,
                                            'date'  =>  date('Y-m-d H:i:s')
                                        ]);
    
                                        $api_url = 'https://api.dataforseo.com/';
                                        try {
                                            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                                        } catch (RestClientException $e) {
                                            
                                        }
    
    
                                        DB::table('domain_keywords')->where('id', $keyword_data->id)->update([
                                            'updated'   =>  $date
                                        ]);
    
                                        $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                                        $post_array = array();
                                        $post_array[] = array(
                                            "keyword" => mb_convert_encoding($keyword_data->keyword, "UTF-8"),
                                            "language_code" => $domain_data->language_code,
                                            "location_code" => $domain_data->location_code,
                                            "device" => $platform
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
                                            
                                        }
                                        $client = null;
                                    }
                                    if ($platform == "desktop and mobile") {
                                        DB::table('user_refreshes')->insert([
                                            'keyword_id'    =>  $keyword->id,
                                            'user_id'    =>  $user_id,
                                            'keyword_platform'  =>  $platform,
                                            'date'  =>  date('Y-m-d H:i:s')
                                        ]);
    
                                        $api_url = 'https://api.dataforseo.com/';
                                        try {
                                            $client = new RestClient($api_url, null, $dataforseo_api->api_email, $dataforseo_api->api_key);
                                        } catch (RestClientException $e) {
                                        }
    
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
                                        }
                                    }
                                }
                            }
                            
                        }
                    }
                    
                }
                DB::table('refresh_cron')->where('id', 1)->update(['status' =>   1]);
            }

            if($cron_get_refresh->status == 1) {
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
                                                    'user_id' => $keyword_data->user_id,
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
                                                'user_id'   =>  $keyword_data->user_id,
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

                        // prx($result);


                    }
                } catch (RestClientException $e) {
                    
                }

                DB::table('refresh_cron')->where('id', 1)->update(['status' =>   1]);
            }
        })->everyMinute();


        
        $schedule->call(function () {
            $basic_settings = DB::table('basic_settings')->where('id',1)->first();

            $all_users = DB::table('users')->where('payment', 1)->where('package_id', '!=', null)->get();
            foreach ($all_users as $user) {
                $user_id = $user->id;
                $user_all_domains = DB::table('domains')->where('user_id', $user->id)->where('status',1)->get();
                if ($user_all_domains != null && count($user_all_domains) > 0) {
                    foreach ($user_all_domains as $domain) {
                        $domain_id = $domain->id;
                        $domain_url = $domain->domain;

                        $domain_all_keywords = DB::table('domain_keywords')->where('domain_id', $domain_id)->get();
                        foreach ($domain_all_keywords as $keyword) {
                            $competitors = DB::table('serp_competitors')->where('keyword_id', $keyword->id)->get();
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
                                'user_id'   =>  $user_id,
                                'keyword_id'   =>  $keyword->id,
                                'desktop_rank'   =>  $desktop_rank,
                                'mobile_rank'   =>  $mobile_rank,
                                'date'   =>  date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                }
                
                foreach ($user_all_domains as $single_domain) {
                    $domain_keywords = DB::table('domain_keywords')->where('domain_id', $single_domain->id)->get();
                    if (count($domain_keywords) > 0) {
                        $notification_keywords = [];
                        foreach ($domain_keywords as $keyword) {
                            $keyword_history = DB::table('keywords_history')->where('keyword_id', $keyword->id)->orderBy('date', 'DESC')->first();
                            $keyword_history->keyword_name = $keyword->keyword;
                            array_push($notification_keywords, $keyword_history);
                        }
                        if ($single_domain->last_notification == null && $single_domain->notification != null && $single_domain->notification != '0') {
                            $subscriber['to'] = $user->email;
                            $subscriber['from'] = $basic_settings->for_emails_email;
                            $data = [
                                'domains' => $single_domain->domain,
                                'keywords'    =>   $notification_keywords,
                                'logo' => $basic_settings->site_logo
                            ];
                            $date = date('Y-m-d');
    

                            Mail::send('mail.user_notification', $data, function ($message) use ($subscriber) {
                                $message->from($subscriber['from'], 'Serp Ranking');
                                $message->sender($subscriber['from'], 'Serp Ranking');
                                $message->to($subscriber['to']);
                                $message->subject('Domains Ranking');
                                $message->priority(3);
                            });
                            DB::table('domains')->where('id', $single_domain->id)->update([
                                'last_notification' => $date
                            ]);
                        } elseif ($single_domain->notification == "daily") {
    
                            $subscriber['to'] = $user->email;
                            $subscriber['from'] = $basic_settings->for_emails_email;
                            $data = [
                                'domains' => $single_domain->domain,
                                'keywords'    =>   $notification_keywords,
                                'logo' => $basic_settings->site_logo
                            ];
                            // $domains = $notification_domains;
                            $date = date('Y-m-d');
    
                            Mail::send('mail.user_notification', $data, function ($message) use ($subscriber) {
                                $message->from($subscriber['from'], 'Serp Ranking');
                                $message->sender($subscriber['from'], 'Serp Ranking');
                                $message->to($subscriber['to']);
                                $message->subject('Domains Ranking');
                                $message->priority(3);
                            });
                            DB::table('domains')->where('id', $single_domain->id)->update([
                                'last_notification' => $date
                            ]);
                        } elseif ($single_domain->notification == "weekly") {
                            $days = "7 days";
                            $new_notification_date = date_add(date_create($user->last_notification), date_interval_create_from_date_string($days));
                            if ($new_notification_date <= date('Y-m-d H:i:s')) {
                                $subscriber['to'] = $user->email;
                                $subscriber['from'] = $basic_settings->for_emails_email;
                                $data = [
                                    'domains' => $single_domain->domain,
                                    'keywords'    =>   $notification_keywords,
                                    'logo' => $basic_settings->site_logo
                                ];
                                // $domains = $notification_domains;
                                $date = date('Y-m-d');
    
                                Mail::send('mail.user_notification', $data, function ($message) use ($subscriber) {
                                    $message->from($subscriber['from'], 'Serp Ranking');
                                    $message->sender($subscriber['from'], 'Serp Ranking');
                                    $message->to($subscriber['to']);
                                    $message->subject('Domains Ranking');
                                    $message->priority(3);
                                });
                                DB::table('domains')->where('id', $single_domain->id)->update([
                                    'last_notification' => $date
                                ]);
                            }
                        } elseif ($single_domain->notification == "monthly") {
                            $days = "30 days";
                            $new_notification_date = date_add(date_create($user->last_notification), date_interval_create_from_date_string($days));
                            if ($new_notification_date <= date('Y-m-d H:i:s')) {
                                $subscriber['to'] = $user->email;
                                $subscriber['from'] = $basic_settings->for_emails_email;

                                $data = [
                                    'domains' => $single_domain->domain,
                                    'keywords'    =>   $notification_keywords,
                                    'logo' => $basic_settings->site_logo
                                ];
                                // $domains = $notification_domains;
                                $date = date('Y-m-d');
    
                                Mail::send('mail.user_notification', $data, function ($message) use ($subscriber) {
                                    $message->from($subscriber['from'], 'Serp Ranking');
                                    $message->sender($subscriber['from'], 'Serp Ranking');
                                    $message->to($subscriber['to']);
                                    $message->subject('Domains Ranking');
                                    $message->priority(3);
                                });
                                DB::table('domains')->where('id', $single_domain->id)->update([
                                    'last_notification' => $date
                                ]);
                            }
                        }
                    }
                }
            }
        })->daily();

        //BAcklinks Notifications
        $schedule->call(function () {
            $basic_settings = DB::table('basic_settings')->where('id',1)->first();
            $date = date('Y-m-d H:i:s');
            $all_users = DB::table('users')->where('payment', 1)->where('package_id', '!=', null)->get();
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
                            $message->from($subscriber['from'], 'Serp Ranking');
                            $message->sender($subscriber['from'], 'Serp Ranking');
                            $message->to($subscriber['to']);
                            $message->subject('Domains Backlink Info');
                            $message->priority(3);
                        });
                    }


                }
            }
        })->daily();

        $schedule->call(function () {
            $basic_settings = DB::table('basic_settings')->where('id',1)->first();

            $all_users = DB::table('users')->where('payment', 1)->get();
            $days = 7;
            foreach ($all_users as $user) {
                // $current_date = $user->expire_date;
                $Date = $user->expire_date;
                $seven_days = date('Y-m-d H:i:s', strtotime($Date. ' - 7 days'));
                // prx($current_date);
                // $days = 7;
                // if($seven_days < date('Y-m-d H:i:s')) {
                //     prx($seven_days);
                // } else {
                //     prx($current_date);
                // }


                if ($user->subscription == 1 && $seven_days < date('Y-m-d H:i:s')) {
                    $subscriber['to'] = $user->email;
                    $subscriber['from'] = $basic_settings->for_emails_email;
                    $data = ['expire_date' => $user->expire_date, 'logo' => $basic_settings->site_logo];
                    if($user->seven_days_reminder != 1) {
                        try {
                            Mail::send('mail.user_extend_subscription', $data, function ($message) use ($subscriber) {
                                $message->from($subscriber['from'], 'Serp Ranking');
                                $message->sender($subscriber['from'], 'Serp Ranking');
                                $message->to($subscriber['to']);
                                $message->subject('Extend Subscription');
                                $message->priority(3);                                
                            });
                        } catch(Exception $e) {
    
                        }
                        DB::table('users')->where('id', $user->id)->update([
                            'seven_days_reminder' => 1
                        ]);
                    }
                }
            }
        })->daily();

        // $schedule->call(function () {

        //     $all_users = DB::table('users')->where('payment', 1)->where('package_id', '!=', null)->get();
        //     foreach ($all_users as $user) {
        //         if ($user->subscription == null || $user->subscription == 1) {
        //             $user_id = $user->id;
        //             $user_all_domains = DB::table('domains')->where('user_id', $user->id)->where('status',1)->get();
        //             $user_package_info = DB::table('packages')->where('id', $user->package_id)->first();
        //             if ($user_all_domains != null && count($user_all_domains) > 0 && $user_package_info->backlinks == 1 && $user->expire_date > date('Y-m-d H:i:s')) {
        //                 foreach ($user_all_domains as $domain) {
        //                     $domain_id = $domain->id;



        //                     $api_url = 'https://api.dataforseo.com/';
        //                     $client = new RestClient($api_url, null, 'llokmana@hotmail.com', '0b53096eda633eb4');

        //                     $post_array = array();
        //                     $post_array[] = array(
        //                         "target" => $domain->domain,
        //                         "limit" => 100,
        //                         "internal_list_limit" => 10,
        //                     );
        //                     try {
        //                         $result = $client->post('/v3/backlinks/anchors/live', $post_array);

        //                         if ($result['status_message'] == "Ok.") {
        //                             if ($result['tasks'][0]['status_message'] == "Ok.") {
        //                                 $all_anchors = $result['tasks'][0]['result'][0]['items'];
        //                                 if (isset($all_anchors) && count($all_anchors) > 0) {
        //                                     DB::table('anchors')->where('domain_id', $domain_id)->delete();
        //                                     foreach ($all_anchors as $anchor) {
        //                                         DB::table('anchors')->insert([
        //                                             'user_id'   =>  $user_id,
        //                                             'domain_id'   =>  $domain_id,
        //                                             'anchor'    =>  $anchor['anchor'],
        //                                             'count'     =>  $anchor['referring_domains']
        //                                         ]);
        //                                     }
        //                                 }
        //                             }
        //                         }
        //                         // do something with post result
        //                     } catch (RestClientException $e) {
        //                     }

        //                     $post_array = array();
        //                     $post_array[] = array(
        //                         "target" => $domain->domain,
        //                     );
        //                     try {
        //                         $result = $client->post('/v3/backlinks/history/live', $post_array);
        //                         if ($result['status_message'] == "Ok.") {
        //                             if ($result['tasks'][0]['status_message'] == "Ok.") {
        //                                 $all_results = $result['tasks'][0]['result'][0]['items'];
        //                                 if (isset($all_results) && count($all_results) > 0) {
        //                                     DB::table('backlinks_history')->where('domain_id', $domain_id)->delete();
        //                                     foreach ($all_results as $res) {

        //                                         DB::table('backlinks_history')->insert([
        //                                             'user_id'   =>  $user_id,
        //                                             'domain_id'   =>  $domain_id,
        //                                             'backlinks_count'    =>  $res['backlinks'],
        //                                             'new_backlinks'    =>  $res['new_backlinks'],
        //                                             'lost_backlinks'    =>  $res['lost_backlinks'],
        //                                             'anchors_count'     =>  $res['referring_links_types']['anchor'],
        //                                             'date'      =>  $res['date']
        //                                         ]);
        //                                     }
        //                                 }
        //                             }
        //                         }
        //                         // do something with post result
        //                     } catch (RestClientException $e) {
        //                     }


        //                     $post_arrayy = array();
        //                     $post_arrayy[] = array(
        //                         "target" => $domain->domain,
        //                         "limit" =>  $user_package_info->domain_backlinks_rows_limit
        //                     );
        //                     try {
        //                         $result = $client->post('/v3/backlinks/backlinks/live', $post_arrayy);
        //                         if ($result['status_message'] == "Ok.") {
        //                             if ($result['tasks'][0]['status_message'] == "Ok.") {
        //                                 $total_count = $result['tasks'][0]['result'][0]['total_count'];
        //                                 $all_backlinks = $result['tasks'][0]['result'][0]['items'];
        //                                 if (isset($all_backlinks) && count($all_backlinks) > 0) {
        //                                     DB::table('backlinks')->where('domain_id', $domain_id)->delete();
        //                                     foreach ($all_backlinks as $backlink) {
        //                                         $already_exists = DB::table('backlinks')->where('domain_id', $domain_id)->where('user_id', $user_id)->where('url_from', $backlink['url_from'])->first();
        //                                         if (!$already_exists) {
        //                                             DB::table('backlinks')->insert([
        //                                                 'user_id'   =>  $user_id,
        //                                                 'domain_id'   =>  $domain_id,
        //                                                 'total_count'   =>  $total_count,
        //                                                 'url_from'   =>  $backlink['url_from'],
        //                                                 'title'   =>  $backlink['page_from_title'],
        //                                                 'domain_to'   =>  $backlink['domain_to'],
        //                                                 'is_new'   =>  $backlink['is_new'],
        //                                                 'is_lost'   =>  $backlink['is_lost'],
        //                                                 'do_follow'   =>  $backlink['dofollow'],
        //                                                 'p_a'   =>  $backlink['page_from_rank'],
        //                                                 'd_a'   =>  $backlink['domain_from_rank'],
        //                                                 'domain_from_rank'  =>  $backlink['domain_from_rank'],
        //                                                 'date'   =>  date('Y-m-d H:i:s')
        //                                             ]);
        //                                         }
        //                                     }
        //                                 }
        //                             }
        //                         }
        //                     } catch (RestClientException $e) {
        //                     }



        //                     $post_array = array();
        //                     $post_array[] = array(
        //                         "target" => $domain->domain,
        //                         "mode" => "as_is",
        //                         "filters" => ["dofollow", "=", false]
        //                     );
        //                     try {
        //                         $result = $client->post('/v3/backlinks/backlinks/live', $post_array);
        //                         // prx($result);
        //                         if ($result['status_message'] == "Ok.") {
        //                             if ($result['tasks'][0]['status_message'] == "Ok.") {
        //                                 $total_count = $result['tasks'][0]['result'][0]['total_count'];
        //                                 $all_backlinks = $result['tasks'][0]['result'][0]['items'];
        //                                 if (isset($backlinks) && count($backlinks) > 0) {
        //                                     foreach ($all_backlinks as $backlink) {
        //                                         $already_exists = DB::table('backlinks')->where('domain_id', $domain_id)->where('user_id', $user_id)->where('url_from', $backlink['url_from'])->first();
        //                                         if (!$already_exists) {
        //                                             DB::table('backlinks')->insert([
        //                                                 'user_id'   =>  $user_id,
        //                                                 'domain_id'   =>  $domain_id,
        //                                                 'total_count'   =>  $total_count,
        //                                                 'url_from'   =>  $backlink['url_from'],
        //                                                 'title'   =>  $backlink['page_from_title'],
        //                                                 'domain_to'   =>  $backlink['domain_to'],
        //                                                 'is_new'   =>  $backlink['is_new'],
        //                                                 'is_lost'   =>  $backlink['is_lost'],
        //                                                 'do_follow'   =>  $backlink['dofollow'],
        //                                                 'p_a'   =>  $backlink['page_from_rank'],
        //                                                 'd_a'   =>  $backlink['domain_from_rank'],
        //                                                 'domain_from_rank'  =>  $backlink['domain_from_rank'],
        //                                                 'date'   =>  date('Y-m-d H:i:s')
        //                                             ]);
        //                                         }
        //                                     }
        //                                 }
        //                             }
        //                         }
        //                     } catch (RestClientException $e) {
        //                     }
        //                     $client = null;
        //                 }
        //             }
        //         }
        //     }
        // })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
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