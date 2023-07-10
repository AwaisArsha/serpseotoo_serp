@extends('seo.layout.admin_master')
@section('competitors', 'active')
@section('page-header')
<div class="bookmark-wrapper d-flex align-items-center">
    <h2 class="card-title mb-50 mb-sm-0">{{__("Detail of Competitors")}}</h2>
    <form action="{{url('/user/competitors/search')}}" style="margin-left: 20px;" method="get">
    @csrf&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
{{__("Search")}} <input type="text" name="s" style="padding:0.571rem 1rem; font-size:1rem; font-weight:400;color:#6e6b7b; background-color: #fff; border-radius:0.357rem; border: 1px solid #d8d6de">
        <input type="submit" value=search style="margin-left: 10px;" class="btn btn-primary">
    </form>
</div>
@endsection
@section('content')

@php
$package_info = UserPackageInfo();
@endphp

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        <div class="content-header row"></div>

        <div class="content-body">
            <!-- users list start -->
            <section class="app-user-list">

        <!-- Basic table -->
        @if(count($domain_data) > 0)
        @foreach ($domain_data as $domain)
        <section id="basic-datatable" class="font_10">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="card-header border-bottom p-1">
                                <label
                                    style="text-align: left;font-weight: normal;"><strong>{{$domain->domain}}</strong>
                                </label>
                                @php
                                $all_competitors = DB::table('competitors')->where('domain_id', $domain->id)->get();
                                @endphp
                                @if($package_info->domain_competitors_limit == "Unlimited")
                                <div class="dt-action-buttons text-end">
                                    <div class="dt-buttons d-inline-flex">
                                        <button class="dt-button create-new btn btn-primary" tabindex="0"
                                            onclick="domain_id(<?php echo $domain->id; ?>)"
                                            aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                            data-bs-target="#editUser"><span>{{__("New Add Competitor")}}</span></button>
                                    </div>
                                </div>
                                @elseif(count($all_competitors) < $package_info->domain_competitors_limit)
                                    <div class="dt-action-buttons text-end">
                                        <div class="dt-buttons d-inline-flex">
                                            <button class="dt-button create-new btn btn-primary" tabindex="0"
                                                onclick="domain_id(<?php echo $domain->id; ?>)"
                                                aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                                data-bs-target="#editUser"><span>{{__("New Add Competitor")}}</span></button>
                                        </div>
                                    </div>
                                    @else
                                    <div class="dt-action-buttons text-end">
                                        <div class="dt-buttons d-inline-flex">
                                            <a href="{{url('/pricing')}}"
                                                class="dt-button create-new btn btn-success"><span>{{__("Upgrade Package")}}</span></a>
                                        </div>
                                    </div>
                                    @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" class="font_10">
                                    <thead>
                                        <tr role="row">
                                            <th class="text-nowrap">{{__("KEYWORD")}}</th>
                                            <th class="text-nowrap text-center">{{__("YOUR SITE")}}</th>
                                            @php
                                            $competitor_domain = DB::table('competitors')->where('domain_id',
                                            $domain->id)->get();
                                            @endphp
                                            @foreach ($competitor_domain as $comp)
                                            <th class="text-center text-nowrap">
                                                {{$comp->competitor}}
                                                <a href="{{url('/user/competitor/delete/'.$comp->id)}}"><i
                                                        data-feather="trash" class="me-50"></i></a>
                                            </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $domain_keywords = DB::table('domain_keywords')->where('domain_id',
                                        $domain->id)->get();
                                        @endphp

                                        @if(count($domain_keywords) > 0)
                                        @foreach($domain_keywords as $keyword)
                                        <tr class="odd">
                                            <td class="text-nowrap">{{$keyword->keyword}}
                                            </td>
                                            @php
                                            $main_desktop = 0;
                                            $main_mobile = 0;
                                            $urls =
                                            DB::table('serp_competitors')->where('domain_id',$domain->id)->where('keyword',
                                            $keyword->keyword)->get();
                                            foreach($urls as $url) {
                                            if(strpos($url->competitor, $domain->domain) !== false) {
                                            if($url->platform == "desktop" && $main_desktop == 0) {
                                            $main_desktop = $url->avg_position;
                                            } elseif($url->platform == "mobile" && $main_mobile == 0) {
                                            $main_mobile = $url->avg_position;
                                            }
                                            }
                                            }
                                            @endphp
                                            <td class="text-nowrap text-center"><span style="color: green;"><i
                                                        class="bi bi-laptop"></i></span>&nbsp;{{$main_desktop}} -
                                                {{$main_mobile}}&nbsp;<span style="color: green;"><i
                                                        class="bi bi-phone"></i></span></td>
                                            @php
                                            foreach ($competitor_domain as $comp) {
                                            $desktop=0;
                                            $mobile=0;
                                            $urls = DB::table('serp_competitors')->where('domain_id',
                                            $domain->id)->where('keyword', $keyword->keyword)->get();
                                            foreach($urls as $url) {
                                            if(strpos($url->competitor, $comp->competitor) !== false) {
                                            if($url->platform == "desktop" && $desktop == 0) {
                                            $desktop = $url->avg_position;
                                            } elseif($url->platform == "mobile" && $mobile == 0) {
                                            $mobile = $url->avg_position;
                                            }
                                            }
                                            }
                                            @endphp
                                            <td class="text-nowrap text-center"><span style="color: green;"><i
                                                        class="bi bi-laptop"></i></span>&nbsp;{{$desktop}} -
                                                {{$mobile}}&nbsp;<span style="color: green;"><i
                                                        class="bi bi-phone"></i></span></td>
                                            @php
                                            }
                                            @endphp


                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="odd">
                                            <td class="text-nowrap">No Keywords Found
                                            </td>
                                        </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" class="font_10" id="editUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pb-5 px-sm-5 pt-50">
                        <div class="text-center mb-2">
                            <h1 class="mb-1">{{__("Add Competitor")}}</h1>
                        </div>


                        <form id="editUserForm" class="row gy-1 pt-75" method="POST"
                            action="{{url('user/competitor/query')}}">
                            @csrf
                            <input type="hidden" name="domain_id" id="domain_id">
                            <div class="col-12 col-md-12">
                                <label class="form-label" for="modalEditUserFirstName">{{__("Domain name (Without https:// or www)")}}</label>
                                <input type="text" id="competitor" name="competitor" class="form-control"
                                    placeholder="{{__("Competition Domain")}}" required />
                            </div>


                            <div class="col-12  mt-2 pt-50">
                                <button type="submit" onclick="add_domain()"
                                    class="btn btn-primary me-1">{{__("Submit")}}</button>
                                <button type="reset" id="discardbutton" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal" aria-label="Close">
                                    {{__("Cancle")}}
                                </button>
                            </div>
                            <p style="display: none;text-align: center;" id="error_message">{{__("Please enter domain name.")}}
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @endforeach
        <div>
            {{$domain_data->links('pagination-2')}}
        </div>

        @else
        <h1 class="display-4" style="color: white;">No Domains created yet.</h1>
        @endif
        <!--/ Basic table -->

        <script>
        function domain_id(id) {
            $("#domain_id").val(id);
        }
        </script>

    </div>
</div>
</div>
@endsection