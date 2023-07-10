@extends('admin.layout.admin_master')
@section('packages', 'active')
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Packages Data</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin/packages')}}">Packages</a>
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
                            <form class="form form-horizontal" method="POST" action="{{url('/admin/package/save/')}}">
                                @csrf
                                <input type="hidden" name="id" value="{{$package->id}}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">Title</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="title" placeholder="Title"
                                                    required value="{{$package->title}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Subscription</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="select2 form-select" id="select2-basic"
                                                    name="subscription">
                                                    @php
                                                    $monthly_selected = "";
                                                    $yealry_selected = "";
                                                    if($package->subscription == "monthly") {
                                                    $monthly_selected = "selected";
                                                    } elseif($package->subscription == "yearly") {
                                                    $yealry_selected = "selected";
                                                    }
                                                    @endphp
                                                    <option value="monthly" {{$monthly_selected}}>Monthly</option>
                                                    <option value="yearly" {{$yealry_selected}}>Yearly</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Price</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="price" placeholder="Price"
                                                    required value="{{$package->price}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Track Keywords
                                                    Limit</label>
                                            </div>
                                            <div class="col-sm-9">

                                                @php
                                                $keywords_limit_checked = null;
                                                if($package->keywords_limit == "Unlimited") {
                                                $keywords_limit_checked = "checked";
                                                }
                                                @endphp
                                                <input class="form-check-input" type="checkbox"
                                                    id="keywords_limit_checkbox" name="keywords_limit_checkbox"
                                                    {{$keywords_limit_checked}}>
                                                <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                                @php
                                                $readonly = null;
                                                if($keywords_limit_checked !=null) {
                                                $readonly = "readonly";
                                                }
                                                @endphp
                                                @if($readonly != null)
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Track Keywords Limit" name="keywords_limit"
                                                    id="keywords_limit" required readonly>
                                                @else
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Track Keywords Limit" name="keywords_limit"
                                                    id="keywords_limit" value="{{$package->keywords_limit}}" required>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Per Domain Keywords
                                                    Limit</label>
                                            </div>
                                            <div class="col-sm-9">
                                                @php
                                                $domain_keyword_limit_checked = null;
                                                if($package->domain_keyword_limit == "Unlimited") {
                                                $domain_keyword_limit_checked = "checked";
                                                }
                                                @endphp
                                                <input class="form-check-input" type="checkbox"
                                                    id="domain_keyword_limit_checkbox"
                                                    name="domain_keyword_limit_checkbox"
                                                    {{$domain_keyword_limit_checked}}>
                                                <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                                @php
                                                $readonly = null;
                                                if($domain_keyword_limit_checked !=null) {
                                                $readonly = "readonly";
                                                }
                                                @endphp
                                                @if($readonly != null)

                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Per Domain Keywords Limit" name="domain_keyword_limit"
                                                    id="domain_keyword_limit" required readonly>
                                                @else
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Per Domain Keywords Limit" name="domain_keyword_limit"
                                                    id="domain_keyword_limit" required
                                                    value="{{$package->domain_keyword_limit}}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Domains
                                                    Limit</label>
                                            </div>
                                            <div class="col-sm-9">
                                                @php
                                                $domain_backlinks_limit_checked = null;
                                                if($package->domain_backlinks_limit == "Unlimited") {
                                                $domain_backlinks_limit_checked = "checked";
                                                }
                                                @endphp
                                                <input class="form-check-input" type="checkbox"
                                                    id="domain_backlinks_limit_checkbox"
                                                    name="domain_backlinks_limit_checkbox"
                                                    {{$domain_backlinks_limit_checked}}>
                                                <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                                @php
                                                $readonly = null;
                                                if($domain_backlinks_limit_checked !=null) {
                                                $readonly = "readonly";
                                                }
                                                @endphp

                                                @if($readonly != null)
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Domain Backlinks Limit" name="domain_backlinks_limit" id="domain_backlinks_limit"
                                                    required {{$readonly}}>
                                                @else
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Domain Backlinks Limit" id="domain_backlinks_limit" name="domain_backlinks_limit"
                                                    required value="{{$package->domain_backlinks_limit}}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Keywords Planner
                                                    Limit</label>
                                            </div>
                                            <div class="col-sm-9">
                                                @php
                                                $keywords_planner_limit_checked = null;
                                                if($package->keywords_planner_limit == "Unlimited") {
                                                $keywords_planner_limit_checked = "checked";
                                                }
                                                @endphp
                                                <input class="form-check-input" type="checkbox"
                                                    id="keywords_planner_checkbox"
                                                    name="keywords_planner_checkbox"
                                                    {{$keywords_planner_limit_checked}}>
                                                <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                                @php
                                                $readonly = null;
                                                if($keywords_planner_limit_checked !=null) {
                                                $readonly = "readonly";
                                                }
                                                @endphp
                                                @if($readonly != null)

                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Keywords Planner Limit" name="keywords_planner_limit"
                                                    id="keywords_planner_limit" required readonly>
                                                @else
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Keywords Planner Limit" name="keywords_planner_limit"
                                                    id="keywords_planner_limit" required
                                                    value="{{$package->keywords_planner_limit}}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Per Domain Competitors
                                                    Limit</label>
                                            </div>
                                            <div class="col-sm-9">
                                                @php
                                                $domain_competitors_limit_checked = null;
                                                if($package->domain_competitors_limit == "Unlimited") {
                                                $domain_competitors_limit_checked = "checked";
                                                }
                                                @endphp
                                                <input class="form-check-input" type="checkbox"
                                                    id="domain_competitors_limit_checkbox"
                                                    name="domain_competitors_limit_checkbox"
                                                    {{$domain_competitors_limit_checked}}>
                                                <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                                @php
                                                $readonly = null;
                                                if($domain_competitors_limit_checked !=null) {
                                                $readonly = "readonly";
                                                }
                                                @endphp
                                                @if($readonly != null)
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Per Domain Competitors Limit"
                                                    name="domain_competitors_limit" id="domain_competitors_limit"
                                                    {{$readonly}} required>
                                                @else
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Per Domain Competitors Limit"
                                                    name="domain_competitors_limit" required
                                                    id="domain_competitors_limit"
                                                    value="{{$package->domain_competitors_limit}}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Backlink Domain
                                                    Limit</label>
                                            </div>
                                            <div class="col-sm-9">
                                                @php
                                                $domain_actual_backlink_limit_checked = null;
                                                if($package->domain_actual_backlink_limit == "Unlimited") {
                                                $domain_actual_backlink_limit_checked = "checked";
                                                }
                                                @endphp
                                                <input class="form-check-input" type="checkbox"
                                                    id="domain_actual_backlink_limit_checkbox"
                                                    name="domain_actual_backlink_limit_checkbox"
                                                    {{$domain_actual_backlink_limit_checked}}>
                                                <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                                @php
                                                $readonly = null;
                                                if($domain_actual_backlink_limit_checked !=null) {
                                                $readonly = "readonly";
                                                }
                                                @endphp

                                                @if($readonly != null)
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Domain Backlinks Limit" name="domain_actual_backlink_limit" id="domain_actual_backlink_limit"
                                                    required {{$readonly}}>
                                                @else
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Domain Backlinks Limit" id="domain_actual_backlink_limit" name="domain_actual_backlink_limit"
                                                    required value="{{$package->domain_actual_backlink_limit}}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Per Domain Backlinks Rows
                                                    Limit</label>
                                            </div>
                                            <div class="col-sm-9">
                                            @php
                                                $domain_backlinks_rows_limit_checked = null;
                                                if($package->domain_backlinks_rows_limit == "Unlimited") {
                                                $domain_backlinks_rows_limit_checked = "checked";
                                                }
                                                @endphp
                                                <input class="form-check-input" type="checkbox"
                                                    id="domain_backlinks_rows_limit_checkbox"
                                                    name="domain_backlinks_rows_limit_checkbox"
                                                    {{$domain_backlinks_rows_limit_checked}}>
                                                <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                                @php
                                                $readonly = null;
                                                if($domain_backlinks_rows_limit_checked !=null) {
                                                $readonly = "readonly";
                                                }
                                                @endphp

                                                @if($readonly != null)
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Per Domain Backlinks Limit"
                                                    name="domain_backlinks_rows_limit" id="domain_backlinks_rows_limit" required {{$readonly}}>
                                                    @else
                                                    <input type="number" class="form-control dt-post"
                                                    placeholder="Per Domain Backlinks Limit"
                                                    name="domain_backlinks_rows_limit" id="domain_backlinks_rows_limit" required
                                                    value="{{$package->domain_backlinks_rows_limit}}">
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Backlinks Workload
                                                    Limit</label>
                                            </div>
                                            <div class="col-sm-9">
                                            @php
                                                $backlinks_backlinks_limit_checked = null;
                                                if($package->backlinks_workload_limit == "Unlimited") {
                                                $backlinks_backlinks_limit_checked = "checked";
                                                }
                                                @endphp
                                                <input class="form-check-input" type="checkbox"
                                                    id="backlinks_workload_limit_checkbox"
                                                    name="backlinks_workload_limit_checkbox"
                                                    {{$backlinks_backlinks_limit_checked}}>
                                                <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                                @php
                                                $readonly = null;
                                                if($backlinks_backlinks_limit_checked !=null) {
                                                $readonly = "readonly";
                                                }
                                                @endphp

                                                @if($readonly != null)
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Per Domain Backlinks Limit"
                                                    name="backlinks_workload_limit" id="backlinks_workload_limit" required {{$readonly}}>
                                                    @else
                                                    <input type="number" class="form-control dt-post"
                                                    placeholder="Per Domain Backlinks Limit"
                                                    name="backlinks_workload_limit" id="backlinks_workload_limit" required
                                                    value="{{$package->backlinks_workload_limit}}">
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Search Volume Limit</label>
                                            </div>
                                            <div class="col-sm-9">
                                            @php
                                                $search_volume_limit_checked = null;
                                                if($package->search_volume_limit == "Unlimited") {
                                                $search_volume_limit_checked = "checked";
                                                }
                                                @endphp
                                                <input class="form-check-input" type="checkbox"
                                                    id="search_volume_limit_checkbox"
                                                    name="search_volume_limit_checkbox"
                                                    {{$search_volume_limit_checked}}>
                                                <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                                @php
                                                $readonly = null;
                                                if($search_volume_limit_checked !=null) {
                                                $readonly = "readonly";
                                                }
                                                @endphp

                                                @if($readonly != null)
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Per Domain Backlinks Limit"
                                                    name="search_volume_limit" id="search_volume_limit" required {{$readonly}}>
                                                    @else
                                                    <input type="number" class="form-control dt-post"
                                                    placeholder="Per Domain Backlinks Limit"
                                                    name="search_volume_limit" id="search_volume_limit" required
                                                    value="{{$package->search_volume_limit}}">
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">SERP Limit</label>
                                            </div>
                                            <div class="col-sm-9">
                                            @php
                                                $serp_limit_checked = null;
                                                if($package->serp_limit == "Unlimited") {
                                                $serp_limit_checked = "checked";
                                                }
                                                @endphp
                                                <input class="form-check-input" type="checkbox"
                                                    id="serp_limit_checkbox"
                                                    name="serp_limit_checkbox"
                                                    {{$serp_limit_checked}}>
                                                <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                                @php
                                                $readonly = null;
                                                if($serp_limit_checked !=null) {
                                                $readonly = "readonly";
                                                }
                                                @endphp

                                                @if($readonly != null)
                                                <input type="number" class="form-control dt-post"
                                                    placeholder="Per Domain Backlinks Limit"
                                                    name="serp_limit" id="serp_limit" required {{$readonly}}>
                                                    @else
                                                    <input type="number" class="form-control dt-post"
                                                    placeholder="Per Domain Backlinks Limit"
                                                    name="serp_limit" id="serp_limit" required
                                                    value="{{$package->serp_limit}}">
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Competitors Access</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="select2 form-select" id="select2-basic"
                                                    name="competitors">
                                                    @php
                                                    $enable_selected = "";
                                                    $disable_selected = "";
                                                    if($package->competitors == 1) {
                                                    $enable_selected = "selected";
                                                    } elseif($package->competitors == 0) {
                                                    $disable_selected = "selected";
                                                    }
                                                    @endphp
                                                    <option value="1" {{$enable_selected}}>Enable</option>
                                                    <option value="0" {{$disable_selected}}>Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Keyword Planner
                                                    Access</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="select2 form-select" id="select2-basic"
                                                    name="keyword_planner">
                                                    @php
                                                    $enable_selected = "";
                                                    $disable_selected = "";
                                                    if($package->keyword_planner == 1) {
                                                    $enable_selected = "selected";
                                                    } elseif($package->keyword_planner == 0) {
                                                    $disable_selected = "selected";
                                                    }
                                                    @endphp
                                                    <option value="1" {{$enable_selected}}>Enable</option>
                                                    <option value="0" {{$disable_selected}}>Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Backlinks Access</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="select2 form-select" id="select2-basic" name="backlinks">
                                                    @php
                                                    $enable_selected = "";
                                                    $disable_selected = "";
                                                    if($package->backlinks == 1) {
                                                    $enable_selected = "selected";
                                                    } elseif($package->backlinks == 0) {
                                                    $disable_selected = "selected";
                                                    }
                                                    @endphp
                                                    <option value="1" {{$enable_selected}}>Enable</option>
                                                    <option value="0" {{$disable_selected}}>Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">SERP API Access</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="select2 form-select" id="select2-basic" name="serp_api">
                                                    @php
                                                    $enable_selected = "";
                                                    $disable_selected = "";
                                                    if($package->serp_api == 1) {
                                                    $enable_selected = "selected";
                                                    } elseif($package->serp_api == 0) {
                                                    $disable_selected = "selected";
                                                    }
                                                    @endphp
                                                    <option value="1" {{$enable_selected}}>Enable</option>
                                                    <option value="0" {{$disable_selected}}>Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">DataForSeo Labs API Access</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="select2 form-select" id="select2-basic"
                                                    name="keywords_api">
                                                    @php
                                                    $enable_selected = "";
                                                    $disable_selected = "";
                                                    if($package->keywords_api == 1) {
                                                    $enable_selected = "selected";
                                                    } elseif($package->keywords_api == 0) {
                                                    $disable_selected = "selected";
                                                    }
                                                    @endphp
                                                    <option value="1" {{$enable_selected}}>Enable</option>
                                                    <option value="0" {{$disable_selected}}>Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 offset-sm-3">
                                        <input type="submit"
                                            class="btn btn-primary me-1 waves-effect waves-float waves-light"
                                            value="Save">
                                        <a type="reset" href="{{url('/admin/packages')}}"
                                            class="btn btn-outline-secondary waves-effect">Cancel</a>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script type="text/javascript">
