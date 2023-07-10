@extends('admin.layout.admin_master')
@section('packages', 'active')
@section('content')




<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Packages Data</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin/packages')}}">Packages</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
            </div>
            <!-- Basic table -->
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="card-header border-bottom p-1">
                                    <div class="dt-action-buttons text-end">
                                        <div class="dt-buttons d-inline-flex">
                                            <button class="dt-button create-new btn btn-primary" tabindex="0"
                                                aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                                data-bs-target="#modals-slide-in"><span><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-plus me-50 font-small-4">
                                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    </svg>Add New Package</span></button>
                                            <a href="{{url('/admin/trial_package/17')}}" class="dt-button create-new btn btn-success" style="margin-left: 5px;"><span><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-plus me-50 font-small-4">
                                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    </svg>Edit Trial Package</span></a>
                                        </div>
                                    </div>
                                </div>
                                <table class="datatables-basic table dataTable no-footer dtr-column"
                                    id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info"
                                    style="width: 1218px;">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting sorting_asc" tabindex="0"
                                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                style="width: 117px;"
                                                aria-label="Name: activate to sort column descending"
                                                aria-sort="ascending">Title</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Price</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Keywords Limit</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Domains Limit</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Keywords Per Domain
                                                Limit</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Keywords Planner
                                                Limit</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Competitors Per
                                                Domain Limit</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Backlinks Domain
                                                Limit</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Backlinks Rows Per
                                                Domain Limit</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Backlinks Workload Limit</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Search Volume Limit
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Serp Limit
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 134px;"
                                                aria-label="Status: activate to sort column ascending">Status</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 145px;"
                                                aria-label="Actions">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($packages) > 0)
                                        @foreach ($packages as $package)
                                        @php
                                        $package_features = DB::table('package_features')->where('package_id',
                                        $package->id)->get();
                                        @endphp
                                        <tr class="odd">
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$package->title}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$package->price}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                {{$package->keywords_limit}}</td>
                                                <td valign="top" colspan="1" class="dataTables_empty">
                                                {{$package->domain_backlinks_limit}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                {{$package->domain_keyword_limit}}</td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                {{$package->keywords_planner_limit}}</td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                {{$package->domain_competitors_limit}}</td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                {{$package->domain_actual_backlink_limit}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                {{$package->domain_backlinks_rows_limit}}</td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                {{$package->backlinks_workload_limit}}</td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                {{$package->search_volume_limit}}</td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                {{$package->serp_limit}}</td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                @if($package->status == '1')
                                                <a href="{{url('admin/package/inactive/'.$package->id)}}"><span
                                                        class="badge rounded-pill badge-light-success me-1">Active</span></a>
                                                @elseif ($package->status == '0')
                                                <a href="{{url('admin/package/active/'.$package->id)}}"><span
                                                        class="badge rounded-pill badge-light-secondary me-1">Inactive</span></a>
                                                @endif
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                <a href="{{url('admin/package/edit/'.$package->id)}}"><span
                                                        class="badge rounded-pill badge-light-warning me-1"><i
                                                            data-feather="edit-2" class="me-50"></i>Edit</span></a>
                                                <a href="{{url('admin/package/delete/'.$package->id)}}"><span
                                                        class="badge rounded-pill badge-light-danger me-1"><i
                                                            data-feather="trash" class="me-50"></i>Delete</span></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="odd" style="text-align: center;">
                                            <td valign="top" colspan="5" class="dataTables_empty">No Data To Show</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal to add new record -->
                <div class="modal modal-slide-in fade" id="modals-slide-in" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog sidebar-sm">
                        <form class="add-new-record modal-content pt-0" method="POST"
                            action="{{url('/admin/package/add')}}">
                            @csrf
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close">×</button>
                            <div class="modal-header mb-1">
                                <h5 class="modal-title" id="exampleModalLabel">New Package</h5>
                            </div>
                            <div class="modal-body flex-grow-1">
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-fullname">Title</label>
                                    <input type="text" class="form-control dt-full-name" name="title"
                                        id="basic-icon-default-fullname" placeholder="Title" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post">Subscription</label>
                                    <select class="select2 form-select" id="subscription" name="subscription">
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post">Price</label>
                                    <input type="number" class="form-control dt-post" placeholder="Price" name="price"
                                        required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post"
                                        style="display: block;">Total Keywords Limit</label>
                                    <input class="form-check-input" type="checkbox" id="keywords_limit_checkbox"
                                        name="keywords_limit_checkbox">
                                    <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                    <input type="number" class="form-control dt-post" placeholder="Total Keywords Limit"
                                        name="keywords_limit" id="keywords_limit" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post" style="display: block;">Per
                                        Domain Keywords Limit</label>
                                    <input class="form-check-input" type="checkbox" id="domain_keyword_limit_checkbox"
                                        name="domain_keyword_limit_checkbox">
                                    <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                    <input type="number" class="form-control dt-post"
                                        placeholder="Per Domain Keywords Limit" name="domain_keyword_limit"
                                        id="domain_keyword_limit" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post"
                                        style="display: block;">Domains
                                        Limit</label>
                                    <input class="form-check-input" type="checkbox" id="domain_backlinks_limit_checkbox"
                                        name="domain_backlinks_limit_checkbox">
                                    <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                    <input type="number" class="form-control dt-post"
                                        placeholder="Per Domain Backlinks Limit" name="domain_backlinks_limit"
                                        id="domain_backlinks_limit" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post" style="display: block;">Keywords Planner Limit</label>
                                    <input class="form-check-input" type="checkbox" id="keywords_planner_checkbox"
                                        name="keywords_planner_checkbox">
                                    <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                    <input type="number" class="form-control dt-post"
                                        placeholder="Keywords Planner Limit" name="keywords_planner_limit"
                                        id="keywords_planner_limit" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post" style="display: block;">Per
                                        Domain Competitors Limit</label>
                                    <input class="form-check-input" type="checkbox"
                                        id="domain_competitors_limit_checkbox" name="domain_competitors_limit_checkbox">
                                    <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                    <input type="number" class="form-control dt-post"
                                        placeholder="Per Domain Competitors Limit" name="domain_competitors_limit"
                                        id="domain_competitors_limit" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post"
                                        style="display: block;">Domain Backlinks
                                        Limit</label>
                                    <input class="form-check-input" type="checkbox" id="domain_actual_backlink_limit_checkbox"
                                        name="domain_actual_backlink_limit_checkbox">
                                    <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                    <input type="number" class="form-control dt-post"
                                        placeholder="Per Domain Backlinks Limit" name="domain_actual_backlink_limit"
                                        id="domain_actual_backlink_limit" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post" style="display: block;">Per
                                        Domain Backlinks Rows
                                        Limit</label>
                                    <input class="form-check-input" type="checkbox"
                                        id="domain_backlinks_rows_limit_checkbox"
                                        name="domain_backlinks_rows_limit_checkbox">
                                    <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                    <input type="number" class="form-control dt-post"
                                        placeholder="Per Domain Backlinks Rows Limit" name="domain_backlinks_rows_limit"
                                        id="domain_backlinks_rows_limit" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post" style="display: block;">Backlinks Workload
                                        Limit</label>
                                    <input class="form-check-input" type="checkbox"
                                        id="backlinks_workload_limit_checkbox"
                                        name="backlinks_workload_limit_checkbox">
                                    <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                    <input type="number" class="form-control dt-post"
                                        placeholder="Domain Workload Limit" name="backlinks_workload_limit"
                                        id="backlinks_workload_limit" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post"
                                        style="display: block;">Search Volume Limit</label>
                                    <input class="form-check-input" type="checkbox" id="search_volume_limit_checkbox"
                                        name="search_volume_limit_checkbox">
                                    <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                    <input type="number" class="form-control dt-post"
                                        placeholder="Per Domain Backlinks Rows Limit" name="search_volume_limit"
                                        id="search_volume_limit" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post"
                                        style="display: block;">SERP Limit</label>
                                    <input class="form-check-input" type="checkbox" id="serp_limit_checkbox"
                                        name="serp_limit_checkbox">
                                    <label class="form-check-label" for="inlineCheckbox1">Unlimited</label>
                                    <input type="number" class="form-control dt-post"
                                        placeholder="SERP Depth" name="serp_limit"
                                        id="serp_limit" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="select2-basic">Competitors Access</label>
                                    <select class="select2 form-select" id="select2-basic" name="competitors">
                                        <option value="1">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="select2-basic">Keyword Planner Access</label>
                                    <select class="select2 form-select" id="select2-basic" name="keyword_planner">
                                        <option value="1">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="select2-basic">Backlinks Access</label>
                                    <select class="select2 form-select" id="select2-basic" name="backlinks">
                                        <option value="1">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="select2-basic">SERP API Access</label>
                                    <select class="select2 form-select" id="select2-basic" name="serp_api">
                                        <option value="1">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="select2-basic">DataForSeo Labs API Access</label>
                                    <select class="select2 form-select" id="select2-basic" name="keywords_api">
                                        <option value="1">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                                <input type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light"
                                    value="Save">
                                <button type="reset" class="btn btn-outline-secondary waves-effect"
                                    data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <!--/ Basic table -->



        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('#keywords_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("keywords_limit").readOnly = true;
        } else {
            document.getElementById("keywords_limit").readOnly = false;
        }
    });

    $('#domain_keyword_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("domain_keyword_limit").readOnly = true;
        } else {
            document.getElementById("domain_keyword_limit").readOnly = false;
        }
    });

    $('#domain_competitors_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("domain_competitors_limit").readOnly = true;
        } else {
            document.getElementById("domain_competitors_limit").readOnly = false;
        }
    });

    $('#domain_backlinks_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("domain_backlinks_limit").readOnly = true;
        } else {
            document.getElementById("domain_backlinks_limit").readOnly = false;
        }
    });
    
    $('#domain_actual_backlink_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("domain_actual_backlink_limit").readOnly = true;
        } else {
            document.getElementById("domain_actual_backlink_limit").readOnly = false;
        }
    });

    $('#domain_backlinks_rows_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("domain_backlinks_rows_limit").readOnly = true;
        } else {
            document.getElementById("domain_backlinks_rows_limit").readOnly = false;
        }
    });
    
    $('#backlinks_workload_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("backlinks_workload_limit").readOnly = true;
        } else {
            document.getElementById("backlinks_workload_limit").readOnly = false;
        }
    });

    $('#search_volume_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("search_volume_limit").readOnly = true;
        } else {
            document.getElementById("search_volume_limit").readOnly = false;
        }
    });
    
    $('#serp_limit_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("serp_limit").readOnly = true;
        } else {
            document.getElementById("serp_limit").readOnly = false;
        }
    });

    
    $('#keywords_planner_checkbox').click(function(event) {
        if (this.checked) {
            document.getElementById("keywords_planner_limit").readOnly = true;
        } else {
            document.getElementById("keywords_planner_limit").readOnly = false;
        }
    });


});
</script>
@endsection