<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;

class FrontController extends Controller
{
    public function InstallApp()
    {
        $DB_HOST = env('DB_HOST');
        $DB_DATABASE = env('DB_DATABASE');
        $DB_USERNAME = env('DB_USERNAME');
        $DB_PASSWORD = env('DB_PASSWORD');
        if (!isset($DB_HOST) || $DB_HOST == '' || !isset($DB_DATABASE) || $DB_DATABASE == '' || !isset($DB_USERNAME) || $DB_USERNAME == '' || !isset($DB_PASSWORD) || $DB_PASSWORD == '') {
            return view('install');
        } else {
            $api_data = DB::table('api')->get();
            if (count($api_data) > 0) {
                $api = DB::table('api')->first();
                if ($api->api_email == null || $api->api_key == null) {
                    return view('install');
                }
            }
        }
        return redirect('/');
    }
    public function changeLang($langcode)
    {

        App::setLocale($langcode);
        session()->put("lang_code", $langcode);
        return redirect()->back();
    }
    public function AdminConfiguration()
    {
        $admin_data = DB::table('admin')->first();
        if ($admin_data) {
            if ($admin_data->username == null || $admin_data->password == null) {

                return view('admin-configuration');
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    public function AdminConfigurationDone(Request $request)
    {
        $request->validate([
            'username'   =>  'required',
            'password'   =>  'required',
        ]);
        $admin_data = DB::table('admin')->first();
        if ($admin_data) {
            if ($admin_data->username == null || $admin_data->password == null) {
                DB::table('admin')->where('id', $admin_data->id)->update([
                    'username'  =>  $request->username,
                    'password'  =>  $request->password
                ]);
            }
            return view('installation-complete');
        }
    }
    public function InstallationComplete()
    {
        return view('installation-complete');
    }

    public function DatabaseSetting()
    {
        $DB_HOST = env('DB_HOST');
        $DB_DATABASE = env('DB_DATABASE');
        $DB_USERNAME = env('DB_USERNAME');
        $DB_PASSWORD = env('DB_PASSWORD');
        if (!isset($DB_HOST) || $DB_HOST == '' || !isset($DB_DATABASE) || $DB_DATABASE == '' || !isset($DB_USERNAME) || $DB_USERNAME == '' || !isset($DB_PASSWORD) || $DB_PASSWORD == '') {
            return view('database-setting');
        } else {
            $api_data = DB::table('api')->get();
            if (count($api_data) > 0) {
                $api = DB::table('api')->first();
                if ($api->api_email != null && $api->api_key != null) {
                    return view('database-setting');
                }
            }
        }
        return redirect('/');
    }

    public function DatabaseSettingFailed()
    {
        $DB_HOST = env('DB_HOST');
        $DB_DATABASE = env('DB_DATABASE');
        $DB_USERNAME = env('DB_USERNAME');
        $DB_PASSWORD = env('DB_PASSWORD');
        if (!isset($DB_HOST) || $DB_HOST == '' || !isset($DB_DATABASE) || $DB_DATABASE == '' || !isset($DB_USERNAME) || $DB_USERNAME == '' || !isset($DB_PASSWORD) || $DB_PASSWORD == '') {
            return view('database-setting-failed');
        } else {
            $api_data = DB::table('api')->get();
            if (count($api_data) > 0) {
                $api = DB::table('api')->first();
                if ($api->api_email != null && $api->api_key != null) {
                    return view('database-setting-failed');
                }
            }
        }
        return redirect('/');
    }

    public function DatabaseSettingConfigure(Request $request)
    {
        $request->validate([
            'db_host'   =>  'required',
            'db_username'   =>  'required',
            'db_password'   =>  'required',
            'db_database'   =>  'required'
        ]);
        $oldDbName = env("DB_DATABASE");
        $oldDbHost = env("DB_HOST");
        $oldDbUsername = env("DB_USERNAME");
        $oldDbPassword = env("DB_PASSWORD");

        DB::disconnect(env("DB_CONNECTION"));
        Config::set('database.connections.' . env("DB_CONNECTION"), array(
            'driver'    => 'mysql', //or $request['driver'],
            'host'      => $request->db_host,
            'database'  => $request->db_database,
            'username'  => $request->db_username,
            'password'  => $request->db_password,
            'charset'   => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix'    => '',
        ));

        try {
            $this->changeEnvironmentVariable('DB_HOST', $request->db_host);
            $this->changeEnvironmentVariable('DB_USERNAME', $request->db_username);
            $this->changeEnvironmentVariable('DB_PASSWORD', $request->db_password);
            $this->changeEnvironmentVariable('DB_DATABASE', $request->db_database);

            if (is_file(public_path() . "/install/main_sql_file.sql")) {
                foreach (DB::select('SHOW TABLES') as $table) {
                    $all_table_names = get_object_vars($table);
                    Schema::drop($all_table_names[key($all_table_names)]);
                }
                DB::unprepared(file_get_contents(public_path() . '/install/main_sql_file.sql'));
            }
            return redirect('/admin-configuration');
        } catch (\Exception $e) {
            DB::disconnect(env("DB_CONNECTION"));
            Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $oldDbName);

            $this->changeEnvironmentVariable('DB_HOST', $oldDbHost);
            $this->changeEnvironmentVariable('DB_USERNAME', $oldDbUsername);
            $this->changeEnvironmentVariable('DB_PASSWORD', $oldDbPassword);
            $this->changeEnvironmentVariable('DB_DATABASE', $oldDbName);

            return redirect('/database-setting-failed');
        }
        // $api = DB::table('basic_settings')->where('id',1)->update([
        //     'for_emails_email' =>  $request->for_emails_email,
        //     'for_emails_password' =>  $request->for_emails_password
        // ]);
        // if($api) {
        //     $this->changeEnvironmentVariable('MAIL_HOST', $request->for_emails_host);
        //     $this->changeEnvironmentVariable('MAIL_USERNAME', $request->for_emails_email);
        //     $this->changeEnvironmentVariable('MAIL_FROM_ADDRESS', $request->for_emails_email);
        //     $this->changeEnvironmentVariable('MAIL_PASSWORD', $request->for_emails_password);

        //     return redirect('/admin-configuration');
        // } else {
        // return redirect('/database-setting-failed');
        // }
    }

    public static function changeEnvironmentVariable($key, $value)
    {
        $path = base_path('.env');
        $old = env($key);
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                "$key=" . $old,
                "$key=" . $value,
                file_get_contents($path)
            ));
        }
    }


    public function ActivateTrialPackage($id)
    {
        $date = date('Y-m-d H:i:s');
        $user_info = DB::table('users')->where('trial_code', $id)->where('subscription', 2)->first();
        if ($user_info) {
            $package_info = DB::table('packages')->where('id', 17)->first();
            $days = $package_info->subscription . " days";
            $expiry_date = date_format(date_add(date_create($date), date_interval_create_from_date_string($days)), 'Y-m-d H:i:s');
            DB::table('users')->where('id', $user_info->id)->update([
                'payment'   =>  1,
                'package_id'   =>  17,
                'subscription'   =>  1,
                'payment_method'   =>  "paypal",
                'payment_date'  =>  $date,
                'expire_date'   =>  $expiry_date,
            ]);
            Session::flash('message', 'Your account has been activated. Please login to continue...');
            Session::flash('alert-type', 'success');
            return redirect('/login-register');
        } else {
            return redirect('/');
        }
    }

    public function GetSubscriptionPackage($package_id)
    {
        $date = date('Y-m-d H:i:s');
        $package_info = DB::table('packages')->where('id', $package_id)->first();
        $payment_settings = DB::table('paypal')->where('id', 1)->first();

        $basic_settings = DB::table('basic_settings')->first();
        $currency_data = DB::table('currency')->where('html_symbol', $basic_settings->currency)->first();
        $user_selected_payment_mehtod = null;
        // prx($currency_data);

        if (Session::get('user_id')) {
            $user_info = DB::table('users')->where('id', Session::get('user_id'))->first();
            if ($basic_settings->payment_method == "both") {
                $user_selected_payment_mehtod = $user_info->payment_method;
            } else {
                $user_selected_payment_mehtod = $basic_settings->payment_method;
            }
            if ($user_selected_payment_mehtod == "paypal") {

                $shopier_id = DB::table('shopier')->insertGetId([
                    'user_id' => $user_info->id,
                    'package_id'    =>  $package_info->id,
                    'price' => $package_info->price,
                    'payment' => 0,
                    'payment_method' => "paypal",
                    'date'    =>  $date
                ]);

                $row_apiayar = DB::table('paypal')->where('id', 1)->first();

                date_default_timezone_set('Europe/Amsterdam');
                $shopier = new Shopier($row_apiayar->shipy_apikey, $row_apiayar->shopiersecret);
                $shopier->setBuyer([
                    'id' => 23,
                    'first_name' => $user_info->Name, 'last_name' => $user_info->Name, 'email' => $user_info->email, 'phone' => "99999097989"
                ]);


                // if ($row_apiayar->aktif == "paypal") {
                //die($shopier->run($row_shopier->shopier_id,$_POST['tutar'], "http://".$_SERVER['SERVER_NAME']."/sd.php"));

                $paypalConfig = [
                    'email' => $row_apiayar->paypal_mail,
                    'return_url' => "http://" . $_SERVER['HTTP_HOST'] . "/pricing/done/" . $user_info->id . "/" . $shopier_id,
                    'cancel_url' => "http://" . $_SERVER['HTTP_HOST'] . "/pricing",
                    'notify_url' => "http://" . $_SERVER['HTTP_HOST'] . "/pricing"
                ];

                $paypalUrl = 'https://www.paypal.com/cgi-bin/webscr';
                $itemName = 'Digital Product';
                $itemAmount = $package_info->price;
                $data = [];
                $_POST['cmd'] = '_xclick';
                $_POST['no_note'] = '1';
                $_POST['lc'] = 'UK';
                $_POST['bn'] = 'PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest';
                $_POST['first_name'] = $user_info->Name;
                $_POST['last_name'] = "a";
                $_POST['payer_email'] = $user_info->email;
                $_POST['item_number'] = rand();
                foreach ($_POST as $key => $value) {
                    $data[$key] = stripslashes($value);
                }

                $data['business'] = $paypalConfig['email'];
                $data['return'] = stripslashes($paypalConfig['return_url']);
                $data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
                $data['notify_url'] = stripslashes($paypalConfig['notify_url']);
                $data['item_name'] = $itemName;
                $data['amount'] = $itemAmount;
                $data['currency_code'] = $currency_data->currency_code;
                $data['custom'] = $shopier_id;
                $queryString = http_build_query($data);
                header('location:' . $paypalUrl . '?' . $queryString);
                exit();
            } else if ($user_selected_payment_mehtod == "stripe") {

                $shopier_id = DB::table('shopier')->insertGetId([
                    'user_id' => $user_info->id,
                    'package_id'    =>  $package_info->id,
                    'price' => $package_info->price,
                    'payment' => 0,
                    'payment_method' => "stripe",
                    'date'    =>  $date
                ]);

                \Stripe\Stripe::setApiKey($payment_settings->stripe_secret_key);

                $amount = $package_info->price;
                $amount *= 100;
                $amount = (int) $amount;

                $payment_intent = \Stripe\PaymentIntent::create([
                    'description' => 'Payment for Subscription',
                    'amount' => $amount,
                    'currency' => $currency_data->currency_code,
                    'payment_method_types' => ['card'],
                ]);
                $intent = $payment_intent->client_secret;
                $user_id = $user_info->id;
                $package_id = $package_info->id;
                $price = $package_info->price;
                $stripe_publishable_key = $payment_settings->stripe_publishable_key;
                // prx($in tent);

                return view('seo.credit-card-get-subscription', compact('intent', 'shopier_id', 'user_id', 'package_id', 'price', 'stripe_publishable_key'));
            }
        }
        return redirect()->back();
    }

    public function StripePricingGetSubscription(Request $request)
    {
        $user_id = $request->user_id;
        $package_id = $request->package_id;
        $shopier_id = $request->shopier_id;
        // $price = $request->price;

        $date = date('Y-m-d H:i:s');

        $user_info = DB::table('users')->where('id', $user_id)->first();
        $remaining_days = 0;
        // if($user_info->payment != 0 && $user_info->payment != null) {
        //     $expire_date = new DateTime($user_info->expire_date);
        //     $now_date = new DateTime(date('Y-m-d H:i:s'));
        //     $interval = $expire_date->diff($now_date);
        //     $remaining_days = $interval->d;
        // }


        $package_info = DB::table('packages')->where('id', $package_id)->first();
        $days = 0;
        if ($package_info->subscription == "monthly") {
            $days = 30;
        } elseif ($package_info->subscription == "yearly") {
            $days = 365;
        }
        if ($remaining_days < 0) {
            $remaining_days = 0;
        }
        $days += $remaining_days;
        $days = $days . " days";
        // prx($days);

        if ($user_info->expire_date == null) {
            $date = $date;
        } elseif ($user_info->expire_date <= date('Y-m-d H:i:S')) {
            $date = $date;
        } else {
            $date = $user_info->expire_date;
        }

        $expiry_date = date_format(date_add(date_create($date), date_interval_create_from_date_string($days)), 'Y-m-d H:i:s');

        DB::table('users')->where('id', $user_id)->update([
            'payment'   =>  1,
            'payment_date'  =>  $date,
            'package_id'    =>  $package_id,
            'payment_method'   =>  "stripe",
            'expire_date'   =>  $expiry_date
        ]);
        DB::table('shopier')->where('id', $shopier_id)->update([
            'payment'   =>  1,
            'payment_date'      =>  $date
        ]);

        $user_date = DB::table('users')->where('id', $user_id)->first();
        Session::put('user_id', $user_date->id);
        Session::put('user_email', $user_date->email);
        Session::put('user_name', $user_date->Name);
        return redirect('/user/dashboard');
    }

    public function PaypalPricingGetSubscription($user_id, $shopier_id)
    {
        $date = date('Y-m-d H:i:s');
        $user_info = DB::table('users')->where('id', $user_id)->first();
        $remaining_days = 0;
        // if($user_info->payment != 0 && $user_info->payment != null) {
        //     $expire_date = new DateTime($user_info->expire_date);
        //     $now_date = new DateTime(date('Y-m-d H:i:s'));
        //     $interval = $expire_date->diff($now_date);
        //     $remaining_days = $interval->d;
        // }

        $shopier_info = DB::table('shopier')->where('id', $shopier_id)->first();

        $package_info = DB::table('packages')->where('id', $shopier_info->package_id)->first();

        if ($package_info->subscription == "monthly") {
            $days = 30;
        } elseif ($package_info->subscription == "yearly") {
            $days = 365;
        }
        if ($remaining_days < 0) {
            $remaining_days = 0;
        }
        $days += $remaining_days;
        $days = $days . " days";

        if ($user_info->expire_date == null) {
            $date = $date;
        } elseif ($user_info->expire_date <= date('Y-m-d H:i:S')) {
            $date = $date;
        } else {
            $date = $user_info->expire_date;
        }

        $expiry_date = date_format(date_add(date_create($date), date_interval_create_from_date_string($days)), 'Y-m-d H:i:s');

        DB::table('users')->where('id', $user_id)->update([
            'payment'   =>  1,
            'payment_method'   =>  "paypal",
            'package_id'    =>  $package_info->id,
            'payment_date'  =>  $date,
            'expire_date'   =>  $expiry_date
        ]);

        DB::table('shopier')->where('id', $shopier_id)->update([
            'payment'   =>  1,
            'payment_date'      =>  $date
        ]);

        $user_date = DB::table('users')->where('id', $user_id)->first();

        Session::put('user_id', $user_date->id);
        Session::put('user_email', $user_date->email);
        Session::put('user_name', $user_date->Name);
        return redirect('/user/dashboard');
    }

    public function GetSubscription()
    {
        if (Session::has('user_id')) {
            $user_info = DB::table('users')->where('id', Session::get('user_id'))->first();
            $user_package_info = DB::table('packages')->where('id', $user_info->package_id)->first();
        } else {
            return redirect('/logout');
        }
        $packages = DB::table('packages')->where('status', 1)->where('price', '>=', $user_package_info->price)->orderBy('price', 'ASC')->get();
        $customer_reviews = DB::table('cus_reviews')->where('status', 1)->orderBy('id', 'ASC')->get();

        return view('front.get_pricing', compact('packages', 'customer_reviews'));
    }

    public function Message()
    {
        //echo "Bro i really need a good review. PLz give me a 5 star rating and review.";
        // echo "<br>";
        // echo "You can write that 'I always feel relaxed when i am working with you' or any other thing that you want to write";
        die;
    }
    public function dashboard()
    {
        $satisfaction_reasons = DB::table('satisfaction_reasons')->where('status', 1)->orderBy('id', 'ASC')->get();
        $teams = DB::table('team')->where('status', 1)->orderBy('id', 'ASC')->get();
        $customer_reviews = DB::table('cus_reviews')->where('status', 1)->orderBy('id', 'ASC')->get();
        $home_banner = DB::table('home_banner')->first();
        $home_highlights = DB::table('home_highlights')->orderBy('id', 'DESC')->get();
        $home_third_section = DB::table('home_third_section')->first();
        $home_fourth_section = DB::table('home_fourth_section')->first();
        $home_case_studies = DB::table('home_case_studies')->orderBy('id', 'ASC')->get();
        $trial_package = DB::table('packages')->where('id', 17)->where('status', 1)->get();
        $packages = DB::table('packages')->where('status', 1)->orderBy('price', 'ASC')->get();
        return view('front.dashboard', compact('customer_reviews', 'satisfaction_reasons', 'teams', 'home_banner', 'home_highlights', 'home_third_section', 'home_fourth_section', 'home_case_studies', 'trial_package', 'packages'));
    }

    public function AboutUs()
    {
        $teams = DB::table('team')->where('status', 1)->orderBy('id', 'ASC')->get();
        $about_us = DB::table('about_us')->first();
        $services_page_highlights = DB::table('services_page_highlights')->orderBy('id', 'ASC')->get();
        $home_case_studies = DB::table('home_case_studies')->orderBy('id', 'ASC')->get();
        $trial_package = DB::table('packages')->where('id', 17)->where('status', 1)->get();
        $packages = DB::table('packages')->where('status', 1)->orderBy('price', 'ASC')->get();
        return view('front.about-us', compact('teams', 'about_us', 'services_page_highlights', 'home_case_studies', 'trial_package', 'packages'));
    }

    public function Pricing()
    {
        $trial_package = DB::table('packages')->where('id', 17)->where('status', 1)->get();
        // prx(Session::get('user_id'));
        $packages = DB::table('packages')->where('status', 1)->orderBy('price', 'ASC')->get();
        $customer_reviews = DB::table('cus_reviews')->where('status', 1)->orderBy('id', 'ASC')->get();

        return view('front.pricing', compact('packages', 'customer_reviews', 'trial_package'));
    }

    public function UserMessage(Request $request)
    {
        $request->validate([
            'g-recaptcha-response'  =>  'required'
        ]);
        $api = DB::table('api')->first();
        $secret_key = $api->recaptcha_secret_key;
        $ip = $_SERVER['REMOTE_ADDR'];
        $response = $request->post('g-recaptcha-response');
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$response&rempteip=$ip";
        $fire = file_get_contents($url);
        $data = json_decode($fire);
        if ($data->success == true) {
        } else {
            $request->session()->flash('message', 'Invalid Recaptcha.');
            $request->session()->flash('alert-type', 'error');
            return redirect()->back();
        }

        DB::table('messages')->insert([
            'name'  =>  $request->name,
            'email'  =>  $request->email,
            'subject'  =>  $request->subject,
            'message'  =>  $request->message,
            'read_status'  =>  0
        ]);

        $basic_settings = DB::table('basic_settings')->where('id', 1)->first();
        $data = ['name' => $request->name, 'subject' => $request->subject, 'response' => $request->message, 'logo' => $basic_settings->site_logo];
        $user['to'] = $request->email;
        $user['from'] = $basic_settings->for_emails_email;

        try {
            Mail::send('mail.user_message_received', $data, function ($message) use ($user) {
                $message->from($user['from'], 'Serp Rank');
                $message->sender($user['from'], 'Serp Rank');
                $message->to($user['to']);
                $message->subject('Message Received');
                $message->priority(3);
            });
        } catch (Exception $e) {
        }

        $request->session()->flash('message', 'Your message has been sent successfully.');
        $request->session()->flash('alert-type', 'success');
        return redirect()->back();
    }

    public function Contact()
    {
        $contact_page = DB::table('contact_page')->first();
        $api = DB::table('api')->first();
        return view('front.contact', compact('contact_page', 'api'));
    }

    public function Blogs()
    {
        $blogs = DB::table('blogs')->where('status', 1)->where('purpose', 'blog')->orderby('id', 'DESC')->paginate(9);
        return view('front.blogs', compact('blogs'));
    }

    public function BlogDetail($id)
    {
        $blog = DB::table('blogs')->where('id', $id)->first();
        return view('front.blog-detail', compact('blog'));
    }

    public function Learn()
    {
        $blogs = DB::table('blogs')->where('status', 1)->where('purpose', 'learn')->orderby('id', 'DESC')->paginate(9);
        return view('front.learn', compact('blogs'));
    }

    public function LearnDetail($id)
    {
        $blog = DB::table('blogs')->where('id', $id)->first();
        return view('front.learn-detail', compact('blog'));
    }

    public function AddSubscriber(Request $request)
    {
        $already_subscribed = DB::table('subscribers')->where('email', $request->email)->get();
        if (count($already_subscribed) > 0) {
            $request->session()->flash('message', 'You have already subscribed.');
            $request->session()->flash('alert-type', 'info');
            return redirect()->back();
        }
        DB::table('subscribers')->insert([
            'email' =>  $request->email,
            'status'    =>  1
        ]);
        $request->session()->flash('message', 'You have been successfully subscribed.');
        $request->session()->flash('alert-type', 'success');
        return redirect()->back();
    }

    public function LoginRegister(Request $request)
    {
        if ($request->session()->has('user_id') && $request->session()->has('user_email') && $request->session()->has('user_name')) {
            return redirect('/user/dashboard');
        }
        return view('front.login-register');
    }

    public function GetStartedPackage($id)
    {
        $package = DB::table('packages')->where('id', $id)->first();
        $settings = DB::table('basic_settings')->first();

        return view('front.pricing-register', compact('package', 'settings'));
    }

    public function TrialGetStartedPackage()
    {
        $settings = DB::table('basic_settings')->first();
        $api = DB::table('api')->first();

        return view('front.trial-package-register', compact('settings', 'api'));
    }

    public function UserRegister(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $payment_settings = DB::table('paypal')->where('id', 1)->first();
        $package_info = DB::table('packages')->where('id', $request->package_id)->first();
        $days = null;
        // prx($request->post());


        $already_exists = DB::table('users')->where('email', $request->email)->where('payment', 1)->get();
        $basic_settings = DB::table('basic_settings')->first();
        $currency_data = DB::table('currency')->where('html_symbol', $basic_settings->currency)->first();

        if (count($already_exists) > 0) {
            $request->session()->flash('message', 'E-mail Bestaat Al.');
            $request->session()->flash('alert-type', 'error');
            return redirect()->back();
        } else {
            $already_exists = DB::table('users')->where('email', $request->email)->get();

            if (count($already_exists) > 0) {
                $password = Hash::make($request->password);
                DB::table('users')->where('id', $already_exists[0]->id)->update([
                    'password' => $password,
                    'name' => $request->name,
                    'package_id'    =>  $request->package_id,
                    'payment'    =>  0,
                    'status'    =>  1,
                    'date'    =>  $date
                ]);
                $user_id = $already_exists[0]->id;
            } else {
                $password = Hash::make($request->password);
                $user_id = DB::table('users')->insertGetId([
                    'email' => $request->email,
                    'password' => $password,
                    'name' => $request->name,
                    'package_id'    =>  $request->package_id,
                    'payment'    =>  0,
                    'status'    =>  1,
                    'date'    =>  $date
                ]);
            }

            if ($request->payment_method == 'trial') {
                $date = date('Y-m-d H:i:s');
                $user_info = DB::table('users')->where('id', $user_id)->first();
                $package_info = DB::table('packages')->where('id', 17)->first();

                $days = $package_info->subscription . " days";

                $expiry_date = date_format(date_add(date_create($date), date_interval_create_from_date_string($days)), 'Y-m-d H:i:s');
                $rand = rand(1111111, 9999999);
                DB::table('users')->where('id', $user_id)->update([
                    'payment'   =>  1,
                    'package_id'   =>  17,
                    'subscription'   =>  2,
                    'payment_method'   =>  "paypal",
                    'payment_date'  =>  $date,
                    'expire_date'   =>  $expiry_date,
                    'trial_code'    =>  $rand
                ]);

                $user_date = DB::table('users')->where('id', $user_id)->first();

                $basic_settings = DB::table('basic_settings')->where('id', 1)->first();
                $data = ['name' => $request->name, 'rand' => $rand, 'logo' => $basic_settings->site_logo];
                $user['to'] = $request->email;
                $user['from'] = $basic_settings->for_emails_email;

                try {
                    Mail::send('mail.trial_package_activation', $data, function ($message) use ($user) {
                        $message->from($user['from'], 'Serp Ranking');
                        $message->sender($user['from'], 'Serp Ranking');
                        $message->to($user['to']);
                        $message->subject('Activation trial package');
                        $message->priority(3);
                    });
                } catch (Exception $e) {
                }

                Session::flash('message', 'An email has been sent to your email address. Verify your email address to continue...');
                Session::flash('alert-type', 'success');
                return redirect('/');
            }


            if ($request->payment_method == "paypal") {

                $shopier_id = DB::table('shopier')->insertGetId([
                    'user_id' => $user_id,
                    'package_id'    =>  $request->package_id,
                    'price' => $request->price,
                    'payment' => 0,
                    'payment_method' => "paypal",
                    'date'    =>  $date
                ]);

                // $row_apiayar = DB::table('odeme_api')->where('Id', 1)->first();
                $row_apiayar = DB::table('paypal')->where('id', 1)->first();
                // $row_shopier = DB::table('shopier')->where('username', Session::get('MM_Username'))->orderBy('shopier_id', 'DESC')->first();
                // $row_uyebilgisi = DB::table('uye')->where('uyeadi', Session::get('MM_Username'))->first();
                $user_info = DB::table('users')->where('id', $user_id)->first();
                // $row_ayarbilgi = DB::table('ayar')->where('ayar_id', 1)->first();

                date_default_timezone_set('Europe/Amsterdam');
                $shopier = new Shopier($row_apiayar->shipy_apikey, $row_apiayar->shopiersecret);
                $shopier->setBuyer([
                    'id' => 23,
                    'first_name' => $user_info->Name, 'last_name' => $user_info->Name, 'email' => $user_info->email, 'phone' => "99999097989"
                ]);
                $shopier->setOrderBilling([
                    'billing_address' => 'Sanal Ürün',
                    'billing_city' => 'İstanbul',
                    'billing_country' => 'turkey',
                    'billing_postcode' => '34200',
                ]);
                $shopier->setOrderShipping([
                    'shipping_address' => 'Sanal Ürün',
                    'shipping_city' => 'İstanbul',
                    'shipping_country' => 'turkey',
                    'shipping_postcode' => '34200',
                ]);
                // if ($row_apiayar->aktif == "paypal") {
                //die($shopier->run($row_shopier->shopier_id,$_POST['tutar'], "http://".$_SERVER['SERVER_NAME']."/sd.php"));

                $paypalConfig = [
                    'email' => $row_apiayar->paypal_mail,
                    'return_url' => "http://" . $_SERVER['HTTP_HOST'] . "/pricing/done/" . $user_info->id . "/" . $shopier_id,
                    'cancel_url' => "http://" . $_SERVER['HTTP_HOST'] . "/pricing",
                    'notify_url' => "http://" . $_SERVER['HTTP_HOST'] . "/pricing"
                ];

                $paypalUrl = 'https://www.paypal.com/cgi-bin/webscr';
                $itemName = 'Digital Product';
                $itemAmount = $_POST['price'];
                $data = [];
                $_POST['cmd'] = '_xclick';
                $_POST['no_note'] = '1';
                $_POST['lc'] = 'UK';
                $_POST['bn'] = 'PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest';
                $_POST['first_name'] = $user_info->Name;
                $_POST['last_name'] = "a";
                $_POST['payer_email'] = $user_info->email;
                $_POST['item_number'] = rand();
                foreach ($_POST as $key => $value) {
                    $data[$key] = stripslashes($value);
                }

                $data['business'] = $paypalConfig['email'];
                $data['return'] = stripslashes($paypalConfig['return_url']);
                $data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
                $data['notify_url'] = stripslashes($paypalConfig['notify_url']);
                $data['item_name'] = $itemName;
                $data['amount'] = $itemAmount;
                $data['currency_code'] = $currency_data->currency_code;
                $data['custom'] = $shopier_id;
                $queryString = http_build_query($data);
                header('location:' . $paypalUrl . '?' . $queryString);
                exit();
            } else if ($request->payment_method == "stripe") {
                $user_info = DB::table('users')->where('id', $user_id)->first();

                $shopier_id = DB::table('shopier')->insertGetId([
                    'user_id' => $user_id,
                    'package_id'    =>  $request->package_id,
                    'price' => $request->price,
                    'payment' => 0,
                    'payment_method' => "stripe",
                    'date'    =>  $date
                ]);

                \Stripe\Stripe::setApiKey($payment_settings->stripe_secret_key);

                $amount = $request->price;
                $amount *= 100;
                $amount = (int) $amount;

                $payment_intent = \Stripe\PaymentIntent::create([
                    'description' => 'Payment for Subscription',
                    'amount' => $amount,
                    'currency' => $currency_data->currency_code,
                    'payment_method_types' => ['card'],
                ]);
                $intent = $payment_intent->client_secret;
                $user_id = $user_info->id;
                $package_id = $user_info->package_id;
                $price = $request->price;
                $stripe_publishable_key = $payment_settings->stripe_publishable_key;
                // prx($intent);

                return view('seo.credit-card', compact('intent', 'shopier_id', 'user_id', 'package_id', 'price', 'stripe_publishable_key'));
            }
        }
        return redirect()->back();
    }


    public function PricePaid($user_id, $shopier_id)
    {
        $date = date('Y-m-d H:i:s');
        $user_info = DB::table('users')->where('id', $user_id)->first();
        $new_package = DB::table('shopier')->where('id', $shopier_id)->first();
        $package_info = DB::table('packages')->where('id', $new_package->package_id)->first();

        if ($package_info->subscription == "monthly") {
            $days = "30 days";
        } elseif ($package_info->subscription == "yearly") {
            $days = "365 days";
        }

        if ($user_info->expire_date == null) {
            $date = $date;
        } elseif ($user_info->expire_date <= date('Y-m-d H:i:S')) {
            $date = $date;
        } else {
            $date = $user_info->expire_date;
        }

        $expiry_date = date_format(date_add(date_create($date), date_interval_create_from_date_string($days)), 'Y-m-d H:i:s');

        DB::table('users')->where('id', $user_id)->update([
            'payment'   =>  1,
            'package_id'   =>  $new_package->package_id,
            'payment_method'   =>  "paypal",
            'payment_date'  =>  date('Y-m-d H:i:s'),
            'expire_date'   =>  $expiry_date
        ]);

        DB::table('shopier')->where('id', $shopier_id)->update([
            'payment'   =>  1,
            'payment_date'      =>  $date
        ]);

        $user_date = DB::table('users')->where('id', $user_id)->first();

        Session::put('user_id', $user_date->id);
        Session::put('user_email', $user_date->email);
        Session::put('user_name', $user_date->Name);
        return redirect('/user/dashboard');
    }

    public function StripePricing(Request $request)
    {
        $user_id = $request->user_id;
        $date = date('Y-m-d H:i:s');

        $user_info = DB::table('users')->where('id', $user_id)->first();
        $new_package = DB::table('shopier')->where('id', $request->shopier_id)->first();

        $package_info = DB::table('packages')->where('id', $new_package->package_id)->first();

        if ($package_info->subscription == "monthly") {
            $days = "30 days";
        } elseif ($package_info->subscription == "yearly") {
            $days = "365 days";
        }

        if ($user_info->expire_date == null) {
            $date = $date;
        } elseif ($user_info->expire_date <= date('Y-m-d H:i:S')) {
            $date = $date;
        } else {
            $date = $user_info->expire_date;
        }

        $expiry_date = date_format(date_add(date_create($date), date_interval_create_from_date_string($days)), 'Y-m-d H:i:s');

        $package_id = $request->package_id;
        $shopier_id = $request->shopier_id;
        $price = $request->price;


        DB::table('users')->where('id', $user_id)->where('package_id', $package_id)->update([
            'payment'   =>  1,
            'package_id'   =>  $new_package->package_id,
            'payment_date'  =>  date('Y-m-d H:i:s'),
            'payment_method'   =>  "stripe",
            'expire_date'   =>  $expiry_date
        ]);

        DB::table('shopier')->where('id', $shopier_id)->update([
            'payment'   =>  1,
            'payment_date'      =>  $date
        ]);

        $user_date = DB::table('users')->where('id', $user_id)->first();

        Session::put('user_id', $user_date->id);
        Session::put('user_email', $user_date->email);
        Session::put('user_name', $user_date->Name);
        return redirect('/user/dashboard');
    }

    public function UserLogin(Request $request)
    {
        //prx($request->post());
        $email_exists = DB::table('users')->where('email', $request->email)->where('payment', 1)->where('status', 1)->first();
        if ($email_exists) {
            if ($email_exists->subscription == 2) {
                $request->session()->flash('message', 'Please verify your email address first to continue...');
                $request->session()->flash('alert-type', 'error');
                return redirect()->back();
            }

            $password_exists = Hash::check($request->password, $email_exists->password);
            if ($password_exists) {
                $request->session()->put('user_id', $email_exists->id);
                $request->session()->put('user_email', $email_exists->email);
                $request->session()->put('user_name', $email_exists->Name);

                if ($request->rememberme != null) {
                    setcookie('login_email', $request->email, time() + 60 * 60 * 24 * 100);
                    setcookie('login_password', $request->password, time() + 60 * 60 * 24 * 100);
                } else {
                    setcookie('login_email', $request->email, 100);
                    setcookie('login_password', $request->password, 100);
                }

                return redirect('/user/dashboard');
            } else {
                $request->session()->flash('message', 'Password is not correct');
                $request->session()->flash('alert-type', 'error');
                return redirect()->back();
            }
        } else {
            $request->session()->flash('message', 'Email or password is incorrect');
            $request->session()->flash('alert-type', 'error');
            return redirect()->back();
        }
    }

    public function ForgotPassword()
    {
        return view('front.forgot_password');
    }

    public function ForgotPasswordSendCode(Request $request)
    {
        $rand = rand(100000, 999999);
        $email = $request->email;
        $email_exists = DB::table('users')->where('email', $request->email)->get();
        if (count($email_exists) > 0) {
            DB::table('users')->where('email', $request->email)->update([
                'forgot_password_code'  =>  $rand,
                'forgot_code_used'  =>  0
            ]);
            $basic_settings = DB::table('basic_settings')->where('id', 1)->first();

            $data = ['random' => $rand, 'logo' => $basic_settings->site_logo];
            $user['to'] = $request->email;
            $user['from'] = $basic_settings->for_emails_email;

            try {
                Mail::send('mail.user_forgot_password', $data, function ($message) use ($user) {
                    $message->from($user['from'], 'Serp Ranking');
                    $message->sender($user['from'], 'Serp Ranking');
                    $message->to($user['to']);
                    $message->subject('Forgot your password');
                    $message->priority(3);
                });
            } catch (Exception $e) {
            }
            $request->session()->flash('message', 'Email sent to your registered email ID.');
            $request->session()->flash('alert-type', 'info');
            return redirect('/user/forgot_password_email_sent/');
        } else {
            $request->session()->flash('message', 'E-mail bestaat niet.');
            $request->session()->flash('alert-type', 'error');
            return redirect()->back();
        }
    }

    public function forgot_password_email_sent()
    {
        return view('front.forgot_password_email_sent');
    }

    public function forgot_password_code($id)
    {
        $user_info = DB::table('users')->where('forgot_password_code', $id)->where('forgot_code_used', 0)->first();
        if ($user_info) {
            $email = $user_info->email;
            return view('front.forgot_password_code_submit', compact('email'));
        } else {
            return redirect('/login-register');
        }
    }

    public function forgot_password_code_check(Request $request)
    {
        $code_exists = DB::table('users')->where('email', $request->email)->where('forgot_code_used', 0)->where('forgot_password_code', $request->code)->get();
        if (count($code_exists) > 0) {
            return redirect('/user/change_password/' . $code_exists[0]->id . '/' . $request->code);
        } else {
            $request->session()->flash('message', 'Code does not match.');
            $request->session()->flash('alert-type', 'error');
            return redirect()->back();
        }
    }

    public function ChangePassword($id, $code)
    {
        $code_exists = DB::table('users')->where('id', $id)->where('forgot_code_used', 0)->where('forgot_password_code', $code)->get();
        if (count($code_exists) > 0) {
            return view('front.change_password', compact('id'));
        } else {
            return redirect('/');
        }
    }

    public function ChangePasswordSave(Request $request)
    {
        if ($request->new_password != $request->confirm_password) {
            $request->session()->flash('message', 'Password does not match.');
            $request->session()->flash('alert-type', 'error');
            return redirect()->back();
        }
        $password = Hash::make($request->new_password);

        DB::table('users')->where('email', $request->email)->update([
            'password' => $password,
            'forgot_code_used'  =>  1
        ]);
        $request->session()->flash('message', 'Please login to continue.');
        $request->session()->flash('alert-type', 'success');
        return redirect('/login-register');
    }

    public function UserLogout()
    {
        Session::forget('user_id');
        Session::forget('user_name');
        Session::forget('user_email');
        return redirect('/');
    }

    public function UpgradeSubscription()
    {
        // prx(Session::get('user_id'));
        if (Session::has('user_id')) {
            $user_info = DB::table('users')->where('id', Session::get('user_id'))->first();
            $user_package_info = DB::table('packages')->where('id', $user_info->package_id)->first();
        }
        $packages = DB::table('packages')->where('status', 1)->where('price', '>', $user_package_info->price)->orderBy('price', 'ASC')->get();
        $packages->current_package = $user_package_info;

        $customer_reviews = DB::table('cus_reviews')->where('status', 1)->orderBy('id', 'ASC')->get();

        return view('front.upgrade_pricing', compact('packages', 'customer_reviews'));
    }

    public function UpgradePaymentMethod(Request $request)
    {
        DB::table('users')->where('id', Session::get('user_id'))->update([
            'payment_method'    =>  $request->payment_method
        ]);
        return redirect()->back();
    }

    public function UpgradePackage($package_id)
    {
        $date = date('Y-m-d H:i:s');
        $package_info = DB::table('packages')->where('id', $package_id)->first();
        $payment_settings = DB::table('paypal')->where('id', 1)->first();

        $basic_settings = DB::table('basic_settings')->first();
        $currency_data = DB::table('currency')->where('html_symbol', $basic_settings->currency)->first();
        $user_selected_payment_mehtod = null;

        if (Session::get('user_id')) {
            $user_info = DB::table('users')->where('id', Session::get('user_id'))->first();
            if ($basic_settings->payment_method == "both") {
                $user_selected_payment_mehtod = $user_info->payment_method;
            } else {
                $user_selected_payment_mehtod = $basic_settings->payment_method;
            }
            if ($user_selected_payment_mehtod == "paypal") {

                $shopier_id = DB::table('shopier')->insertGetId([
                    'user_id' => $user_info->id,
                    'package_id'    =>  $package_info->id,
                    'price' => $package_info->price,
                    'payment' => 0,
                    'payment_method' => "paypal",
                    'date'    =>  $date
                ]);

                $row_apiayar = DB::table('paypal')->where('id', 1)->first();

                date_default_timezone_set('Europe/Istanbul');
                $shopier = new Shopier($row_apiayar->shipy_apikey, $row_apiayar->shopiersecret);
                $shopier->setBuyer([
                    'id' => 23,
                    'first_name' => $user_info->Name, 'last_name' => $user_info->Name, 'email' => $user_info->email, 'phone' => "99999097989"
                ]);
                $shopier->setOrderBilling([
                    'billing_address' => 'Sanal Ürün',
                    'billing_city' => 'İstanbul',
                    'billing_country' => 'turkey',
                    'billing_postcode' => '34200',
                ]);
                $shopier->setOrderShipping([
                    'shipping_address' => 'Sanal Ürün',
                    'shipping_city' => 'İstanbul',
                    'shipping_country' => 'turkey',
                    'shipping_postcode' => '34200',
                ]);
                // if ($row_apiayar->aktif == "paypal") {
                //die($shopier->run($row_shopier->shopier_id,$_POST['tutar'], "http://".$_SERVER['SERVER_NAME']."/sd.php"));

                $paypalConfig = [
                    'email' => $row_apiayar->paypal_mail,
                    'return_url' => "http://" . $_SERVER['HTTP_HOST'] . "/pricing/done/" . $user_info->id . "/" . $shopier_id,
                    'cancel_url' => "http://" . $_SERVER['HTTP_HOST'] . "/pricing",
                    'notify_url' => "http://" . $_SERVER['HTTP_HOST'] . "/pricing"
                ];

                $paypalUrl = 'https://www.paypal.com/cgi-bin/webscr';
                $itemName = 'Digital Product';
                $itemAmount = $package_info->price;
                $data = [];
                $_POST['cmd'] = '_xclick';
                $_POST['no_note'] = '1';
                $_POST['lc'] = 'UK';
                $_POST['bn'] = 'PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest';
                $_POST['first_name'] = $user_info->Name;
                $_POST['last_name'] = "a";
                $_POST['payer_email'] = $user_info->email;
                $_POST['item_number'] = rand();
                foreach ($_POST as $key => $value) {
                    $data[$key] = stripslashes($value);
                }

                $data['business'] = $paypalConfig['email'];
                $data['return'] = stripslashes($paypalConfig['return_url']);
                $data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
                $data['notify_url'] = stripslashes($paypalConfig['notify_url']);
                $data['item_name'] = $itemName;
                $data['amount'] = $itemAmount;
                $data['currency_code'] = $currency_data->currency_code;
                $data['custom'] = $shopier_id;
                $queryString = http_build_query($data);
                header('location:' . $paypalUrl . '?' . $queryString);
                exit();
            } else if ($user_selected_payment_mehtod == "stripe") {

                $shopier_id = DB::table('shopier')->insertGetId([
                    'user_id' => $user_info->id,
                    'package_id'    =>  $package_info->id,
                    'price' => $package_info->price,
                    'payment' => 0,
                    'payment_method' => "stripe",
                    'date'    =>  $date
                ]);

                \Stripe\Stripe::setApiKey($payment_settings->stripe_secret_key);

                $amount = $package_info->price;
                $amount *= 100;
                $amount = (int) $amount;

                $payment_intent = \Stripe\PaymentIntent::create([
                    'description' => 'Payment for Subscription',
                    'amount' => $amount,
                    'currency' => $currency_data->currency_code,
                    'payment_method_types' => ['card'],
                ]);
                $intent = $payment_intent->client_secret;
                $user_id = $user_info->id;
                $package_id = $package_info->id;
                $price = $package_info->price;
                $stripe_publishable_key = $payment_settings->stripe_publishable_key;
                // prx($in tent);

                return view('seo.credit-card-upgradation', compact('intent', 'shopier_id', 'user_id', 'package_id', 'price', 'stripe_publishable_key'));
            }
        }
        return redirect()->back();
    }

    public function StripePricingUpgradation(Request $request)
    {
        $user_id = $request->user_id;
        $package_id = $request->package_id;
        $shopier_id = $request->shopier_id;
        $price = $request->price;

        $date = date('Y-m-d H:i:s');

        $user_info = DB::table('users')->where('id', $user_id)->first();
        $remaining_days = 0;
        // if($user_info->payment != 0 && $user_info->payment != null) {
        //     $expire_date = new DateTime($user_info->expire_date);
        //     $now_date = new DateTime(date('Y-m-d H:i:s'));
        //     $interval = $expire_date->diff($now_date);
        //     $remaining_days = $interval->d;
        // }


        $package_info = DB::table('packages')->where('id', $package_id)->first();
        $days = 0;
        if ($package_info->subscription == "monthly") {
            $days = 30;
        } elseif ($package_info->subscription == "yearly") {
            $days = 365;
        }
        $days += $remaining_days;
        $days = $days . " days";

        if ($user_info->expire_date == null) {
            $date = $date;
        } elseif ($user_info->expire_date <= date('Y-m-d H:i:S')) {
            $date = $date;
        } else {
            $date = $user_info->expire_date;
        }

        $expiry_date = date_format(date_add(date_create($date), date_interval_create_from_date_string($days)), 'Y-m-d H:i:s');


        DB::table('users')->where('id', $user_id)->update([
            'payment'   =>  1,
            'payment_date'  =>  date('Y-m-d H:i:s'),
            'package_id'    =>  $package_id,
            'payment_method'   =>  "stripe",
            'expire_date'   =>  $expiry_date
        ]);
        DB::table('shopier')->where('id', $shopier_id)->update([
            'payment'   =>  1,
            'payment_date'      =>  $date
        ]);

        $user_date = DB::table('users')->where('id', $user_id)->first();
        $basic_settings = DB::table('basic_settings')->where('id', 1)->first();
        $data = ['name' => $user_info->name, 'logo' => $basic_settings->site_logo];
        $user['to'] = $user_info->email;
        $user['from'] = $basic_settings->for_emails_email;

        try {
            Mail::send('mail.user_package_upgraded', $data, function ($message) use ($user) {
                $message->from($user['from'], 'Serp Rank');
                $message->sender($user['from'], 'Serp Rank');
                $message->to($user['to']);
                $message->subject('Package Upgraded');
                $message->priority(3);
            });
        } catch (Exception $e) {
        }

        Session::put('user_id', $user_date->id);
        Session::put('user_email', $user_date->email);
        Session::put('user_name', $user_date->Name);
        return redirect('/user/dashboard');
    }

    public function PaypalPricingUpgradation($user_id, $shopier_id)
    {
        $date = date('Y-m-d H:i:s');
        $user_info = DB::table('users')->where('id', $user_id)->first();

        $remaining_days = 0;
        // if($user_info->payment != 0 && $user_info->payment != null) {
        //     $expire_date = new DateTime($user_info->expire_date);
        //     $now_date = new DateTime(date('Y-m-d H:i:s'));
        //     $interval = $expire_date->diff($now_date);
        //     $remaining_days = $interval->d;
        // }

        $shopier_info = DB::table('shopier')->where('id', $shopier_id)->first();

        $package_info = DB::table('packages')->where('id', $shopier_info->package_id)->first();

        if ($package_info->subscription == "monthly") {
            $days = 30;
        } elseif ($package_info->subscription == "yearly") {
            $days = 365;
        }
        $days += $remaining_days;
        $days = $days . " days";

        if ($user_info->expire_date == null) {
            $date = $date;
        } elseif ($user_info->expire_date <= date('Y-m-d H:i:S')) {
            $date = $date;
        } else {
            $date = $user_info->expire_date;
        }

        $expiry_date = date_format(date_add(date_create($date), date_interval_create_from_date_string($days)), 'Y-m-d H:i:s');

        DB::table('users')->where('id', $user_id)->update([
            'payment'   =>  1,
            'payment_method'   =>  "paypal",
            'package_id'    =>  $package_info->id,
            'payment_date'  =>  date('Y-m-d H:i:s'),
            'expire_date'   =>  $expiry_date
        ]);

        DB::table('shopier')->where('id', $shopier_id)->update([
            'payment'   =>  1,
            'payment_date'      =>  $date
        ]);

        $user_date = DB::table('users')->where('id', $user_id)->first();
        $basic_settings = DB::table('basic_settings')->where('id', 1)->first();
        $data = ['name' => $user_info->name, 'logo' => $basic_settings->site_logo];
        $user['to'] = $user_info->email;
        $user['from'] = $basic_settings->for_emails_email;

        try {
            Mail::send('mail.user_package_upgraded', $data, function ($message) use ($user) {
                $message->from($user['from'], 'Serp Rank');
                $message->sender($user['from'], 'Serp Rank');
                $message->to($user['to']);
                $message->subject('Package Upgraded');
                $message->priority(3);
            });
        } catch (Exception $e) {
        }

        $user_date = DB::table('users')->where('id', $user_id)->first();

        Session::put('user_id', $user_date->id);
        Session::put('user_email', $user_date->email);
        Session::put('user_name', $user_date->Name);
        return redirect('/user/dashboard');
    }
}



















