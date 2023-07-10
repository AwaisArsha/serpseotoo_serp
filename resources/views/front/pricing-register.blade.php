@include('front.layout.header')
@php
get_currency();
@endphp
<div role="main" class="main">
    <section class="page-header page-header-modern bg-color-primary p-relative">
        <div class="container container-xl-custom">
            <div class="row py-5">
                <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                    <h1 class="text-color-light font-weight-bold text-8">{{__("Aan de Slag")}}</h1>
                </div>
                <div class="col-md-4 order-1 order-md-2 align-self-center">
                    <ul class="breadcrumb d-flex justify-content-md-end text-3-5">
                        <li><a href="{{url('/')}}" class="text-color-light font-weight-semibold text-decoration-none">HOME</a></li>
                        <li class="text-color-light font-weight-semibold active">{{__("Aan de Slag")}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <h2 class="font-weight-bold text-5 mb-0">Register for an account</h2>
                <form action="{{url('/user-register')}}" id="frmSignUp" method="post">
                    @csrf
                   <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Name <span
                                    class="text-color-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg text-4" required name="name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Email Adres  <span
                                    class="text-color-danger">*</span></label>
                            <input type="email" class="form-control form-control-lg text-4" required name="email">
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Password <span
                                    class="text-color-danger">*</span></label>
                            <input type="password" class="form-control form-control-lg text-4" required name="password">
                        </div>
                    </div>
                   <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Confirm Password <span
                                    class="text-color-danger">*</span></label>
                            <input type="password" class="form-control form-control-lg text-4" required name="confirm_password">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px;">
                    <label class="form-label text-color-dark text-3">Payment Methods <span
                                    class="text-color-danger">*</span></label>
                        <div class="d-flex flex-column">
                            @if ($settings->payment_method == "both" || $settings->payment_method == null)    
                            <label class="d-flex align-items-center text-color-grey mb-0" for="payment_method1">
                                <input id="payment_method1" type="radio" class="me-2" name="payment_method"
                                    value="stripe" checked />
                                Stripe
                            </label>
                            <label class="d-flex align-items-center text-color-grey mb-0" for="payment_method2">
                                <input id="payment_method2" type="radio" class="me-2" name="payment_method"
                                    value="paypal" />
                                PayPal
                            </label>
                            @elseif ($settings->payment_method == "stripe")    
                            <label class="d-flex align-items-center text-color-grey mb-0" for="payment_method1">
                                <input id="payment_method1" type="radio" class="me-2" name="payment_method"
                                    value="stripe" checked />
                                Stripe
                            </label>
                            @elseif ($settings->payment_method == "paypal")
                            <label class="d-flex align-items-center text-color-grey mb-0" for="payment_method2">
                                <input id="payment_method2" type="radio" class="me-2" name="payment_method"
                                    value="paypal" checked />
                                PayPal
                            </label>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="price" value="{{$package->price}}">
                    <input type="hidden" name="package_id" value="{{$package->id}}">
                    <strong class="d-block text-color-dark mb-2">Total : {{$package->price}}{!!Session::get('currency')!!}</strong>
                    <div class="row">
                        <div class="form-group col">
                            <button type="submit"
                                class="btn btn-dark btn-modern w-100 text-uppercase rounded-0 font-weight-bold text-3 py-3"
                                data-loading-text="Loading...">Continue</button>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col">
                            <p class="text-2 mb-2">Do you already have an account?
                                <a href="{{url('/login-register')}}" class="text-decoration-none">Sign In</a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@include('front.layout.footer')