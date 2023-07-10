@include('front.layout.header')

<div role="main" class="main">
    <section class="page-header page-header-modern bg-color-primary p-relative">
        <div class="container container-xl-custom">
            <div class="row py-5">
                <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                    <h1 class="text-color-light font-weight-bold text-8">{{__("Change Password")}}</h1>
                </div>
                <div class="col-md-4 order-1 order-md-2 align-self-center">
                    <ul class="breadcrumb d-flex justify-content-md-end text-3-5">
                        <li><a href="{{url('/')}}" class="text-color-light font-weight-semibold text-decoration-none">HOME</a></li>
                        <li class="text-color-light font-weight-semibold active">{{__("Change Password")}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="font-weight-bold text-5 mb-0">{{__('Change Password')}}</h2>
                <form action="{{url('/user/change_password/save')}}" method="post" class="needs-validation">
                    @csrf
                    <input type="hidden" name="id" value="{{$id}}">
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">{{__('New Password')}} <span class="text-color-danger">*</span></label>
                            <input type="password" name="new_password" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">{{__('Confirm Password')}} <span class="text-color-danger">*</span></label>
                            <input type="password" name="confirm_password" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <button type="submit" class="btn btn-dark btn-modern w-100 text-uppercase rounded-0 font-weight-bold text-3 py-3" data-loading-text="Loading...">{{__('Submit')}}</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@include('front.layout.footer')