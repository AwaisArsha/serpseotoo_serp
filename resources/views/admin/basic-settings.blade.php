@extends('admin.layout.admin_master')
@section('basic-settings', 'active')
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Basic Settings</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin/about-us')}}">Settings</a>
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
                                <form class="form" action="{{url('/admin/basic-settings/save')}}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$settings->id}}">
                                    <div class="row">

                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="company-column">Site Logo</label>
                                                <input type="file" class="form-control" name="site_logo">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="company-column">Admin Logo</label>
                                                <input type="file" class="form-control" name="admin_logo">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Address</label>
                                                <input type="text" class="form-control" name="address" placeholder="15"
                                                    required value="{{$settings->address}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Phone</label>
                                                <input type="text" class="form-control" name="phone" required
                                                    value="{{$settings->phone}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Email</label>
                                                <input type="email" class="form-control" name="email" required
                                                    value="{{$settings->email}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Twitter Handle</label>
                                                <input type="text" class="form-control" name="twitter" required
                                                    value="{{$settings->twitter}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Facebook Handle</label>
                                                <input type="text" class="form-control" name="facebook" required
                                                    value="{{$settings->facebook}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Instagram Handle</label>
                                                <input type="instagram" class="form-control" name="instagram" required
                                                    value="{{$settings->instagram}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="select2-basic">Currency</label>
                                                <select class="select2 form-select" id="select2-basic" name="currency">
                                                    
                                                    @foreach ($currencies as $currency)
                                                    @php
                                                        if($currency->html_symbol == $settings->currency) {
                                                            $selected = "selected";
                                                        } else {
                                                            $selected = "";
                                                        }
                                                    @endphp
                                                    <option value="{{$currency->html_symbol}}" {{$selected}}>
                                                        {{$currency->currency_name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="select2-basic">Payment Method</label>
                                                <select class="select2 form-select" id="select2-basic" name="payment_method">
                                                    <option value="paypal" <?php if($settings->payment_method == "paypal") { echo "selected"; } ?> >Paypal</option>
                                                    <option value="stripe" <?php if($settings->payment_method == "stripe") { echo "selected"; } ?>>Stripe</option>
                                                    <option value="both" <?php if($settings->payment_method == "both") { echo "selected"; } ?>>Paypal and Stripe</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Domain Email Host</label>
                                                <input type="text" class="form-control" placeholder="Domain Host to be used for Emails" value="{{$settings->for_emails_host}}" name="for_emails_host">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Domain Email ID</label>
                                                <input type="email" class="form-control" placeholder="Domain Email to be used for Emails" value="{{$settings->for_emails_email}}" name="for_emails_email">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="email-id-column">Domain Email Password</label>
                                                <input type="text" class="form-control" placeholder="Domain Password to be used for Emails" value="{{$settings->for_emails_password}}" name="for_emails_password">
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