@extends('seo.layout.admin_master')
@section('volume-history', 'active')
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
                        <h2 class="content-header-title float-start mb-0">{{__("Search Volume")}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/user-dashboard')}}">{{__("Dashboard")}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{url('/user/search-volume/history')}}">{{__("Search Volume History")}}</a>
                                </li>
                                <li class="breadcrumb-item"><strong style="color: black;">{{$volume_data[0]->keyword}}</strong>
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
                                            <th scope="col" class="text-nowrap">{{__("TOTAL VOLUME")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("MONTHLY VOLUME")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("COMPETITION")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("CPC")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("LANGUAGE")}}</th>
                                            <th scope="col" class="text-nowrap">{{__("COUNTRY")}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($volume_data as $data)
                                        <tr>
                                            <td class="text-nowrap">{{$data->total_search_volume}}</td>
                                            <td class="text-nowrap">
                                                {{$data->search_volume}}
                                                [{{$data->month}}-{{$data->year}}]
                                            </td>
                                            <td class="text-nowrap">{{$data->competition}}</td>
                                            <td class="text-nowrap">{{$data->cpc}}</td>
                                            <td class="text-nowrap">{{$language}}</td>
                                            <td class="text-nowrap">{{$location}}</td>
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