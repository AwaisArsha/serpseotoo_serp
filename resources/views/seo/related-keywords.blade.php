@extends('seo.layout.admin_master')
@section('related-keywords', 'active')
@section('page-header')

<h2 class="content-header-title float-start mb-0" style="font-weight: 500; color:#636363; padding-right:1rem; border-right:1px solid #D6DCE1">{{__("New Keyword")}}</h2>
<div class="breadcrumb-wrapper">
    <ol class="breadcrumb" style="padding-left: 1rem !important; font-size:1rem;display:flex; flex-wrap:wrap;padding:0.3rem 0;">
        <li class="breadcrumb-item"><a href="{{url('/user/dashboard')}}">{{__("Dashboard")}}</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{url('/user/related-keywords')}}">{{__("New
                                         Search")}}</a>
        </li>
    </ol>
</div>
@endsection
@section('content')

@php
$package_info = UserPackageInfo();

$keywords_count = keyword_planner_keywords_count();

@endphp
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-5">
                        @if ($package_info->keywords_planner_limit == "Unlimited")
                        <div class="card" style="margin-bottom: 0px;">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">
                                        {{__("Unlimited")}}
                                    </h3>
                                    <span>{{__("Keywords Limit")}}</span>
                                </div>
                                <div class="avatar bg-light-primary p-50">
                                    <span class="avatar-content">
                                        <i data-feather="box" class="font-medium-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        @php
                        $packages = DB::table('packages')->where('status',
                        1)->where('price','>',$package_info->price)->orderBy('price', 'ASC')->get();
                        if(count($packages) > 0) {
                        @endphp
                        <div style="display:block; margin:10px;">
                            <a href="{{url('/user/upgrade_subscription')}}" class="btn btn-primary me-1 waves-effect waves-float waves-light">{{__("Upgrade Subscription")}}</a>
                        </div>
                        @php
                        }
                        @endphp

                        @else
                        <div class="card" style="margin-bottom: 0px;">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">
                                        {{$keywords_count}} / {{$package_info->keywords_planner_limit}}
                                    </h3>
                                    <span>{{__("Keywords Limit")}}</span>
                                </div>
                                <div class="avatar bg-light-primary p-50">
                                    <span class="avatar-content">
                                        <i data-feather="box" class="font-medium-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card">
                        @php
                        $package_info = UserPackageInfo();
                        @endphp
                        @if($package_info->keywords_planner_limit == "Unlimited")
                        <div class="card-body">
                            <form class="form form-horizontal" method="POST" action="{{url('/user/related-keywords/query')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">{{__("Keyword")}}</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="keyword" placeholder="{{__("Enter Keyword")}}" required>
                                            </div>
                                        </div>
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">{{__("Country")}}</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="">
                                                    <select class="select2-size-lg form-select" id="large-select2" name="location" required>
                                                        @foreach ($locations as $loc)
                                                        <option value="{{$loc->location_code}}">
                                                            {{$loc->display_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">Language</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="">
                                                    <select class="select2-size-lg form-select" id="large-select" name="language" required>
                                                        @foreach ($languages as $lan)
                                                        <option value="{{$lan->language_code}}">
                                                            {{$lan->language_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 offset-sm-3">
                                        <input type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light" value="{{__("Submit")}}">
                                    </div>
                                </div>
                            </form>
                        </div>
                        @elseif(($package_info->keywords_planner_limit - $keywords_count) > 0)
                        <div class="card-body">
                            <form class="form form-horizontal" method="POST" action="{{url('/user/related-keywords/query')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">{{__("Keyword")}}</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="keyword" placeholder="{{__("Enter Keyword")}}" required>
                                            </div>
                                        </div>
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">{{__("Country")}}</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="">
                                                    <select class="select2-size-lg form-select" id="large-select2" name="location" required>
                                                        @foreach ($locations as $loc)
                                                        <option value="{{$loc->location_code}}">
                                                            {{$loc->display_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">{{__("Language")}}</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="">
                                                    <select class="select2-size-lg form-select" id="large-select" name="language" required>
                                                        @foreach ($languages as $lan)
                                                        <option value="{{$lan->language_code}}">
                                                            {{$lan->display_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 offset-sm-3">
                                        <input type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light" value="{{__("Submit")}}">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="card-body">
                        <p>You have reached the limit of keywords. Upgrade your package to keep track of it.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->

@endsection