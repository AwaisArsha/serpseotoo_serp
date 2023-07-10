@php
$settings = settings_data();
@endphp
<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
    <div class="navbar-container d-flex content">
        <ul class="nav navbar-nav d-xl-none" style="display: flex;
    justify-content: center;
    align-items: center;">
            <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
        </ul>
        <ul class="nav navbar-nav align-items-center" style="flex: 1;">
            @yield('page-header')
            <li class="nav-item dropdown dropdown-user ms-auto"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">{{Session::get('user_name')}}</span></div><span class="avatar">
                        @php
                        $user_info = DB::table('users')->where('id', Session::get('user_id'))->first();
                        if($user_info->image != null) {
                        if (is_file(public_path() . '/project_images' .$user_info->image)) {
                        @endphp
                        <img class="round" src="{{asset('project_images'. $user_info->image)}}" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                    @php
                    }
                    } else {
                    @endphp
                    <img class="round" src="{{asset('admin_assets/images/portrait/small/avatar-s-11.jpg')}}" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                    @php
                    }
                    @endphp
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{{url('user/logout')}}"><i class="me-50" data-feather="power"></i>
                        {{__("Logout")}}</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
@php
$package_info = UserPackageInfo();
if(check_subscribed() == true) {
$disabled = '';
} else {
$disabled = 'disabled';
}
@endphp

<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto" style="display: flex; justify-content: center; align-items: center;">
                <a class="navbar-brand" style="margin-top:0.3rem;" href="../../../html/ltr/vertical-collapsed-menu-template/index.html">
                    <div class="header-logo">
                        <a href="{{url('/user/dashboard')}}"><img alt="Porto" height="22" src="{{asset('project_images'.$settings->admin_logo)}}"></a>
                    </div>
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="@yield('dashboard') nav-item {{$disabled}}"><a class="d-flex align-items-center" href="{{url('/user/dashboard')}}"><i style="top: -5px;" class="bi bi-speedometer2"></i><span class="menu-title text-truncate" data-i18n="Serp Checker">{{__("Serp Checker")}}</span></a>
            </li>
            @if($package_info->competitors == 1)
            <li class="@yield('competitors') nav-item {{$disabled}}"><a class="d-flex align-items-center" href="{{url('/user/competitors')}}"><i data-feather="check-circle"></i><span class="menu-title text-truncate" data-i18n="Dashboards">{{__("Competitors")}}</span></a>
            </li>
            @endif

            @if($package_info->keyword_planner == 1)
            <li class="nav-item {{$disabled}}"><a class="d-flex align-items-center" href="index.html"><i data-feather="map"></i><span class="menu-title text-truncate" data-i18n="Dashboards">{{__("Keyword Planner")}}</span><span class="badge badge-light-warning rounded-pill ms-auto me-1"></span></a>
                <ul class="menu-content">
                    <li class="@yield('related-keywords')"><a class="d-flex align-items-center" href="{{url('/user/related-keywords')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">{{__("New Keywords")}}</span></a>
                    </li>
                    <li class="@yield('related-keywords-history')"><a class="d-flex align-items-center" href="{{url('/user/related-keywords/history')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">{{__("Last Keywords")}}</span></a>
                    </li>
                </ul>
            </li>
            @endif

            @if($package_info->backlinks == 1)
            <li class="@yield('backlinks') nav-item {{$disabled}}"><a class="d-flex align-items-center" href="{{url('/user/backlinks-dashboard')}}"><i data-feather="square"></i><span class="menu-title text-truncate" data-i18n="Serp Checker">{{__("Backlinks")}}</span></a>
            </li>
            @endif
            @php
            $reference = true;
            @endphp
            @if($package_info->serp_api == 1  && $reference)
            <li class=" nav-item {{$disabled}}"><a class="d-flex align-items-center" href="index.html"><i data-feather="life-buoy"></i><span class="menu-title text-truncate" data-i18n="Dashboards">{{__("SERP Search")}}</span><span class="badge badge-light-warning rounded-pill ms-auto me-1">2</span></a>
                <ul class="menu-content">
                    <li class="@yield('new-serp')"><a class="d-flex align-items-center" href="{{url('/user/new-serp')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">{{__("New Search")}}</span></a>
                    </li>
                    <li class="@yield('serp-history')"><a class="d-flex align-items-center" href="{{url('/user/serp-history')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">{{__("SERP History")}}</span></a>
                    </li>
                </ul>
            </li>
            @endif

            @if($package_info->keywords_api == 1 && $reference)
            <li class=" nav-item {{$disabled}}"><a class="d-flex align-items-center" href="index.html"><i data-feather="grid"></i><span class="menu-title text-truncate" data-i18n="Dashboards">{{__("Search Volume Labs")}}</span><span class="badge badge-light-warning rounded-pill ms-auto me-1">2</span></a>
                <ul class="menu-content">
                    <li class="@yield('search-volume')"><a class="d-flex align-items-center" href="{{url('/user/search-volume')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">{{__("Search Volume Query")}}</span></a>
                    </li>
                    <li class="@yield('volume-history')"><a class="d-flex align-items-center" href="{{url('/user/search-volume/history')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">{{__("Search Volume History")}}</span></a>
                    </li>
                </ul>
            </li>
            @endif

            <li class="@yield('subscription') nav-item"><a class="d-flex align-items-center" href="{{url('/user/subscription')}}"><i data-feather="user-check"></i><span class="menu-title text-truncate" data-i18n="Dashboards">{{__("Subscription")}}</span></a>
            </li>
            <li class="@yield('profile') nav-item {{$disabled}}"><a class="d-flex align-items-center" href="{{url('/user/profile')}}"><i data-feather="user"></i><span class="menu-title text-truncate" data-i18n="Dashboards">Account</span></a>
            </li>
            <!-- <li class=" nav-item"><a class="d-flex align-items-center" href="index.html"><i data-feather="eye"></i><span
                        class="menu-title text-truncate" data-i18n="Dashboards">{{__("Traffic Analytics API")}}</span><span
                        class="badge badge-light-warning rounded-pill ms-auto me-1">2</span></a>
                <ul class="menu-content">
                    <li class="@yield('traffic-analytics')"><a class="d-flex align-items-center"
                            href="{{url('/user/search-traffic')}}"><i data-feather="circle"></i><span
                                class="menu-item text-truncate" data-i18n="Analytics">{{__("Search Similarweb")}}</span></a>
                    </li>
                    <li class="@yield('analytics-history')"><a class="d-flex align-items-center"
                            href="{{url('/user/search-traffic/history')}}"><i data-feather="circle"></i><span
                                class="menu-item text-truncate" data-i18n="Analytics">{{__("Analytics History")}}</span></a>
                    </li>
                </ul>
            </li> -->

        </ul>
    </div>
</div>