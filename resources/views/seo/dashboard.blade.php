@extends('seo.layout.admin_master')
@section('dashboard', 'active')
@php
$package_info = UserPackageInfo();
@endphp
@section('page-header')


<link rel="stylesheet" type="text/css" href="{{asset('admin_assets/DataTables/datatables.min.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('front_assets/css/custom.css')}}" />

<h2 class="card-title mb-50 mb-sm-0">{{__("Dashboard of")}} {{Session::get('user_name')}}</h2>
@php
$all_user_domains = DB::table('domains')->where('user_id', Session::get('user_id'))->where('status',1)->get();
$domains_count = count($all_user_domains);
@endphp

@if($package_info->domain_backlinks_limit == "Unlimited")
<button type="button" style="margin-left: 10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUser">{{__("Add Domain")}}</button>
@elseif ($domains_count < $package_info->domain_backlinks_limit)
    <button type="button" style="margin-left: 10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUser">{{__("Add Domain")}}</button>
    @else
    <a href="{{url('/user/upgrade_subscription')}}" style="margin-left: 10px;" class="btn btn-primary">Upgrade
        Pakket</a>
    @endif





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
                                            {{$domains_count}}/{{$package_info->domain_backlinks_limit}}
                                        </h3>
                                        <span>{{__("Domains")}}</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h3 class="fw-bolder mb-75">
                                            <?php echo all_domain_keywords_count(); ?>/{{$package_info->keywords_limit}}
                                        </h3>
                                        <span>{{__("Keywords")}}</span>
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
                                        <h3 class="fw-bolder mb-75"><?php echo remaining_refreshes(); ?></h3>
                                        <span>{{__("Daily Workload Limit")}}</span>
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
                                <table id="table_id" style="font: 10" ; class="table table-striped table-bordered">

                                    <thead>
                                        <tr class="font_10">
                                            <th style="text-align: center; " class="min_padding font_10 text-nowrap" id="country">{{__("COUNTRY")}}</th>
                                            <th style="text-align: center;" class=" min_padding font_10 text-nowrap" id="domainn">{{__("DOMAIN")}}</th>
                                            <th style="text-align: center;" class=" min_padding font_10 text-nowrap" id="avg_position">{{__("AVG POSITION")}}</th>
                                            <th style="text-align: center;" class="min_padding font_10 text-nowrap" id="competitors">{{__("COMPETITORS")}}</a></th>
                                            <th style="text-align: center;" class="min_padding font_10 text-nowrap" id="last_updated">{{__("LAST UPDATED")}}</th>
                                            <th style="text-align: center;" class="min_padding font_10 text-nowrap" id="keywords">{{__("KEYWORDS")}}</th>
                                            <th style="text-align: center;" class="min_padding font_10 text-nowrap" id="notifications">{{__("NOTIFICATIONS")}}</th>
                                            <th style="text-align: center;" class="min_padding font_10 text-nowrap" "center; ">{{__("ACTIONS")}}</th>
                                        </tr>
                                    </thead>


                                    <tbody id="domain_rows">
                                        @foreach ($domains as $domain)
                                        <tr>
                                            @php
                                            $country_data = DB::table('serp_google_locations')->where('location_code',
                                            $domain->location_code)->first();

                                            @endphp
                                            <td style="text-align: center;" class="text-nowrap">
                                                <img src="{{asset('project_images/countries/'.strtolower($country_data->country_iso_code).'.png')}}" class="img-fluid" width="32">
                                            </td>
                                            <td style="text-align: left;" class=" font_10 text-nowrap">{{$domain->domain}}</td>
                                            @php
                                            if($domain->avg_position > 100 || $domain->avg_position == 0) {
                                            @endphp
                                            <td style="text-align: center;" class=" font_10 text-nowrap">
                                                <p style="display:none;">10000</p>
                                            </td>

                                            @php
                                            } else {
                                            @endphp
                                            <td style="text-align: center;" class=" font_10 text-nowrap">
                                                {{$domain->avg_position}}
                                            </td>
                                            @php
                                            }
                                            @endphp
                                            <td style="text-align: center;" class=" font_10 text-nowrap">
                                                <?php echo $count = domain_competitors_count($domain->id); ?></td>
                                            <td style="text-align: center;" class=" font_10 text-nowrap">
                                                @php
                                                if($domain->updated == null) {
                                                $updated_date = new DateTime($domain->date);
                                                $now_date = new DateTime('now');
                                                $interval = $updated_date->diff($now_date);
                                                if($interval->h < 1) { echo $interval->i." minutes";
                                                    } else {
                                                    echo $interval->h."hour";
                                                    }
                                                    } else {
                                                    $updated_date = new DateTime($domain->updated);
                                                    $now_date = new DateTime('now');
                                                    $interval = $updated_date->diff($now_date);
                                                    if($interval->h < 1) { echo $interval->i." minutes";
                                                        } else {
                                                        echo $interval->h." hour";
                                                        }
                                                        }

                                                        @endphp
                                            </td>

                                            <td style="text-align: center;" class="font_10">
                                                <?php echo $count = domain_keywords_count($domain->id); ?></td>
                                            <td style="text-align: center;" class="font_10">
                                                <select class="font_10" form-select" id="notification_value{{$domain->id}}" onchange="updateNotification({{$domain->id}})">
                                                    <option value="0" class="font_10" @php if($domain->notification == '0') {
                                                        echo 'selected';
                                                        }
                                                        @endphp
                                                        >
                                                        {{__("Off")}}
                                                    </option>
                                                    <option value="daily" class="font_10" @php if($domain->notification == 'daily') {
                                                        echo 'selected';
                                                        }
                                                        @endphp
                                                        >{{__("Daily")}}</option>
                                                    <option value="weekly" class="font_10" @php if($domain->notification == 'weekly') {
                                                        echo 'selected';
                                                        }
                                                        @endphp
                                                        >{{__("Weekly")}}</option>
                                                    <option value="monthly" class="font_10" @php if($domain->notification == 'monthly') {
                                                        echo 'selected';
                                                        }
                                                        @endphp
                                                        >{{__("Monthly")}}</option>
                                                </select>

                                            </td>
                                            <td style="" class="font_10 text-nowrap">
                                                <div style="display: flex;">
                                                    <a href="{{url('/user/domain/detail/'.$domain->id)}}">
                                                        <span class="badge rounded-pill badge-light-warning me-1" style="display: flex; flex-direction: row; justify-content: center; align-items: ">
                                                            <i class="bi bi-eye"></i>
                                                            <span style="font-style: normal; margin-left: 0.5rem; font-size: 12px;">{{__("Detail")}}</span>
                                                        </span>
                                                    </a>
                                                    <a href="{{url('/user/domain/delete/'.$domain->id)}}">
                                                        <span class="badge rounded-pill badge-light-danger me-1" style="display: flex; flex-direction: row; justify-content: center; align-items: center; ">
                                                            <i class="bi bi-trash">
                                                            </i>
                                                            <span style="font-style: normal; margin-left: 0.5rem; font-size: 12px;">{{__("Delete")}}</span>
                                                        </span>
                                                    </a>
                                                </div>
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

    <script>

    </script>

    <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">Add Domain</h1>
                    </div>


                    <form id="editUserForm" class="row gy-1 pt-75">
                        @csrf
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalEditUserFirstName">Domain name (Without https:// or www)</label>
                            <input type="text" id="domain" name="domain" class="form-control" placeholder="Target Domain" required value="" />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalEditUserLastName">Select a Country</label>
                            <select id="location_code" name="location_code" class="select2 form-select">
                                <!-- <option value="0">Select a country</option> -->
                                @foreach ($locations as $loc)
                                <option value="{{$loc->location_code}}">
                                    {{$loc->display_name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalEditUserLastName">Keyword (Separated by commas or start in newline)label>
                                <textarea name="keyword" id="keyword" rows="7" placeholder="Enter a keyword on a new line or use a comma" class="form-control" onkeyup="keydown()"></textarea>
                        </div>
                        <input type="hidden" name="all_keywords" id="all_keywords" value="">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalEditUserLastName">Select Language</label>
                            <select id="language_code" name="language_code" class="select2 form-select">
                                <!-- <option value="0">Select Language</option> -->
                                @foreach ($languages as $lan)
                                <option value="{{$lan->language_code}}">
                                    {{$lan->display_name}}
                                </option>
                                @endforeach
                            </select>

                            <label class="form-label" for="modalEditUserLastName" style="margin-top:10px;">Select
                                Device</label>
                            <select id="platform" name="platform" class="select2 form-select">
                                <!-- <option value="0">Select Language</option> -->
                                <option value="desktop">Desktop</option>
                                <option value="mobile">Mobile</option>
                                <option value="desktop and mobile">Desktop and Mobile</option>
                            </select>
                        </div>

                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="button" onclick="add_domain()" class="btn btn-primary me-1">Submit</button>
                            <button type="reset" id="discardbutton" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                Cancle
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
            var value = $('#notification_value' + id).val();
            console.log(value);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{url('/user/notification/update')}}",
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

        function keydown() {
            // document.getElementById("discardbutton").click();

            var lines = $('#keyword').val().split('\n');
            var texts = [];
            for (var i = 0; i < lines.length; i++) {
                // only push this line if it contains a non whitespace character.
                if (/\S/.test(lines[i])) {
                    texts.push($.trim(lines[i]));
                }
            }
            $('#all_keywords').val(texts);
            console.log($('#domain').val());
        }

        function add_domain() {
            // console.log(yazar);
            var domain = $("#domain").val();
            // alert(domain);
            // return;
            var langauge_code = $("#language_code").val();
            var location_code = $("#location_code").val();
            var all_keywords = $("#all_keywords").val();
            var platform = $("#platform").val();
            var html = $("#domain_rows").html();
            var reference = $("#domain_rows").html();

            if (domain !== null && domain !== " " && domain !== "") {
                document.getElementById("discardbutton").click();

                var random_id = Math.random();
                html += `<tr id="` + random_id + `">
        <td style="text-align: center;"><div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
        <td style="text-align: left;" class="font_10">` + domain + `</td>
        <td style="text-align: center;" class="font_10"><div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
        </div></td>
        <td style="text-align: center;" class="font_10"><div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
        </div></td>
        <td style="text-align: center;" class="font_10"><div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
        </div></td>
        <td style="text-align: center;" class="font_10"><div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
        </div></td>
        <td style="text-align: center;" class="font_10"><div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
        </div></td>
        </tr>`;
                $('#domain_rows').html(html);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: "{{url('/user/add-domain')}}",
                    data: {
                        "domain": domain,
                        "language_code": langauge_code,
                        "location_code": location_code,
                        "all_keywords": all_keywords,
                        "platform": platform
                    },
                    success: function(response) {
                        // console.log(response);
                        // console.log(response.status);
                        // console.log(response.keywords);
                        // console.log(response.domain_id);
                        if (response.status == "successfull") {
                            window.location.href = "<?php echo url('/user/dashboard'); ?>"

                            reference += `<tr>
                        <td style="text-align: center;">` + domain + `</td>
                        <td style="text-align: center;">` + response.new_avg_position + `</td>
                        <td style="text-align: center;">0</td>
                        <td style="text-align: center;">` + response.last_updated + `</td>
                        <td style="text-align: center;">` + response.keywords + `</td>
                        <td style="text-align: center;">
                            <a href="{{url('/user/domain/detail/` + response.domain_id + `')}}"><span class="badge rounded-pill badge-light-warning me-1"><i class="bi bi-eye" style="margin-right: 0.5rem;"></i>Detail</span></a>
                            <a href="{{url('/user/domain/delete/` + response.domain_id + `')}}"><span class="badge rounded-pill badge-light-danger me-1"><i data-feather="trash" class="me-50"></i>Delete</span></a>
                        </td>
                    </tr>`;
                        } else {
                            toastr.info(" Invalid Domain. ");
                        }
                        // $('#domain_rows').html(reference);

                    }
                });
            } else {
                document.getElementById('error_message').style.display = "inline-block";
            }
        }
    </script>



    @endsection