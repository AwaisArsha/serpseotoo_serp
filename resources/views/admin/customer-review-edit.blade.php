@extends('admin.layout.admin_master')
@section('customer-reviews', 'active')
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
                                <li class="breadcrumb-item active"><a href="#">Reviews Data</a>
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
                            <form class="form form-horizontal" method="POST" action="{{url('/admin/customer-review/save/')}}">
                                @csrf
                                <input type="hidden" name="id" value="{{$comment->id}}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">Customer Name</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="name" class="form-control" name="name"
                                                    placeholder="Name" required value="{{$comment->cus_name}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Service</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="service" class="form-control" name="service"
                                                    placeholder="Service" required value="{{$comment->service}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Review</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <textarea type="text" id="review" class="form-control" name="review"
                                                    placeholder="Review" required rows="5">{{$comment->review}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 offset-sm-3">
                                        <input type="submit"
                                            class="btn btn-primary me-1 waves-effect waves-float waves-light" value="Save">
                                        <a type="reset" href="{{url('/admin/customer-reviews')}}"
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