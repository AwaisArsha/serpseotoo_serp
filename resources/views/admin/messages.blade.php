@extends('admin.layout.admin_master')
@if ($status == "unread")
@section('unread', 'active')
@else
@section('read', 'active')
@endif
@section('content')




<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Messages Data</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin/messages')}}">Messages</a>
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
                                
                                <table class="datatables-basic table dataTable no-footer dtr-column"
                                    id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info"
                                    style="width: 1218px;">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting sorting_asc" tabindex="0"
                                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                style="width: 117px;"
                                                aria-label="Name: activate to sort column descending"
                                                aria-sort="ascending">Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Email</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 109px;"
                                                aria-label="Date: activate to sort column ascending">Subject</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" style="width: 139px;"
                                                aria-label="Salary: activate to sort column ascending">Mark as Read</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 145px;"
                                                aria-label="Actions">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($messages) > 0)
                                        @foreach ($messages as $message)
                                        <tr class="odd">
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$message->name}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$message->email}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">{{$message->subject}}
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                @if ($message->read_status == 0)    
                                                <a href="{{url('admin/message/mark-read/'.$message->id)}}"><span
                                                        class="badge rounded-pill badge-light-success me-1">Mark as read</span></a>
                                                @else
                                                <a href="{{url('admin/message/mark-unread/'.$message->id)}}"><span
                                                        class="badge rounded-pill badge-light-success me-1">Mark as unread</span></a>
                                                @endif
                                            </td>
                                            <td valign="top" colspan="1" class="dataTables_empty">
                                                <a href="{{url('admin/view-message/'.$message->id)}}"><span
                                                        class="badge rounded-pill badge-light-warning me-1"><i
                                                            data-feather="edit-2" class="me-50"></i>View</span></a>
                                                <a href="{{url('admin/message/delete/'.$message->id)}}"><span
                                                        class="badge rounded-pill badge-light-danger me-1"><i
                                                            data-feather="trash" class="me-50"></i>Delete</span></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="odd" style="text-align: center;">
                                            <td valign="top" colspan="6" class="dataTables_empty">No Data To Show</td>
                                        </tr>
                                        @endif
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
</div>s
@endsection