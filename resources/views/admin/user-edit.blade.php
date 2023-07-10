@extends('admin.layout.admin_master')
@section('users', 'active')
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Users Data</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('/admin/users')}}">Users</a>
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
                            <form class="form form-horizontal" method="POST" action="{{url('/admin/user/edit/done/')}}">
                                @csrf
                                <input type="hidden" name="id" value="{{$user->id}}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">Name</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="name" class="form-control" name="name"
                                                    placeholder="Name" required value="{{$user->Name}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">Email</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="name" class="form-control" name="email"
                                                    placeholder="Email" required value="{{$user->email}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">Password</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="password" class="form-control" name="password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Country</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="service" class="form-control" name="country"
                                                    placeholder="Country" required value="{{$user->country}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Payment Mode</label>
                                            </div>
                                            <div class="col-sm-9">
                                                @php
                                                $paypal = null;
                                                $stripe = null;
                                                if($user->payment_method == "paypal") {
                                                $paypal = "selected";
                                                } else if($user->payment_method == "stripe") {
                                                $stripe = "selected";
                                                }
                                                @endphp
                                                <select name="payment_mode" class="select2 form-select">
                                                    <option value="paypal" {{$paypal}}>Paypal</option>
                                                    <option value="stripe" {{$stripe}}>Stripe</option>
                                                </select>
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