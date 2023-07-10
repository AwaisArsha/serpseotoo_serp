@extends('seo.layout.admin_master')
@section('traffic-analytics', 'active')
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{__("Traffic Query")}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/user/dashboard')}}">{{__("Dashboard")}}</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="{{url('/user/search-traffic')}}">{{__("New Traffic Query")}}</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card">

                        <div class="card-body">
                            <form class="form form-horizontal" method="POST" action="{{url('/user/traffic/query')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">{{__("Enter Target Domain")}}</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="link" class="form-control" name="target"
                                                    placeholder="{{__("Enter Target Domain")}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 offset-sm-3">
                                        <input type="submit"
                                            class="btn btn-primary me-1 waves-effect waves-float waves-light"
                                            value="Submit">
                                        <a type="reset" href="{{url('/user/dashboard')}}"
                                            class="btn btn-outline-secondary waves-effect">{{__("Cancel")}}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->

@endsection