@include('front.layout.header')

<div role="main" class="main">
    <section class="page-header page-header-modern bg-color-primary p-relative">
        <div class="container container-xl-custom">
            <div class="row py-5">
                <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                    <h1 class="text-color-light font-weight-bold text-8">{{__("Login")}}</h1>
                </div>
                <div class="col-md-4 order-1 order-md-2 align-self-center">
                    <ul class="breadcrumb d-flex justify-content-md-end text-3-5">
                        <li><a href="{{url('/')}}" class="text-color-light font-weight-semibold text-decoration-none">HOME</a></li>
                        <li class="text-color-light font-weight-semibold active">{{__("Login")}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    @php
    if(isset($_COOKIE['login_email']) && isset($_COOKIE['login_password'])) {
    $login_email = $_COOKIE['login_email'];
    $login_password = $_COOKIE['login_password'];
    $is_remember = "checked='checked'";
    } else {
    $login_email = '';
    $login_password = '';
    $is_remember = '';
    }
    @endphp

    <div class="container py-4">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="font-weight-bold text-5 mb-0">{{__("Log in to your account")}}</h2>
                <form action="{{url('/user-login')}}" method="post" class="needs-validation">
                    @csrf
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">{{__("E-mail address")}} <span class="text-color-danger">*</span></label>
                            <input type="email" name="email" class="form-control form-control-lg text-4" required value="{{$login_email}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">{{__("Password")}} <span class="text-color-danger">*</span></label>
                            <input type="password" name="password" class="form-control form-control-lg text-4" required value="{{$login_password}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <button type="submit" class="btn btn-dark btn-modern w-100 text-uppercase rounded-0 font-weight-bold text-3 py-3" data-loading-text="Bezig met laden...">{{__("Login")}}</button>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <input type="checkbox" class="custom-control-input" style="transform: scale(1.3);" id="rememberme" name="rememberme" {{$is_remember}}>
                            <label class="form-label custom-control-label cur-pointer text-2" for="rememberme" style="transform: scale(1.3); margin-left:15px;">{{__("Remember")}}</label>

                        </div>
                    </div>
                </form>
                <p><a href="{{url('/login/forgot_password')}}">{{__("Forgot Password?")}}</a></p>
                <p>Do not have an account? <a href="{{url('/pricing')}}">{{__("Sign Up")}}</a></p>
            </div>
            <div style="display: none;" class="col-md-6 col-lg-5">
                <h2 class="font-weight-bold text-5 mb-0">{{__("Register")}}</h2>
                <form action="{{url('/user-register')}}" id="frmSignUp" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">{{__("User name or e-mail address")}} <span class="text-color-danger">*</span></label>
                            <input type="email" class="form-control form-control-lg text-4" required name="email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Naam <span class="text-color-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg text-4" required name="name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Wachtwoord <span class="text-color-danger">*</span></label>
                            <input type="password" class="form-control form-control-lg text-4" required name="password">
                        </div>
                    </div>
                  <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Confirm Password <span class="text-color-danger">*</span></label>
                            <input type="password" class="form-control form-control-lg text-4" required name="confirm_password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <p class="text-2 mb-2">{{__("Your personal information is used to support your experience throughout this website, to manage access to your account and for other purposes described in our")}}
                                <a href="#" class="text-decoration-none">{{__("privacybeleid.")}}</a>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <button type="submit" class="btn btn-dark btn-modern w-100 text-uppercase rounded-0 font-weight-bold text-3 py-3" data-loading-text="Loading...">{{__("Register")}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@include('front.layout.footer')