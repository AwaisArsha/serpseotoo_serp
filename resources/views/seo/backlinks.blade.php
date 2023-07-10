@extends('seo.layout.admin_master')
@section('backlinks', 'active')
@section('page-header')
<div class="bookmark-wrapper d-flex align-items-center">
    <h2 class="card-title mb-50 mb-sm-0">{{__("Detail of Backlinks")}}</h2>
    @if(backlinks_refreshes_exists())
    <a href="{{url('/user/backlinks/refresh/'.$first_domain->id)}}" style="margin-left: 10px;" class="btn btn-primary">{{__("Refresh")}}</a>
    @endif
    <ul class="nav navbar-nav d-xl-none">
        <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
    </ul>
</div>
@endsection
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        <div class="content-header row">
        </div>
        @if($domains_exists == null)
        <p class="display-4 text-center">{{__("No Domains Created yet.")}}</p>
        @else
        <div class="content-body">
            <div class="row">
                <div class="col-md-12" style="margin-bottom: 10px;">
                    <script type="text/javascript"
                        src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
                    @php
                    //prx($backlinks_history);

                    $month = null;
                    $value = null;
                    $anchor_count = null;
                    $new_backlinks_count = null;
                    $lost_backlinks_count = null;
                    @endphp
                    @if(isset($backlinks_history) && $backlinks_history != null && count($backlinks_history) > 0)
                    @foreach ($backlinks_history as $history)
                    <?php
                            $month .= (string)("'".date_format(date_create($history->date), 'm')."-");
                        $month .= date_format(date_create($history->date), 'Y')."',";
                        $value .= (string)$history->backlinks_count.",";
                        $anchor_count .= (string)$history->anchors_count.",";
                        $new_backlinks_count .= (string)$history->new_backlinks.",";
                        $lost_backlinks_count .= (string)$history->new_backlinks.",";
                    ?>
                    @endforeach
                    @php
                    //prx($month);
                    @endphp
                    @endif
                    <canvas id="chart"
                        style="width: 100%; height:400px; background:white; border: 1px solid #555652; margin-bottom:10px;"></canvas>
                    <script>
                    var ctx = document.getElementById("chart").getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [<?php echo $month; ?>],
                            datasets: [{
                                    label: 'Backlinks',
                                    data: [<?php echo $value; ?>],
                                    backgroundColor: 'transparent',
                                    borderColor: 'rgba(255,99,132)',
                                    borderWidth: 3
                                },
                                {
                                    label: 'Anchors',
                                    data: [<?php echo $anchor_count; ?>],
                                    backgroundColor: 'transparent',
                                    borderColor: 'rgba(0,255,255)',
                                    borderWidth: 3
                                },
                                {
                                    label: 'New Backlinks',
                                    data: [<?php echo $new_backlinks_count; ?>],
                                    backgroundColor: 'transparent',
                                    borderColor: 'rgb(255, 99, 71)',
                                    borderWidth: 3
                                },
                                {
                                    label: 'Lost Backlinks',
                                    data: [<?php echo $lost_backlinks_count; ?>],
                                    backgroundColor: 'transparent',
                                    borderColor: 'rgb(255, 165, 0)',
                                    borderWidth: 3
                                }
                            ]
                        },
                        options: {
                            scales: {
                                scales: {
                                    yAxes: [{
                                        beginAtZero: false
                                    }],
                                    xAxes: [{
                                        autoskip: true,
                                        maxTicketsLimit: 20
                                    }]
                                }
                            },
                            tooltips: {
                                mode: 'index'
                            },
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    fontColor: 'rgb(0,0,0)',
                                    fontSize: 16
                                }
                            }
                        }
                    });
                    </script>
                </div>
            </div>
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-5">
                        <div class="card">
                            <div class="card-body align-items-center justify-content-between">
                                <select id="domain" name="domain" class="select2 form-select"
                                    onchange="new_domain_selected()">
                                    <!-- <option value="0">Select a Country</option> -->
                                    @php
                                    $selected = "";
                                    foreach ($domain_data as $domain) {
                                    if ($first_domain->id == $domain->id) {
                                    $selected = "selected";
                                    } else {
                                    $selected = "";
                                    }
                                    @endphp
                                    <option value="{{$domain->id}}" {{$selected}}>{{$domain->domain}}</option>
                                    @php
                                    }
                                    @endphp
                                </select>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-between"
                                style="padding-top:5px !important;">

                                <h3 class="fw-bolder" style="display: block;">{{$first_domain->domain}}</h3>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">{{$total_backlinks_count}}</h3>
                                    <span>Total Backlinks</span>
                                </div>
                                <div class="avatar bg-light-primary p-50">
                                    <span class="avatar-content">
                                        <i data-feather="box" class="font-medium-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">Spam Score: &nbsp;&nbsp;&nbsp;{{$final_spam_score}}%</h3>
                                    <div style="font-size: 16px; font-weight:bold;">
                                        <span style="font-size: 16px;">{{__("Spam Score Breakdown")}}</span><br>
                                        <span>0-30%&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;<span style="color: green;">{{$final_thirty_score}}%</span></span><br>
                                        <span>31-60%&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;<span style="color: #a9a900;">{{$final_sixty_score}}%</span></span><br>
                                        <span>61-100%&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;<span style="color:red;">{{$final_hundered_score}}%</span></span><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="card">
                            <div class="table-responsive" style="max-height:450px;">
                                <table class="table table-bordered"
                                    style="position: relative; border-collapse:collapse;">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center; position: sticky; top:0;">{{__("Anchor Text")}}</th>
                                            <th style="text-align: center; position: sticky; top:0;">{{__("Count")}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($anchors != null && count($anchors) > 0))
                                        @foreach($anchors as $anchor)
                                        <tr>
                                            <td style="text-align: center;">
                                                @php
                                                if ($anchor->anchor == null) {
                                                echo "Blank";
                                                } else {
                                                echo $anchor->anchor;
                                                }
                                                @endphp
                                            </td>
                                            <td style="text-align: center;">
                                                {{$anchor->count}}
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td>{{__("No Data Found.")}}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


        <div class="content-body">
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__("BackLinks")}}</h4>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="homeIcon-tab" data-bs-toggle="tab"
                                            href="#homeIcon" aria-controls="home" role="tab" aria-selected="true">{{__("All Backlinks")}}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profileIcon-tab" data-bs-toggle="tab"
                                            href="#profileIcon" aria-controls="profile" role="tab"
                                            aria-selected="false">{{__("New Backlinks")}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profileIcon-tab" data-bs-toggle="tab"
                                            href="#profileeIcon" aria-controls="profile" role="tab"
                                            aria-selected="false">{{__("Lost Backlinks")}}</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="homeIcon" aria-labelledby="homeIcon-tab"
                                        role="tabpanel">
                                        <div class="card">
                                            <div class="table-responsive">
                                                <table id="table_id" class="table table-bordered table-striped"
                                                    style="position: relative; border-collapse:collapse;">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1">{{__("Date")}}
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1">{{__("Link From")}}
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1">{{__("Landing Page")}}
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">{{__("DO / NO Follow")}}
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">{{__("Spam Score")}}</th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">{{__("D.A")}}
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">{{__("P.A")}}
                                                            </th>

                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">{{__("Domain Rank")}}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $count_backlinks = 0;
                                                            foreach($remaining_backlinks  as $backlink) {
                                                                $count_backlinks++;
                                                            }
                                                        @endphp
                                                        @if(isset($remaining_backlinks) && $count_backlinks > 0)
                                                        @foreach($remaining_backlinks as $backlink)
                                                        <tr>
                                                            <td style="text-align: center;" colspan="1"
                                                                class="text-nowrap">{{$backlink->date}}
                                                            </td>
                                                            <td colspan="1">
                                                                <a href="{{$backlink->url_from}}"
                                                                    style="color: black;"><strong><?php echo substr($backlink->url_from, 0, 50); ?></strong></a>
                                                                <br><?php echo substr($backlink->title, 0, 50); ?>
                                                            </td>
                                                            <td colspan="1"><a href="{{$backlink->domain_to}}"
                                                                    style="color: black;"><strong><?php echo substr($backlink->domain_to, 0, 50); ?></strong></a></td>
                                                            <td colspan="1">
                                                                @if ($backlink->do_follow == 1)
                                                                DO Follow
                                                                @else
                                                                NO Follow
                                                                @endif
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                {{$backlink->spam_score}}
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                <?php echo round($backlink->d_a/10); ?>
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                <?php echo round($backlink->p_a/10); ?>
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                {{$backlink->domain_from_rank}}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        @foreach($lost_backlinks as $backlink)
                                                        <tr>
                                                            <td style="text-align: center;" colspan="1"
                                                                class="text-nowrap">{{$backlink->date}}
                                                            </td>
                                                            <td colspan="1">
                                                                <a href="{{$backlink->url_from}}"
                                                                    style="color: black;"><strong><?php echo substr($backlink->url_from, 0, 50); ?></strong></a>
                                                                <br><?php echo substr($backlink->title, 0, 50); ?>
                                                            </td>
                                                            <td colspan="1">
                                                                <a href="{{$backlink->domain_to}}"
                                                                    style="color: black;"><strong><?php echo substr($backlink->domain_to, 0, 50); ?></strong></a>
                                                            </td>
                                                            <td colspan="1">
                                                                @if ($backlink->do_follow == 1)
                                                                DO Follow
                                                                @else
                                                                NO Follow
                                                                @endif
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                {{$backlink->spam_score}}
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                <?php echo round($backlink->d_a/10); ?>
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                <?php echo round($backlink->p_a/10); ?>
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                {{$backlink->domain_from_rank}}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        @else
                                                        <tr>
                                                            <td colspan="8" class="text-center">No Data Found.</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane" id="profileIcon" aria-labelledby="profileIcon-tab"
                                        role="tabpanel">
                                        <div class="card">
                                            <div class="table-responsive">
                                                <table id="table_id1" class="table table-bordered table-striped"
                                                    style="position: relative; border-collapse:collapse;">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1">Date
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1">Link From
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1">Landing Page
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">DO / NO Follow
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">Spam Score
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">D.A
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">P.A
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">Domain Rank
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                        $is_new_exists = 0;
                                                        @endphp
                                                        @if(isset($lost_backlinks) && count($lost_backlinks) > 0)
                                                        @foreach($lost_backlinks as $backlink)
                                                        @if ($backlink->is_new == 1)
                                                        @php
                                                        $is_new_exists = 1;
                                                        @endphp
                                                        <tr>
                                                            <td style="text-align: center;" class="text-nowrap"
                                                                colspan="1">{{$backlink->date}}
                                                            </td>
                                                            <td colspan="1">
                                                                <strong
                                                                    style="color: black;"><?php echo substr($backlink->url_from, 0, 50); ?></strong><br><?php echo substr($backlink->title, 0, 50); ?>
                                                            </td>
                                                            <td colspan="1"><a href="{{$backlink->domain_to}}"
                                                                    style="color: black;"><strong><?php echo substr($backlink->domain_to, 0, 50); ?></strong></a></td>
                                                            <td colspan="1">
                                                                @if ($backlink->do_follow == 1)
                                                                DO Follow
                                                                @else
                                                                NO Follow
                                                                @endif
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                {{$backlink->spam_score}}
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                <?php echo round($backlink->d_a/10); ?>
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                <?php echo round($backlink->p_a/10); ?>
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                {{$backlink->domain_from_rank}}
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        @endforeach
                                                        @endif
                                                        @if($is_new_exists == 0)
                                                        <tr>
                                                            <td colspan="8" class="text-center">No Data Found.</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="profileeIcon" aria-labelledby="profileIcon-tab"
                                        role="tabpanel">
                                        <div class="card">
                                            <div class="table-responsive">
                                                <table id="table_id2" class="table table-bordered table-striped"
                                                    style="position: relative; border-collapse:collapse;">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1">{{__("Date")}}
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1">{{__("Link From")}}
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1">{{__("Landing Page")}}
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">{{__("DO / NO Follow")}}
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">{{__("Spam Score")}}
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">{{__("D.A")}}
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">{{__("P.A")}}
                                                            </th>
                                                            <th style="text-align: center; position: sticky; top:0;"
                                                                colspan="1" class="text-nowrap">{{__("Domain Rank")}}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                        $is_lost_exists = 0;
                                                        @endphp
                                                        @if(isset($lost_backlinks) && count($lost_backlinks) > 0)
                                                        @foreach($lost_backlinks as $backlink)
                                                        @if ($backlink->is_lost)
                                                        @php
                                                        $is_lost_exists = 1;
                                                        @endphp
                                                        <tr>
                                                            <td style="text-align: center;" colspan="1" class="text-nowrap">
                                                                {{$backlink->date}}
                                                            </td>
                                                            <td colspan="1">
                                                                <strong
                                                                    style="color: black;"><?php echo substr($backlink->url_from, 0, 50); ?></strong><br><?php echo substr($backlink->title, 0, 50); ?>
                                                            </td>
                                                            <td colspan="1">
                                                            <a href="{{$backlink->domain_to}}"
                                                                    style="color: black;"><strong><?php echo substr($backlink->domain_to, 0, 50); ?></strong></a>
                                                            </td>
                                                            <td colspan="1">
                                                                @if ($backlink->do_follow == 1)
                                                                DO Follow
                                                                @else
                                                                NO Follow
                                                                @endif
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                {{$backlink->spam_score}}
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                <?php echo round($backlink->d_a/10); ?>
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                <?php echo round($backlink->p_a/10); ?>
                                                            </td>
                                                            <td colspan="1" class="text-center">
                                                                {{$backlink->domain_from_rank}}
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        @endforeach
                                                        @endif
                                                        @if($is_lost_exists == 0 )
                                                        <tr>
                                                            <td colspan="8" class="text-center">{{__("No Data Found.")}}</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </div>

        @endif
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{{asset('admin_assets/DataTables/datatables.js')}}"></script>

<script>

$('#table_id').dataTable({
    "pageLength": 100,
    "searching": false,
    "info": false,
    "lengthChange": false,
    'order': [],
    "columnDefs": [{
        "orderable": false,
        "targets": [0, 1]
    }]
});

$('#table_id').DataTable();

$('#table_id1').dataTable({
    "pageLength": 100,
    "searching": false,
    "info": false,
    "lengthChange": false,
    'order': [],
    "columnDefs": [{
        "orderable": false,
        "targets": [0, 1]
    }]
});

$('#table_id1').DataTable();

$('#table_id2').dataTable({
    "pageLength": 100,
    "searching": false,
    "info": false,
    "lengthChange": false,
    'order': [],
    "columnDefs": [{
        "orderable": false,
        "targets": [0, 1]
    }]
});

$('#table_id2').DataTable();



function new_domain_selected() {
    var new_domain_id = $("#domain").val();
    window.location.href = "/user/backlinks/" + new_domain_id;
    // alert(new_domain_id);

}
</script>
@endsection