$(document).ready(function() {
    $('#keywords_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("keywords_limit").readOnly = true;
        } else {
            document.getElementById("keywords_limit").readOnly = false;
        }
    });

    $('#domain_keyword_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("domain_keyword_limit").readOnly = true;
        } else {
            document.getElementById("domain_keyword_limit").readOnly = false;
        }
    });

    $('#domain_competitors_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("domain_competitors_limit").readOnly = true;
        } else {
            document.getElementById("domain_competitors_limit").readOnly = false;
        }
    });

    $('#domain_backlinks_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("domain_backlinks_limit").readOnly = true;
        } else {
            document.getElementById("domain_backlinks_limit").readOnly = false;
        }
    });
    
    $('#domain_actual_backlink_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("domain_actual_backlink_limit").readOnly = true;
        } else {
            document.getElementById("domain_actual_backlink_limit").readOnly = false;
        }
    });

    $('#domain_backlinks_rows_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("domain_backlinks_rows_limit").readOnly = true;
        } else {
            document.getElementById("domain_backlinks_rows_limit").readOnly = false;
        }
    });
    
    $('#backlinks_workload_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("backlinks_workload_limit").readOnly = true;
        } else {
            document.getElementById("backlinks_workload_limit").readOnly = false;
        }
    });
    
    $('#search_volume_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("search_volume_limit").readOnly = true;
        } else {
            document.getElementById("search_volume_limit").readOnly = false;
        }
    });
    
    $('#serp_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("serp_limit").readOnly = true;
        } else {
            document.getElementById("serp_limit").readOnly = false;
        }
    });

    
    $('#keywords_planner_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("keywords_planner_limit").readOnly = true;
        } else {
            document.getElementById("keywords_planner_limit").readOnly = false;
        }
    });

});
</script>

@endsection