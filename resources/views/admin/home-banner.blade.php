@extends('admin.layout.admin_master')
@section('home-banner', 'active')
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Home Banner</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin/home-banner')}}">Home Banner</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="form" action="{{url('/admin/home-banner/update')}}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$home_banner->id}}">
                                    <div class="row">

                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="company-column">Image</label>
                                                <input type="file" class="form-control" name="img">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">First Heading</label>
                                                <input type="text" class="form-control" name="first_heading" placeholder="First Heading"
                                                    required value="{{$home_banner->first_heading}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Second Heading</label>
                                                <input type="text" class="form-control" name="second_heading" required placeholder="Second Heading"
                                                    value="{{$home_banner->second_heading}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Third Heading</label>
                                                <input type="text" class="form-control" name="third_heading" required placeholder="Third Heading"
                                                    value="{{$home_banner->third_heading}}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <input type="submit"
                                                class="btn btn-primary me-1 waves-effect waves-float waves-light"
                                                value="Save">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>


@endsection