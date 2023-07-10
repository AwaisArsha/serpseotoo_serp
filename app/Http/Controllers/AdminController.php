<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;


class AdminController extends Controller
{
  public function DeleteMessage($id)
    {
        DB::table('messages')->where('id', $id)->delete();
        return redirect('/admin/messages/read');
    }
  public function TrialPackage($id)
    {
        $package = DB::table('packages')->where('id', $id)->first();
        return view('admin.trial_package', compact('package'));
    }

    public function TrialPackageSave(Request $request)
    {
        // prx($request->post());
        if($request->domain_keyword_limit_checkbox == "on") {
            $request->domain_keyword_limit = "Unlimited";
        }

        if($request->keywords_limit_checkbox == "on") {
            $request->keywords_limit = "Unlimited";
        }

        if($request->domain_competitors_limit_checkbox == "on") {
            $request->domain_competitors_limit = "Unlimited";
        }

        if($request->domain_backlinks_limit_checkbox == "on") {
            $request->domain_backlinks_limit = "Unlimited";
        }
        
        if($request->domain_actual_backlink_limit_checkbox == "on") {
            $request->domain_actual_backlink_limit = "Unlimited";
        }
        
        if($request->domain_backlinks_rows_limit_checkbox == "on") {
            $request->domain_backlinks_rows_limit = "Unlimited";
        }
        
        if($request->search_volume_limit_checkbox == "on") {
            $request->search_volume_limit = "Unlimited";
        }
        
        if($request->serp_limit_checkbox == "on") {
            $request->serp_limit = "Unlimited";
        }

        if($request->backlinks_workload_limit_checkbox == "on") {
            $request->backlinks_workload_limit = "Unlimited";
        }

        // if($request->domain_keyword_refresh_checkbox == "on") {
        //     $request->refresh_limit = "Unlimited";
        // }
        
        if($request->keywords_planner_checkbox == "on") {
            $request->keywords_planner_limit = "Unlimited";
        }

        DB::table('packages')->where('id', 17)->update([
            'title'     =>  $request->title,
            'price'     =>  $request->price,
            'domain_keyword_limit'     =>  $request->domain_keyword_limit,
            'keywords_limit'     =>  $request->keywords_limit,
            // 'refresh_limit'     =>  $request->refresh_limit,
            'keywords_planner_limit'     =>  $request->keywords_planner_limit,
            'domain_competitors_limit'     =>  $request->domain_competitors_limit,
            'domain_backlinks_limit'     =>  $request->domain_backlinks_limit,
            'domain_actual_backlink_limit'     =>  $request->domain_actual_backlink_limit,
            'domain_backlinks_rows_limit'     =>  $request->domain_backlinks_rows_limit,
            'backlinks_workload_limit'     =>  $request->backlinks_workload_limit,
            'search_volume_limit'   =>  $request->search_volume_limit,
            'serp_limit'   =>  $request->serp_limit,
            'competitors'     =>  $request->competitors,
            'keyword_planner'     =>  $request->keyword_planner,
            'backlinks'     =>  $request->backlinks,
            'serp_api'     =>  $request->serp_api,
            'keywords_api'     =>  $request->keywords_api,
            'subscription'     =>  $request->subscription,
            'status'    =>  $request->package_status
        ]);
        return redirect('/admin/packages');
    }
  
  public function Duplicate()
  {
      $locations = DB::table('serp_google_locations')->get();
      foreach($locations as $loc) {
          DB::table('serp_google_locations')->where('id', $loc->id)->update([
              'display_name' => $loc->location_name
          ]);
      }
      $languages = DB::table('serp_google_languages')->get();
      foreach($languages as $lan) {
        DB::table('serp_google_languages')->where('id', $lan->id)->update([
            'display_name' => $lan->language_name
        ]);
    }
  }
  
  public function Languages()
    {
        $languages = DB::table('serp_google_languages')->get();
        return view('admin.languages', compact('languages'));
    }

    public function LanguageActive($id)
    {
        DB::table('serp_google_languages')->where('id', $id)->update([
            'status' => 1
        ]);
        return redirect('/admin/languages');
    }

    public function LanguageInactive($id)
    {
        DB::table('serp_google_languages')->where('id', $id)->update([
            'status' => 0
        ]);
        return redirect('/admin/languages');
    }
    
    public function LanguageEdit($id)
    {
        $language = DB::table('serp_google_languages')->where('id', $id)->first();
        return view('admin.language-edit', compact('language'));
    }

    public function LanguageSave(Request $request)
    {
        DB::table('serp_google_languages')->where('id', $request->id)->update([
            'display_name'  =>  $request->display_name
        ]);
        return redirect('/admin/languages');
    }
    
    public function Locations()
    {
        $locations = DB::table('serp_google_locations')->get();
        return view('admin.locations', compact('locations'));
    }

    public function LocationActive($id)
    {
        DB::table('serp_google_locations')->where('id', $id)->update([
            'status' => 1
        ]);
        return redirect('/admin/locations');
    }

    public function LocationInactive($id)
    {
        DB::table('serp_google_locations')->where('id', $id)->update([
            'status' => 0
        ]);
        return redirect('/admin/locations');
    }
    
    public function LocationEdit($id)
    {
        $location = DB::table('serp_google_locations')->where('id', $id)->first();
        return view('admin.location-edit', compact('location'));
    }

    public function LocationSave(Request $request)
    {
        DB::table('serp_google_locations')->where('id', $request->id)->update([
            'display_name'  =>  $request->display_name
        ]);
        return redirect('/admin/locations');
    }

    public function AdminLogin()
    {
        // prx(Session::get('admin_id'));
        return view('admin.login');
    }

    public function AdminLoginQuery(Request $request)
    {
        $admin = DB::table('admin')->where([
            'id'    =>  1,
            'username'  =>  $request->username,
            'password'  =>  $request->password
        ])->first();
        // prx($admin);
        if ($admin) {
            $request->session()->put('admin_id', $admin->id);
            $request->session()->put('admin_username', $admin->username);

            if($request->rememberme != null) {
                setcookie('admin_email', $request->username, time()+60*60*24*100);
                setcookie('admin_password', $request->password, time()+60*60*24*100);
            } else {
                setcookie('admin_email', $request->username, 100);
                setcookie('admin_password', $request->password, 100);
            }

            return redirect('/admin');
        } else {
            return redirect()->back();
        }
    }

