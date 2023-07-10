@extends('admin.layout.admin_master')
@section('users', 'active')
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Users Data</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin/users')}}">Users</a>
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
                        <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                <form method="get" action="{{url('/admin/users/search')}}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6">
                                            <input type="search" class="form-control my-1 mx-1" placeholder="Search" name="s"
                                                aria-controls="DataTables_Table_0">
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <input type="submit" value="Search" class="btn btn-primary my-1">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <table class="datatables-basic table dataTable no-footer dtr-column"
                                    id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info"
                                    style="width: 1218px;">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Email</th>
                                    
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 134px;"
                                                aria-label="Status: activate to sort column ascending">Name</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 145px;"
                                                aria-label="Actions">Package</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 145px;"
                                                aria-label="Actions">Package Expire</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 145px;"
                                                aria-label="Actions">Country</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 145px;"
                                                aria-label="Actions">Status</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 145px;"
                                                aria-label="Actions">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($users) > 0)
                                        @foreach ($users as $user)
                                        <tr class="odd">
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$user->email}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$user->Name}}
                                            </td>
                                            @php
                                                $package_info = DB::table('packages')->where('id', $user->package_id)->first();
                                            @endphp
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$package_info->title}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$user->expire_date}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty text-center">
                                            @php
                                            if($user->country != null) {
                                                echo $user->country;
                                            } else {
                                                echo "-";
                                            }
                                            @endphp
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                @if($user->status == '1')
                                                <a href="{{url('admin/user/inactive/'.$user->id)}}"><span
                                                        class="badge rounded-pill badge-light-success me-1">Active</span></a>
                                                @elseif ($user->status == '0')
                                                <a href="{{url('admin/user/active/'.$user->id)}}"><span
                                                        class="badge rounded-pill badge-light-secondary me-1">Inactive</span></a>
                                                @endif
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                <a href="{{url('admin/user/edit/'.$user->id)}}"><span
                                                        class="badge rounded-pill badge-light-warning me-1"><i
                                                            data-feather="edit-2" class="me-50"></i>Edit</span></a>
                                                <a href="{{url('admin/user/delete/'.$user->id)}}"><span
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
                            {{$users->links('pagination')}}
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Basic table -->



        </div>
    </div>
</div>

@endsection