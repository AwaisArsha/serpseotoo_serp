<!-- BEGIN: Footer-->
<footer id="footer" class="border-0 bg-transparent mt-0">
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
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
<!-- END: Footer-->


<!-- BEGIN: Vendor JS-->
<script src="{{asset('admin_assets/vendors/js/vendors.min.js')}}"></script>
<script src="{{asset('admin_assets/js/core/app-menu.min.js')}}"></script>
<script src="{{asset('admin_assets/js/core/app.js')}}"></script>

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>