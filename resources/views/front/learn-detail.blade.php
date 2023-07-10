@include('front.layout.header')

<div role="main" class="main">
    <section class="page-header page-header-modern bg-color-primary p-relative">
        <div class="container container-xl-custom">
            <div class="row py-5">
                <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                    <h1 class="text-color-light font-weight-bold text-8">{{__("Learn")}}</h1>
                </div>
                <div class="col-md-4 order-1 order-md-2 align-self-center">
                    <ul class="breadcrumb d-flex justify-content-md-end text-3-5">
                        <li><a href="{{url('/')}}" class="text-color-light font-weight-semibold text-decoration-none">HOME</a></li>
                        <li class="text-color-light font-weight-semibold active">{{__("Learn")}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">

        <div class="row">
            <div class="col">
                <div class="blog-posts single-post">

                    <article class="post post-large blog-single-post border-0 m-0 p-0">
                        <div class="post-image ms-0">
                            <a href="blog-post.html">
                                <img src="{{asset('project_images'.$blog->image)}}" style="width:100%; height:auto;"
                                    class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0" alt="" />
                            </a>
                        </div>

                        <div class="post-date ms-0">
                        <?php
                            $timestamp = strtotime($blog->date);
                            $day = date('d', $timestamp);
                            $Month = date('M', $timestamp);
                            ?>
                            <span class="day">{{$day}}</span>
                            <span class="month">{{$Month}}</span>
                        </div>

                        <div class="post-content ms-0">

                            <h2 class="font-weight-semi-bold"><a href="{{url('blog-detail/'.$blog->id)}}">{{$blog->title}}</a></h2>

                            <div class="post-meta">
                                <span><i class="far fa-user"></i> By <strong>Zampa</strong> </span>
                            </div>

                            {!!$blog->detail!!}


                        </div>
                    </article>

                </div>
            </div>
        </div>

    </div>
</div>

@include('front.layout.footer')