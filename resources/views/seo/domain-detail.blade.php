@extends('seo.layout.admin_master')
@section('dashboard', 'active')
@section('page-header')

<link rel="stylesheet" type="text/css" href="{{asset('front_assets/css/custom.css')}}" />

<h2 class="content-header-title float-start mb-0" style="font-weight: 500; color:#636363; padding-right:1rem; border-right:1px solid #D6DCE1; display: flex; align-items: center;">
    @php
    $country_data = DB::table('serp_google_locations')->where('location_code',
    $domain_data->location_code)->first();
    @endphp
    <img src="{{asset('project_images/countries/'.strtolower($country_data->country_iso_code).'.png')}}" class="img-fluid" style="width:32px; height:32px; margin-right: 5px;" />
    {{$domain_data->domain}}
</h2>
<div class="breadcrumb-wrapper">
    <ol class="breadcrumb" style="padding-left: 1rem !important; font-size:1rem;display:flex; flex-wrap:wrap;padding:0.3rem 0;">
        <li class="breadcrumb-item"><a href="{{url('/')}}/user/dashboard">{{__("Dashboard")}}</a>
        </li>
        <li class="breadcrumb-item"><a href="{{url('/')}}/user/domain/detail/{{$domain_data->id}}">
                {{__("Domain Detail")}}</a>
        </li>
    </ol>
</div>



@endsection
@section('content')
<style>
    .pagination {
        float: right !important;
    }
