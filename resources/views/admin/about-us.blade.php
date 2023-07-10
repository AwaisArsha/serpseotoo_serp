@extends('admin.layout.admin_master')
@section('about-us', 'active')
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">About Us</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin/about-us')}}">About Us</a>
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
                                    <form class="form" action="{{url('/admin/about-us/update')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$about->id}}">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Short Detail</label>
                                                    <textarea class="form-control" name="short_detail"
                                                    placeholder="Detail" required rows="5">{{$about->short_detail}}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="last-name-column">Our Mission</label>
                                                    <textarea type="text" id="last-name-column" class="form-control" placeholder="Mission" name="mission" required rows="5">{{$about->mission}}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="city-column">Our Vision</label>
                                                    <textarea class="form-control" placeholder="Vision" name="vision" required rows="5">{{$about->vision}}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="country-floating">Why Us</label>
                                                    <textarea class="form-control" name="why_us" placeholder="Why Us?" required rows="5">{{$about->why_us}}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="company-column">Happy Cliens</label>
                                                    <input type="num" class="form-control" name="happy_clients" placeholder="40000" required value="{{$about->happy_clients}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="email-id-column">Years in Business</label>
                                                    <input type="num" class="form-control" name="years_in_business" placeholder="15" required value="{{$about->years_in_business}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="email-id-column">High Score</label>
                                                    <input type="num" class="form-control" name="high_score" placeholder="178" required value="{{$about->high_score}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="email-id-column">Cups of Coffee</label>
                                                    <input type="num" class="form-control" name="cups_of_coffee" placeholder="352" required value="{{$about->cups_of_coffee}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="email-id-column">Image</label>
                                                    <input type="file" class="form-control" name="img">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="country-floating">Detail</label>
                                                    <textarea class="form-control" name="detail" placeholder="Detail" required rows="10">{{$about->detail}}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <input type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light" value="Save">
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