@extends('seo.layout.admin_master')

@section('related-keywords-history', 'active')

@section('page-header')

<h2 class="content-header-title float-start mb-0" style="font-weight: 500; color:#636363; padding-right:1rem; border-right:1px solid #D6DCE1;">{{__("Related Keywords History")}}</h2>
<div class="breadcrumb-wrapper">
    <ol class="breadcrumb" style="padding-left: 1rem !important; font-size:1rem;display:flex; flex-wrap:wrap;padding:0.3rem 0;">
        <li class="breadcrumb-item"><a href="{{url('/user/dashboard')}}">{{__("Dashboard")}}</a>
        </li>
        <li class="breadcrumb-item"><a href="{{url('/user/related-keywords/history')}}">{{__("Keywords History")}}</a>
        </li>
    </ol>
</div>
@endsection

@section('content')



<!-- BEGIN: Content-->
<div class="app-content content ">

    <div class="content-overlay"></div>

    <div class="header-navbar-shadow"></div>

    <div class="content-wrapper p-0">

        

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

                                    @foreach ($keywords_data as $data)

                                    <tr>

                                        <td style="text-align: center;">{{$data->keyword}}</td>

                                        <td style="text-align: center;">{{$data->date}}</td>

                                        <td style="text-align: center;">

                                        <a href="{{url('/user/related-keyword/detail/'.$data->related_keywords_id)}}"><span

                                                        class="badge rounded-pill badge-light-warning me-1"><i

                                                            class="bi bi-eye" style="margin-right: 0.5rem;"></i>{{__("Detail")}}</span></a>


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