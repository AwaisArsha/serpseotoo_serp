@extends('admin.layout.admin_master')
@section('services-page-highlights', 'active')
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Services Page Highlights</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin/services-page-highlights')}}">Services Page Highlights</a>
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
                            <form class="form form-horizontal" method="POST"
                                action="{{url('/admin/services-page-highlights/save/')}}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{$services_page_highlights->id}}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">Name</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="name" class="form-control" name="title"
                                                    placeholder="Title" required
                                                    value="{{$services_page_highlights->title}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Description</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="service" class="form-control"
                                                    name="description" placeholder="Description" required
                                                    value="{{$services_page_highlights->description}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Image</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="file" id="service" class="form-control" name="img">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 offset-sm-3">
                                        <input type="submit"
                                            class="btn btn-primary me-1 waves-effect waves-float waves-light"
                                            value="Save">
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