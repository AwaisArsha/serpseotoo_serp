@include('front.layout.header')
@php
get_currency();
@endphp
<div role="main" class="main">
    <section
        class="page-header page-header-modern page-header-background page-header-background-md overlay overlay-color-dark overlay-show overlay-op-7"
        style="background-image: url({{asset('front_assets/images/banner-7.jpg')}}); background-size: cover; background-repeat:no repeat; background-position: 50% 40%;">
        <div class="container">
            <div class="row mt-5">
                <div class="col-md-12 align-self-center p-static order-2 text-center">
                    <h1 class="text-9 font-weight-bold">{{__("Payment By Stripe")}}</h1>
                    <span class="sub-title">{{__("Aspire to inspire before we expire")}}</span>
                </div>
            </div>
        </div>
    </section>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @php
    $stripe_key = $stripe_publishable_key;
    @endphp

    <div class="container py-4">

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <h2 class="font-weight-bold text-5 mb-0">{{__("Stripe Payment")}}</h2>
                <p>{{__("You will be charged")}} {{$price}}{!!Session::get('currency')!!}</p>

                <form action="{{url('/stripe/pricing/upgradation')}}" method="post" id="payment-form">
                    @csrf
                    <input type="hidden" name="user_id" value="{{$user_id}}">
                    <input type="hidden" name="package_id" value="{{$package_id}}">
                    <input type="hidden" name="shopier_id" value="{{$shopier_id}}">
                    <input type="hidden" name="price" value="{{$price}}">
                    <div class="form-group">
                        <div class="card-header">
                            <label for="card-element">
                                {{__("Enter your credit card information")}}
                            </label>
                        </div>
                        <div class="card-body">
                            <div id="card-element">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert"></div>
                            <input type="hidden" name="plan" value="" />
                        </div>
                    </div>
                    <div class="card-footer">
                        <button id="card-button" class="btn btn-dark" type="submit" data-secret="{{ $intent }}">
                            {{__("Pay")}} </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)

    var style = {
        base: {
            color: '#32325d',
            lineHeight: '18px',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    const stripe = Stripe('{{ $stripe_key }}', {
        locale: 'en'
    }); // Create a Stripe client.
    const elements = stripe.elements(); // Create an instance of Elements.
    const cardElement = elements.create('card', {
        style: style
    }); // Create an instance of the card Element.
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    cardElement.mount('#card-element'); // Add an instance of the card Element into the `card-element` <div>.

    // Handle real-time validation errors from the card Element.
    cardElement.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission.
    var form = document.getElementById('payment-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.handleCardPayment(clientSecret, cardElement, {
                payment_method_data: {
                    //billing_details: { name: cardHolderName.value }
                }
            })
            .then(function(result) {
                console.log(result);
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    console.log(result);
                    form.submit();
                }
            });
    });
    </script>

    @include('front.layout.footer')