    public function AdminLogout()
    {
        Session::forget('admin_id');
        Session::forget('admin_username');
        return redirect('/admin/login');
    }

    public function SEO()
    {
        $api_url = 'https://api.dataforseo.com/';
        try {
            // Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
            $client = new DFSClient('awesomeusama920@gmail.com', 'ca47aa4b9faa6e4a', null, null, $api_url, null);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }

        $post_array = array();
        $post_array[] = array(
            "language_code" => "en",
            "location_code" => 2840,
            "keyword" => mb_convert_encoding("albert einstein", "UTF-8")
        );

        if (count($post_array) > 0) {
            try {
                // POST /v3/serp/google/organic/task_post
                // in addition to 'google' and 'organic' you can also set other search engine and type parameters
                // the full list of possible parameters is available in documentation
                $result = $client->post('/v3/serp/google/organic/task_post', $post_array);
                print_r($result);
                // do something with post result
            } catch (RestClientException $e) {
                echo "\n";
                print "HTTP code: {$e->getHttpCode()}\n";
                print "Error code: {$e->getCode()}\n";
                print "Message: {$e->getMessage()}\n";
                print  $e->getTraceAsString();
                echo "\n";
            }
        }
        $client = null;
    }
    public function Home()
    {
        $users = DB::table('users')->where('status', 1)->where('payment', '!=' , 0)->get();
        $user_count = count($users);
        $domains = DB::table('domains')->where('status', '!=', 0)->get();
        $domains_count = count($domains);
        $keywords = DB::table('domain_keywords')->get();
        $keywords_count = 0;
        foreach ($keywords as $keyword) {
            if ($keyword->platform == "desktop and mobile") {
                $keywords_count += 2;
            } else {
                $keywords_count++;
            }
        }
        $transactions = DB::table('shopier')->where('payment', 1)->get();
        $total_revenue = 0;
        foreach ($transactions as $transaction) {
            $total_revenue += $transaction->price;
        }
      	$backlink_domains = DB::table('backlinks_domains')->get();
        $backlink_domains_count = count($backlink_domains);
        $backlink_domains_rows = DB::table('backlinks')->get();
        $backlink_domains_rows_count = count($backlink_domains_rows);
        return view('admin.home', compact('user_count', 'domains_count', 'keywords_count', 'total_revenue', 'backlink_domains_count', 'backlink_domains_rows_count'));

    }

    public function CustomerReviews()
    {
        $comments = DB::table('cus_reviews')->orderBy('id', 'DESC')->get();
        return view('admin.customer-reviews', compact('comments'));
    }

    public function CustomerReviewAdd(Request $request)
    {
        DB::table('cus_reviews')->insert([
            'cus_name'  =>  $request->name,
            'service'   =>  $request->service,
            'review'    =>  $request->review,
            'status'    =>  '1'
        ]);
        return redirect('/admin/customer-reviews');
    }

    public function CustomerReviewEdit($id)
    {
        $comment = DB::table('cus_reviews')->where('id', $id)->first();
        return view('admin.customer-review-edit', compact('comment'));
    }

    public function CustomerReviewSave(Request $request)
    {
        DB::table('cus_reviews')->where('id', $request->id)->update([
            'cus_name'  =>  $request->name,
            'service'   =>  $request->service,
            'review'    =>  $request->review
        ]);
        return redirect('/admin/customer-reviews');
    }

    public function CustomerReviewDelete($id)
    {
        DB::table('cus_reviews')->where('id', $id)->delete();
        return redirect('/admin/customer-reviews');
    }

    public function CustomerReviewActive($id)
    {
        DB::table('cus_reviews')->where('id', $id)->update([
            'status' => 1
        ]);
        return redirect('/admin/customer-reviews');
    }

    public function CustomerReviewInactive($id)
    {
        DB::table('cus_reviews')->where('id', $id)->update([
            'status' => 0
        ]);
        return redirect('/admin/customer-reviews');
    }

    public function SatisfactionReasons()
    {
        $reasons = DB::table('satisfaction_reasons')->orderBy('id', 'DESC')->get();
        return view('admin.satisfaction-reasons', compact('reasons'));
    }

