@include('front.layout.header')
@php
get_currency();
@endphp
<div role="main" class="main">
    <section class="page-header page-header-modern bg-color-primary p-relative">
        <div class="container container-xl-custom">
            <div class="row py-5">
                <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                    <h1 class="text-color-light font-weight-bold text-8">{{__("Prices")}}</h1>
                </div>
                <div class="col-md-4 order-1 order-md-2 align-self-center">
                    <ul class="breadcrumb d-flex justify-content-md-end text-3-5">
                        <li><a href="{{url('/')}}" class="text-color-light font-weight-semibold text-decoration-none">HOME</a></li>
                        <li class="text-color-light font-weight-semibold active">{{__("Prices")}}</li>
                    </ul>
                </div>
            </div>
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

                                                <ul class="list list-icons list-icons-style-3 <?php if($i == 2) { echo 'list-primary'; } else { echo 'list-dark'; }  ?> list-icons-sm ms-3 text-start">
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

                                               <!-- <div class="text-center mt-4 pt-2">
                                                    <a href="{{url('/GetStarted/'.$package->id)}}" class="btn <?php if($i == 2) { echo 'btn-primary'; } else { echo 'btn-dark'; }  ?> border-0 font-weight-semi-bold positive-ls-1 text-uppercase text-2-5 px-5 py-3">{{__("Buy Now")}}</a>
                                                </div>-->

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
                                                    <li><i class="fas fa-check"></i> {{__("Keywords Workload Limit")}} {{$package->keywords_limit}} / Daily</li>
                                                    <li><i class="fas fa-check"></i> {{__("Per Domain Keywords Limit")}} {{$package->domain_keyword_limit}}</li>
                                                    <li><i class="fas fa-check"></i> {{__("Domains Limit")}} {{$package->domain_backlinks_limit}}</li>
                                                    <li><i class="fas fa-check"></i> {{__("Keywords Planner Limit")}} {{$package->keywords_planner_limit}} / monthly</li>
                                                    @if($package->backlinks == 1)
                                                    <li><i class="fas fa-check"></i> {{__("Backlinks Workload Limit")}} {{$package->backlinks_workload_limit}} / monthly</li>
                                                    <li><i class="fas fa-check"></i> {{__("Per Backlinks Domains Limit")}} {{$package->domain_actual_backlink_limit}}</li>
                                                    <li><i class="fas fa-check"></i> {{__("Per Domain Backlinks Rows Limit")}} {{$package->domain_backlinks_rows_limit}}</li>
                                                    @endif
                                                    @if($package->keywords_api == 1)
                                                    <li><i class="fas fa-check"></i> {{__("Search Volume Limit")}} {{$package->search_volume_limit}}</li>
                                                    @endif
                                                    @if($package->serp_api == 1)
                                                    <li><i class="fas fa-check"></i> {{__("SERP Limit")}} {{$package->serp_limit}}</li>
                                                    @endif
                                                    @if($package->competitors == 1)
                                                    <li><i class="fas fa-check"></i> {{__("Per Domain Competitors Limit")}} {{$package->domain_competitors_limit}} / montly</li>
                                                    @endif
                                                   <li><i class="fas fa-check"></i> {{__("Real-Time Notifications")}}</li>
                                                </ul>

                                               <!-- <div class="text-center mt-4 pt-2">
                                                    <a href="{{url('/trial/GetStarted/')}}" class="btn btn-dark border-0 font-weight-semi-bold positive-ls-1 text-uppercase text-2-5 px-5 py-3">{{__("Buy Now")}}</a>
                                                </div>-->

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

                                                <ul class="list list-icons list-icons-style-3 <?php if($i == 2) { echo 'list-primary'; } else { echo 'list-dark'; }  ?> list-icons-sm ms-3 text-start">
                                                    <li><i class="fas fa-check"></i> {{__("Keywords Workload Limit")}} {{$package->keywords_limit}} / daily</li>
                                                    <li><i class="fas fa-check"></i> {{__("Per Domain Keywords Limit")}} {{$package->domain_keyword_limit}}</li>
                                                    <li><i class="fas fa-check"></i> {{__("Domains Limit")}} {{$package->domain_backlinks_limit}}</li>
                                                    <li><i class="fas fa-check"></i> {{__("Keywords Planner Limit")}} {{$package->keywords_planner_limit}} / montly</li>
                                                    @if($package->backlinks == 1)
                                                    <li><i class="fas fa-check"></i> {{__("Backlinks Workload Limit")}} {{$package->backlinks_workload_limit}} / montly</li>
                                                    <li><i class="fas fa-check"></i> {{__("Backlinks Domains Limit")}} {{$package->domain_actual_backlink_limit}} / montly</li>
                                                    <li><i class="fas fa-check"></i> {{__("Per Domain Backlinks Rows Limit")}} {{$package->domain_backlinks_rows_limit}} / montly</li>
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
                                                    <a href="{{url('/GetStarted/'.$package->id)}}" class="btn <?php if($i == 2) { echo 'btn-primary'; } else { echo 'btn-dark'; }  ?> border-0 font-weight-semi-bold positive-ls-1 text-uppercase text-2-5 px-5 py-3">{{__("Buy Now")}}</a>


                                                 <!-- <br> <br> <a href="{{url('/trial/GetStarted/')}}">Try 7 Days</a>-->


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

    </div>

</div>

@include('front.layout.footer')