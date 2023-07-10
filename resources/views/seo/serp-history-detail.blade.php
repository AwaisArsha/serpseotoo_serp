@extends('seo.layout.admin_master')
@section('serp-history', 'active')
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
                                <li class="breadcrumb-item"><a href="{{url('/user/serp-history')}}">{{__("SERP History")}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{url('/user/serp-history')}}"><strong style="color: black;">{{$keyword}}</strong></a>
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
                                            <th scope="col" class="text-nowrap">{{__("GROEP RANG")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("ABSOLUTE RANG")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("TYPE")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("DOMAIN")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("TITLE")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("TITLE")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("URL")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("BREADCRUMB")}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($serp_data as $data)
                                        <tr>
                                            <td class="text-nowrap">{{$data->rank_group}}</td>
                                            <td class="text-nowrap">{{$data->rank_absolute}}</td>
                                            <td class="text-nowrap">{{$data->type}}</td>
                                            <td class="text-nowrap">{{$data->domain}}</td>
                                            <td class="text-nowrap">{{$data->title}}</td>
                                            <td class="text-nowrap">{{$data->description}}</td>
                                            <td class="text-nowrap">{{$data->url}}</td>
                                            <td class="text-nowrap">{{$data->breadcrumb}}</td>
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