</style>
@php
$package_info = UserPackageInfo();
//prx($keywords_data);
$all_domain_keywords = DB::table('domain_keywords')->where('user_id',
Session::get('user_id'))->get();
$all_volume_keywords = DB::table('google_adwords_search_volume')->where('user_id',
Session::get('user_id'))->groupBy('search_volume_id')->get();
$all_related_keywords = DB::table('related_keywords_data')->where('user_id',
Session::get('user_id'))->groupBy('related_keywords_id')->get();
$all_keywords_count = count($all_domain_keywords) + count($all_volume_keywords) + count($all_related_keywords);
@endphp
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        <div class="content-header row"></div>

        <div class="content-body">
            <!-- users list start -->
            <section class="app-user-list">
                @if(isset($monthly_tarffic) && $monthly_tarffic != null && count($monthly_tarffic) > 0)
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div>
                                            @if (count($keywords_data) > 0)
                                            <h3 class="fw-bolder mb-75">
                                                @php
                                                $i = 0;
                                                foreach ($keywords_data as $keyword) {
                                                if ($keyword->desktop_rank != null && $keyword->desktop_rank <= 1) { $i++; } if($keyword->mobile_rank != null && $keyword->mobile_rank <= 1) { $i++; } } $percent=$i/domain_keywords_count($domain_data->
                                                        id) * 100;
                                                        echo $i . " / " . domain_keywords_count($domain_data->id);
                                                        echo " (" . round($percent) . "%)";
                                                        @endphp
                                            </h3>
                                            @else
                                            <h3 class="fw-bolder mb-75">0/0 (0%)</h3>
                                            @endif
                                            <span>Top 1</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div>
                                            @if (count($keywords_data) > 0)
                                            <h3 class="fw-bolder mb-75">
                                                @php
                                                $i = 0;
                                                foreach ($keywords_data as $keyword) {
                                                if ($keyword->desktop_rank != null && $keyword->desktop_rank <= 10) { $i++; } if($keyword->mobile_rank != null && $keyword->mobile_rank <= 10) { $i++; } } $percent=$i/domain_keywords_count($domain_data->
                                                        id) * 100;
                                                        @endphp
                                                        <?php
                                                        echo $i . "/" . domain_keywords_count($domain_data->id);
                                                        echo " (" . round($percent) . "%)";
                                                        ?></h3>
                                            @else
                                            <h3 class="fw-bolder mb-75">0/0 (0%)</h3>
                                            @endif
                                            <span>Top 10</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div>
                                            @if (count($keywords_data) > 0)
                                            <h3 class="fw-bolder mb-75">
                                                @php
                                                $i = 0;
                                                foreach ($keywords_data as $keyword) {
                                                if ($keyword->desktop_rank != null && $keyword->desktop_rank <= 50) { $i++; } if($keyword->mobile_rank != null && $keyword->mobile_rank <= 50) { $i++; } } $percent=$i/domain_keywords_count($domain_data->
                                                        id) * 100;
                                                        @endphp
                                                        <?php
                                                        echo $i . "/" . domain_keywords_count($domain_data->id);
                                                        echo " (" . round($percent) . "%)";
                                                        ?></h3>
                                            @else
                                            <h3 class="fw-bolder mb-75">0/0 (0%)</h3>
                                            @endif
                                            <span>Top 50</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div>
                                            @if (count($keywords_data) > 0)
                                            <h3 class="fw-bolder mb-75">
                                                @php
                                                $i = 0;
                                                foreach ($keywords_data as $keyword) {
                                                if ($keyword->desktop_rank != null && $keyword->desktop_rank <= 100) { $i++; } if($keyword->mobile_rank != null && $keyword->mobile_rank <= 100) { $i++; } } $percent=$i/domain_keywords_count($domain_data->id) * 100;
                                                        @endphp
                                                        <?php
                                                        echo $i . "/" . domain_keywords_count($domain_data->id);
                                                        echo " (" . round($percent) . "%)";
                                                        ?></h3>
                                            @else
                                            <h3 class="fw-bolder mb-75">0/0 (0%)</h3>
                                            @endif
                                            <span>Top 100</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script> -->



                    @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                @if (count($keywords_data) > 0)
                                                <h3 class="fw-bolder mb-75">
                                                    @php
                                                    $i = 0;
                                                    foreach ($keywords_data as $keyword) {
                                                    if ($keyword->desktop_rank != null && $keyword->desktop_rank <= 1) { $i++; } if($keyword->mobile_rank != null &&
                                                        $keyword->mobile_rank <= 1) { $i++; } } $percent=$i/domain_keywords_count($domain_data->
                                                            id) * 100;
                                                            echo $i . "/" . domain_keywords_count($domain_data->id);
                                                            echo " (" . round($percent) . "%)";
                                                            @endphp
                                                </h3>
                                                @else
                                                <h3 class="fw-bolder mb-75">0/0 (0%)</h3>
                                                @endif
                                                <span>Top 1</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                @if (count($keywords_data) > 0)
                                                <h3 class="fw-bolder mb-75">
                                                    @php
                                                    $i = 0;
                                                    foreach ($keywords_data as $keyword) {
                                                    if ($keyword->desktop_rank != null && $keyword->desktop_rank <= 10) { $i++; } if($keyword->mobile_rank != null &&
                                                        $keyword->mobile_rank <= 10) { $i++; } } $percent=$i/domain_keywords_count($domain_data->
                                                            id) * 100;
                                                            echo $i . "/" . domain_keywords_count($domain_data->id);
                                                            echo " (" . round($percent) . "%)";
                                                            @endphp
                                                </h3>
                                                @else
                                                <h3 class="fw-bolder mb-75">0 / 0 (0%)</h3>
                                                @endif
                                                <span>Top 10</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                @if (count($keywords_data) > 0)
                                                <h3 class="fw-bolder mb-75">
                                                    @php
                                                    $i = 0;
                                                    foreach ($keywords_data as $keyword) {
                                                    if ($keyword->desktop_rank != null && $keyword->desktop_rank <= 50) { $i++; } if($keyword->mobile_rank != null &&
                                                        $keyword->mobile_rank <= 50) { $i++; } } $percent=$i/domain_keywords_count($domain_data->
                                                            id) * 100;
                                                            echo $i . "/" . domain_keywords_count($domain_data->id);
                                                            echo " (" . round($percent) . "%)";
                                                            @endphp
                                                </h3>
                                                @else
                                                <h3 class="fw-bolder mb-75">0/0 (0%)</h3>
                                                @endif
                                                <span>Top 50</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                @if (count($keywords_data) > 0)
                                                <h3 class="fw-bolder mb-75">
                                                    @php
                                                    $i = 0;
                                                    foreach ($keywords_data as $keyword) {
                                                    if ($keyword->desktop_rank != null && $keyword->desktop_rank <= 100) { $i++; } if($keyword->mobile_rank != null &&
                                                        $keyword->mobile_rank <= 100) { $i++; } } $percent=$i/domain_keywords_count($domain_data->id) * 100;
                                                            echo $i . "/" . domain_keywords_count($domain_data->id);
                                                            echo " (" . round($percent) . "%)";
                                                            @endphp
                                                </h3>
                                                @else
                                                <h3 class="fw-bolder mb-75">0/0 (0%)</h3>
                                                @endif
                                                <span>Top 100</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    @endif
            </section>
            <!-- users list ends -->

        </div>


        @if (count($keywords_data) == 0)
        <div>
            @if($package_info->keywords_limit == "Unlimited")
            <button type="button" style="margin-left: 10px;" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editUser">Keyword Add</button>

            @elseif($package_info->keywords_limit > $all_keywords_count)
            @if($package_info->domain_keyword_limit > domain_keywords_count($domain_data->id))
            <button type="button" style="margin-left: 10px;" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editUser">Keyword Add</button>
            @else
            <a href="{{url('/user/upgrade_subscription')}}" style="margin-left: 10px;" class="btn btn-warning">Upgrade
                Package</a>
            @endif
            @else
            <a href="{{url('/user/upgrade_subscription')}}" style="margin-left: 10px;" class="btn btn-warning">Upgrade
                Package</a>

            @endif
            </span>
        </div>
        @endif


        @if (count($keywords_data) > 0)
        <div>
            <h2 class="display-5" style="color: black; display:inline-block;">{{__("All Keyword Statistics")}}</h2>
            <span style="float: right;">
                @if(refreshes() > 0)
                <a href="{{url('/user/keyword/refresh_all/'.$domain_data->id)}}" style="margin-left: 10px;" class="btn btn-primary">{{__("Refresh All")}}</a>
                @endif
                @if($package_info->keywords_limit == "Unlimited")
                <button type="button" style="margin-left: 10px;" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editUser">{{__("Keyword Add")}}</button>

                @elseif($package_info->keywords_limit > $all_keywords_count)
                @if($package_info->domain_keyword_limit > domain_keywords_count($domain_data->id))
                <button type="button" style="margin-left: 10px;" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editUser">{{__("Keyword Add")}}</button>
                @else
                <a href="{{url('/user/upgrade_subscription')}}" style="margin-left: 10px;" class="btn btn-warning">Upgrade Package</a>
                @endif
                @else
                <a href="{{url('/user/upgrade_subscription')}}" style="margin-left: 10px;" class="btn btn-warning">{{__("Upgrade Package")}}</a>

                @endif
            </span>
        </div>
        @endif
        <script>
            let labels;
            let data;
            let ctx;
        </script>
        @foreach ($keywords_data as $keyword)
        @php
        $competitor = $keyword->competitor[0];
        $keyword_volume_data = DB::table('domain_keywords_monthly_volume')->where('keyword_id', $keyword->id)->get();
        $label = "";
        $graph_data = null;
        if(count($keyword_volume_data) > 0) {
        foreach($keyword_volume_data as $data) {
        $label .= "'".$data->month."',";
        $graph_data .= $data->search_volume.",";
        }
        } else {
        $month = time();
        $reversed = null;
        $months = array();
        for ($i = 1; $i <= 12; $i++) { $month=strtotime('last month', $month); $months[]=date("F", $month); } $reversed=array_reverse($months); foreach($reversed as $data) { $label .="'" .$data."',"; $graph_data .="0" .","; } } @endphp <div class="content-body">
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header" style="display: block; padding-right:0px; padding-left:0px; padding-top:0px;overflow:hidden; padding-bottom:5px;">
                                <div class="row">
                                    <div class="col-md-12" style="padding: 0px;">
                                        <div class="table-responsive" style="max-height:450px;">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr style="position: sticky;">
                                                        <th style="text-align: center;" class="min_padding  " colspan="1" >{{__("Keyword")}}
                                                        </th>
                                                        <th style="text-align: center;"  class="min_padding " colspan="1">{{__("CHANGE")}}</th>
                                                        <th style="text-align: center;" colspan="1">{{__("RANK")}}</th>
                                                        <th style="text-align: center;" colspan="1" class="text-nowrap min_padding ">
                                                            {{__("7 DAYS")}}
                                                        </th>
                                                        <th style="text-align: center;" colspan="1" class="text-nowrap min_padding ">
                                                            {{__("30 DAYS")}}
                                                        </th>

                                                        <th style="text-align: center;" colspan="1" class="text-nowrap min_padding ">
                                                            {{__("Start")}}
                                                        </th>

                                                        <th style="text-align: center;" colspan="1" class="text-nowrap min_padding ">
                                                            {{__("VOLUME")}}
                                                        </th>
                                                        <th style="text-align: center;" colspan="1" class="text-nowrap min_padding ">
                                                            {{__("GRAPH")}}
                                                        </th>
                                                        <th style="text-align: center;" colspan="1" class="text-nowrap min_padding" >
                                                            {{__("UPDATE")}}
                                                        </th>
                                                        <th style="text-align: center;" colspan="1" class="text-nowrap min_padding ">
                                                            {{__("Action")}}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="font_10" >
                                                        <td style="text-align: left; position:relative;" class="text-nowrap" width="17%">
                                                            <span style="top:15px; " >
                                                                {{$keyword->keyword}}
                                                            </span>
                                                            <a class=" btn-primary" data-bs-toggle="collapse" href="#collapseExample{{$keyword->id}}" role="button" aria-expanded="false" aria-controls="collapseExample" style="float: right;"> <i class=" bi-caret-down-fill"> </i>
                                                            </a>
                                                        </td>
                                                        <td style="text-align: center;" class="text-nowrap"  width="10%"> <i class="bi bi-laptop" > </i>
                                                            @php
                                                            if($keyword->final_avg_desktop_change > 0) {
                                                            echo " <i class='bi bi-caret-up-fill' style='color: green;'> </i> ".$keyword->final_avg_desktop_change;
                                                            } elseif($keyword->final_avg_desktop_change < 0) { $keyword->final_avg_desktop_change *= (-1);
                                                                echo " <i class='bi bi-caret-down-fill' style='color: red;'> </i> ".$keyword->final_avg_desktop_change;
                                                                } else {
                                                                echo $keyword->final_avg_desktop_change;
                                                                }
                                                                @endphp / <i class="bi bi-phone"> </i>
                                                                @php
                                                                if($keyword->final_avg_mobile_change > 0) {
                                                                echo " <i class='bi bi-caret-up-fill' style='color: green;'></i> ".$keyword->final_avg_mobile_change;
                                                                } elseif($keyword->final_avg_mobile_change < 0) { $keyword->final_avg_mobile_change *= (-1);
                                                                    echo " <i class='bi bi-caret-down-fill' style='color: red;'></i> ".$keyword->final_avg_mobile_change;
                                                                    } else {
                                                                    echo $keyword->final_avg_mobile_change;
                                                                    }
                                                                    @endphp
                                                        </td>
                                                        <td style="text-align: center;" class="text-nowrap" width="8%">
                                                            @php
                                                            if ($keyword->desktop_rank != null) {
                                                            echo ' <i class="bi bi-laptop"></i> '.$keyword->desktop_rank.' / ';
                                                            } else {
                                                            echo '- / ';
                                                            }

                                                            if ($keyword->mobile_rank != null) {
                                                            echo ' <i class="bi bi-phone"></i> '.$keyword->mobile_rank;
                                                            } else {
                                                            echo ' - ';
                                                            }
                                                            @endphp
                                                        </td>
                                                        <td style="text-align: center;" class="text-nowrap"  width="8%">
                                                            @php
                                                            if ($keyword->desktop_rank != null) {
                                                            if(isset($keyword->seven_days_desktop) &&
                                                            $keyword->seven_days_desktop != null) {
                                                            echo ' <i class="bi bi-laptop"> </i> '.$keyword->seven_days_desktop.' / ';
                                                            } else {
                                                            echo '- / ';
                                                            }
                                                            } else {
                                                            echo '- / ';
                                                            }

                                                            if ($keyword->mobile_rank != null) {
                                                            if(isset($keyword->seven_days_mobile) &&
                                                            $keyword->seven_days_mobile != null) {
                                                            echo ' <i class="bi bi-phone"> </i> '.$keyword->seven_days_mobile;
                                                            } else {
                                                            echo '-';
                                                            }
                                                            } else {
                                                            echo '-';
                                                            }
                                                            @endphp
                                                        </td>
                                                        <td style="text-align: center;" class="text-nowrap"  width="8%">
                                                            @php
                                                            if ($keyword->desktop_rank != null) {
                                                            if(isset($keyword->thirty_days_desktop) &&
                                                            $keyword->thirty_days_desktop != null) {
                                                            echo ' <i class="bi bi-laptop"> </i> '.$keyword->thirty_days_desktop." / ";
                                                            } else {
                                                            echo '- / ';
                                                            }
                                                            } else {
                                                            echo '- / ';
                                                            }

                                                            if ($keyword->mobile_rank != null) {
                                                            if(isset($keyword->thirty_days_mobile) &&
                                                            $keyword->thirty_days_mobile != null) {
                                                            echo ' <i class="bi bi-phone"> </i> '.$keyword->thirty_days_mobile;
                                                            } else {
                                                            echo '-';
                                                            }
                                                            } else {
                                                            echo '-';
                                                            }
                                                            @endphp
                                                        </td>
                                                        <td style="text-align: center;" class="text-nowrap"  width="8%">
                                                            @php
                                                            if ($keyword->start_desktop != null) {
                                                            echo '<i class="bi bi-laptop"></i> '.$keyword->start_desktop.' / ';

                                                            } else {
                                                            echo ' <i class="bi bi-laptop"> </i>0 / ';
                                                            }

                                                            if ($keyword->start_mobile != null) {
                                                            echo ' <i class="bi bi-phone"></i> '.$keyword->start_mobile;

                                                            } else {
                                                            echo ' 0 <i class="bi bi-phone" </i> ';
                                                                }
                                                                @endphp
                                                        </td>
                                                        <td style="text-align: center;" class="text-nowrap " width="10%">
                                                            @php
                                                            if($keyword->volume == null) {
                                                            echo 0;
                                                            } else {
                                                            echo $keyword->volume;
                                                            }
                                                            @endphp
                                                        </td>
                                                        <script>
                                                            labels = [
                                                                <?php echo $label; ?>
                                                            ];
                                                            data = {
                                                                labels: labels,
                                                                datasets: [{
                                                                    label: '',
                                                                    backgroundColor: 'rgb(255, 99, 132)',
                                                                    borderColor: 'blue',
                                                                    data: [
                                                                        <?php echo $graph_data; ?>
                                                                    ],
                                                                    fill: false,
                                                                    tension: 0.3,
                                                                    borderWidth: 2
                                                                }]
                                                            };
                                                        </script>
                                                        <td style="text-align: center;" class="text-nowrap"  width="8%">
                                                            <div style="width: 100px;">
                                                                <canvas id="myChart{{$keyword->id}}"></canvas>
                                                            </div>
                                                        </td>
                                                        <script>
                                                            ctx = document.getElementById("myChart{{$keyword->id}}");
                                                            ctx.getContext("2d");
                                                            ctx.lineWidth = 50;
                                                            new Chart(ctx, {
                                                                type: 'line',
                                                                data: data,
                                                                options: {
                                                                    scales: {
                                                                        x: {
                                                                            display: false,

                                                                        },
                                                                        y: {
                                                                            display: false
                                                                        }
                                                                    },
                                                                    elements: {
                                                                        point: {
                                                                            radius: 1
                                                                        }
                                                                    },
                                                                    plugins: {
                                                                        legend: {
                                                                            display: false
                                                                        }
                                                                    }
                                                                }
                                                            });
                                                        </script>
                                                        <td style="text-align: center;" class="text-nowrap" width="8%">
                                                            @php
                                                            if($keyword->updated == null) {
                                                            $updated_date = new DateTime($keyword->date);
                                                            $now_date = new DateTime('now');
                                                            $interval = $updated_date->diff($now_date);
                                                            if($interval->h < 1) { echo $interval->i." minut";
                                                                } else {
                                                                echo $interval->h." hour";
                                                                }
                                                                } else {
                                                                $updated_date = new DateTime($keyword->updated);
                                                                $now_date = new DateTime('now');
                                                                $interval = $updated_date->diff($now_date);
                                                                if($interval->h < 1) { echo $interval->i." minut";
                                                                    } else {
                                                                    echo $interval->h." hour";
                                                                    }
                                                                    }

                                                                    @endphp
                                                        </td>
                                                        <td style="text-align: center; class="text-nowrap  >
                                                            @if($keyword->api_running == 'no' || $keyword->api_running
                                                            == null)
                                                            @if(refreshes() != 10)
                                                            <a href="{{url('/user/keyword/refresh/'.$keyword->id)}}"><span class="badge rounded-pill badge-light-success "><i class="bi bi-arrow-repeat" style="font-size: 20px; height:1.2rem; "></i></span></a>
                                                            @endif
                                                            <a href="{{url('/user/keyword/delete/'.$keyword->id)}}"><span class="badge rounded-pill badge-light-danger "><i class="bi bi-trash-fill" style="font-size: 20px; height:1.2rem; "></i></span></a>

                                                            @elseif($keyword->api_running == 'yes')
                                                            <div class="spinner-border" role="status">
                                                                <span class="visually-hidden">{{__("Loading...")}}</span>
                                                            </div>
                                                            @endif

                                                        </td>


                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    @php
                                    $month = null;
                                    $value = null;
                                    $keyword_monthly_traffic =
                                    DB::table('domain_keywords_monthly_volume')->where('domain_id',
                                    $domain_data->id)->where('keyword',$keyword->keyword)->limit(10)->get();
                                    $keyword_monthly_traffic = $keyword_monthly_traffic->reverse()->values()
                                    @endphp
                                    @if(isset($keyword_monthly_traffic) && $keyword_monthly_traffic != null &&
                                    count($keyword_monthly_traffic) > 0)
                                    @foreach ($keyword_monthly_traffic as $monthly_traffic)
                                    <?php
                                    $month .= (string)("'" . $monthly_traffic->month . "-");
                                    $month .= $monthly_traffic->year . "',";
                                    $value .= $monthly_traffic->search_volume . ",";
                                    ?>
                                    @endforeach

                                    @endif


                                </div>

                            </div>

                            @if ($keyword->platform == "desktop and mobile")
                            <div class="card-body collapse" id="collapseExample{{$keyword->id}}">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="homeIcon-tab{{$keyword->id}}" data-bs-toggle="tab" href="#homeIcon{{$keyword->id}}" aria-controls="home" role="tab" aria-selected="true"> <i class="bi bi-laptop"> </i> {{__("Desktop")}} (Rank -
                                            @php
                                            if ($keyword->desktop_rank != null) {
                                            echo $keyword->desktop_rank;
                                            } else {
                                            echo "Not in 100";
                                            }
                                            @endphp
                                            )
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profileIcon-tab{{$keyword->id}}" data-bs-toggle="tab" href="#profileIcon{{$keyword->id}}" aria-controls="profile" role="tab" aria-selected="false"> <i class="bi bi-phone"> </i> {{__("Mobile")}} (Rank -
                                            @php
                                            if ($keyword->mobile_rank != null) {
                                            echo $keyword->mobile_rank;
                                            } else {
                                            echo "Not in 100";
                                            }
                                            @endphp
                                            )</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="homeIcon{{$keyword->id}}" aria-labelledby="homeIcon-tab{{$keyword->id}}" role="tabpanel">
                                        <div class="card" style="margin-bottom: 0px;">
                                            <div class="table-responsive">
                                                <table class="table_id table table-bordered table-dark" style="position: relative; border-collapse:collapse;">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center; position: sticky; top:0;" colspan="1">
                                                                Rank
                                                            </th>

                                                            <th style=" position: sticky; top:0;" colspan="1">{{__("Domain")}}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($competitor) > 0)
                                                        @foreach ($competitor as $comp)
                                                        @if ($comp ->platform == "desktop")
                                                        @if(strpos($comp->competitor, $domain_data->domain) !=
                                                        false)
                                                        <tr class="table-info">
                                                            <td style="text-align: center;" colspan="1">
                                                                {{$comp->avg_position}}
                                                            </td>
                                                            <td colspan="1">{{$comp->competitor}}</td>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <td style="text-align: center;" colspan="1">
                                                                {{$comp->avg_position}}
                                                            </td>
                                                            <td colspan="1">{{$comp->competitor}}</td>
                                                        </tr>
                                                        @endif
                                                        @endif
                                                        @endforeach
                                                        @else
                                                        <tr>
                                                            <td style="text-align: center;" colspan="2">{{__("No Results
                                                                Found.")}}</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="profileIcon{{$keyword->id}}" aria-labelledby="profileIcon-tab{{$keyword->id}}" role="tabpanel{{$keyword->id}}">
                                        <div class="card" style="margin-bottom: 0px;">
                                            <div class="table-responsive">
                                                <table class="table table_id table-bordered table-dark" style="position: relative; border-collapse:collapse;">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center; position: sticky; top:0;" colspan="1">
                                                                Rank
                                                            </th>

                                                            <th style=" position: sticky; top:0;" colspan="1">Domain
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($competitor) > 0)
                                                        @foreach ($competitor as $comp)
                                                        @if ($comp ->platform == "mobile")
                                                        @if(strpos($comp->
                                                        competitor, $domain_data->domain) !=
                                                        false)
                                                        <tr class="table-info">
                                                            <td style="text-align: center;" colspan="1">
                                                                {{$comp->avg_position}}
                                                            </td>
                                                            <td colspan="1">{{$comp->competitor}}</td>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <td style="text-align: center;" colspan="1">
                                                                {{$comp->avg_position}}
                                                            </td>
                                                            <td colspan="1">{{$comp->competitor}}</td>
                                                        </tr>
                                                        @endif
                                                        @endif
                                                        @endforeach
                                                        @else
                                                        <tr>
                                                            <td style="text-align: center;" colspan="2">{{__("No Results
                                                                Found.")}}</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @elseif($keyword->platform == "desktop")
                            <div class="card-body collapse" id="collapseExample{{$keyword->id}}">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="homeIcon-tab{{$keyword->id}}" data-bs-toggle="tab" href="#homeIcon{{$keyword->id}}" aria-controls="home" role="tab" aria-selected="true"> <i class="bi bi-laptop"> </i> {{__("Desktop")}} (Rank -
                                            @php
                                            if ($keyword->desktop_rank != null) {
                                            echo $keyword->desktop_rank;
                                            } else {
                                            echo "Not in 100";
                                            }
                                            @endphp
                                            )
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="homeIcon{{$keyword->id}}" aria-labelledby="homeIcon-tab{{$keyword->id}}" role="tabpanel{{$keyword->id}}">
                                        <div class="card" style="margin-bottom: 0px;">
                                            <div class="table-responsive">
                                                <table class="table_id table table-bordered table-dark" style="position: relative; border-collapse:collapse;">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center; position: sticky; top:0;" colspan="1">
                                                                {{__("Rank")}}
                                                            </th>

                                                            <th style=" position: sticky; top:0;" colspan="1">{{__("Domain")}}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($competitor) > 0)
                                                        @foreach ($competitor as $comp)
                                                        @if ($comp ->platform == "desktop")
                                                        @if(strpos($comp->competitor, $domain_data->domain) !=
                                                        false)
                                                        <tr class="table-info">
                                                            <td style="text-align: center;" colspan="1">
                                                                {{$comp->avg_position}}
                                                            </td>
                                                            <td colspan="1">{{$comp->competitor}}</td>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <td style="text-align: center;" colspan="1">
                                                                {{$comp->avg_position}}
                                                            </td>
                                                            <td colspan="1">{{$comp->competitor}}</td>
                                                        </tr>
                                                        @endif
                                                        @endif
                                                        @endforeach
                                                        @else
                                                        <tr>
                                                            <td style="text-align: center;" colspan="2">{{__("No Results
                                                                Found.")}}</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @elseif($keyword->platform == "mobile")
                            <div class="card-body collapse" id="collapseExample{{$keyword->id}}">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="profileIcon-tab{{$keyword->id}}" data-bs-toggle="tab" href="#profileIcon{{$keyword->id}}" aria-controls="profile" role="tab" aria-selected="false"><i class="bi bi-phone"></i> {{__("Mobile")}} (Rank -
                                            @php
                                            if ($keyword->mobile_rank != null) {
                                            echo $keyword->mobile_rank;
                                            } else {
                                            echo "Not in 100";
                                            }
                                            @endphp
                                            )</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="profileIcon{{$keyword->id}}" aria-labelledby="profileIcon-tab{{$keyword->id}}" role="tabpanel{{$keyword->id}}">
                                        <div class="card" style="margin-bottom: 0px;">
                                            <div class="table-responsive">
                                                <table class="table_id table table-bordered table-dark" style="position: relative; border-collapse:collapse;">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center; position: sticky; top:0;" colspan="1">
                                                                Rank
                                                            </th>

                                                            <th style=" position: sticky; top:0;" colspan="1">{{__("Domain")}}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($competitor) > 0)
                                                        @foreach ($competitor as $comp)
                                                        @if ($comp ->platform == "mobile")
                                                        @if(strpos($comp->competitor, $domain_data->domain) !=
                                                        false)
                                                        <tr class="table-info">
                                                            <td style="text-align: center;" colspan="1">
                                                                {{$comp->avg_position}}
                                                            </td>
                                                            <td colspan="1">{{$comp->competitor}}</td>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <td style="text-align: center;" colspan="1">
                                                                {{$comp->avg_position}}
                                                            </td>
                                                            <td colspan="1">{{$comp->competitor}}</td>
                                                        </tr>
                                                        @endif
                                                        @endif
                                                        @endforeach
                                                        @else
                                                        <tr>
                                                            <td style="text-align: center;" colspan="2">{{__("No Results
                                                                Found.")}}</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
    </div>
    @endforeach




</div>
</div>


<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{__("Keyword Add")}}</h1>
                </div>
                <form id="editUserForm" class="row gy-1 pt-75" method="POST" action="{{url('user/domain/add_keyword')}}">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalEditUserLastName">{{__("Keyword (Separated by commas or start in new line)")}}</label>
                        <textarea name="keyword" id="keyword" rows="7" placeholder="{{__("Enter a Keyword on a new line or use a comma)")}}" class="form-control" onkeyup="keydown()" required></textarea>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalEditUserLastName">{{__("Select Device")}}</label>
                        <select id="platform" name="platform" class="select2 form-select">
                            <!-- <option value="0">Select Language</option> -->
                            <option value="desktop">{{__("Desktop")}}</option>
                            <option value="mobile">{{__("Mobile")}}</option>
                            <option value="desktop and mobile">{{__("Desktop and Mobile")}}</option>
                        </select>
                    </div>



                    <input type="hidden" name="all_keywords" id="all_keywords" value="">
                    <input type="hidden" name="language_code" value="{{$domain_data->language_code}}">
                    <input type="hidden" name="location_code" value="{{$domain_data->location_code}}">
                    <input type="hidden" name="domain_id" value="{{$domain_data->id}}">

                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1">Submit</button>
                        <button type="reset" id="discardbutton" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="{{__("Close)")}}">
                            Cancel
                        </button>
                    </div>
                    <p style="display: none;text-align: center;" id="error_message">{{__("Enter the domain name.)")}}</p>
                </form>
            </div>
        </div>
    </div>


</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{{asset('admin_assets/DataTables/datatables.js')}}"></script>
<script>
    $('.table_id').dataTable({
        "pageLength": 10,
        "searching": false,
        "info": false,
        "lengthChange": false,
        'order': [],
        "columnDefs": [{
            "orderable": false,
            "targets": [0, 1]
        }]
    });

    const interval = setInterval(function() {
        update_keyword();
    }, 5000);


    function update_keyword() {
        var domain_id = <?php echo $domain_data->id; ?>;
        var keywords_refresh_count = <?php echo $api_running_count; ?>;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{{url('/user/check/keyword/data')}}",
            data: {
                "domain_id": domain_id
            },
            success: function(response) {
                if (response.status == "successfull") {
                    if (keywords_refresh_count != response.count) {

                        window.location.href = "<?php echo url('/user/domain/detail/'); ?>" + "/" + domain_id;
                    }
                } else {}
            }
        });
    }

    function keydown() {
        var lines = $('#keyword').val().split('\n');
        var texts = [];
        for (var i = 0; i < lines.length; i++) {
            if (/\S/.test(lines[i])) {
                texts.push($.trim(lines[i]));
            }
        }
        $('#all_keywords').val(texts);
        console.log($('#domain').val());
    }
</script>
@endsection