class Shopier
{
    private $payment_url = 'https://www.shopier.com/ShowProduct/api_pay4.php';
    private
        $api_key,
        $api_secret,
        $module_version,
        $buyer = [],
        $currency = 'TRY';

    public function __construct($api_key, $api_secret, $module_version = ('1.0.4'))
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->module_version = $module_version;
    }

    public function setBuyer(array $fields = [])
    {
        $this->buyerValidateAndLoad($this->buyerFields(), $fields);
    }

    public function setOrderBilling(array $fields = [])
    {
        $this->buyerValidateAndLoad($this->orderBillingFields(), $fields);
    }

    public function setOrderShipping(array $fields = [])
    {
        $this->buyerValidateAndLoad($this->orderShippingFields(), $fields);
    }

    private function buyerValidateAndLoad($validationFields, $fields)
    {
        $diff = array_diff_key($validationFields, $fields);

        if (count($diff) > 0)
            throw new Exception(implode(',', array_keys($diff)) . ' velden zijn verplicht');

        foreach ($validationFields as $key => $buyerField) {
            $this->buyer[$key] = $fields[$key];
        }
    }

    public function generateFormObject($order_id, $order_total, $callback_url)
    {

        $diff = array_diff_key($this->buyerFields(), $this->buyer);

        if (count($diff) > 0)
            throw new Exception(implode(',', array_keys($diff)) . ' fields are required use "setBuyer()" method ');

        $diff = array_diff_key($this->orderBillingFields(), $this->buyer);

        if (count($diff) > 0)
            throw new Exception(implode(',', array_keys($diff)) . ' fields are required use "setOrderBilling()" method ');

        $diff = array_diff_key($this->orderShippingFields(), $this->buyer);

        if (count($diff) > 0)
            throw new Exception(implode(',', array_keys($diff)) . ' fields are required use "setOrderShipping()" method ');


        $args = array(
            'API_key' => $this->api_key,
            'website_index' => 1,
            'platform_order_id' => $order_id,
            'product_name' => '',
            'product_type' => 0, //1 : downloadable-virtual 0:real object,2:default
            'buyer_name' => $this->buyer['first_name'],
            'buyer_surname' => $this->buyer['last_name'],
            'buyer_email' => $this->buyer['email'],
            'buyer_account_age' => 0,
            'buyer_id_nr' => $this->buyer['id'],
            'buyer_phone' => $this->buyer['phone'],
            'billing_address' => $this->buyer['billing_address'],
            'billing_city' => $this->buyer['billing_city'],
            'billing_country' => $this->buyer['billing_country'],
            'billing_postcode' => $this->buyer['billing_postcode'],
            'shipping_address' => $this->buyer['shipping_address'],
            'shipping_city' => $this->buyer['shipping_city'],
            'shipping_country' => $this->buyer['shipping_country'],
            'shipping_postcode' => $this->buyer['shipping_postcode'],
            'total_order_value' => $order_total,
            'currency' => $this->getCurrency(),
            'platform' => 0,
            'is_in_frame' => 0,
            'current_language' => $this->lang(),
            'modul_version' => $this->module_version,
            'random_nr' => rand(100000, 999999)
        );


        $data = $args["random_nr"] . $args["platform_order_id"] . $args["total_order_value"] . $args["currency"];
        $signature = hash_hmac('sha256', $data, $this->api_secret, true);
        $signature = base64_encode($signature);
        $args['signature'] = $signature;
        $args['callback'] = $callback_url;

        return [
            'elements' => [
                [
                    'tag' => 'form',
                    'attributes' => [
                        'id' => 'shopier_form_special',
                        'method' => 'post',
                        'action' => $this->payment_url
                    ],
                    'children' => array_map(function ($key, $value) {
                        return [
                            'tag' => 'input',
                            'attributes' => [
                                'name' => $key,
                                'value' => $value,
                                'type' => 'hidden',
                            ]
                        ];
                    }, array_keys($args), array_values($args))
                ]
            ]
        ];
    }


    public function generateForm($order_id, $order_total, $callback_url)
    {
        $obj = $this->generateFormObject($order_id, $order_total, $callback_url);

        return $this->recursiveHtmlStringGenerator($obj['elements']);
    }

    public function run($order_id, $order_total, $callback_url)
    {

        $form = $this->generateForm($order_id, $order_total, $callback_url);

        return '<!doctype html>
             <html lang="en">
            <head>
			<meta charset="UTF-8">
			<meta name="viewport"
				content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
			<meta http-equiv="X-UA-Compatible" content="ie=edge">
			<title></title>
			</head>
		' . $form . '
			<body>
				<script type="text/javascript">
							document.getElementById("shopier_form_special").submit();
				</script>
			</body>
			</html>
			';
    }

    // generateFormObject() sınıfının verdiği formattaki arrayden structure çıkartan yapıdırı.
    private function recursiveHtmlStringGenerator(array $elements = [], $string = null)
    {
        foreach ($elements as $element) {
            //$attributes = $element['attributes'] ?? [];


            //attributes = $element['attributes'] ?? [];

            $attributes = isset($element['attributes']) ? $element['attributes'] : [];


            $attributes = array_map(function ($key, $value) {
                return $key . '="' . $value . '"';
            }, array_keys($attributes), array_values($attributes));
            $attribute_string = implode(' ', $attributes);


            //attributes = $element['attributes'] ?? [];

            $html_in = isset($element['source']) ? $element['source'] : null;



            //$html_in = $element['source'] ?? null;




            $string .= "<{$element['tag']} {$attribute_string} > " . $html_in;

            if (isset($element['children']) && is_array($element['children']))
                $string = $this->recursiveHtmlStringGenerator($element['children'], $string);

            $string .= "</{$element['tag']}>";
        }
        return $string;
    }


    //shopierden gelen dataları kontrol eder.
    public function verifyShopierSignature($post_data)
    {

        if (isset($post_data['platform_order_id'])) {
            $order_id = $post_data['platform_order_id'];
            $random_nr = $post_data['random_nr'];
            if ($order_id != '') {
                $signature = base64_decode($_POST["signature"]);
                $expected = hash_hmac('sha256', $random_nr . $order_id, $this->api_secret, true);

                if ($signature == $expected)
                    return true;
            }
        }
        return false;
    }

    private function buyerFields()
    {
        return [
            'id' => true,
            'first_name' => true,
            'last_name' => true,
            'email' => true,
            'phone' => true,
        ];
    }

    private function orderBillingFields()
    {
        return [
            'billing_address' => true,
            'billing_city' => true,
            'billing_country' => true,
            'billing_postcode' => true,
        ];
    }

    private function orderShippingFields()
    {
        return [
            'shipping_address' => true,
            'shipping_city' => true,
            'shipping_country' => true,
            'shipping_postcode' => true,
        ];
    }

    private function getCurrency()
    {
        $currencyList = [
            'TRY' => 0,
            'USD' => 1,
            'EUR' => 2,
        ];
        //return $currencyList[strtoupper($this->currency)] ?? 0;

        return 0;
    }

    private function lang()
    {
        $current_language = "tr-TR";
        $current_lan = 1;
        if ($current_language == "tr-TR") {
            $current_lan = 0;
        }

        return $current_lan;
    }
}
