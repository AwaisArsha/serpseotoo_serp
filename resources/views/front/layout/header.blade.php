<!DOCTYPE html>
<html>

<head>

	<!-- Basic -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title>Serp Checker Seo Tools</title>

	<meta name="keywords" content="serp,serp checker,serp google,serp ranking,serp seo,ser seo tools" />
	<meta name="description" content="With this accessible rank tracking tool you can easily and quickly see how high your website is in Google.">
	<meta name="author" content="okler.net">
	<link rel="icon" type="image/x-icon" href="{{asset('front_assets/img/favicon.ico')}}">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
	<link id="googleFonts" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800%7COpen+Sans:400,700,800&display=swap" rel="stylesheet" type="text/css">

	<link rel="stylesheet" href="{{asset('front_assets/vendor/bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('front_assets/vendor/fontawesome-free/css/all.min.css')}}">
	<link rel="stylesheet" href="{{asset('front_assets/vendor/animate/animate.compat.css')}}">
	<link rel="stylesheet" href="{{asset('front_assets/vendor/simple-line-icons/css/simple-line-icons.min.css')}}">
	<link rel="stylesheet" href="{{asset('front_assets/vendor/owl.carousel/assets/owl.carousel.min.css')}}">
	<link rel="stylesheet" href="{{asset('front_assets/vendor/owl.carousel/assets/owl.theme.default.min.css')}}">
	<link rel="stylesheet" href="{{asset('front_assets/vendor/magnific-popup/magnific-popup.min.css')}}">
	<link rel="stylesheet" href="{{asset('front_assets/vendor/bootstrap-icons/bootstrap-icons.css')}}">

	<!-- Theme CSS -->
	<link rel="stylesheet" href="{{asset('front_assets/css/theme.css')}}">
	<link rel="stylesheet" href="{{asset('front_assets/css/theme-elements.css')}}">
	<link rel="stylesheet" href="{{asset('front_assets/css/theme-blog.css')}}">
	<link rel="stylesheet" href="{{asset('front_assets/css/theme-shop.css')}}">

	<!-- Demo CSS -->
	<link rel="stylesheet" href="{{asset('front_assets/css/demos/demo-seo-3.css')}}">

	<!-- Skin CSS -->
	<link id="skinCSS" rel="stylesheet" href="{{asset('front_assets/css/skins/skin-seo-3.css')}}">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
</head>

@php
$settings = settings_data();
@endphp

