@extends('seo.layout.admin_master')
@section('subscription', 'active')

@section('page-header')

<h2 class="content-header-title float-start mb-0" style="font-weight: 500; color:#636363; padding-right:1rem; border-right:1px solid #D6DCE1">{{__("Subscription")}}</h2>
<div class="breadcrumb-wrapper">
    <ol class="breadcrumb" style="padding-left: 1rem !important; font-size:1rem;display:flex; flex-wrap:wrap;padding:0.3rem 0;">
        <li class="breadcrumb-item"><a href="{{url('/user/dashboard')}}">{{__("Dashboard")}}</a>
        </li>
        <li class="breadcrumb-item"><a href="{{url('/user/subscription')}}">{{__("Subscription")}}</a>
        </li>
    </ol>
</div>
@endsection

@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        
        <div class="content-body">
            <!-- Card Actions Section -->
            <section id="card-actions">
                <!-- Info table about actions -->
                <div class="row">
                    <div class="col-12">
                        @if(check_subscribed() == true)
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__("Subscription Details:")}}</h4>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li>
                                            <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                        </li>
                                        <li>
                                            <a data-action="reload"><i data-feather="rotate-cw"></i></a>
                                        </li>
                                        <li>
                                            <a data-action="close"><i data-feather="x"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered font_10">
                                                    <thead>
                                                        <tr>
                                                            <th>{{__("Title")}}</th>
                                                            <th>{{__("Details")}}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{__("Package Name")}}</td>
                                                            <td>{{$package_info->title}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__("Expiration Date and Time")}}</td>
                                                            <td>{{$user_data->expire_date}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__("Keywords Workload Limit")}}</td>
                                                            <td>{{$package_info->keywords_limit}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__("Per Domain Keywords Limit")}}</td>
                                                            <td>{{$package_info->domain_keyword_limit}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__("Domains Limit")}}</td>
                                                            <td>{{$package_info->domain_backlinks_limit}}</td>
                                                        </tr>
                                                        @if($package_info->keyword_planner == 1)
                                                        <tr>
                                                            <td>{{__("Keywords Planner Limit")}}</td>
                                                            <td>{{$package_info->keywords_planner_limit}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($package_info->competitors == 1)
                                                        <tr>
                                                            <td>{{__("Per Domain Competitors Limit")}}</td>
                                                            <td>{{$package_info->domain_competitors_limit}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($package_info->backlinks == 1)
                                                        <tr>
                                                            <td>{{__("Backlink Domain Limit")}}</td>
                                                            <td>{{$package_info->domain_actual_backlink_limit}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__("Domain Backlinks Rows Limit")}}</td>
                                                            <td>{{$package_info->domain_backlinks_rows_limit}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__("Backlinks Workload Limit")}}</td>
                                                            <td>{{$package_info->backlinks_workload_limit}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($package_info->keywords_api == 1)
                                                        <tr>
                                                            <td>{{__("Search Volume Limit")}}</td>
                                                            <td>{{$package_info->search_volume_limit}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($package_info->serp_api == 1)
                                                        <tr>
                                                            <td>{{__("SERP Limit")}}</td>
                                                            <td>{{$package_info->serp_limit}}</td>
                                                        </tr>
                                                        @endif


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card font_10">
                            <div class="card-header">
                                <h4 class="card-title">{{__("Frequency of Notifications")}}</h4>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li>
                                            <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                        </li>
                                        <li>
                                            <a data-action="reload"><i data-feather="rotate-cw"></i></a>
                                        </li>
                                        <li>
                                            <a data-action="close"><i data-feather="x"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content collapse show font_10">
                                <div class="card-body">
                                    <div class="demo-inline-spacing">
                                        <form action="{{url('/user/notification/update')}}" method="post">
                                            @php
                                            $off = "";
                                            $daily = "";
                                            $weekly = "";
                                            $monthly = "";
                                            if($user_data->notification == '0') {
                                            $off = "checked";
                                            } elseif($user_data->notification == "daily") {
                                            $daily = "checked";
                                            } elseif($user_data->notification == "weekly") {
                                            $weekly = "checked";
                                            } elseif($user_data->notification == "monthly") {
                                            $monthly = "checked";
                                            }
                                            @endphp
                                            @csrf
                                            <div class="form-check form-check-inline font_10">
                                                <input class="form-check-input" type="radio" name="frequency" value="0"
                                                    {{$off}} />
                                                <label class="form-check-label" for="inlineRadio1">{{__("Off")}}</label>
                                            </div>
                                            <div class="form-check form-check-inline font_10">
                                                <input class="form-check-input" type="radio" name="frequency"
                                                    value="daily" {{$daily}} />
                                                <label class="form-check-label" for="inlineRadio2 font_10">{{__("Daily")}}</label>
                                            </div>
                                            <div class="form-check form-check-inline font_10">
                                                <input class="form-check-input" type="radio" name="frequency"
                                                    {{$weekly}} value="weekly" />
                                                <label class="form-check-label" for="inlineRadio2">{{__("Weekly")}}</label>
                                            </div>
                                            <div class="form-check form-check-inline font_10">
                                                <input class="form-check-input" type="radio" name="frequency"
                                                    {{$monthly}} value="monthly" />
                                                <label class="form-check-label" for="inlineRadio2">{{__("Monthly")}}</label>
                                            </div>
                                            <div class="form-check form-check-inline font_10">
                                                <input class="btn btn-success" type="submit" value="{{__("Update")}}" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card font_10">
                            <div class="card-header">
                                <h4 class="card-title">{{__("Payment Method")}}</h4>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li>
                                            <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                        </li>
                                        <li>
                                            <a data-action="reload"><i data-feather="rotate-cw"></i></a>
                                        </li>
                                        <li>
                                            <a data-action="close"><i data-feather="x"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="demo-inline-spacing">
                                        @php
                                        $paypal = null;
                                        $stripe = null;
                                        if($user_data->payment_method == "paypal") {
                                        $paypal = "checked";
                                        } else if($user_data->payment_method == "stripe") {
                                        $stripe = "checked";
                                        }
                                        @endphp
                                        <form action="{{url('/user/update/payment_method')}}" method="post">
                                            @csrf
                                            @if($payment_method_data->payment_method == "both")
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    id="inlineRadio1" value="paypal" {{$paypal}} />
                                                <label class="form-check-label" for="inlineRadio1">Paypal</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    id="inlineRadio2" value="stripe" {{$stripe}} />
                                                <label class="form-check-label" for="inlineRadio2">Stripe</label>
                                            </div>
                                            @elseif ($payment_method_data->payment_method == "stripe")
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    id="inlineRadio2" value="stripe" checked />
                                                <label class="form-check-label" for="inlineRadio2">Stripe</label>
                                            </div>
                                            @else ($payment_method_data->payment_method == "paypal")
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    id="inlineRadio1" value="paypal" checked />
                                                <label class="form-check-label" for="inlineRadio1">Paypal</label>
                                            </div>
                                            @endif
                                            <div class="form-check form-check-inline">
                                                <input class="btn btn-success" type="submit" value="{{__("Change")}}" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="justify-content: center; text-align:center; margin-bottom: 30px;">
                            <a href="{{url('user/get_subscription')}}" class="btn btn-success">{{__("Extend Subscription")}}</a>
                            @php
                            $user_package_info = DB::table('packages')->where('id', $user_data->package_id)->first();
                            $packages = DB::table('packages')->where('status',
                            1)->where('price','>',$user_package_info->price)->orderBy('price', 'ASC')->get();
                            if(count($packages) > 0) {
                            @endphp
                            <a href="{{url('user/upgrade_subscription')}}" class="btn btn-primary">{{__("Upgrade Subscription")}}</a>
                            @php
                            }
                            @endphp
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#addNewCard">
                                {{__("Cancel Subscription")}}
                            </button>
                        </div>
                        @else
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__("Your subscription has expired. Subscribe to continue using our service.")}}</h4>
                            </div>
                            <div style="margin-left:20px;margin-bottom: 30px;">
                                <a href="{{url('user/get_subscription')}}" class="btn btn-success">{{__("Extend Subscription")}}</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <!--/ Info table about actions -->

            </section>
            <!--/ Card Actions Section -->

        </div>

    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<div class="modal fade" id="addNewCard" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-5">
                <h1 class="text-center mb-1" id="addNewCardTitle">{{__("Cancel Subscription?")}}</h1>
                <p class="text-center">{{__("If you have decided to unsubscribe, your entire data will be deleted on the day deleted of the package expiration date")}} ({{$user_data->expire_date}}). {{__("Remember that at any time you can can subscribe again want...")}}</p>

                <!-- form -->


                <div class="col-12 text-center">
                    <a href="{{url('/user/cancel_subscription')}}" class="btn btn-primary me-1 mt-1">Submit</a>
                    <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal"
                        aria-label="Close">
                        {{__("Cancel")}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection