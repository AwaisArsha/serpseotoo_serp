<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BacklinkController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\KeywordResearchController;
use App\Http\Controllers\SerpController;
use App\Http\Controllers\KeywordsController;
use App\Http\Controllers\TrafficAnalyticsController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'App\Http\Middleware\Language'], function () {

Route::get('/change-language/{lang}', [FrontController::class, 'changeLang']);

Route::get('/install-app', [FrontController::class, 'InstallApp']);
Route::get('/database-setting', [FrontController::class, 'DatabaseSetting']);
Route::post('/database-setting-configure', [FrontController::class, 'DatabaseSettingConfigure']);
Route::get('/database-setting-failed', [FrontController::class, 'DatabaseSettingFailed']);


Route::group(['middleware' => 'install_auth'], function () {
  Route::get('/admin-configuration', [FrontController::class, 'AdminConfiguration']);
  Route::post('/admin-configuration-done', [FrontController::class, 'AdminConfigurationDone']);
  Route::get('/installation-complete', [FrontController::class, 'InstallationComplete']);
  Route::group(['middleware' => 'install_admin'], function () {
    Route::get('trial/activate/{id}', [FrontController::class, 'ActivateTrialPackage']);
    Route::get('/testing', [UserDashboardController::class, 'testing']);

    Route::group(['middleware' => 'user_auth'], function () {
        Route::get('/user/dashboard', [UserDashboardController::class, 'UserDashboard']);
        Route::post('/user/add-domain', [UserDashboardController::class, 'AddDomain']);
        Route::get('/user/domain/detail/{id}', [UserDashboardController::class, 'DomainDetail']);
        Route::get('/user/domain/delete/{id}', [UserDashboardController::class, 'DeleteDomain']);
        Route::get('/user/serp-competitors', [UserDashboardController::class, 'SerpCompetitors']);
        Route::post('/user/domain/add_keyword', [UserDashboardController::class, 'AddKeyword']);
        Route::get('/user/keyword/delete/{keyword_id}', [UserDashboardController::class, 'DeleteKeyword']);
        //Route::get('/user/keyword/refresh/{domain_id}/{keyword_id}', [UserDashboardController::class, 'RefreshKeyword']);
        Route::get('/user/keyword/refresh/{keyword_id}', [UserDashboardController::class, 'set_api_manual_refresh']);
        //Route::get('/user/keyword/refresh_all/{domain_id}', [UserDashboardController::class, 'RefreshAllKeywords']);
        Route::get('/user/keyword/refresh_all/{domain_id}', [UserDashboardController::class, 'set_api_manual_all_refresh']);

        Route::post('/user/add-backlink', [BacklinkController::class, 'AddBacklink']);
        Route::get('/user/backlinks/delete/{domain_id}', [BacklinkController::class, 'DeleteBacklinks']);

        Route::get('/user/backlinks-dashboard', [BacklinkController::class, 'BacklinksDashboard']);

      Route::get('/user/keyword/get_data', [UserDashboardController::class, 'get_api_maual_refresh_keyword']);
      Route::post('/user/check/keyword/data', [UserDashboardController::class, 'check_keyword_data']);

        Route::get('/user/competitors', [UserDashboardController::class, 'Competitors']);
      Route::get('/user/competitors/search', [UserDashboardController::class, 'CompetitorsSearch']);
        Route::post('/user/competitor/query', [UserDashboardController::class, 'CompetitorsQuery']);
        Route::get('/user/competitor/delete/{id}', [UserDashboardController::class, 'DeleteCompetitor']);


        Route::get('/user/related-keywords/locations', [KeywordResearchController::class, 'RelatedKeywordsLocationsLanguages']);
        Route::get('/user/related-keywords', [KeywordResearchController::class, 'RelatedKeywords']);
        Route::post('/user/related-keywords/query', [KeywordResearchController::class, 'RelatedKeywordsQuery']);
        Route::get('/user/related-keywords/history', [KeywordResearchController::class, 'RelatedKeywordsHistory']);
        Route::get('/user/related-keyword/detail/{id}', [KeywordResearchController::class, 'RelatedKeywordDetail']);
        Route::get('/user/related-keyword/delete/{id}', [KeywordResearchController::class, 'RelatedKeywordDelete']);
        Route::get('/user/related-keyword/monthly-detail/{id}/{keyword}', [KeywordResearchController::class, 'RelatedKeywordMonthlyDetail']);

        Route::get('/user/ranked_keywords', [BacklinkController::class, 'RankedKeywords']);
        Route::get('/select', [UserDashboardController::class, 'Select']);



        Route::get('/user/search-traffic', [TrafficAnalyticsController::class, 'SearchTraffic']);
        Route::get('/user/traffic/query', [TrafficAnalyticsController::class, 'TrafficQuery']);


        Route::get('/user/backlinks', [BacklinkController::class, 'Backlinks']);
        Route::get('/user/backlinks/refresh/{domain_id}', [BacklinkController::class, 'UserBacklinksRefresh']);
        Route::get('/user/backlinks/{id}', [BacklinkController::class, 'DomainIdBacklink']);
        Route::get('/user/backlink/query', [BacklinkController::class, 'BacklinkQuery']);
        Route::get('/user/anchor/query', [BacklinkController::class, 'AnchorQuery']);



        Route::get('/keyword/locations', [KeywordsController::class, 'keweordLocations']);
        Route::get('/keyword/languages', [KeywordsController::class, 'keweordLanguages']);
        Route::get('/user/adwords-status', [KeywordsController::class, 'AdwordsStatus']);
        Route::get('/user/search-volume', [KeywordsController::class, 'SearchVolume']);
        Route::post('/user/volume/query', [KeywordsController::class, 'VolumeQuery']);
        Route::get('/user/search-volume/detail/{id}', [KeywordsController::class, 'SearchVolumeDetail']);
        Route::get('/user/search-volume/delete/{id}', [KeywordsController::class, 'SearchVolumeDelete']);
        Route::get('/user/search-volume/history', [KeywordsController::class, 'SearchVolumeHistory']);




        Route::get('/user/new-serp', [SerpController::class, 'NewSerp']);
        Route::post('/user/serp/query', [SerpController::class, 'SerpQuery']);
        Route::get('/user/serp-history', [SerpController::class, 'SerpHistory']);
        Route::get('/user/serp/detail/{id}', [SerpController::class, 'SerpDetailHistory']);
        Route::get('/user/serp/delete/{id}', [SerpController::class, 'SerpDeleteHistory']);

        Route::get('/user/profile', [UserDashboardController::class, 'Profile']);
        Route::post('/user/profile/update', [UserDashboardController::class, 'ProfileUpdate']);

      Route::post('/user/notification/update', [UserDashboardController::class, 'NotificationUpdate']);
      Route::post('/user/backlinks/notification/update', [BacklinkController::class, 'NotificationUpdate']);

        Route::get('/user/upgrade_subscription', [FrontController::class, 'UpgradeSubscription']);
        Route::post('/user/update/payment_method', [FrontController::class, 'UpgradePaymentMethod']);
        Route::get('/upgrade/package/{id}', [FrontController::class, 'UpgradePackage']);
        Route::get('/user/cancel_subscription', [UserDashboardController::class, 'CancelSubscription']);


    });
    Route::get('/user/subscription', [UserDashboardController::class, 'Subscription']);
    Route::get('/user/get_subscription', [FrontController::class, 'GetSubscription']);
    Route::get('/user/get/package/{id}', [FrontController::class, 'GetSubscriptionPackage']);
    Route::post('/stripe/pricing/get_subscription', [FrontController::class, 'StripePricingGetSubscription']);
    Route::get('/paypal/pricing/get_subscription', [FrontController::class, 'PaypalPricingGetSubscription']);

    Route::get('/user/logout', [FrontController::class, 'UserLogout']);
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout']);
    Route::get('/admin/login', [AdminController::class, 'AdminLogin']);
    Route::post('/admin/login/query', [AdminController::class, 'AdminLoginQuery']);


    Route::get('/', [FrontController::class, 'Dashboard'])->name('/');
    Route::get('/about-us', [FrontController::class, 'AboutUs']);
    Route::get('/pricing', [FrontController::class, 'Pricing']);
    Route::post('/user/message', [FrontController::class, 'UserMessage']);
    Route::get('/contact', [FrontController::class, 'Contact']);
    Route::get('/blogs', [FrontController::class, 'Blogs']);
    Route::get('/blog-detail/{id}', [FrontController::class, 'BlogDetail']);
    Route::get('/learn', [FrontController::class, 'Learn']);
    Route::get('/learn-detail/{id}', [FrontController::class, 'LearnDetail']);
    Route::post('/subscribers/add', [FrontController::class, 'AddSubscriber']);
    Route::get('/login-register', [FrontController::class, 'LoginRegister']);
    Route::get('/login/forgot_password', [FrontController::class, 'ForgotPassword']);
    Route::post('/user/forgot_password', [FrontController::class, 'ForgotPasswordSendCode']);
    Route::get('/user/forgot_password_email_sent', [FrontController::class, 'forgot_password_email_sent']);
    Route::get('/user/forgot_password_code/{id}', [FrontController::class, 'forgot_password_code']);
    Route::post('/user/forgot_password_code_check', [FrontController::class, 'forgot_password_code_check']);

    Route::get('/user/change_password/{id}/{code}', [FrontController::class, 'ChangePassword']);
    Route::post('/user/change_password/save', [FrontController::class, 'ChangePasswordSave']);

    Route::post('/user-register', [FrontController::class, 'UserRegister']);
    Route::post('/pricing/stripe', [FrontController::class, 'StripePricing']);
    Route::post('/stripe/pricing/upgradation', [FrontController::class, 'StripePricingUpgradation']);
    Route::get('/paypal/pricing/upgradation', [FrontController::class, 'PaypalPricingUpgradation']);
    Route::get('/pricing/done/{user_id}/{shopier_id}', [FrontController::class, 'PricePaid']);

    Route::post('/user-login', [FrontController::class, 'Userlogin']);

    Route::get('/GetStarted/{id}', [FrontController::class, 'GetStartedPackage']);
    Route::get('/trial/GetStarted', [FrontController::class, 'TrialGetStartedPackage']);

    Route::get('/view', [FrontController::class, 'Message']);

    Route::get('/re-subscribe', [UserDashboardController::class, 'ReSubscribe']);
    Route::get('/re-subscribe/update', [UserDashboardController::class, 'ReSubscribeUpdate']);


    Route::group(['middleware' => 'admin_auth'], function () {
        Route::get('/admin', [AdminController::class, 'Home'])->name('/admin');

        Route::get('/admin/customer-reviews', [AdminController::class, 'CustomerReviews']);
        Route::post('/admin/customer-review/add', [AdminController::class, 'CustomerReviewAdd']);
        Route::get('/admin/customer-review/active/{id}', [AdminController::class, 'CustomerReviewActive']);
        Route::get('/admin/customer-review/inactive/{id}', [AdminController::class, 'CustomerReviewInactive']);
        Route::get('/admin/customer-review/edit/{id}', [AdminController::class, 'CustomerReviewEdit']);
        Route::post('/admin/customer-review/save', [AdminController::class, 'CustomerReviewSave']);
        Route::get('/admin/customer-review/delete/{id}', [AdminController::class, 'CustomerReviewDelete']);

        Route::get('/admin/satisfaction-reasons', [AdminController::class, 'SatisfactionReasons']);
        Route::post('/admin/satisfaction-reason/add', [AdminController::class, 'SatisfactionReasonAdd']);
        Route::get('/admin/satisfaction-reason/active/{id}', [AdminController::class, 'SatisfactionReasonActive']);
        Route::get('/admin/satisfaction-reason/inactive/{id}', [AdminController::class, 'SatisfactionReasonInactive']);
        Route::get('/admin/satisfaction-reason/edit/{id}', [AdminController::class, 'SatisfactionReasonEdit']);
        Route::post('/admin/satisfaction-reason/save', [AdminController::class, 'SatisfactionReasonSave']);
        Route::get('/admin/satisfaction-reason/delete/{id}', [AdminController::class, 'SatisfactionReasonDelete']);

        Route::get('/admin/our-team', [AdminController::class, 'OurTeam']);
        Route::post('/admin/our-team/add', [AdminController::class, 'OurTeamAdd']);
        Route::get('/admin/our-team/active/{id}', [AdminController::class, 'OurTeamActive']);
        Route::get('/admin/our-team/inactive/{id}', [AdminController::class, 'OurTeamInactive']);
        Route::get('/admin/our-team/edit/{id}', [AdminController::class, 'OurTeamEdit']);
        Route::post('/admin/our-team/save', [AdminController::class, 'OurTeamSave']);
        Route::get('/admin/our-team/delete/{id}', [AdminController::class, 'OurTeamDelete']);

        Route::get('/admin/about-us', [AdminController::class, 'AboutUs']);
        Route::post('/admin/about-us/update', [AdminController::class, 'AboutUsUpdate']);

        Route::get('/admin/home-banner', [AdminController::class, 'HomeBanner']);
        Route::post('/admin/home-banner/update', [AdminController::class, 'HomeBannerUpdate']);

        Route::get('/admin/home-highlights', [AdminController::class, 'HomeHighlights']);
        Route::post('/admin/home-highlights/add', [AdminController::class, 'HomeHighlightsAdd']);
        Route::get('/admin/home-highlights/active/{id}', [AdminController::class, 'HomeHighlightsActive']);
        Route::get('/admin/home-highlights/inactive/{id}', [AdminController::class, 'HomeHighlightsInactive']);
        Route::get('/admin/home-highlights/edit/{id}', [AdminController::class, 'HomeHighlightsEdit']);
        Route::post('/admin/home-highlights/save', [AdminController::class, 'HomeHighlightsSave']);
        Route::get('/admin/home-highlights/delete/{id}', [AdminController::class, 'HomeHighlightsDelete']);

        Route::get('/admin/home-third-section', [AdminController::class, 'ThirdSection']);
        Route::post('/admin/home-third-section/update', [AdminController::class, 'ThirdSectionUpdate']);

        Route::get('/admin/home-fourth-section', [AdminController::class, 'FourthSection']);
        Route::post('/admin/home-fourth-section/update', [AdminController::class, 'FourthSectionUpdate']);

        Route::get('/admin/home-case-studies', [AdminController::class, 'HomeCaseStudies']);
        Route::post('/admin/home-case-studies/add', [AdminController::class, 'HomeCaseStudiesAdd']);
        Route::get('/admin/home-case-studies/active/{id}', [AdminController::class, 'HomeCaseStudiesActive']);
        Route::get('/admin/home-case-studies/inactive/{id}', [AdminController::class, 'HomeCaseStudiesInactive']);
        Route::get('/admin/home-case-studies/delete/{id}', [AdminController::class, 'HomeCaseStudiesDelete']);

        Route::get('/admin/services-page-highlights', [AdminController::class, 'ServicesPageHighlights']);
        Route::post('/admin/services-page-highlights/add', [AdminController::class, 'ServicesPageHighlightsAdd']);
        Route::get('/admin/services-page-highlights/active/{id}', [AdminController::class, 'ServicesPageHighlightsActive']);
        Route::get('/admin/services-page-highlights/inactive/{id}', [AdminController::class, 'ServicesPageHighlightsInactive']);
        Route::get('/admin/services-page-highlights/edit/{id}', [AdminController::class, 'ServicesPageHighlightsEdit']);
        Route::post('/admin/services-page-highlights/save', [AdminController::class, 'ServicesPageHighlightsSave']);
        Route::get('/admin/services-page-highlights/delete/{id}', [AdminController::class, 'ServicesPageHighlightsDelete']);

        Route::get('/admin/contact-page', [AdminController::class, 'ContactPage']);
        Route::post('/admin/contact-page/update', [AdminController::class, 'ContactPageUpdate']);

        Route::get('/admin/packages', [AdminController::class, 'Package']);
        Route::post('/admin/package/add', [AdminController::class, 'PackageAdd']);
        Route::get('/admin/package/active/{id}', [AdminController::class, 'PackageActive']);
        Route::get('/admin/package/inactive/{id}', [AdminController::class, 'PackageInactive']);
        Route::get('/admin/package/edit/{id}', [AdminController::class, 'PackageEdit']);
        Route::post('/admin/package/save', [AdminController::class, 'PackageSave']);
        Route::get('/admin/package/delete/{id}', [AdminController::class, 'PackageDelete']);

        Route::get('/admin/messages/unread', [AdminController::class, 'UnreadMessages']);
        Route::get('/admin/messages/read', [AdminController::class, 'ReadMessages']);
        Route::get('/admin/view-message/{id}', [AdminController::class, 'ViewMessage']);
        Route::get('/admin/message/delete/{id}', [AdminController::class, 'DeleteMessage']);

          Route::post('/admin/view-message/reply', [AdminController::class, 'ViewMessageReply']);

        Route::get('/admin/message/mark-read/{id}', [AdminController::class, 'MarkAsRead']);
        Route::get('/admin/message/mark-unread/{id}', [AdminController::class, 'MarkAsUnread']);

        Route::get('/admin/basic-settings', [AdminController::class, 'BasicSettings']);
        Route::post('/admin/basic-settings/save', [AdminController::class, 'BasicSettingsSave']);

        Route::get('/admin/usefull-links', [AdminController::class, 'UsefullLinks']);
        Route::post('/admin/usefull-link/add', [AdminController::class, 'UsefullLinkAdd']);
        Route::get('/admin/usefull-link/active/{id}', [AdminController::class, 'UsefullLinkActive']);
        Route::get('/admin/usefull-link/inactive/{id}', [AdminController::class, 'UsefullLinkInactive']);
        Route::get('/admin/usefull-link/delete/{id}', [AdminController::class, 'UsefullLinkDelete']);
        Route::get('/admin/usefull-link/edit/{id}', [AdminController::class, 'UsefullLinkEdit']);
        Route::post('/admin/usefull-link/save', [AdminController::class, 'UsefullLinkSave']);

        Route::get('/admin/trial_package/{id}', [AdminController::class, 'TrialPackage']);
        Route::post('/admin/trial_package/save', [AdminController::class, 'TrialPackageSave']);

        Route::get('/admin/blogs', [AdminController::class, 'Blogs']);
        Route::get('/admin/blog/new', [AdminController::class, 'BlogNew']);
        Route::post('/admin/blog/add', [AdminController::class, 'BlogAdd']);
        Route::get('/admin/blog/active/{id}', [AdminController::class, 'BlogActive']);
        Route::get('/admin/blog/inactive/{id}', [AdminController::class, 'BlogInactive']);
        Route::get('/admin/blog/delete/{id}', [AdminController::class, 'BlogDelete']);
        Route::get('/admin/blog/edit/{id}', [AdminController::class, 'BlogEdit']);
        Route::post('/admin/blog/save', [AdminController::class, 'BlogSave']);

        Route::get('/admin/learn', [AdminController::class, 'Learn']);
        Route::get('/admin/learn/new', [AdminController::class, 'LearnNew']);
        Route::post('/admin/learn/add', [AdminController::class, 'LearnAdd']);
        Route::get('/admin/learn/active/{id}', [AdminController::class, 'LearnActive']);
        Route::get('/admin/learn/inactive/{id}', [AdminController::class, 'LearnInactive']);
        Route::get('/admin/learn/delete/{id}', [AdminController::class, 'LearnDelete']);
        Route::get('/admin/learn/edit/{id}', [AdminController::class, 'LearnEdit']);
        Route::post('/admin/learn/save', [AdminController::class, 'LearnSave']);

        Route::get('/admin/subscribers', [AdminController::class, 'Subscriber']);
        Route::get('/admin/subscriber/active/{id}', [AdminController::class, 'SubscriberActive']);
        Route::get('/admin/subscriber/inactive/{id}', [AdminController::class, 'SubscriberInactive']);
        Route::get('/admin/subscriber/delete/{id}', [AdminController::class, 'SubscriberDelete']);

        Route::get('/admin/users', [AdminController::class, 'Users']);
        Route::get('/admin/users/search', [AdminController::class, 'UsersSearch']);
        Route::get('/admin/user/active/{id}', [AdminController::class, 'UserActive']);
        Route::get('/admin/user/inactive/{id}', [AdminController::class, 'UserInactive']);
        Route::get('/admin/user/delete/{id}', [AdminController::class, 'UserDelete']);
        Route::get('/admin/user/edit/{id}', [AdminController::class, 'UserEdit']);
        Route::post('/admin/user/edit/done', [AdminController::class, 'UserUpdate']);

        Route::get('/admin/paypal', [AdminController::class, 'Paypal']);
        Route::post('/admin/paypal/update', [AdminController::class, 'PaypalUpdate']);

        Route::get('/admin/api', [AdminController::class, 'Api']);
        Route::post('/admin/api/update', [AdminController::class, 'ApiUpdate']);

        Route::get('/admin/admin_settings', [AdminController::class, 'AdminSettings']);
        Route::post('/admin/admin_settings/update', [AdminController::class, 'AdminSettingsUpdate']);

        Route::get('/duplicate', [AdminController::class, 'Duplicate']);
        Route::get('/admin/languages', [AdminController::class, 'Languages']);
        Route::get('/admin/language/active/{id}', [AdminController::class, 'LanguageActive']);
        Route::get('/admin/language/inactive/{id}', [AdminController::class, 'LanguageInactive']);
        Route::get('/admin/language/edit/{id}', [AdminController::class, 'LanguageEdit']);
        Route::post('/admin/language/save', [AdminController::class, 'LanguageSave']);

        Route::get('/admin/locations', [AdminController::class, 'Locations']);
        Route::get('/admin/location/active/{id}', [AdminController::class, 'LocationActive']);
        Route::get('/admin/location/inactive/{id}', [AdminController::class, 'LocationInactive']);
        Route::get('/admin/location/edit/{id}', [AdminController::class, 'LocationEdit']);
        Route::post('/admin/location/save', [AdminController::class, 'LocationSave']);
    });
  });

});
});