<body data-plugin-page-transition>
	<div class="body">
		<div class="">
			<header id="header" data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyChangeLogo': false, 'stickyStartAt': 53, 'stickySetTop': '-53px'}">
				<div class="header-body border-top-0 h-auto box-shadow-none">
					<div class="header-top border-0">
						<div class="container container-xl-custom h-100">
							<div class="header-row h-100">
								<div class="header-column justify-content-start">
									<div class="header-row">
										<nav class="header-nav-top">
											<ul class="nav nav-pills">
												<li class="nav-item py-2 pe-2">
													<a href="tel:123-456-7890" class="text-color-default ps-0 text-2-5 text-color-hover-primary font-weight-semibold"><i class="bi bi-telephone text-primary text-4 me-1 p-relative top-1"></i> {{$settings->phone}}</a>
												</li>
												<li class="nav-item py-2 d-none d-md-inline-flex">
													<a href="mailto:mail@domain.com" class="text-color-default text-2-5 text-color-hover-primary font-weight-semibold"><i class="bi bi-envelope text-primary text-4 me-1 p-relative top-1"></i> {{$settings->email}}</a>
												</li>
											</ul>
										</nav>
									</div>
								</div>
								<div class="header-column justify-content-end">
									<div class="header-row">
										<nav class="header-nav-top">
											<ul class="nav nav-pills p-relative bottom-2">
												@if($settings->facebook != null)
												<li class="nav-item py-2 d-none d-lg-inline-flex">
													<a href="{{$settings->facebook}}" target="_blank" title="Facebook" class="text-color-dark text-color-hover-primary text-3 anim-hover-translate-top-5px transition-2ms"><i class="fab fa-facebook-f text-3 p-relative top-1"></i></a>
												</li>
												@endif
												@if($settings->twitter != null)
												<li class="nav-item py-2 d-none d-lg-inline-flex">
													<a href="{{$settings->twitter}}" target="_blank" title="Twitter" class="text-color-dark text-color-hover-primary text-3 anim-hover-translate-top-5px transition-2ms"><i class="fab fa-twitter text-3 p-relative top-1"></i></a>
												</li>
												@endif
												@if($settings->instagram != null)
												<li class="nav-item py-2 d-none d-lg-inline-flex">
													<a href="{{$settings->instagram}}" target="_blank" title="Instagram" class="text-color-dark text-color-hover-primary text-3 anim-hover-translate-top-5px transition-2ms"><i class="fab fa-instagram text-3 p-relative top-1"></i></a>
												</li>
												@endif
												<li class="nav-item py-2 d-none d-lg-inline-flex">
													<div class="language" style="padding-top: 5px;margin-left: 5px;color: black;">{{__("Langauge")}}
														:&nbsp;
														<select onchange="changeLanguage(this.value)">
															<option {{session()->has('lang_code')?(session()->get('lang_code')=='en'?'selected':''):''}} value="en">English</option>
															<option {{session()->has('lang_code')?(session()->get('lang_code')=='dt'?'selected':''):''}} value="dt">Dutch </option>
															<option {{session()->has('lang_code')?(session()->get('lang_code')=='tu'?'selected':''):''}} value="tu">Turkish</option>
														</select>
													</div>
												</li>
											</ul>
										</nav>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="header-main box-shadow-6 p-relative z-index-2">
						<div class="header-container header-container-height-sm container container-xl-custom p-static">
							<div class="header-row">
								<div class="header-column">
									<div class="header-row">
										<div class="header-logo">
											<a href="{{url('/')}}">
												<img alt="Porto"  height="25" src="{{asset('project_images'.$settings->site_logo)}}">
											</a>
										</div>
									</div>
								</div>
								<div class="header-column justify-content-end">
									<div class="header-row">
										<div class="header-nav header-nav-links">
											<div class="header-nav-main header-nav-main-square header-nav-main-dropdown-no-borders header-nav-main-dropdown-border-radius header-nav-main-text-capitalize header-nav-main-text-size-4 header-nav-main-arrows header-nav-main-full-width-mega-menu header-nav-main-mega-menu-bg-hover header-nav-main-effect-2">
												<nav class="collapse">
													<ul class="nav nav-pills" id="mainNav">
														<li>
															<a class="nav-link @yield('home-selected')" href="{{url('/')}}">
																{{__("Home")}}
															</a>
														</li>
														<li>
															<a class="nav-link @yield('service-selected')" href="{{url('/about-us')}}">
																{{__("SEO Tools")}}
															</a>
														</li>
														@if(!Session::has('user_id'))
														<li>
															<a class="nav-link" href="{{url('/pricing')}}">
																{{__("Prices")}}
															</a>
														</li>
														@endif
                                                        <li>
															<a class="nav-link" href="{{url('/learn')}}">
																{{__("Learn")}}
															</a>
														</li>
														<li>
															<a class="nav-link" href="{{url('/blogs')}}">
																{{__("Blogs")}}
															</a>
														</li>
														<li>
															<a class="nav-link" href="{{url('contact')}}">
																{{__("Contact")}}
															</a>
														</li>
														<li>
															@if(Session::has('user_id') && Session::has('user_email') && Session::has('user_name'))
															<a class="nav-link" href="{{url('user/dashboard')}}">
																<i class="bi bi-person"></i>&nbsp;
																{{__("Dashboard")}}
															</a>
															@else
															<a class="nav-link" href="{{url('login-register')}}">
																<i class="bi bi-person"></i>&nbsp;
																{{__("Login")}}
															</a>
															@endif
														</li>
													</ul>
												</nav>
											</div>
											<button class="btn header-btn-collapse-nav" data-bs-toggle="collapse" data-bs-target=".header-nav-main nav">
												<i class="fas fa-bars"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>

			<script>
				function changeLanguage(lang) {
					window.location = '{{url("change-language")}}/' + lang;
				}
			</script>