@extends('admin.layout.admin_master')
@section('blogs', 'active')
@section('content')

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Blogs Data</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#">Blog</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card">
                        
                        <div class="card-body">
                            <form class="form form-horizontal" method="POST" action="{{url('/admin/blog/save')}}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{$blog->id}}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="first-name">Title</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="title"
                                                    placeholder="Title" required value="{{$blog->title}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Image</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="file" class="form-control" name="img">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Date</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" name="date"
                                                    placeholder="Date" required value="{{$blog->date}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="email-id">Detail</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <textarea type="text" id="editor1" class="form-control" name="detail"
                                                    placeholder="Detail" required rows="10">{{$blog->detail}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 offset-sm-3">
                                        <input type="submit"
                                            class="btn btn-primary me-1 waves-effect waves-float waves-light" value="Save">
                                        <a type="reset" href="{{url('/admin/blogs')}}"
                                            class="btn btn-outline-secondary waves-effect">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.10.0/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('editor1');
</script>

@endsection