@php
	$settings = settings_data();
@endphp
<nav
    class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon"
                            data-feather="menu"></i></a></li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">

            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                        data-feather="moon"></i></a></li>
            <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon"
                        data-feather="search"></i></a>
                <div class="search-input">
                    <div class="search-input-icon"><i data-feather="search"></i></div>
                    <input class="form-control input" type="text" placeholder="Explore Vuexy..." tabindex="-1"
                        data-search="search">
                    <div class="search-input-close"><i data-feather="x"></i></div>
                    <ul class="search-list search-list-main"></ul>
                </div>
            </li>
            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                    id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">John Doe</span><span
                            class="user-status">Admin</span></div><span class="avatar"><img class="round"
                            src="{{asset('admin_assets/images/portrait/small/avatar-s-11.jpg')}}" alt="avatar"
                            height="40" width="40"><span class="avatar-status-online"></span></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    <a class="dropdown-item"
                        href="{{url('/admin/logout')}}"><i class="me-50" data-feather="power"></i> Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>


<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto"><a class="navbar-brand"
                    href="{{url('/')}}"><span class="">
                        <img style="height: 25px" src="{{asset('project_images'.$settings->admin_logo)}}"
                            alt="">
                    </span>

                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item"><a class="d-flex align-items-center" href="{{url('/')}}"><i
                        data-feather="home"></i><span class="menu-title text-truncate"
                        data-i18n="Dashboards">Dashboards</span><span
                        class="badge badge-light-warning rounded-pill ms-auto me-1">2</span></a>
                <ul class="menu-content">
                  <li class="@yield('dashboard')"><a class="d-flex align-items-center" href="{{url('/admin')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Dashboard</span></a>
                    </li>
                    <li class="@yield('api')"><a class="d-flex align-items-center" href="{{url('/admin/api')}}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate"
                                data-i18n="Analytics">API Key</span></a>
                    </li>
                    <li class="@yield('admin_settings')"><a class="d-flex align-items-center" href="{{url('/admin/admin_settings')}}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate"
                                data-i18n="Analytics">Admin Settings</span></a>
                    </li>
                </ul>
            </li>
            <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Apps &amp; Pages</span><i
                    data-feather="more-horizontal"></i>
            </li>
            <li class="@yield('users')">
                <a class="d-flex align-items-center" href="{{url('/admin/users')}}">
                    <i data-feather="user"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Users</span>
                </a>
            </li>
            <li class="@yield('basic-settings')">
                <a class="d-flex align-items-center" href="{{url('/admin/basic-settings')}}">
                    <i data-feather="grid"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Basic Settings</span>
                </a>
            </li>
            <li class=" nav-item"><a class="d-flex align-items-center" href="{{url('/')}}"><i
                        data-feather="home"></i><span class="menu-title text-truncate"
                        data-i18n="Dashboards">Home Page</span><span
                        class="badge badge-light-warning rounded-pill ms-auto me-1">2</span></a>
                <ul class="menu-content">
                    <li class="@yield('home-banner')">
                        <a class="d-flex align-items-center" href="{{url('/admin/home-banner')}}">
                        <i
                                data-feather="circle"></i>
                            <span class="menu-title text-truncate" data-i18n="Email">Home Banner</span>
                        </a>
                    </li>
                    <li class="@yield('home-highlights')"><a class="d-flex align-items-center" href="{{url('/admin/home-highlights')}}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate"
                                data-i18n="Analytics">Highlights</span></a>
                    </li>
                    <li class="@yield('home-third-section')"><a class="d-flex align-items-center" href="{{url('/admin/home-third-section')}}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate"
                                data-i18n="Analytics">Third Section</span></a>
                    </li>
                    <li class="@yield('home-fourth-section')"><a class="d-flex align-items-center" href="{{url('/admin/home-fourth-section')}}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate"
                                data-i18n="Analytics">Fourth Section</span></a>
                    </li>
                    <li class="@yield('home-case-studies')"><a class="d-flex align-items-center" href="{{url('/admin/home-case-studies')}}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate"
                                data-i18n="Analytics">Case Studies</span></a>
                    </li>
                </ul>
            </li>
            <li class="nav-item"><a class="d-flex align-items-center" href="{{url('/')}}"><i
                        data-feather="home"></i><span class="menu-title text-truncate"
                        data-i18n="Dashboards">Services Page</span><span
                        class="badge badge-light-warning rounded-pill ms-auto me-1">2</span></a>
                <ul class="menu-content">
                    <li class="@yield('services-page-highlights')">
                        <a class="d-flex align-items-center" href="{{url('/admin/services-page-highlights')}}">
                        <i data-feather="circle"></i>
                            <span class="menu-title text-truncate" data-i18n="Email">Services Page Highlights</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="@yield('contact-page')">
                <a class="d-flex align-items-center" href="{{url('/admin/contact-page')}}">
                    <i data-feather="life-buoy"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Contact Page</span>
                </a>
            </li>
            <li class="@yield('our-team')">
                <a class="d-flex align-items-center" href="{{url('/admin/our-team')}}">
                    <i data-feather="life-buoy"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Our Team</span>
                </a>
            </li>
            
            <li class="@yield('about-us')">
                <a class="d-flex align-items-center" href="{{url('/admin/about-us')}}">
                    <i data-feather="map"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">About Us Page</span>
                </a>
            </li>
          <li class="@yield('languages')">
                <a class="d-flex align-items-center" href="{{url('/admin/languages')}}">
                    <i data-feather="grid"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Languages</span>
                </a>
            </li>
            <li class="@yield('locations')">
                <a class="d-flex align-items-center" href="{{url('/admin/locations')}}">
                    <i data-feather="grid"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Locations</span>
                </a>
            </li>
            <li class="@yield('packages')">
                <a class="d-flex align-items-center" href="{{url('/admin/packages')}}">
                    <i data-feather="check-circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Packages</span>
                </a>
            </li>
            <li class="@yield('blogs')">
                <a class="d-flex align-items-center" href="{{url('/admin/blogs')}}">
                    <i data-feather="square"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Blogs</span>
                </a>
            </li>
            <li class="@yield('Learning')">
                <a class="d-flex align-items-center" href="{{url('/admin/learn')}}">
                    <i data-feather="eye"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Learning</span>
                </a>
            </li>
            <li class="@yield('usefull-links')">
                <a class="d-flex align-items-center" href="{{url('/admin/usefull-links')}}">
                    <i data-feather="copy"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Usefull Links</span>
                </a>
            </li>
            <li class="@yield('satisfaction-reasons')">
                <a class="d-flex align-items-center" href="{{url('/admin/satisfaction-reasons')}}">
                    <i data-feather="grid"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Satisfaction Reasons</span>
                </a>
            </li>
            <li class="@yield('customer-reviews')">
                <a class="d-flex align-items-center" href="{{url('/admin/customer-reviews')}}">
                    <i data-feather="mail"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Customer Reviews</span>
                </a>
            </li>
            <li class="@yield('paypal')">
                <a class="d-flex align-items-center" href="{{url('/admin/paypal')}}">
                    <i data-feather="check-square"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Payment</span>
                </a>
            </li>
            <li class=" nav-item"><a class="d-flex align-items-center" href="index.html"><i
                        data-feather="message-square"></i><span class="menu-title text-truncate"
                        data-i18n="Dashboards">Messages</span><span
                        class="badge badge-light-warning rounded-pill ms-auto me-1">2</span></a>
                <ul class="menu-content">
                    <li class="@yield('unread')"><a class="d-flex align-items-center" href="{{url('/admin/messages/unread')}}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate"
                                data-i18n="Analytics">Unread Messages</span></a>
                    </li>
                    <li class="@yield('read')"><a class="d-flex align-items-center"
                            href="{{url('/admin/messages/read')}}"><i data-feather="circle"></i><span
                                class="menu-item text-truncate" data-i18n="eCommerce">Read Messages</span></a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>