@extends('seo.layout.admin_master')
@section('search-volume', 'active')
@section('content')

<!-- BEGIN: Content-->
@php
$volume_keywords_count = volume_keywords_count(); 
$package_info = UserPackageInfo();
@endphp
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{__("Search Volume")}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/user/dashboard')}}">{{__("Dashboard")}}</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="{{url('/user/search-volume')}}"> {{__("New Search")}}
                                        Volume Query</a>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="col-5 pt-2">

                        @if ($package_info->search_volume_limit == "Unlimited")
                        <div class="card" style="margin-bottom: 0px;">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">
                                        {{__("Unlimited")}}</h3>
                                    <span>{{__("Keywords Limit")}}</span>
                                </div>
                                <div class="avatar bg-light-primary p-50">
                                    <span class="avatar-content">
                                        <i data-feather="box" class="font-medium-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="card" style="margin-bottom: 0px;">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">
                                        {{$volume_keywords_count}} / {{$package_info->search_volume_limit}}</h3>
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
                        @if($package_info->search_volume_limit > $volume_keywords_count)
                        <div class="card-body">
                            <form class="form form-horizontal" method="POST" action="{{url('/user/volume/query')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">{{__("Keyword")}}</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="keyword"
                                                    placeholder="{{__("Enter Keyword")}}" required>
                                            </div>
                                        </div>

                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">{{__("Country")}}</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="mb-1">
                                                    <select class="select2-size-lg form-select" id="large-select"
                                                        name="location_code" required>
                                                        @foreach ($locations as $loc)
                                                        <option value="{{$loc->location_code}}">
                                                            {{$loc->display_name}}</option>
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
                                                <div class="mb-1">
                                                    <select class="select2-size-lg form-select" id="large-select"
                                                        name="language_code" required>
                                                        @foreach ($languages as $lan)
                                                        <option value="{{$lan->language_code}}">
                                                            {{$lan->display_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 offset-sm-3">
                                        <input type="submit"
                                            class="btn btn-primary me-1 waves-effect waves-float waves-light"
                                            value="Submit">
                                        <a type="reset" href="{{url('/admin/learn')}}"
                                            class="btn btn-outline-secondary waves-effect">{{__("Cancel")}}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @else
                        <div class="card-body">
                            <p>{{__("You have reached limit of keywords. Please upgrade your package to keep on tracking.")}}</p>
                            <a href="{{url('/pricing')}}" class="btn btn-warning">{{__("Upgrade Package")}}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->

@endsection