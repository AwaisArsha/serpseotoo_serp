@extends('seo.layout.admin_master')
@section('new-serp', 'active')
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{__("SERP")}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/user-dashboard')}}">{{__("Dashboard")}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__("SERP Result")}}</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">



            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="table-responsive" style="height:450px;">
                                <table class="table table-bordered datatables-basic">
                                    <thead>
                                        <tr>
                                        <th scope="col" class="text-nowrap">{{__("Group Rank")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("Absolute Rank")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("Type")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("Domain")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("Title")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("Description")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("URL")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("Breadcrumb")}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($all_results as $result)
                                        <tr>
                                            <td class="text-nowrap">{{$result['rank_group']}}</td>
                                            <td class="text-nowrap">{{$result['rank_absolute']}}</td>
                                            <td class="text-nowrap">{{$result['type']}}</td>
                                            <td class="text-nowrap">{{$result['domain']}}</td>
                                            <td class="text-nowrap">{{$result['title']}}</td>
                                            <td class="text-nowrap">{{$result['description']}}</td>
                                            <td class="text-nowrap">{{$result['url']}}</td>
                                            <td class="text-nowrap">{{$result['breadcrumb']}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <!--/ Basic table -->


        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
@endsection