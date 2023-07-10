@extends('admin.layout.admin_master')
@section('our-team', 'active')
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Our Team</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin/our-team')}}">Team</a>
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
                                    <label style="text-align: left;font-weight: normal; ">Search:
                                        <input type="search" class="form-control" placeholder=""
                                            aria-controls="DataTables_Table_0"
                                            style="display: inline-block; width:auto;">
                                    </label>
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
                                                    </svg>Add New Record</span></button>
                                        </div>
                                    </div>
                                </div>
                                <table class="datatables-basic table dataTable no-footer dtr-column"
                                    id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info"
                                    style="width: 1218px;">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Image</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Salary: activate to sort column ascending">Job</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 139px;"
                                                aria-label="Salary: activate to sort column ascending">Facebook handle</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 139px;"
                                                aria-label="Salary: activate to sort column ascending">Twitter Handle</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 134px;"
                                                aria-label="Status: activate to sort column ascending">Status</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 145px;"
                                                aria-label="Actions">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($teams) > 0)
                                        @foreach ($teams as $team)
                                        <tr class="odd">
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                <img src="{{asset('project_images'.$team->image)}}" style="width: 100px; height:60px;" >
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$team->name}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$team->job}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$team->fb}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$team->tw}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                @if($team->status == '1')
                                                <a href="{{url('admin/our-team/inactive/'.$team->id)}}"><span
                                                        class="badge rounded-pill badge-light-success me-1">Active</span></a>
                                                @elseif ($team->status == '0')
                                                <a href="{{url('admin/our-team/active/'.$team->id)}}"><span
                                                        class="badge rounded-pill badge-light-secondary me-1">Inactive</span></a>
                                                @endif
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                <a href="{{url('admin/our-team/edit/'.$team->id)}}"><span
                                                        class="badge rounded-pill badge-light-warning me-1"><i
                                                            data-feather="edit-2" class="me-50"></i>Edit</span></a>
                                                <a href="{{url('admin/our-team/delete/'.$team->id)}}"><span
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
                        <form class="add-new-record modal-content pt-0" method="POST" action="{{url('/admin/our-team/add')}}" enctype="multipart/form-data">
                            @csrf
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close">×</button>
                            <div class="modal-header mb-1">
                                <h5 class="modal-title" id="exampleModalLabel">New Reason</h5>
                            </div>
                            <div class="modal-body flex-grow-1">
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-fullname">Name</label>
                                    <input type="text" class="form-control dt-full-name" name="name"
                                        id="basic-icon-default-fullname" placeholder="Name" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-post">Job</label>
                                    <input type="text" id="basic-icon-default-post" class="form-control dt-post"
                                        placeholder="Job" name="job" required>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-email">Facebook Handle</label>
                                    <input type="text" id="basic-icon-default-email" class="form-control dt-email"
                                        placeholder="Facebook Handle" required name="fb">
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-email">Twitter Handle</label>
                                    <input type="text" id="basic-icon-default-email" class="form-control dt-email"
                                        placeholder="Twitter Handle" required name="tw">
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-email">Image</label>
                                    <input type="file" id="basic-icon-default-post" class="form-control dt-post"
                                        name="img" required>
                                </div>
                                <input type="submit"
                                            class="btn btn-primary me-1 waves-effect waves-float waves-light" value="Save">
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

@endsection