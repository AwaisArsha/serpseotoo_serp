@include('front.layout.header')



<div role="main" class="main">
    <section class="page-header page-header-modern bg-color-primary p-relative">
        <div class="container container-xl-custom">
            <div class="row py-5">
                <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                    <h1 class="text-color-light font-weight-bold text-8">{{__("Blogs")}}</h1>
                </div>
                <div class="col-md-4 order-1 order-md-2 align-self-center">
                    <ul class="breadcrumb d-flex justify-content-md-end text-3-5">
                        <li><a href="{{url('/')}}" class="text-color-light font-weight-semibold text-decoration-none">HOME</a></li>
                        <li class="text-color-light font-weight-semibold active">{{__("Blogs")}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


    <div class="container py-4">



        <div class="row">

            <div class="col">

                <div class="blog-posts">



                    <div class="row">

						@foreach ($blogs as $blog)

                        <div class="col-md-4">

                            <article class="post post-medium border-0 pb-0 mb-5">

                                <div class="post-image">

                                    <a href="{{url('/blog-detail/'.$blog->id)}}">

                                        <img src="{{asset('project_images'.$blog->image)}}" style="width: 100%; height:200px;"

                                            class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0" alt="" />

                                    </a>

                                </div>



                                <div class="post-content">



                                    <h2 class="font-weight-semibold text-5 line-height-6 mt-3 mb-2" style="height:70px; overflow:hidden"><a

                                            href="{{url('/blog-detail/'.$blog->id)}}">{{$blog->title}}</a></h2>

                                    <div class="post-meta">

                                        <span><i class="bi bi-calendar"></i> <a style="cursor: default; ">{{$blog->date}}</a></span>

                                        <span class="d-block mt-2"><a href="{{url('/blog-detail/'.$blog->id)}}"

                                                class="btn btn-xs btn-light text-1 text-uppercase">Lees Verder</a></span>

                                    </div>



                                </div>

                            </article>

                        </div>

						@endforeach

                    </div>

					{{$blogs->links('front.pagination')}}

                </div>

            </div>



        </div>



    </div>

</div>



@include('front.layout.footer')