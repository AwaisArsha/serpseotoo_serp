@extends('seo.layout.admin_master')
@section('backlinks', 'active')

@php
$package_info = UserPackageInfo();
@endphp
@section('page-header')

<link rel="stylesheet" type="text/css" href="{{asset('admin_assets/DataTables/datatables.min.css')}}" />

<div class="bookmark-wrapper d-flex align-items-center">
    <h2 class="card-title mb-50 mb-sm-0">{{__("Dashboard of")}} {{Session::get('user_name')}}</h2>
    @php
    $domains_count = count($domains);
    @endphp

    @if($package_info->domain_actual_backlink_limit == "Unlimited" && backlinks_refreshes_exists())
    <button type="button" style="margin-left: 10px;" class="btn btn-primary" data-bs-toggle="modal"
        data-bs-target="#editUser">Add Backlink Domain</button>
    @elseif ($domains_count < $package_info->domain_actual_backlink_limit && backlinks_refreshes_exists())
        <button type="button" style="margin-left: 10px;" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#editUser">Add Backlink Domain</button>
        @else
        <a href="{{url('/user/upgrade_subscription')}}" style="margin-left: 10px;" class="btn btn-primary">Upgrade
            Pakket</a>
        @endif
        <ul class="nav navbar-nav d-xl-none">
            <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a>
            </li>
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
        <div class="content-body">
            <!-- users list start -->
            <section class="app-user-list">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">
                                        {{$domains_count}}/{{$package_info->domain_actual_backlink_limit}}
                                    </h3>
                                    <span>{{__("Backlink Domain Limit")}}</span>
                                </div>
                                <div class="avatar bg-light-primary p-50">
                                    <span class="avatar-content">
                                        <i data-feather="box" class="font-medium-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">{{$package_info->domain_backlinks_rows_limit}}
                                    </h3>
                                    <span>{{__("Backlink Domain Row Limit")}}</span>
                                </div>
                                <div class="avatar bg-light-danger p-50">
                                    <span class="avatar-content">
                                        <i data-feather="star" class="font-medium-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75"><?php echo remaining_backlinks_refreshes(); ?></h3>
                                    <span>{{__("Backlink Domain Workload")}}</span>
                                </div>
                                <div class="avatar bg-light-success p-50">
                                    <span class="avatar-content">
                                        <i data-feather="refresh-cw" class="font-medium-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">{{$package_info->title}}</h3>
                                    <span>{{__("Account Type")}}</span>
                                </div>
                                <div class="avatar bg-light-warning p-50">
                                    <span class="avatar-content">
                                        <i data-feather="briefcase" class="font-medium-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- users list ends -->

        </div>
        <!-- 
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css"> -->




        <div class="content-body"> 
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="padding-left: 10px; padding-right:10px; padding-bottom:20px;">
                            <table id="table_id" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;" class="text-nowrap">{{__("Domain")}}</th>
                                        <th style="text-align: center;" class="text-nowrap">{{__("Total Backlinks")}}</th>
                                        <th style="text-align: center;" class="text-nowrap">{{__("Spam Score")}}</th>
                                        <th style="text-align: center;" class="text-nowrap" id="last_updated">{{__("Last Updated")}}</th>
                                        <th style="text-align: center;" class="text-nowrap" id="last_updated">{{__("Last Notifications")}}</th>
                                        <th style="text-align: center;" class="text-nowrap" id="actions">{{__("Actions")}}</th>
                                    </tr>
                                </thead>


                                <tbody id="domain_rows">
                                    @foreach ($domains as $domain)
                                    <tr>
                                        <td style="text-align: center;" class="text-nowrap">{{$domain->domain}}</td>
                                        <td style="text-align: center;" class="text-nowrap">{{total_backlinks($domain->id)}}</td>
                                        <td style="text-align: center;" class="text-nowrap">{{spam_score($domain->id)}}%</td>
                                        <td style="text-align: center;" class="text-nowrap">
                                            @php
                                            if($domain->updated == null) {
                                            $updated_date = new DateTime($domain->date);
                                            $now_date = new DateTime('now');
                                            $interval = $updated_date->diff($now_date);
                                            if($interval->h < 1) { echo $interval->i." minuten";
                                                } else {
                                                echo $interval->h." uur";
                                                }
                                                } else {
                                                $updated_date = new DateTime($domain->updated);
                                                $now_date = new DateTime('now');
                                                $interval = $updated_date->diff($now_date);
                                                if($interval->h < 1) { echo $interval->i." minuten";
                                                    } else {
                                                    echo $interval->h." uur";
                                                    }
                                                    }
                                                    @endphp
                                        </td>
                                        <td style="text-align: center;">
                                            <select class="form-select" id="notification_value{{$domain->id}}"
                                                onchange="updateNotification({{$domain->id}})">
                                                <option value="0"
                                                @php
                                                if($domain->notification == '0') {
                                                    echo 'selected';
                                                }    
                                                @endphp
                                                >
                                                {{__("Off")}}</option>
                                                <option value="daily"
                                                @php
                                                if($domain->notification == 'daily') {
                                                    echo 'selected';
                                                }    
                                                @endphp
                                                >{{__("Daily")}}</option>
                                                <option value="weekly"
                                                @php
                                                if($domain->notification == 'weekly') {
                                                    echo 'selected';
                                                }    
                                                @endphp
                                                >{{__("Weekly")}}</option>
                                                <option value="monthly"
                                                @php
                                                if($domain->notification == 'monthly') {
                                                    echo 'selected';
                                                }    
                                                @endphp
                                                >{{__("Monthly")}}</option>
                                            </select>

                                        </td>
                                        <td style="text-align: center;" class="text-nowrap">
                                            <a href="{{url('/user/backlinks/'.$domain->id)}}"><span
                                                    class="badge rounded-pill badge-light-warning me-1"><i
                                                        class="bi bi-eye"
                                                        style="margin-right: 0.5rem;"></i>{{__("Detail")}}</span></a>
                                            @if(backlinks_refreshes_exists())
                                            <a href="{{url('/user/backlinks/refresh/'.$domain->id)}}"><span
                                                    class="badge rounded-pill badge-light-info me-1"><i
                                                        class="bi bi-arrow-repeat"
                                                        style="margin-right: 0.5rem;"></i>{{__("Refresh")}}</span></a>
                                            @endif
                                            <a href="{{url('/user/backlinks/delete/'.$domain->id)}}"><span
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
        </div>
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
                    <h1 class="mb-1">{{__("Add Backlink Domain")}}</h1>
                </div>


                <form id="editUserForm" class="row gy-1 pt-75" method="POST" action="{{url('/user/add-backlink')}}">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="modalEditUserFirstName">{{__("Domain name (Without https:// or
                            www)")}}</label>
                        <input type="text" id="domain" name="domain" class="form-control" placeholder="{{__("Target Domain")}}"
                            required />
                    </div>

                    <div class="col-12 text-center mt-2 pt-50">
                        <input type="submit" class="btn btn-primary me-1" value="Submit" />
                        <button type="reset" id="discardbutton" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal" aria-label="Close">
                            {{__("Cancel")}}
                        </button>
                    </div>
                    <p style="display: none;text-align: center;" id="error_message">Please enter domain name.</p>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{{asset('admin_assets/DataTables/datatables.js')}}"></script>

<script>
$('#table_id').dataTable({
    language: {
        search: "Search:"
    },
    "pageLength": 50,
    'order': [],
    "columnDefs": [{
        "orderable": false,
        "targets": [0, 3, 4, 5, 6, 7]
    }]
});
$('#table_id').DataTable();

function updateNotification(id) {
    var value = $('#notification_value'+id).val();
    console.log(value);
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{{url('/user/backlinks/notification/update')}}",
            data: {
                "domain_id": id,
                "frequency": value
            },
            success: function(response) {
                if (response.status == "successfull") {
                    
                }

            }
        });
}

</script>



@endsection