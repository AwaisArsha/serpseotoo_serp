@extends('admin.layout.admin_master')
@section('paypal', 'active')
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">API</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin/about-us')}}">API</a>
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
                                    <form class="form" action="{{url('/admin/api/update')}}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="email-id-column">API Mail DataFoSeo</label>
                                                    <input type="text" class="form-control" required name="api_email" value="{{$api->api_email}}">
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="email-id-column">API Key DataFoSeo</label>
                                                    <input type="text" class="form-control" required name="api_key" value="{{$api->api_key}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="email-id-column">API Keywords Everywhere</label>
                                                    <input type="text" class="form-control" required name="keywords_everywhere_api" value="{{$api->keywords_everywhere_api}}">
                                                </div>
                                            </div>
                                          <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="email-id-column">Recaptcha Site Key</label>
                                                    <input type="text" class="form-control" required name="recaptcha_site_key" value="{{$api->recaptcha_site_key}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="email-id-column">Recaptcha Secret Key</label>
                                                    <input type="text" class="form-control" required name="recaptcha_secret_key" value="{{$api->recaptcha_secret_key}}">
                                                </div>
                                            </div>
                                          
                                            
                                              
                                            
                                            <div class="col-12">
                                                <input type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light" value="Update">
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