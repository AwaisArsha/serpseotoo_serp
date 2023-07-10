@extends('admin.layout.admin_master')
@section('satisfaction-reasons', 'active')
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Customer Reviews</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="{{url('/admin/satisfaction-reasons')}}">Reasons Data</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="card">
                        
                        <div class="card-body">
                            <form class="form form-horizontal" method="POST" action="{{url('/admin/satisfaction-reason/save/')}}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{$reason->id}}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">First Heading</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="name" class="form-control" name="first_heading"
                                                    placeholder="First Heading" required value="{{$reason->first_heading}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Second Heading</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="service" class="form-control" name="second_heading"
                                                    placeholder="Second Heading" required value="{{$reason->second_heading}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Detail</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <textarea type="text" id="review" class="form-control" name="detail"
                                                    placeholder="Detail" required rows="5">{{$reason->detail}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Banner</label>
                                            </div>
                                            <div class="col-sm-9">
                                            <input type="file" id="service" class="form-control" name="img">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 offset-sm-3">
                                        <input type="submit"
                                            class="btn btn-primary me-1 waves-effect waves-float waves-light" value="Save">
                                        <a type="reset" href="{{url('/admin/satisfaction-resons')}}"
                                            class="btn btn-outline-secondary waves-effect">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection