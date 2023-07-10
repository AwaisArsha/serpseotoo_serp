@extends('admin.layout.admin_master')
@section('home-fourth-section', 'active')
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Home Fourth Section</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin/home-fourth-section')}}">Home Fourth Section</a>
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
                                <form class="form" action="{{url('/admin/home-fourth-section/update')}}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$home_fourth_section->id}}">
                                    <div class="row">

                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Title</label>
                                                <input type="text" class="form-control" name="title" placeholder="Title"
                                                    required value="{{$home_fourth_section->title}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">First Section Title</label>
                                                <input type="text" class="form-control" name="first_title" required placeholder="First Section Title"
                                                    value="{{$home_fourth_section->first_title}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="company-column">First Section Image</label>
                                                <input type="file" class="form-control" name="first_img">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Second Section Title</label>
                                                <input type="text" class="form-control" name="second_title" required placeholder="Second Section Title"
                                                    value="{{$home_fourth_section->second_title}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="company-column">Second Section Image</label>
                                                <input type="file" class="form-control" name="second_img">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Third Section Title</label>
                                                <input type="text" class="form-control" name="third_title" required placeholder="Third Section Title"
                                                    value="{{$home_fourth_section->third_title}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="company-column">Third Section Image</label>
                                                <input type="file" class="form-control" name="third_img">
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