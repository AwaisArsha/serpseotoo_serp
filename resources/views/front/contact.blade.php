@include('front.layout.header')
@php
$settings = settings_data();
@endphp

<style>
  #g-recaptcha-response {
  display: block !important;
  position: absolute;
  margin: -78px 0 0 0 !important;
  width: 302px !important;
  height: 76px !important;
  z-index: -999999;
  opacity: 0;
}
</style>

<div role="main" class="main">

    <section class="page-header page-header-modern bg-color-primary p-relative">
        <div class="container container-xl-custom">
            <div class="row py-5">
                <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                    <h1 class="text-color-light font-weight-bold text-8">{{__("Contact")}}</h1>
                </div>
                <div class="col-md-4 order-1 order-md-2 align-self-center">
                    <ul class="breadcrumb d-flex justify-content-md-end text-3-5">
                        <li><a href="{{url('/')}}" class="text-color-light font-weight-semibold text-decoration-none">HOME</a></li>
                        <li class="text-color-light font-weight-semibold active">{{__("Contact")}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container container-xl-custom p-relative z-index-2 py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9 text-center">

                <h2 class="text-color-dark font-weight-bold text-10 ls-0 pb-2 mb-3">{{$contact_page->heading}}</h2>

                <p class="font-weight-medium text-4-5 line-height-5">{{$contact_page->text}}</p>

            </div>
        </div>

        <div class="row p-relative mt-5 py-3">
            <div class="col-lg-6 py-5 py-lg-0 align-self-center">
                <h2 class="text-color-dark font-weight-bold text-6 ls-0 pb-2 mb-3">{{__("Contact Us")}}</h2>
                <ul class="list list-icons list-icons-style-2 mt-2">
                    <li><i class="fas fa-map-marker-alt top-6"></i> <strong class="text-dark">{{__('Address')}}:</strong>
                        {{$settings->address}}
                    </li>
                    <li><i class="fas fa-phone top-6"></i> <strong class="text-dark">{{__('Phone')}}:</strong>
                        {{$settings->phone}}
                    </li>
                    <li><i class="fas fa-envelope top-6"></i> <strong class="text-dark">{{__('E-mail')}}:</strong> <a href="mailto:{{$settings->email}}">{{$settings->email}}</a></li>
                </ul>
                <h2 class="text-color-dark font-weight-bold text-6 ls-0 pb-2 mb-3 mt-5">Company {{__('Office Hours')}}</h2>
                <ul class="list list-icons list-dark mt-2">
                    <li><i class="far fa-clock top-6"></i> {{__('Monday - Friday - 9am to 5pm')}}</li>
                    <li><i class="far fa-clock top-6"></i> {{__('Saturday - 9am to 2pm')}}</li>
                    <li><i class="far fa-clock top-6"></i> {{__('Sunday - Closed')}}</li>
                </ul>
            </div>
            <div class="col-lg-6 text-center text-lg-start py-5 py-lg-0 align-self-center">

                <div class="card border-0 bg-color-light">
                    <div class="card-body px-lg-5 py-5 box-shadow-6 border-radius-2">

                        <h2 class="text-color-dark font-weight-bold text-6 ls-0 pb-2 mb-3">{{__("Send a Message")}}</h2>
                        <form class="form-style-3" action="{{url('/user/messageXXXXXXX')}}" method="POST">
                            @csrf
                            <div class="row row-gutter-sm">
                                <div class="form-group col-lg-6 mb-4">
                                    <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name" required placeholder="{{__('Your Name')}}">
                                </div>
                                <div class="form-group col-lg-6 mb-4">
                                    <input type="email" value="" data-msg-required="{{__('Please enter your email address.')}}" maxlength="100" class="form-control" name="email" id="email" required placeholder="{{__('Email Address')}}">
                                </div>
                            </div>
                            <div class="row row-gutter-sm">
                                <div class="form-group col-lg-12 mb-4">
                                    <input type="text" value="" data-msg-required="{{__('Please enter the subject.')}}" maxlength="200" class="form-control" name="subject" id="subject" required placeholder="{{__('Subject')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col mb-4">
                                    <textarea maxlength="5000" data-msg-required="{{__('Please enter your message.')}}" rows="10" class="form-control" name="message" id="message" required placeholder="{{__('Your Message')}}"></textarea>
                                </div>
                            </div>
                          	@if($api->recaptcha_site_key != null)
                            <div class="row">
                                <div class="form-group col mb-4">
                                    <div class="g-recaptcha" data-sitekey="{{$api->recaptcha_site_key}}"></div>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="form-group col mb-0">
                                    <button type="submit" class="btn btn-modern btn-primary border-0 font-weight-semi-bold positive-ls-1 text-uppercase text-2-5 px-5 py-3" data-loading-text="Loading...">{{__('Send Message')}}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
window.addEventListener('load', () => {
  const $recaptcha = document.getElementById('g-recaptcha-response');
  if ($recaptcha) {
    $recaptcha.setAttribute('required', 'required');
  }
})
</script>

@include('front.layout.footer')