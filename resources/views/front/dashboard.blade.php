@section('home-selected', 'active current-page-active')
@include('front.layout.header')

<div role="main" class="main">

    <div class="mouse-hover-split mb-0">
        <div id="side-left" class="side side-left bg-color-primary">
            <div class="side-content d-flex align-items-center p-relative">
                <div class="container container-xl-custom">
                    <div class="row">
                        <div class="col-lg-6 text-center text-lg-start py-5 py-lg-0 align-self-center">
                            <div class="appear-animation p-absolute" style="width: 80px; height: 80px; top: 25%; left: 6%;" data-appear-animation="expandInWithBlur" data-appear-animation-delay="900" data-appear-animation-duration="2s">
                                <div class="particle particle-repeating-lines bg-light rounded-circle w-100 h-100" data-plugin-float-element data-plugin-options="{'startPos': 'top', 'speed': 2.1, 'transition': true, 'transitionDuration': 1000}"></div>
                            </div>

                            <div class="appear-animation p-absolute" style="width: 25px; height: 25px; top: 15%; left: 20%;" data-appear-animation="expandInWithBlur" data-appear-animation-delay="900" data-appear-animation-duration="2s">
                                <div class="bg-light opacity-4 rounded-circle w-100 h-100" data-plugin-float-element data-plugin-options="{'startPos': 'top', 'speed': 0.3, 'transition': true, 'transitionDuration': 1000}"></div>
                            </div>

                            <div class="appear-animation p-absolute" style="width: 15px; height: 15px; top: 50%; left: 8%;" data-appear-animation="expandInWithBlur" data-appear-animation-delay="900" data-appear-animation-duration="2s">
                                <div class="bg-light opacity-4 rounded-circle w-100 h-100" data-plugin-float-element data-plugin-options="{'startPos': 'top', 'speed': 0.3, 'transition': true, 'transitionDuration': 1000}"></div>
                            </div>

                            <div class="overflow-hidden mb-3 opacity-7">
                                <h2 class="font-weight-semi-bold text-color-light text-uppercase positive-ls-3 text-4-5 line-height-2 line-height-sm-7 mb-0" data-plugin-animated-words data-plugin-options="{'contentType': 'word', 'animationName': 'fadeInUpShorter', 'animationSpeed': 200, 'startDelay': 0, 'minWindowWidth': 0}">{{$home_banner->first_heading}}</h2>
                            </div>
                            <h1 class="text-color-light font-weight-bold text-12 line-height-1 ls-0 pb-2 mb-3" data-plugin-animated-letters data-plugin-options="{'animationName': 'fadeInUpShorter', 'animationSpeed': 20, 'startDelay': 0, 'minWindowWidth': 0}">{{$home_banner->second_heading}}</h1>
                            <p class="font-weight-medium text-color-light text-4 line-height-2 line-height-sm-7 mb-4 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="0">{{$home_banner->third_heading}}</p>
                            <div class="d-block appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="300">
                                <a href="{{url('/pricing')}}" data-hash data-hash-offset="0" data-hash-offset-lg="100" class="btn btn-modern btn-light border-0 font-weight-semi-bold positive-ls-1 text-color-secondary text-uppercase text-2-5 px-5 py-3">Get Started</a>
                            </div>
                        </div>
                        <div class="col-lg-6 py-5 py-lg-0 align-self-center text-center">
                            <img class="img-fluid" src="{{asset('project_images'.$home_banner->image)}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="side-right" class="side side-right bg-color-light-scale-1">
            <div class="side-content d-flex align-items-center p-relative">
                <div class="container container-xl-custom">
                    <div class="row">
                        <div class="col-lg-6 text-center text-lg-start py-5 py-lg-0 align-self-center">

                            <div class="appear-animation p-absolute" style="width: 80px; height: 80px; top: 25%; left: 6%;" data-appear-animation="expandInWithBlur" data-appear-animation-delay="900" data-appear-animation-duration="2s">
                                <div class="particle particle-repeating-lines bg-dark rounded-circle w-100 h-100" data-plugin-float-element data-plugin-options="{'startPos': 'top', 'speed': 2.1, 'transition': true, 'transitionDuration': 1000}"></div>
                            </div>

                            <div class="appear-animation p-absolute" style="width: 25px; height: 25px; top: 15%; left: 20%;" data-appear-animation="expandInWithBlur" data-appear-animation-delay="900" data-appear-animation-duration="2s">
                                <div class="bg-dark opacity-4 rounded-circle w-100 h-100" data-plugin-float-element data-plugin-options="{'startPos': 'top', 'speed': 0.3, 'transition': true, 'transitionDuration': 1000}"></div>
                            </div>

                            <div class="appear-animation p-absolute" style="width: 15px; height: 15px; top: 50%; left: 8%;" data-appear-animation="expandInWithBlur" data-appear-animation-delay="900" data-appear-animation-duration="2s">
                                <div class="bg-dark opacity-4 rounded-circle w-100 h-100" data-plugin-float-element data-plugin-options="{'startPos': 'top', 'speed': 0.3, 'transition': true, 'transitionDuration': 1000}"></div>
                            </div>

                            <div class="overflow-hidden mb-3 opacity-7">
                                <h2 class="font-weight-semi-bold text-color-dark text-uppercase positive-ls-3 text-4-5 line-height-2 line-height-sm-7 mb-0" data-plugin-animated-words data-plugin-options="{'contentType': 'word', 'animationName': 'fadeInUpShorter', 'animationSpeed': 200, 'startDelay': 0, 'minWindowWidth': 992}">{{$home_banner->first_heading}}</h2>
                            </div>
                            <h1 class="text-color-dark font-weight-bold text-12 line-height-1 ls-0 pb-2 mb-3" data-plugin-animated-letters data-plugin-options="{'animationName': 'fadeInUpShorter', 'animationSpeed': 20, 'startDelay': 0, 'minWindowWidth': 992}">{{$home_banner->second_heading}}</h1>
                            <p class="font-weight-medium text-color-dark text-4 line-height-2 line-height-sm-7 mb-4 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">{{$home_banner->third_heading}}</p>
                            <div class="d-block appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="300">
                                <a href="{{url('/pricing')}}" data-hash data-hash-offset="0" data-hash-offset-lg="100" class="btn btn-modern btn-light border-0 font-weight-semi-bold positive-ls-1 text-color-secondary text-uppercase text-2-5 px-5 py-3">Get Started</a>
                            </div>
                        </div>
                        <div class="col-lg-6 py-5 py-lg-0 align-self-center text-center">
                            <img class="img-fluid" src="{{asset('project_images'.$home_banner->image)}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <!-- <div class="marquee py-3" data-duration="70000" data-gap="10" data-duplicated="true" data-startVisible="true">
        <p class="text-6 text-dark font-weight-bold positive-ls-3 text-uppercase m-0 d-block py-5"><span data-clone-element="5"><!--{{__('Rank Checker Key Planner Competitor Analysis Notifications ')}}--></span></p>
    </div>-->

    <div class="container container-xl-custom p-relative z-index-2">
        <div class="row">
            @foreach($home_highlights as $highlight)
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="card border-0 bg-color-light anim-hover-translate-top-10px transition-3ms">
                    <div class="card-body px-lg-5 py-5 text-center box-shadow-6 border-radius-2 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">
                        <img height="80" src="{{asset('project_images'.$highlight->image)}}" alt="" data-icon data-plugin-options="{'onlySVG': true, 'extraClass': ' mb-4'}" />
                        <h4 class="font-weight-semi-bold mt-4">{{$highlight->title}}</h4>
                        <p class="text-3 mb-0">{{$highlight->description}}</p>
                        <a href="{{url('/about-us')}}" class="btn btn-arrow-effect-1 ws-nowrap font-weight-semi-bold positive-ls-1 text-primary text-2 bg-transparent border-0 px-0 text-uppercase">More Details <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row p-relative mt-5 py-3">
            <div class="col-lg-6 py-5 py-lg-0 align-self-center text-center">
                <div class="p-lg-5">
                    <div class="appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="0">
                        <img class="img-fluid" src="{{asset('project_images'.$home_third_section->image)}}" alt="">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-start py-5 py-lg-0 align-self-center">

                <div class="appear-animation p-absolute" style="width: 25px; height: 25px; top: 5%; left: 10%;" data-appear-animation="expandInWithBlur" data-appear-animation-delay="900" data-appear-animation-duration="2s">
                    <div class="bg-primary opacity-4 rounded-circle w-100 h-100" data-plugin-float-element data-plugin-options="{'startPos': 'top', 'speed': 0.3, 'transition': true, 'transitionDuration': 1000}"></div>
                </div>

                <div class="appear-animation p-absolute" style="width: 15px; height: 15px; top: 40%; left: 2%;" data-appear-animation="expandInWithBlur" data-appear-animation-delay="900" data-appear-animation-duration="2s">
                    <div class="bg-primary opacity-4 rounded-circle w-100 h-100" data-plugin-float-element data-plugin-options="{'startPos': 'top', 'speed': 0.3, 'transition': true, 'transitionDuration': 1000}"></div>
                </div>

                <div class="overflow-hidden mb-3 opacity-7">
                    <h2 class="font-weight-semi-bold text-color-dark text-uppercase positive-ls-3 text-4-5 line-height-2 line-height-sm-7 mb-0" data-plugin-animated-words data-plugin-options="{'contentType': 'word', 'animationName': 'fadeInUpShorter', 'animationSpeed': 200, 'startDelay': 0, 'minWindowWidth': 992}">{{$home_third_section->title}}</h2>
                </div>

                <h2 class="text-color-dark font-weight-bold text-10 ls-0 pb-2 mb-3" data-plugin-animated-letters data-plugin-options="{'animationName': 'fadeInUpShorter', 'animationSpeed': 20, 'startDelay': 0, 'minWindowWidth': 992}">{{$home_third_section->sub_title}}</h2>

                <div class="appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="300">
                    <p class="font-weight-medium text-4-5 line-height-5">{{$home_third_section->description}}</p>
                </div>

               <div class="d-block appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="500">
                    <a href="{{url('/pricing')}}" data-hash data-hash-offset="0" data-hash-offset-lg="100" class="btn btn-primary border-0 font-weight-semi-bold positive-ls-1 text-uppercase text-2-5 px-5 py-3">Get Started</a>
                </div>
            </div>

        </div>

    </div>


    <section class="section section-angled bg-tertiary border-top-0">
        <div class="section-angled-layer-top bg-light"></div>
        <div class="section-angled-layer-bottom bg-light"></div>
        <div class="section-angled-content">
            <div class="container-fluid">
                <div class="row pt-5">
                    <div class="col pt-4 text-center">

                        <div class="overflow-hidden mb-3 opacity-7">
                            <h2 class="font-weight-semi-bold text-color-dark text-uppercase positive-ls-3 text-4-5 line-height-2 line-height-sm-7 mb-0" data-plugin-animated-words data-plugin-options="{'contentType': 'word', 'animationName': 'fadeInUpShorter', 'animationSpeed': 200, 'startDelay': 0, 'minWindowWidth': 992}">{{$home_fourth_section->title}}</h2>
                        </div>

                        <div class="appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="800" data-plugin-options="{'forceAnimation': true, 'accY': -100}">
                            <h2 class="text-color-dark font-weight-bold text-10 ls-0 pb-2 mb-3 pt-2 ws-nowrap" data-plugin-float-element data-plugin-options="{'forceInit': true,'startPos': '0%', 'speed': 2, 'transition': true, 'horizontal': true, 'transitionDuration': 1000, 'class': 'p-relative right-100pct'}"><span data-clone-element="50">Rank Checker Key Planner Competitor Analysis Notifications  - </span></h2>
                        </div>

                    </div>
                </div>
            </div>
            <div class="container container-xl-custom">
                <div class="row pb-5">
                    <div class="col pb-4 text-center">

                        <div class="row pt-3">
                            <div class="col-lg-4 mb-4 mb-lg-0 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">
                                <img src="{{asset('project_images'.$home_fourth_section->first_image)}}" alt="" data-icon data-plugin-options="{'onlySVG': true, 'extraClass': 'svg-stroke-color-primary mb-4'}" />
                                <h4 class="font-weight-semi-bold text-5-5 mt-4">{{$home_fourth_section->first_title}}</h4>
                            </div>
                            <div class="col-lg-4 mb-4 mb-lg-0 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400">
                                <img src="{{asset('project_images'.$home_fourth_section->second_image)}}" alt="" data-icon data-plugin-options="{'onlySVG': true, 'extraClass': 'svg-stroke-color-primary mb-4'}" />
                                <h4 class="font-weight-semi-bold text-5-5 mt-4">{{$home_fourth_section->second_title}}</h4>
                            </div>
                            <div class="col-lg-4 mb-4 mb-lg-0 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="600">
                                <img src="{{asset('project_images'.$home_fourth_section->third_image)}}" alt="" data-icon data-plugin-options="{'onlySVG': true, 'extraClass': 'svg-stroke-color-primary mb-4'}" />
                                <h4 class="font-weight-semi-bold text-5-5 mt-4">{{$home_fourth_section->third_title}}</h4>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>



   <!-- <section id="intro" class="section section-no-border section-angled bg-light pt-0 m-0">

        <div class="section-angled-layer-bottom section-angled-layer-increase-angle bg-color-light-scale-1"></div>

        <div class="container pb-5">

            <div class="row mb-5 pb-lg-3 counters">

                <div class="col-lg-10 text-center offset-lg-1">

                    <h2 class="font-weight-bold text-9 mb-0">{{__("The perfect tools for")}}
                        <br>{{__("Beginners or Professionals")}}
                    </h2>

                    <p class="sub-title text-primary text-4 font-weight-semibold positive-ls-2 mt-2 mb-4">{{__("Your project")}} <span class="highlighted-word highlighted-word-animation-1 highlighted-word-animation-1-2 highlighted-word-animation-1 highlighted-word-animation-1-no-rotate alternative-font-4 font-weight-semibold line-height-2 pb-2">{{__("A NEW LEVEL")}}</span></p>

                    <p class="text-1rem text-color-default negative-ls-05 pt-3 pb-4 mb-5">{{__("Meet Ranking Checker, the best Google & Youtube Keyword Research with the latest Search Trends.")}}

                    </p>

                </div>

                <div class="col-sm-6 col-lg-4 offset-lg-2 counter mb-5 mb-md-0">

                    <div class="appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="750" data-appear-animation-duration="750">

                        <h3 class="font-weight-extra-bold text-14 line-height-1 mb-2" data-to="1000" data-append="+" data-plugin-options="{'accY': 50}">0</h3>

                        <label class="font-weight-semibold negative-ls-1 text-color-dark mb-0">{{__("Included keywords")}}</label>

                        <p class="text-color-grey font-weight-semibold pb-1 mb-2">{{__("KEYWORD RANK")}}</p>

                        <p class="mb-0"><a href="/pricing" data-hash data-hash-offset="0" data-hash-offset-lg="120" class="text-color-primary d-flex align-items-center justify-content-center text-4 font-weight-semibold text-decoration-none">{{__("GET STARTED")}} <i class="fas fa-long-arrow-alt-right ms-2 text-4 mb-0"></i></a></p>

                    </div>

                </div>

                <div class="col-sm-6 col-lg-4 counter divider-left-border">

                    <div class="appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="750" data-appear-animation-duration="750">

                        <h3 class="font-weight-extra-bold text-14 line-height-1 mb-2" data-to="3" data-append="K+" data-plugin-options="{'accY': 50}">0</h3>

                        <label class="font-weight-semibold negative-ls-1 text-color-dark mb-0">{{__("Using Ranking Checker")}}

                        </label>

                        <p class="text-color-grey font-weight-semibold pb-1 mb-2">3K+ {{__("IN ALL USERS")}}</p>

                        <p class="mb-0"><a href="/pricing" class="text-color-primary d-flex align-items-center justify-content-center text-4 font-weight-semibold text-decoration-none" target="_blank">{{__("GET STARTED")}} <i class="fas fa-long-arrow-alt-right ms-2 text-4 mb-0"></i></a></p>

                    </div>

                </div>

            </div>

        </div>

    </section>-->





    <section class="section section-no-border pb-0 m-0 pt-0">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-sm-12">
                    <h2 class="font-weight-bold text-center text-10 pt-3 mb-4">{{__("SERP Checker For  + Desktop & Mobile")}}</h2>
                </div>
                <div class="col-lg-8 offset-lg-2 px-lg-0 text-center">
                    <p class="text-4">{{__("The SERPs determine how your site appears on the first page of Google. For example, let's say you put your site on the first page of Google for the keyword 'how to start a website'. That's great...until you see SERP functions push the #1 result way below the fold.")}}</p>
                </div>
            </div>

            <div class="image-wrapper position-relative z-index-3 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="500" data-appear-animation-duration="750" style="height: 0; padding-bottom: 16%;">

                <img src="{{asset('front_assets/images/blog-banner.jpg')}}" data-src="{{asset('front_assets/images/blog-banner.jpg')}}" class="lazyload img-fluid" alt="Connect to apps you need.plate">

            </div>

        </div>

    </section>



    <section class="section section-no-border section-angled section-dark pb-0 m-0" style="margin-top:200px !important; background-repeat: no-repeat; background-color: #0169fe !important;" data-src="{{asset('front_assets/img/landing/reason_bg.png')}}">

        <div class="section-angled-layer-top section-angled-layer-increase-angle bg-color-light-scale-1" style="padding: 4rem 0;"></div>

        <div class="spacer py-md-4 my-md-5"></div>

        <div class="container pt-5 mt-5">

            <div class="row align-items-center pt-md-5">
               <!--  <h2 class="font-weight-bold text-center text-10 pt-3 mb-1">{{__("Why Choose US?")}}</h2>
               <!-- <h2 class="text-6 line-height-2 mb-2 text-center">{{__("With SERPSEOTOOLS, your satisfaction is guaranteed.")}}</h2>

            </div>

            <div class="row justify-content-center mt-md-3 mb-4 pt-lg-4">

                <div class="col-lg-11">

                    <div class="row justify-content-center">

                        @php
                        $i = 1;
                        @endphp
                        @foreach ($satisfaction_reasons as $reason)

                        <div class="col-10 col-sm-6 col-lg-4 image-box appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="600" data-appear-animation-duration="750">

                            <img class="lazyload" style="width: 325px; height:183px;" alt="Speed Performance" src="{{asset('project_images'.$reason->banner)}}" data-src="{{asset('project_images'.$reason->banner)}}">

                            <div class="d-flex align-items-center mb-2">

                                <span class="text-color-dark font-weight-extra-bold text-12 me-2 line-height-1">
                                    @php
                                    echo $i;
                                    $i++;
                                    @endphp
                                </span>

                                <h4 class="d-flex flex-column font-weight-bold text-5 mb-0"><small class="font-weight-semibold positive-ls-2 line-height-1">{{$reason->first_heading}}</small>{{$reason->second_heading}}

                                </h4>

                            </div>

                            <p class="pe-5 custom-text-color-1">{{$reason->detail}}</p>

                        </div>

                        @endforeach

                    </div>

                </div>

            </div>     --> 

        </div>
    </section>

    <div class="container container-xl-custom">
        <div class="row">
            <div class="col py-3 text-center">

                <div class="overflow-hidden mb-3 opacity-7">
                    <h2 class="font-weight-semi-bold text-color-dark text-uppercase positive-ls-3 text-4-5 line-height-2 line-height-sm-7 mb-0" data-plugin-animated-words data-plugin-options="{'contentType': 'word', 'animationName': 'fadeInUpShorter', 'animationSpeed': 200, 'startDelay': 0, 'minWindowWidth': 0}">Pricing Tables</h2>
                </div>
                <h2 class="text-color-dark font-weight-bold text-10 ls-0 pb-2 mb-3 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">Our Plans & Prices</h2> <strong><h6><h1>Yearly Save ~20 %</h1></h6></strong>

                <div class="row mb-5">
                    <div class="col text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="text-3 p-relative top-1">Monthly</div>
                            <div class="px-2">
                                <div class="form-check form-switch form-switch-md mb-0">
                                    <input data-content-switcher data-content-switcher-content-id="pricingTable2" type="checkbox" class="form-check-input">
                                </div>
                            </div>
                            <div class="text-3 p-relative top-1">Yearly</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4" style="position: relative;">
                    <div class="content-switcher active" data-content-switcher-id="pricingTable2" data-content-switcher-rel="1">
                        <div class="row">
                            @php
                            $i = 1;
                            @endphp
                            @foreach ($packages as $package)
                            @if($package->subscription == "yearly")
                            @php
                            $class = '';
                            if($i == 2) {
                            $class = 'text-color-primary';
                            }
                            @endphp
                            <div class="col-lg-4 mb-4 mb-lg-0 mt-4">
                                <div class="card border-0 border-radius-2 bg-color-light box-shadow-6 anim-hover-translate-top-10px transition-3ms">
                                    <div class="card-body py-5">

                                        <div class="pricing-block">
                                            <div class="text-center">
                                                <h4 class="font-weight-bold {{$class}}">{{$package->title}}</h4>
                                                <div class="plan-price bg-transparent mb-4">
                                                    <span class="price {{$class}}"><span class="price-unit">{!!Session::get('currency')!!}</span>{{$package->price}}</span>
                                                    <label class="price-label">{{__("Yearly")}}</label>
                                                </div>
                                            </div>

                                            <ul class="list list-icons list-icons-style-3 <?php if ($i == 2) {
                                                                                                echo 'list-primary';
                                                                                            } else {
                                                                                                echo 'list-dark';
                                                                                            }  ?> list-icons-sm ms-3 text-start">
                                                <li><i class="fas fa-check"></i> {{__("Keywords Workload Limit")}} {{$package->keywords_limit}} / daily</li>
                                                <li><i class="fas fa-check"></i> {{__("Per Domain Keywords Limit")}} {{$package->domain_keyword_limit}}</li>
                                                <li><i class="fas fa-check"></i> {{__("Domains Limit")}} {{$package->domain_backlinks_limit}}</li>
                                                <li><i class="fas fa-check"></i> {{__("Keywords Planner Limit")}} {{$package->keywords_planner_limit}} / monthly</li>
                                                @if($package->backlinks == 1)
                                                <li><i class="fas fa-check"></i> {{__("Backlinks Workload Limit")}} {{$package->backlinks_workload_limit}} / monthly</li>
                                                <li><i class="fas fa-check"></i> {{__("Per Backlinks Domains Limit")}} {{$package->domain_actual_backlink_limit}}</li>
                                                <li><i class="fas fa-check"></i> {{__("Per Domain Backlinks Rows Limit")}} {{$package->domain_backlinks_rows_limit}}</li>
                                                @endif
                                                @if($package->competitors == 1)
                                                <li><i class="fas fa-check"></i> {{__("Per Domain Competitors Limit")}} {{$package->domain_competitors_limit}}</li>
                                                @endif
                                                @if($package->keywords_api == 1)
                                                <li><i class="fas fa-check"></i> {{__("Search Volume Limit")}} {{$package->search_volume_limit}}</li>
                                                @endif
                                                @if($package->serp_api == 1)
                                                <li><i class="fas fa-check"></i> {{__("SERP Limit")}} {{$package->serp_limit}}</li>
                                                @endif
                                                <li><i class="fas fa-check"></i> {{__("Real-Time Notifications")}}</li>
                                            </ul>

                                            <div class="text-center mt-4 pt-2">
                                                <a href="{{url('/GetStarted/'.$package->id)}}" class="btn <?php if ($i == 2) {
                                                                                                                echo 'btn-primary';
                                                                                                            } else {
                                                                                                                echo 'btn-dark';
                                                                                                            }  ?> border-0 font-weight-semi-bold positive-ls-1 text-uppercase text-2-5 px-5 py-3">{{__("Buy Now")}}</a>




                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            @php
                            $i++;
                            @endphp
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="content-switcher" data-content-switcher-id="pricingTable2" data-content-switcher-rel="2">
                        <div class="row">
                            @php
                            $i = 1;
                            @endphp

                            @if(count($trial_package) > 0)
                            @foreach ($trial_package as $package)
                            <div class="col-lg-4 mb-4 mb-lg-0 mt-4">
                                <div class="card border-0 border-radius-2 bg-color-light box-shadow-6 anim-hover-translate-top-10px transition-3ms">
                                    <div class="card-body py-5">

                                        <div class="pricing-block">
                                            <div class="text-center">
                                                <h4 class="font-weight-bold">{{$package->title}}</h4>
                                                <div class="plan-price bg-transparent mb-4">
                                                    <span class="price"><span class="price-unit"></span>{{__("TRIAL")}}</span>
                                                    <label class="price-label">{{__("FOR")}} {{$package->subscription}} {{__("Days")}}</label>
                                                </div>
                                            </div>

                                            <ul class="list list-icons list-icons-style-3 list-dark list-icons-sm ms-3 text-start">
                                                <li><i class="fas fa-check"></i> {{__("Keywords Workload Limit")}} {{$package->keywords_limit}} / daily</li>
                                                <li><i class="fas fa-check"></i> {{__("Per Domain Keywords Limit")}} {{$package->domain_keyword_limit}}</li>
                                                <li><i class="fas fa-check"></i> {{__("Domains Limit")}} {{$package->domain_backlinks_limit}}</li>
                                                <li><i class="fas fa-check"></i> {{__("Keywords Planner Limit")}} {{$package->keywords_planner_limit}} / monthly</li>
                                                @if($package->competitors == 1)
                                                <li><i class="fas fa-check"></i> {{__("Per Domain Competitors Limit")}} {{$package->domain_competitors_limit}}</li>
                                                @endif
                                                @if($package->backlinks == 1)
                                                <li><i class="fas fa-check"></i> {{__("Backlinks Workload Limit")}} {{$package->backlinks_workload_limit}} / monthly</li>
                                                <li><i class="fas fa-check"></i> {{__("Per Domains Backlinks Limit")}} {{$package->domain_actual_backlink_limit}}</li>
                                                <li><i class="fas fa-check"></i> {{__("Per Domain Backlinks Rows Limit")}} {{$package->domain_backlinks_rows_limit}}</li>
                                                @endif
                                                @if($package->keywords_api == 1)
                                                <li><i class="fas fa-check"></i> {{__("Search Volume Limit")}} {{$package->search_volume_limit}}</li>
                                                @endif
                                                @if($package->serp_api == 1)
                                                <li><i class="fas fa-check"></i> {{__("SERP Limit")}} {{$package->serp_limit}}</li>
                                                @endif
                                                <li><i class="fas fa-check"></i> {{__("Real-Time Notifications")}}</li>
                                            </ul>

                                            <div class="text-center mt-4 pt-2">
                                                <a href="{{url('/GetStarted/'.$package->id)}}" class="btn btn-dark border-0 font-weight-semi-bold positive-ls-1 text-uppercase text-2-5 px-5 py-3">{{__("Buy Now")}}</a>




                                          </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                            @endif
                            @foreach ($packages as $package)
                            @if($package->subscription == "monthly")
                            @php
                            $class = '';
                            if($i == 2) {
                            $class = 'text-color-primary';
                            }
                            @endphp
                            <div class="col-lg-4 mb-4 mb-lg-0 mt-4">
                                <div class="card border-0 border-radius-2 bg-color-light box-shadow-6 anim-hover-translate-top-10px transition-3ms">
                                    <div class="card-body py-5">
                                        <div class="pricing-block">
                                            <div class="text-center">
                                                <h4 class="font-weight-bold {{$class}}">{{$package->title}}</h4>
                                                <div class="plan-price bg-transparent mb-4">
                                                    <span class="price {{$class}}"><span class="price-unit">{!!Session::get('currency')!!}</span>{{$package->price}}</span>
                                                    <label class="price-label">{{__("PER MONTH")}}</label>
                                                </div>
                                            </div>

                                            <ul class="list list-icons list-icons-style-3 <?php if ($i == 2) {
                                                                                                echo 'list-primary';
                                                                                            } else {
                                                                                                echo 'list-dark';
                                                                                            }  ?> list-icons-sm ms-3 text-start">
                                                <li><i class="fas fa-check"></i> {{__("Keywords Workload Limit")}} {{$package->keywords_limit}} / daily</li>
                                                <li><i class="fas fa-check"></i> {{__("Per Domain Keywords Limit")}} {{$package->domain_keyword_limit}}</li>
                                                <li><i class="fas fa-check"></i> {{__("Domains Limit")}} {{$package->domain_backlinks_limit}}</li>
                                                <li><i class="fas fa-check"></i> {{__("Keywords Planner Limit")}} {{$package->keywords_planner_limit}} / monthly</li>
                                                @if($package->competitors == 1)
                                                <li><i class="fas fa-check"></i> {{__("Per Domain Competitors Limit")}} {{$package->domain_competitors_limit}}</li>
                                                @endif
                                                @if($package->backlinks == 1)
                                                <li><i class="fas fa-check"></i> {{__("Backlinks Workload Limit")}} {{$package->backlinks_workload_limit}} / monthly</li>
                                                <li><i class="fas fa-check"></i> {{__("Per Domains Domains Limit")}} {{$package->domain_actual_backlink_limit}}</li>
                                                <li><i class="fas fa-check"></i> {{__("Per Domain Backlinks Rows Limit")}} {{$package->domain_backlinks_rows_limit}}</li>
                                                @endif
                                                @if($package->keywords_api == 1)
                                                <li><i class="fas fa-check"></i> {{__("Search Volume Limit")}} {{$package->search_volume_limit}}</li>
                                                @endif
                                                @if($package->serp_api == 1)
                                                <li><i class="fas fa-check"></i> {{__("SERP Limit")}} {{$package->serp_limit}}</li>
                                                @endif
                                                <li><i class="fas fa-check"></i> {{__("Real-Time Notifications")}}</li>
                                            </ul>

                                            <div class="text-center mt-4 pt-2">
                                                <a href="{{url('/GetStarted/'.$package->id)}}" class="btn <?php if ($i == 2) {
                                                                                                                echo 'btn-primary';
                                                                                                            } else {
                                                                                                                echo 'btn-dark';
                                                                                                            }  ?> border-0 font-weight-semi-bold positive-ls-1 text-uppercase text-2-5 px-5 py-3">{{__("Buy Now")}}</a>

                                              <!-- <br><br><a href="{{url('/trial/GetStarted/')}}">Try 7 Days</a>-->


                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            @php
                            $i++;
                            @endphp
                            @endif
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <section class="section section-angled bg-dark border-top-0 m-0">
        <div class="section-angled-content p-relative z-index-2">

            <div class="container container-xl-custom">
                <div class="row pt-5">
                    <div class="col pt-4 text-center">

                        <div class="overflow-hidden mb-3 opacity-7">
                            <h2 class="font-weight-semi-bold text-color-light text-uppercase positive-ls-3 text-4-5 line-height-2 line-height-sm-7 mb-0" data-plugin-animated-words data-plugin-options="{'contentType': 'word', 'animationName': 'fadeInUpShorter', 'animationSpeed': 200, 'startDelay': 0, 'minWindowWidth': 992}">{{('Recent Projects')}}</h2>
                        </div>
                        <h2 class="text-color-light font-weight-bold text-10 ls-0 pb-2 mb-3 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">{{__('Our Case Studies')}}</h2>
                    </div>
                </div>
            </div>

            <div class="horizontal-scroller-wrapper">
                <section class="horizontal-scroller bg-dark">
                    <div class="horizontal-scroller-scroll">
                        <div class="horizontal-scroller-images" id="horizontal-scroll">
                            @foreach($home_case_studies as $study)
                            <div class="horizontal-scroller-item col-12 col-lg-4">
                                <img class="horizontal-scroller-image img-fluid" src="{{asset('project_images'. $study->image)}}" alt="">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>

        </div>
    </section>


</div>



@include('front.layout.footer')