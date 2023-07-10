@extends('seo.layout.admin_master')
@section('serp-history', 'active')
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
                        <h2 class="content-header-title float-start mb-0">{{__("SERP")}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/user/dashboard')}}">{{__("Dashboard")}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{url('/user/serp-history')}}">{{__("SERP History")}}</a>
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
                            <table class="datatables-basic table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">{{__("Keyword")}}</th>
                                        <th style="text-align: center;">{{__("Time")}}</th>
                                        <th style="text-align: center;">{{__("Action")}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($serp_data as $data)
                                    <tr>
                                        <td style="text-align: center;">{{$data->keyword}}</td>
                                        <td style="text-align: center;">{{$data->date}}</td>
                                        <td style="text-align: center;">
                                        <a href="{{url('user/serp/detail/'.$data->serp_id)}}"><span
                                                        class="badge rounded-pill badge-light-warning me-1"><i
                                                            class="bi bi-eye" style="margin-right: 0.5rem;"></i>{{__("Detail")}}</span></a>
                                                <a href="{{url('user/serp/delete/'.$data->serp_id)}}"><span
                                                        class="badge rounded-pill badge-light-danger me-1"><i
                                                            data-feather="trash" class="me-50"></i>{{__("Delete")}}</span></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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