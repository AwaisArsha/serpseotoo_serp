@extends('seo.layout.admin_master')

@section('profile', 'active')

@section('page-header')

<h2 class="content-header-title float-start mb-0" style="font-weight: 500; color:#636363; padding-right:1rem; border-right:1px solid #D6DCE1">{{__("Profile")}}</h2>
<div class="breadcrumb-wrapper">
    <ol class="breadcrumb" style="padding-left: 1rem !important; font-size:1rem;display:flex; flex-wrap:wrap;padding:0.3rem 0;">

        <li class="breadcrumb-item"><a href="{{url('/user/dashboard')}}">{{__("Dashboard")}}</a>

        </li>

        <li class="breadcrumb-item"><a href="{{url('/user/profile')}}">{{__("Profile")}}</a>

        </li>

    </ol>

</div>
@endsection

@section('content')



<!-- BEGIN: Content-->

<div class="app-content content ">

    <div class="content-overlay"></div>

    <div class="header-navbar-shadow"></div>

    <div class="content-wrapper p-0">

        

        <div class="content-body">

            <section id="multiple-column-form">

                <div class="row">

                    <div class="col-4">

                        @if($user_data->image == null)

                        <img src="{{asset('admin_assets/images/portrait/small/avatar-s-11.jpg')}}" alt=""

                            class="img-fluid" width="100%">

                        @else

                        <img src="{{asset('project_images'.$user_data->image)}}" alt="" class="img-fluid" width="100%">

                        @endif

                    </div>

                    <div class="col-6">

                        <div class="card">

                            <div class="card-body">

                                <form class="form" action="{{url('user/profile/update')}}" method="POST"

                                    enctype="multipart/form-data">

                                    @csrf

                                    <div class="row">

                                        <div class="col-12">

                                            <div class="mb-1">

                                                <label class="form-label" for="email-id-column">{{__("Name")}}</label>

                                                <input type="text" class="form-control" required name="name"

                                                    value="{{$user_data->Name}}">

                                            </div>

                                        </div>

                                        <div class="col-12">

                                            <div class="mb-1">

                                                <label class="form-label" for="email-id-column">{{__("E-mail")}}</label>

                                                <input type="text" class="form-control" required name="email"

                                                    value="{{$user_data->email}}">

                                            </div>

                                        </div>

                                        <div class="col-12">

                                            <div class="mb-1">

                                                <label class="form-label" for="email-id-column">Image</label>

                                                <input type="file" class="form-control" name="img">

                                            </div>

                                        </div>

                                        <div class="col-12">

                                            <div class="mb-1">

                                                <label class="form-label" for="email-id-column">{{__("Password")}}</label>

                                                <input type="password" class="form-control" required name="password">

                                            </div>

                                        </div>
                                       <div class="col-12">

                                            <div class="mb-1">

                                                <label class="form-label" for="email-id-column">{{__("Confirm Password")}}</label>

                                                <input type="password" class="form-control" required name="confirm_password">

                                            </div>

                                        </div>

                                        <div class="col-12">

                                            <div class="mb-1">

                                                <label class="form-label" for="email-id-column">{{__("Country")}}</label>

                                                <input type="text" class="form-control" name="country"

                                                    value="{{$user_data->country}}">

                                            </div>

                                        </div>



                                        <div class="col-12">

                                            <input type="submit"

                                                class="btn btn-primary me-1 waves-effect waves-float waves-light"

                                                value="Update">

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

<!-- END: Content-->



<div class="sidenav-overlay"></div>

<div class="drag-target"></div>

@endsection