    public function SatisfactionReasonAdd(Request $request)
    {
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;

            DB::table('satisfaction_reasons')->insert([
                'banner'  =>  $request->img,
                'first_heading'  =>  $request->first_heading,
                'second_heading'   =>  $request->second_heading,
                'detail'    =>  $request->detail,
                'status'    =>  '1'
            ]);
        }
        return redirect('/admin/satisfaction-reasons');
    }

    public function SatisfactionReasonActive($id)
    {
        DB::table('satisfaction_reasons')->where('id', $id)->update([
            'status' => 1
        ]);
        return redirect('/admin/satisfaction-reasons');
    }

    public function SatisfactionReasonInactive($id)
    {
        DB::table('satisfaction_reasons')->where('id', $id)->update([
            'status' => 0
        ]);
        return redirect('/admin/satisfaction-reasons');
    }

    public function SatisfactionReasonEdit($id)
    {
        $reason = DB::table('satisfaction_reasons')->where('id', $id)->first();
        return view('admin.satisfaction-reason-edit', compact('reason'));
    }

    public function SatisfactionReasonSave(Request $request)
    {
        if ($request->hasFile('img')) {
            $old_reason = DB::table('satisfaction_reasons')->where('Id', $request->id)->first();
            $old_reason_img = public_path() . '/project_images' . $old_reason->banner;
            if (is_file($old_reason_img)) {
                unlink($old_reason_img);
            }
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;
            DB::table('satisfaction_reasons')->where('id', $request->id)->update([
                'banner'        =>  $request->img,
                'first_heading'  =>  $request->first_heading,
                'second_heading'   =>  $request->second_heading,
                'detail'    =>  $request->detail
            ]);
        } else {
            DB::table('satisfaction_reasons')->where('id', $request->id)->update([
                'first_heading'  =>  $request->first_heading,
                'second_heading'   =>  $request->second_heading,
                'detail'    =>  $request->detail
            ]);
        }
        return redirect('/admin/satisfaction-reasons');
    }

    public function SatisfactionReasonDelete($id)
    {
        $old_reason = DB::table('satisfaction_reasons')->where('Id', $id)->first();
        $old_reason_img = public_path() . '/project_images' . $old_reason->banner;
        if (is_file($old_reason_img)) {
            unlink($old_reason_img);
        }
        DB::table('satisfaction_reasons')->where('id', $id)->delete();
        return redirect('/admin/satisfaction-reasons');
    }

    public function OurTeam()
    {
        $teams = DB::table('team')->orderBy('id', 'DESC')->get();
        return view('admin.our-team', compact('teams'));
    }

    public function OurTeamAdd(Request $request)
    {
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;

            DB::table('team')->insert([
                'image'  =>  $request->img,
                'name'  =>  $request->name,
                'job'  =>  $request->job,
                'fb'   =>  $request->fb,
                'tw'    =>  $request->tw,
                'status'    =>  '1'
            ]);
        }
        return redirect('/admin/our-team');
    }

    public function OurTeamActive($id)
    {
        DB::table('team')->where('id', $id)->update([
            'status' => 1
        ]);
        return redirect('/admin/our-team');
    }

    public function OurTeamInactive($id)
    {
        DB::table('team')->where('id', $id)->update([
            'status' => 0
        ]);
        return redirect('/admin/our-team');
    }

    public function OurTeamEdit($id)
    {
        $team = DB::table('team')->where('id', $id)->first();
        return view('admin.our-team-edit', compact('team'));
    }

    public function OurTeamSave(Request $request)
    {
        if ($request->hasFile('img')) {
            $old_team = DB::table('team')->where('Id', $request->id)->first();
            $old_team_img = public_path() . '/project_images' . $old_team->image;
            if (is_file($old_team_img)) {
                unlink($old_team_img);
            }
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;
            DB::table('team')->where('id', $request->id)->update([
                'image'        =>  $request->img,
                'name'  =>  $request->name,
                'job'   =>  $request->job,
                'fb'   =>  $request->fb,
                'tw'   =>  $request->tw
            ]);
        } else {
            DB::table('team')->where('id', $request->id)->update([
                'name'  =>  $request->name,
                'job'   =>  $request->job,
                'fb'   =>  $request->fb,
                'tw'   =>  $request->tw
            ]);
        }
        return redirect('/admin/our-team');
    }

    public function OurTeamDelete($id)
    {
        $old_team = DB::table('team')->where('Id', $id)->first();
        $old_team_img = public_path() . '/project_images' . $old_team->image;
        if (is_file($old_team_img)) {
            unlink($old_team_img);
        }
        DB::table('team')->where('id', $id)->delete();
        return redirect('/admin/our-team');
    }

    public function HomeHighlights()
    {
        $home_highlights = DB::table('home_highlights')->orderBy('id', 'DESC')->get();
        return view('admin.home-highlights', compact('home_highlights'));
    }

    public function HomeHighlightsAdd(Request $request)
    {
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;

            DB::table('home_highlights')->insert([
                'image'  =>  $request->img,
                'title'  =>  $request->title,
                'description'  =>  $request->description,
                'status'    =>  '1'
            ]);
        }
        return redirect('/admin/home-highlights');
    }

    public function HomeHighlightsActive($id)
    {
        DB::table('home_highlights')->where('id', $id)->update([
            'status' => 1
        ]);
        return redirect('/admin/home-highlights');
    }

    public function HomeHighlightsInactive($id)
    {
        DB::table('home_highlights')->where('id', $id)->update([
            'status' => 0
        ]);
        return redirect('/admin/home-highlights');
    }

    public function HomeHighlightsEdit($id)
    {
        $home_highlights = DB::table('home_highlights')->where('id', $id)->first();
        return view('admin.home-highlights-edit', compact('home_highlights'));
    }

    public function HomeHighlightsSave(Request $request)
    {
        if ($request->hasFile('img')) {
            $old_team = DB::table('home_highlights')->where('Id', $request->id)->first();
            $old_team_img = public_path() . '/project_images' . $old_team->image;
            if (is_file($old_team_img)) {
                unlink($old_team_img);
            }
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;
            DB::table('home_highlights')->where('id', $request->id)->update([
                'image'        =>  $request->img,
                'title'  =>  $request->title,
                'description'   =>  $request->description,
            ]);
        } else {
            DB::table('home_highlights')->where('id', $request->id)->update([
                'title'  =>  $request->title,
                'description'   =>  $request->description
            ]);
        }
        return redirect('/admin/home-highlights');
    }

    public function HomeHighlightsDelete($id)
    {
        $old_team = DB::table('home_highlights')->where('Id', $id)->first();
        $old_team_img = public_path() . '/project_images' . $old_team->image;
        if (is_file($old_team_img)) {
            unlink($old_team_img);
        }
        DB::table('home_highlights')->where('id', $id)->delete();
        return redirect('/admin/home-highlights');
    }

    public function ServicesPageHighlights()
    {
        $services_page_highlights = DB::table('services_page_highlights')->orderBy('id', 'DESC')->get();
        return view('admin.services-page-highlights', compact('services_page_highlights'));
    }

    public function ServicesPageHighlightsAdd(Request $request)
    {
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;

            DB::table('services_page_highlights')->insert([
                'image'  =>  $request->img,
                'title'  =>  $request->title,
                'description'  =>  $request->description,
                'status'    =>  '1'
            ]);
        }
        return redirect('/admin/services-page-highlights');
    }

    public function ServicesPageHighlightsActive($id)
    {
        DB::table('services_page_highlights')->where('id', $id)->update([
            'status' => 1
        ]);
        return redirect('/admin/services-page-highlights');
    }

    public function ServicesPageHighlightsInactive($id)
    {
        DB::table('services_page_highlights')->where('id', $id)->update([
            'status' => 0
        ]);
        return redirect('/admin/services-page-highlights');
    }

    public function ServicesPageHighlightsEdit($id)
    {
        $services_page_highlights = DB::table('services_page_highlights')->where('id', $id)->first();
        return view('admin.services-page-highlights-edit', compact('services_page_highlights'));
    }

    public function ServicesPageHighlightsSave(Request $request)
    {
        if ($request->hasFile('img')) {
            $old_team = DB::table('services_page_highlights')->where('Id', $request->id)->first();
            $old_team_img = public_path() . '/project_images' . $old_team->image;
            if (is_file($old_team_img)) {
                unlink($old_team_img);
            }
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;
            DB::table('services_page_highlights')->where('id', $request->id)->update([
                'image'        =>  $request->img,
                'title'  =>  $request->title,
                'description'   =>  $request->description,
            ]);
        } else {
            DB::table('services_page_highlights')->where('id', $request->id)->update([
                'title'  =>  $request->title,
                'description'   =>  $request->description
            ]);
        }
        return redirect('/admin/services-page-highlights');
    }

    public function ServicesPageHighlightsDelete($id)
    {
        $old_team = DB::table('services_page_highlights')->where('Id', $id)->first();
        $old_team_img = public_path() . '/project_images' . $old_team->image;
        if (is_file($old_team_img)) {
            unlink($old_team_img);
        }
        DB::table('services_page_highlights')->where('id', $id)->delete();
        return redirect('/admin/services-page-highlights');
    }

    public function HomeCaseStudies()
    {
        $home_case_studies = DB::table('home_case_studies')->orderBy('id', 'ASC')->get();
        return view('admin.home-case-studies', compact('home_case_studies'));
    }

    public function HomeCaseStudiesAdd(Request $request)
    {
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;

            DB::table('home_case_studies')->insert([
                'image'  =>  $request->img,
                'status'    =>  1
            ]);
        }
        return redirect('/admin/home-case-studies');
    }

    public function HomeCaseStudiesActive($id)
    {
        DB::table('home_case_studies')->where('id', $id)->update([
            'status' => 1
        ]);
        return redirect('/admin/home-case-studies');
    }

    public function HomeCaseStudiesInactive($id)
    {
        DB::table('home_case_studies')->where('id', $id)->update([
            'status' => 0
        ]);
        return redirect('/admin/home-case-studies');
    }

    public function HomeCaseStudiesDelete($id)
    {
        $old_team = DB::table('home_case_studies')->where('Id', $id)->first();
        $old_team_img = public_path() . '/project_images' . $old_team->image;
        if (is_file($old_team_img)) {
            unlink($old_team_img);
        }
        DB::table('home_case_studies')->where('id', $id)->delete();
        return redirect('/admin/home-case-studies');
    }

    public function AboutUs()
    {
        $about = DB::table('about_us')->first();
        return view('admin.about-us', compact('about'));
    }

    public function AboutUsUpdate(Request $request)
    {
        if ($request->hasFile('img')) {
            $old_about_us = DB::table('about_us')->where('Id', $request->id)->first();
            $old_about_us_img = public_path() . '/project_images' . $old_about_us->image;
            if (is_file($old_about_us_img)) {
                unlink($old_about_us_img);
            }
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;
            DB::table('about_us')->where('id', $request->id)->update([
                'short_detail'  =>  $request->short_detail,
                'mission'   =>  $request->mission,
                'vision'   =>  $request->vision,
                'why_us'   =>  $request->why_us,
                'happy_clients'   =>  $request->happy_clients,
                'years_in_business'   =>  $request->years_in_business,
                'high_score'   =>  $request->high_score,
                'cups_of_coffee'   =>  $request->cups_of_coffee,
                'detail'   =>  $request->detail,
                'image'     =>  $request->img
            ]);
        } else {
            DB::table('about_us')->where('id', $request->id)->update([
                'short_detail'  =>  $request->short_detail,
                'mission'   =>  $request->mission,
                'vision'   =>  $request->vision,
                'why_us'   =>  $request->why_us,
                'happy_clients'   =>  $request->happy_clients,
                'years_in_business'   =>  $request->years_in_business,
                'high_score'   =>  $request->high_score,
                'cups_of_coffee'   =>  $request->cups_of_coffee,
                'detail'   =>  $request->detail
            ]);
        }
        return redirect('/admin/about-us');
    }

    public function HomeBanner()
    {
        $home_banner = DB::table('home_banner')->first();
        return view('admin.home-banner', compact('home_banner'));
    }

    public function HomeBannerUpdate(Request $request)
    {
        if ($request->hasFile('img')) {
            $old_home_banner = DB::table('home_banner')->where('Id', $request->id)->first();
            $old_home_banner_img = public_path() . '/project_images' . $old_home_banner->image;
            if (is_file($old_home_banner_img)) {
                unlink($old_home_banner_img);
            }
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;
            DB::table('home_banner')->where('id', $request->id)->update([
                'first_heading'  =>  $request->first_heading,
                'second_heading'   =>  $request->second_heading,
                'third_heading'   =>  $request->third_heading,
                'image'     =>  $request->img
            ]);
        } else {
            DB::table('home_banner')->where('id', $request->id)->update([
                'first_heading'  =>  $request->first_heading,
                'second_heading'   =>  $request->second_heading,
                'third_heading'   =>  $request->third_heading
            ]);
        }
        return redirect('/admin/home-banner');
    }

    public function ThirdSection()
    {
        $home_third_section = DB::table('home_third_section')->first();
        return view('admin.home-third-section', compact('home_third_section'));
    }

    public function ThirdSectionUpdate(Request $request)
    {
        if ($request->hasFile('img')) {
            $old_home_banner = DB::table('home_third_section')->where('Id', $request->id)->first();
            $old_home_banner_img = public_path() . '/project_images' . $old_home_banner->image;
            if (is_file($old_home_banner_img)) {
                unlink($old_home_banner_img);
            }
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;
            DB::table('home_third_section')->where('id', $request->id)->update([
                'title'  =>  $request->title,
                'sub_title'   =>  $request->sub_title,
                'description'   =>  $request->description,
                'image'     =>  $request->img
            ]);
        } else {
            DB::table('home_third_section')->where('id', $request->id)->update([
                'title'  =>  $request->title,
                'sub_title'   =>  $request->sub_title,
                'description'   =>  $request->description
            ]);
        }
        return redirect('/admin/home-third-section');
    }

    public function FourthSection()
    {
        $home_fourth_section = DB::table('home_fourth_section')->first();
        return view('admin.home-fourth-section', compact('home_fourth_section'));
    }

    public function FourthSectionUpdate(Request $request)
    {
        if ($request->hasFile('first_img')) {
            $old_home_banner = DB::table('home_fourth_section')->where('Id', $request->id)->first();
            $old_home_banner_img = public_path() . '/project_images' . $old_home_banner->first_image;
            if (is_file($old_home_banner_img)) {
                unlink($old_home_banner_img);
            }
            $image = $request->file('first_img');
            $ext = $image->extension();
            $image_name = time() .rand(111111, 999999). '.' . $ext;
            request()->first_img->move(public_path('project_images/img'), $image_name);
            $request->first_img = '/img/' . $image_name;
            DB::table('home_fourth_section')->where('id', $request->id)->update([
                'first_image'     =>  $request->first_img
            ]);
        }
        if ($request->hasFile('second_img')) {
            $old_home_banner = DB::table('home_fourth_section')->where('Id', $request->id)->first();
            $old_home_banner_img = public_path() . '/project_images' . $old_home_banner->second_image;
            if (is_file($old_home_banner_img)) {
                unlink($old_home_banner_img);
            }
            $image = $request->file('second_img');
            $ext = $image->extension();
            $image_name = time() .rand(111111, 999999). '.' . $ext;
            request()->second_img->move(public_path('project_images/img'), $image_name);
            $request->second_img = '/img/' . $image_name;
            DB::table('home_fourth_section')->where('id', $request->id)->update([
                'second_image'     =>  $request->second_img
            ]);
        }
        if ($request->hasFile('third_img')) {
            $old_home_banner = DB::table('home_fourth_section')->where('Id', $request->id)->first();
            $old_home_banner_img = public_path() . '/project_images' . $old_home_banner->third_image;
            if (is_file($old_home_banner_img)) {
                unlink($old_home_banner_img);
            }
            $image = $request->file('third_img');
            $ext = $image->extension();
            $image_name = time() .rand(111111, 999999). '.' . $ext;
            request()->third_img->move(public_path('project_images/img'), $image_name);
            $request->third_img = '/img/' . $image_name;
            DB::table('home_fourth_section')->where('id', $request->id)->update([
                'third_image'     =>  $request->third_img
            ]);
        }
        DB::table('home_fourth_section')->where('id', $request->id)->update([
            'title'  =>  $request->title,
            'first_title'   =>  $request->first_title,
            'second_title'   =>  $request->second_title,
            'third_title'   =>  $request->third_title
        ]);
        return redirect('/admin/home-fourth-section');
    }

    public function ContactPage()
    {
        $contact_page = DB::table('contact_page')->first();
        return view('admin.contact-page', compact('contact_page'));
    }

    public function ContactPageUpdate(Request $request)
    {
        DB::table('contact_page')->where('id', $request->id)->update([
            'heading'  =>  $request->heading,
            'text'   =>  $request->text
        ]);
        return redirect('/admin/contact-page');
    }

    public function Package()
    {
        $packages = DB::table('packages')->where('id', '!=', 17)->get();
        return view('admin.packages', compact('packages'));
    }

    public function PackageAdd(Request $request)
    {
        // prx($request->post());
        if($request->domain_keyword_limit_checkbox == "on") {
            $request->domain_keyword_limit = "Unlimited";
        }

        if($request->keywords_limit_checkbox == "on") {
            $request->keywords_limit = "Unlimited";
        }

        if($request->domain_competitors_limit_checkbox == "on") {
            $request->domain_competitors_limit = "Unlimited";
        }

        if($request->domain_backlinks_limit_checkbox == "on") {
            $request->domain_backlinks_limit = "Unlimited";
        }
        
        if($request->domain_actual_backlink_limit_checkbox == "on") {
            $request->domain_actual_backlink_limit = "Unlimited";
        }
        
        if($request->domain_backlinks_rows_limit_checkbox == "on") {
            $request->domain_backlinks_rows_limit = "Unlimited";
        }
        
        if($request->search_volume_limit_checkbox == "on") {
            $request->search_volume_limit = "Unlimited";
        }
        
        if($request->serp_limit_checkbox == "on") {
            $request->serp_limit = "Unlimited";
        }

        if($request->backlinks_workload_limit_checkbox == "on") {
            $request->backlinks_workload_limit = "Unlimited";
        }

        // if($request->domain_keyword_refresh_checkbox == "on") {
        //     $request->refresh_limit = "Unlimited";
        // }
        
        if($request->keywords_planner_checkbox == "on") {
            $request->keywords_planner_limit = "Unlimited";
        }

        DB::table('packages')->insert([
            'title'     =>  $request->title,
            'price'     =>  $request->price,
            'domain_keyword_limit'     =>  $request->domain_keyword_limit,
            'keywords_limit'     =>  $request->keywords_limit,
            // 'refresh_limit'     =>  $request->refresh_limit,
            'keywords_planner_limit'     =>  $request->keywords_planner_limit,
            'domain_competitors_limit'     =>  $request->domain_competitors_limit,
            'domain_backlinks_limit'     =>  $request->domain_backlinks_limit,
            'domain_actual_backlink_limit'     =>  $request->domain_actual_backlink_limit,
            'domain_backlinks_rows_limit'     =>  $request->domain_backlinks_rows_limit,
            'backlinks_workload_limit'     =>  $request->backlinks_workload_limit,
            'search_volume_limit'   =>  $request->search_volume_limit,
            'serp_limit'   =>  $request->serp_limit,
            'competitors'     =>  $request->competitors,
            'keyword_planner'     =>  $request->keyword_planner,
            'backlinks'     =>  $request->backlinks,
            'serp_api'     =>  $request->serp_api,
            'keywords_api'     =>  $request->keywords_api,
            'subscription'     =>  $request->subscription,
            'status'    =>  1
        ]);
        return redirect('/admin/packages');
    }

    public function PackageEdit($id)
    {
        $package = DB::table('packages')->where('id', $id)->first();
        $package_features = DB::table('package_features')->where('package_id', $id)->get();
        return view('admin.package-edit', compact('package', 'package_features'));
    }

    public function PackageSave(Request $request)
    {
        // prx($request->post());
        // $features_string = $request->features;
        // $features = explode(",", $features_string);

        if($request->domain_keyword_limit_checkbox == "on") {
            $request->domain_keyword_limit = "Unlimited";
        }

        if($request->keywords_limit_checkbox == "on") {
            $request->keywords_limit = "Unlimited";
        }

        if($request->domain_competitors_limit_checkbox == "on") {
            $request->domain_competitors_limit = "Unlimited";
        }

        if($request->domain_backlinks_limit_checkbox == "on") {
            $request->domain_backlinks_limit = "Unlimited";
        }
        
        if($request->domain_actual_backlink_limit_checkbox == "on") {
            $request->domain_actual_backlink_limit = "Unlimited";
        }
        
        if($request->domain_backlinks_rows_limit_checkbox == "on") {
            $request->domain_backlinks_rows_limit = "Unlimited";
        }
        
        if($request->backlinks_workload_limit_checkbox == "on") {
            $request->backlinks_workload_limit = "Unlimited";
        }
        
        if($request->search_volume_limit_checkbox == "on") {
            $request->search_volume_limit = "Unlimited";
        }

        if($request->serp_limit_checkbox == "on") {
            $request->serp_limit = "Unlimited";
        }

        // if($request->domain_keyword_refresh_checkbox == "on") {
        //     $request->refresh_limit = "Unlimited";
        // }
        
        if($request->keywords_planner_checkbox == "on") {
            $request->keywords_planner_limit = "Unlimited";
        }

        DB::table('packages')->where('id', $request->id)->update([
            'title'     =>  $request->title,
            'price'     =>  $request->price,
            'domain_keyword_limit'     =>  $request->domain_keyword_limit,
            'keywords_limit'     =>  $request->keywords_limit,
            // 'refresh_limit'     =>  $request->refresh_limit,
            'keywords_planner_limit'     =>  $request->keywords_planner_limit,
            'domain_competitors_limit'     =>  $request->domain_competitors_limit,
            'domain_backlinks_limit'     =>  $request->domain_backlinks_limit,
            'domain_actual_backlink_limit'     =>  $request->domain_actual_backlink_limit,
            'domain_backlinks_rows_limit'     =>  $request->domain_backlinks_rows_limit,
            'backlinks_workload_limit'     =>  $request->backlinks_workload_limit,
            'search_volume_limit'   =>  $request->search_volume_limit,
            'serp_limit'   =>  $request->serp_limit,
            'competitors'     =>  $request->competitors,
            'keyword_planner'     =>  $request->keyword_planner,
            'backlinks'     =>  $request->backlinks,
            'serp_api'     =>  $request->serp_api,
            'subscription'     =>  $request->subscription,
            'keywords_api'     =>  $request->keywords_api,
        ]);
        return redirect('/admin/packages');
    }

    public function PackageDelete($id)
    {
        DB::table('packages')->where('id', $id)->delete();
        DB::table('package_features')->where('package_id', $id)->delete();
        return redirect('/admin/packages');
    }

    public function PackageActive($id)
    {
        DB::table('packages')->where('id', $id)->update([
            'status' => 1
        ]);
        return redirect('/admin/packages');
    }

    public function PackageInactive($id)
    {
        DB::table('packages')->where('id', $id)->update([
            'status' => 0
        ]);
        return redirect('/admin/packages');
    }

    public function UnreadMessages()
    {
        $messages = DB::table('messages')->where('read_status', 0)->orderBy('id', 'DESC')->get();
        $status = "unread";
        return view('admin.messages', compact('messages', 'status'));
    }

    public function ReadMessages()
    {
        $messages = DB::table('messages')->where('read_status', 1)->orderBy('id', 'DESC')->get();
        $status = "read";
        return view('admin.messages', compact('messages', 'status'));
    }

    public function ViewMessage($id)
    {
        $message = DB::table('messages')->where('id', $id)->update([
            'read_status'   =>  1
        ]);
        $message = DB::table('messages')->where('id', $id)->first();
        return view('admin.view-message', compact('message'));
    }
  
  public function ViewMessageReply(Request $request)
    {
        $message = DB::table('messages')->where('id', $request->id)->first();
            
        $basic_settings = DB::table('basic_settings')->where('id',1)->first();
        $data = ['name' => $message->name, 'response' => $request->response, 'logo' => $basic_settings->site_logo];
        $user['to'] = $message->email;
        $user['from'] = $basic_settings->for_emails_email;
    
        try {
            Mail::send('mail.user_message_response', $data, function ($message) use ($user) {
                $message->from($user['from'], 'Serp Rank');
                $message->sender($user['from'], 'Serp Rank');
                $message->to($user['to']);
                $message->subject('Message Response');
                $message->priority(3);
            });
        } catch(Exception $e) {

        }
        return redirect('admin/messages/read');
    }

    public function MarkAsRead($id)
    {
        DB::table('messages')->where('id', $id)->update([
            'read_status'   =>  1
        ]);
        return redirect()->back();
    }

    public function MarkAsUnread($id)
    {
        DB::table('messages')->where('id', $id)->update([
            'read_status'   =>  0
        ]);
        return redirect()->back();
    }

    public function BasicSettings()
    {
        $settings = DB::table('basic_settings')->first();
        $currencies = DB::table('currency')->get();
        return view('admin.basic-settings', compact('settings', 'currencies'));
    }

    public function BasicSettingsSave(Request $request)
    {
        if ($request->hasFile('site_logo')) {
            $old_settings = DB::table('basic_settings')->where('id', $request->id)->first();
            $old_settings_img = public_path() . '/project_images' . $old_settings->site_logo;
            if (is_file($old_settings_img)) {
                unlink($old_settings_img);
            }
            $image = $request->file('site_logo');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->site_logo->move(public_path('project_images/img'), $image_name);
            $request->site_logo = '/img/' . $image_name;
            DB::table('basic_settings')->where('id', $request->id)->update([
                'site_logo'   =>  $request->site_logo
            ]);
        }
        if ($request->hasFile('admin_logo')) {
            $old_settings = DB::table('basic_settings')->where('id', $request->id)->first();
            $old_settings_img = public_path() . '/project_images' . $old_settings->admin_logo;
            if (is_file($old_settings_img)) {
                unlink($old_settings_img);
            }
            $image = $request->file('admin_logo');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->admin_logo->move(public_path('project_images/img'), $image_name);
            $request->admin_logo = '/img/' . $image_name;
            DB::table('basic_settings')->where('id', $request->id)->update([
                'admin_logo'   =>  $request->admin_logo
            ]);
        }
        DB::table('basic_settings')->where('id', $request->id)->update([
            'address'  =>  $request->address,
            'phone'   =>  $request->phone,
            'email'   =>  $request->email,
            'twitter'   =>  $request->twitter,
            'instagram'   =>  $request->instagram,
            'facebook'   =>  $request->facebook,
            'currency'   =>  $request->currency,
            'payment_method'   =>  $request->payment_method,
            'for_emails_host' =>  $request->for_emails_host,
            'for_emails_email' =>  $request->for_emails_email,
            'for_emails_password' =>  $request->for_emails_password
        ]);
        $this->changeEnvironmentVariable('MAIL_HOST', $request->for_emails_host);
        $this->changeEnvironmentVariable('MAIL_USERNAME', $request->for_emails_email);
        $this->changeEnvironmentVariable('MAIL_FROM_ADDRESS', $request->for_emails_email);
        $this->changeEnvironmentVariable('MAIL_PASSWORD', $request->for_emails_password);
        return redirect()->back();
    }

    public static function changeEnvironmentVariable($key,$value)
    {
        $path = base_path('.env');
        $old = env($key);
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                "$key=".$old, 
                "$key=".$value, file_get_contents($path)
            ));
        }
    }

    public function UsefullLinks()
    {
        $links = DB::table('usefull_links')->orderBy('id', 'DESC')->get();
        return view('admin.usefull-links', compact('links'));
    }

    public function UsefullLinkAdd(Request $request)
    {
        $links = DB::table('usefull_links')->insert([
            'title'     =>  $request->title,
            'link'     =>  $request->link,
            'status'    =>  1
        ]);
        return redirect()->back();
    }

    public function UsefullLinkActive($id)
    {
        DB::table('usefull_links')->where('id', $id)->update([
            'status'    =>  1
        ]);
        return redirect()->back();
    }

    public function UsefullLinkInactive($id)
    {
        DB::table('usefull_links')->where('id', $id)->update([
            'status'    =>  0
        ]);
        return redirect()->back();
    }

    public function UsefullLinkDelete($id)
    {
        DB::table('usefull_links')->where('id', $id)->delete();
        return redirect()->back();
    }

    public function UsefullLinkEdit($id)
    {
        $link = DB::table('usefull_links')->where('id', $id)->first();
        return view('admin.usefull-links-edit', compact('link'));
    }

    public function UsefullLinkSave(Request $request)
    {
        DB::table('usefull_links')->where('id', $request->id)->update([
            'title'     =>  $request->title,
            'link'     =>  $request->link,
        ]);
        return redirect('/admin/usefull-links');
    }

    public function Blogs()
    {
        $blogs = DB::table('blogs')->where('purpose', 'blog')->orderBy('id', 'DESC')->get();
        return view('admin.blogs', compact('blogs'));
    }

    public function BlogNew()
    {
        return view('admin.blog-new');
    }

    public function BlogEdit($id)
    {
        $blog = DB::table('blogs')->where('id', $id)->first();
        return view('admin.blog-edit', compact('blog'));
    }

    public function BlogAdd(Request $request)
    {
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;
        }
        DB::table('blogs')->insert([
            'title'  =>  $request->title,
            'detail'   =>  $request->detail,
            'date'   =>  $request->date,
            'image' =>  $request->img,
            'purpose'   =>  'blog',
            'status'    =>  1
        ]);
        return redirect('/admin/blogs');
    }

    public function BlogActive($id)
    {
        DB::table('blogs')->where('id', $id)->update([
            'status'    =>  1
        ]);
        return redirect()->back();
    }

    public function BlogInactive($id)
    {
        DB::table('blogs')->where('id', $id)->update([
            'status'    =>  0
        ]);
        return redirect()->back();
    }

    public function BlogDelete($id)
    {
        $old_blog = DB::table('blogs')->where('id', $id)->first();
        $old_blog_img = public_path() . '/project_images' . $old_blog->image;
        if (is_file($old_blog_img)) {
            unlink($old_blog_img);
        }
        DB::table('blogs')->where('id', $id)->delete();
        return redirect()->back();
    }

    public function BlogSave(Request $request)
    {
        if ($request->hasFile('img')) {
            $old_blog = DB::table('blogs')->where('id', $request->id)->first();
            $old_blog_img = public_path() . '/project_images' . $old_blog->image;
            if (is_file($old_blog_img)) {
                unlink($old_blog_img);
            }
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;

            DB::table('blogs')->where('id', $request->id)->update([
                'title'  =>  $request->title,
                'detail'   =>  $request->detail,
                'date'   =>  $request->date,
                'image' =>  $request->img,
            ]);
        } else {
            DB::table('blogs')->where('id', $request->id)->update([
                'title'  =>  $request->title,
                'detail'   =>  $request->detail,
                'date'   =>  $request->date,
            ]);
        }
        return redirect('/admin/blogs');
    }

    public function Learn()
    {
        $blogs = DB::table('blogs')->where('purpose', 'learn')->orderBy('id', 'DESC')->get();
        return view('admin.learn', compact('blogs'));
    }

    public function LearnNew()
    {
        return view('admin.learn-new');
    }

    public function LearnEdit($id)
    {
        $blog = DB::table('blogs')->where('id', $id)->first();
        return view('admin.learn-edit', compact('blog'));
    }

    public function LearnAdd(Request $request)
    {
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;
        }
        DB::table('blogs')->insert([
            'title'  =>  $request->title,
            'detail'   =>  $request->detail,
            'date'   =>  $request->date,
            'image' =>  $request->img,
            'purpose'   =>  'learn',
            'status'    =>  1
        ]);
        return redirect('/admin/learn');
    }

    public function LearnActive($id)
    {
        DB::table('blogs')->where('id', $id)->update([
            'status'    =>  1
        ]);
        return redirect()->back();
    }

    public function LearnInactive($id)
    {
        DB::table('blogs')->where('id', $id)->update([
            'status'    =>  0
        ]);
        return redirect()->back();
    }

    public function LearnDelete($id)
    {
        $old_blog = DB::table('blogs')->where('id', $id)->first();
        $old_blog_img = public_path() . '/project_images' . $old_blog->image;
        if (is_file($old_blog_img)) {
            unlink($old_blog_img);
        }
        DB::table('blogs')->where('id', $id)->delete();
        return redirect()->back();
    }

    public function LearnSave(Request $request)
    {
        if ($request->hasFile('img')) {
            $old_blog = DB::table('blogs')->where('id', $request->id)->first();
            $old_blog_img = public_path() . '/project_images' . $old_blog->image;
            if (is_file($old_blog_img)) {
                unlink($old_blog_img);
            }
            $image = $request->file('img');
            $ext = $image->extension();
            $image_name = time() . '.' . $ext;
            request()->img->move(public_path('project_images/img'), $image_name);
            $request->img = '/img/' . $image_name;

            DB::table('blogs')->where('id', $request->id)->update([
                'title'  =>  $request->title,
                'detail'   =>  $request->detail,
                'date'   =>  $request->date,
                'image' =>  $request->img,
            ]);
        } else {
            DB::table('blogs')->where('id', $request->id)->update([
                'title'  =>  $request->title,
                'detail'   =>  $request->detail,
                'date'   =>  $request->date,
            ]);
        }
        return redirect('/admin/learn');
    }

    public function Subscriber()
    {
        $subscribers = DB::table('subscribers')->get();
        return view('admin.subscribers', compact('subscribers'));
    }

    public function SubscriberActive($id)
    {
        DB::table('subscribers')->where('id', $id)->update([
            'status'    =>  1
        ]);
        return redirect()->back();
    }

    public function SubscriberInactive($id)
    {
        DB::table('subscribers')->where('id', $id)->update([
            'status'    =>  0
        ]);
        return redirect()->back();
    }

    public function SubscriberDelete($id)
    {
        DB::table('subscribers')->where('id', $id)->delete();
        return redirect()->back();
    }

    public function Paypal()
    {
        $paypal = DB::table('paypal')->first();
        // prx($paypal);
        return view('admin.paypal', compact('paypal'));
    }
    
    public function PaypalUpdate(Request $request)
    {
        DB::table('paypal')->where('id', 1)->update([
            'paypal_mail'    =>  $request->paypalmail,
            'stripe_secret_key'    =>  $request->stripe_secret_key,
            'stripe_publishable_key'    =>  $request->stripe_publishable_key,
            'shipy_apikey'    =>  $request->shipy_apikey,
            'shopiersecret'    =>  $request->shopiersecret,
        ]);
        return redirect()->back();
    }

    public function Api()
    {
        $api = DB::table('api')->first();
        // prx($api);
        return view('admin.api', compact('api'));
    }
    
    public function ApiUpdate(Request $request)
    {
        DB::table('api')->where('id', 1)->update([
            'api_email'    =>  $request->api_email,
            'api_key'    =>  $request->api_key,
            'recaptcha_site_key'    =>  $request->recaptcha_site_key,
            'recaptcha_secret_key'    =>  $request->recaptcha_secret_key,
            'keywords_everywhere_api'   =>  $request->keywords_everywhere_api
        ]);
        return redirect()->back();
    }

    public function AdminSettings()
    {
        $admin = DB::table('admin')->first();
        return view('admin.admin_settings', compact('admin'));
    }
    
    public function AdminSettingsUpdate(Request $request)
    {
        if($request->password != null) {
            DB::table('admin')->where('id', 1)->update([
                'username'    =>  $request->username,
                'password'    =>  $request->password
            ]);
        } else {
            DB::table('admin')->where('id', 1)->update([
                'username'    =>  $request->username
            ]);
        }
        return redirect()->back();
    }

    public function Users()
    {
        $users = DB::table('users')->where('payment', '!=' , 0)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.users', compact('users'));
    }
    
    public function UsersSearch(Request $request)
    {
        $users = DB::table('users')->where('payment', '!=' , 0)->where('email','like','%'.$request->s.'%')->orderBy('id', 'DESC')->paginate(10);
        return view('admin.users', compact('users'));
    }
    
    public function UserEdit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        return view('admin.user-edit', compact('user'));
    }
    
    public function UserUpdate(Request $request)
    {
        if($request->password != null) {
           $password = Hash::make($request->password);
           DB::table('users')->where('id', $request->id)->update([
            'Name'  =>  $request->name,
            'email'  =>  $request->email,
            'password'  =>  $password,
            'country'  =>  $request->country,
            'payment_method'  =>  $request->payment_mode
            ]);
        } else {
            DB::table('users')->where('id', $request->id)->update([
                'Name'  =>  $request->name,
                'email'  =>  $request->email,
                'country'  =>  $request->country,
                'payment_method'  =>  $request->payment_mode
            ]);
        }
        return redirect('/admin/users');
    }

    public function UserActive($id)
    {
        DB::table('users')->where('id', $id)->update([
            'status'    =>  1
        ]);
        return redirect()->back();
    }

    public function UserInactive($id)
    {
        DB::table('users')->where('id', $id)->update([
            'status'    =>  0
        ]);
        return redirect()->back();
    }

    public function UserDelete($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect()->back();
    }
}
