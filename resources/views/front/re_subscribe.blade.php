@include('front.layout.header')



<div role="main" class="main">
    <section class="page-header page-header-modern bg-color-primary p-relative">
        <div class="container container-xl-custom">
            <div class="row py-5">
                <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                    <h1 class="text-color-light font-weight-bold text-8">{{__("Subscribe Again")}}</h1>
                </div>
                <div class="col-md-4 order-1 order-md-2 align-self-center">
                    <ul class="breadcrumb d-flex justify-content-md-end text-3-5">
                        <li><a href="{{url('/')}}" class="text-color-light font-weight-semibold text-decoration-none">HOME</a></li>
                        <li class="text-color-light font-weight-semibold active">{{__("Subscribe Again")}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container">

        <div class="row" style="margin-bottom: 100px;margin-top:50px;">

            <div class="col">

                <div class="pricing-block border rounded">

                    <div class="row">

                        <div class="col-lg-9" style="display:inline-block;vertical-align: middle;">

                            <h2 class="font-weight-semibold text-6 line-height-1 mb-3">You really want to?

                                  Subscribe again

                            </h2>



                        </div>

                        <div class="col-lg-3"

                            style="padding-top: 0px; padding-bottom:0px; margin-top:0px; margin-bottom:0px;">

                            <a href="{{url('/re-subscribe/update')}}" class="btn btn-primary btn-modern btn-xl">Subscribe Again</a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>





</div>



@include('front.layout.footer')