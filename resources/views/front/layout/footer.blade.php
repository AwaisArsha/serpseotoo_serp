@php
$settings = settings_data();
@endphp

<footer id="footer" class="border-0 bg-transparent mt-0">
	<section class="section section-angled bg-tertiary border-top-0">
		<div class="section-angled-layer-bottom bg-light"></div>
		<div class="section-angled-content">
			<div class="container container-xl-custom">

				<div class="row py-5 px-5">
					<div class="col-lg-4">
						<a href="{{url('/')}}" class="text-decoration-none">
							<img src="{{asset('project_images'.$settings->site_logo)}}" width="200" height="40" class="img-fluid mb-4" alt="Porto" />
						</a>
						<ul class="list list-unstyled">
							<li class="d-flex align-items-center mb-4">
								<i class="icon icon-envelope text-color-dark text-5 font-weight-bold position-relative top-1 me-3-5"></i>
								<a href="mailto:porto@seo-3.com" class="d-inline-flex align-items-center text-decoration-none text-color-dark text-color-hover-primary font-weight-semibold text-4-5">{{$settings->email}}</a>
							</li>
							<li class="d-flex align-items-center mb-4">
								<i class="icon icon-phone text-color-dark text-5 font-weight-bold position-relative top-1 me-3-5"></i>
								<a href="tel:8001234567" class="d-inline-flex align-items-center text-decoration-none text-color-dark text-color-hover-primary font-weight-semibold text-4-5">{{$settings->phone}}</a>
							</li>
						</ul>
						<ul class="social-icons social-icons-clean social-icons-medium">
							@if($settings->facebook != null)
							<li class="social-icons-facebook">
								<a href="{{$settings->facebook}}" target="_blank" title="Facebook">
									<i class="fab fa-facebook-f text-color-dark"></i>
								</a>
							</li>
							@endif
							@if($settings->twitter != null)
							<li class="social-icons-twitter">
								<a href="{{$settings->twitter}}" target="_blank" title="Twitter">
									<i class="fab fa-twitter text-color-dark"></i>
								</a>
							</li>
							@endif
							@if($settings->instagram != null)
							<li class="social-icons-instagram">
								<a href="{{$settings->instagram}}" target="_blank" title="Instagram">
									<i class="fab fa-instagram text-color-dark"></i>
								</a>
							</li>
							@endif
						</ul>
					</div>
					@php
					$usefull_links = usefull_links();
					@endphp
					<div class="col-lg-8">
						<div class="row mb-5-5">
							<div class="col-lg-6 mb-4 mb-lg-0">
								<h4 class="text-color-dark font-weight-bold mb-3">{{__('USEFUL LINKS')}}</h4>
								<ul class="list list-unstyled columns-lg-2">
								@foreach ($usefull_links as $link)
									<li><a href="{{url($link->link)}}" class="text-color-grey text-color-hover-primary">{{$link->title}}</a></li>
									@endforeach
								</ul>
							</div>
							@php
							$recent_blogs = recent_blogs();
							@endphp

							<div class="col-lg-6">
								<h4 class="text-color-dark font-weight-bold mb-3">{{__('RECENT NEWS')}}</h4>
								@foreach ($recent_blogs as $blog)
								<?php
								$timestamp = strtotime($blog->date);
								$day = date('d', $timestamp);
								$Month = date('M', $timestamp);
								$Year = date('Y', $timestamp);
								?>

								<article class="mb-3">
									<a href="{{url('/blog-detail/'.$blog->id)}}" class="text-color-dark text-3-5">{{__($blog->title)}}</a>
									<p class="line-height-2 mb-0">{{$Month}} {{$day}}, {{$Year}}</p>
								</article>
								@endforeach
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="alert alert-success d-none" id="newsletterSuccess">
									<strong>Success!</strong> You've been added to our email list.
								</div>
								<div class="alert alert-danger d-none" id="newsletterError"></div>
								<div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center">
									<h4 class="text-color-dark ws-nowrap me-3 mb-3 mb-lg-0">{{__('Subscribe to Newsletter')}}:</h4>
									<form class="form-style-3 w-100" action="{{url('/subscribers/add')}}" method="POST">
										@csrf
										<div class="d-flex">
											<input class="form-control bg-color-light border-0 box-shadow-none" placeholder="Email Address" name="email" id="newsletterEmail" type="email" />
											<input class="btn btn-primary ms-2 btn-px-3 btn-py-2 font-weight-bold" type="submit" value="{{__('GO')}}" />
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>
	<div class="footer-copyright bg-transparent">
		<div class="container">
			<div class="row">
				<div class="col mb-5">
					<p class="text-center text-3 mb-0">{{__('Copyright © 2022. All rights reserved by Serpseotools')}}</p>
				</div>
			</div>
		</div>
	</div>
</footer>

</div>



<!-- Vendor -->

<script src="{{asset('front_assets/vendor/plugins/js/plugins.min.js')}}"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

		<script src="{{asset('front_assets/vendor/gsap/gsap.min.js')}}"></script>
		<script src="{{asset('front_assets/vendor/gsap/ScrollTrigger.min.js')}}"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="{{asset('front_assets/js/theme.js')}}"></script>

		<!-- Demo -->
		<script src="{{asset('front_assets/js/demos/demo-seo-3.js')}}"></script>

		<!-- Current Page Vendor and Views -->
		<script src="{{asset('front_assets/js/views/view.contact.js')}}"></script>

		<!-- Theme Custom -->
		<script src="{{asset('front_assets/js/custom.js')}}"></script>

		<!-- Theme Initialization Files -->
		<script src="{{asset('front_assets/js/theme.init.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" type="text/javascript"></script>



<script>
	var currency = "<?php echo Session::get('currency'); ?>";

	<?php

	if (Session::has('message')) { ?>

		var type = "{{Session::get('alert-type','info')}}";

		switch (type) {

			case 'info':

				toastr.info(" {{Session::get('message')}} ");

				break;



			case 'success':

				toastr.success(" {{Session::get('message')}} ");

				break;



			case 'warning':

				toastr.warning(" {{Session::get('message')}} ");

				break;



			case 'error':

				toastr.error(" {{Session::get('message')}} ");

				break;

		}

	<?php } ?>
</script>


<!-- Default Statcounter code for serpseotools.com
https://serpseotools.com/ -->
<script type="text/javascript">
	var sc_project = 12818211;
	var sc_invisible = 1;
	var sc_security = "5f5bcdb8";
</script>
<script type="text/javascript" src="https://www.statcounter.com/counter/counter.js" async></script>
<noscript>
	<div class="statcounter"><a title="Web Analytics
Made Easy - Statcounter" href="https://statcounter.com/" target="_blank"><img class="statcounter" src="https://c.statcounter.com/12818211/0/5f5bcdb8/1/" alt="Web Analytics Made Easy - Statcounter" referrerPolicy="no-referrer-when-downgrade"></a></div>
</noscript>
<!-- End of Statcounter Code -->

</body>



</html>