@extends('seo.layout.admin_master')

@section('related-keywords-history', 'active')

@section('page-header')
<h2 class="content-header-title float-start mb-0" style="font-weight: 500; color:#636363; padding-right:1rem; border-right:1px solid #D6DCE1">Related Keywords History</h2>

<div class="breadcrumb-wrapper">
    <ol class="breadcrumb" style="padding-left: 1rem !important; font-size:1rem;display:flex; flex-wrap:wrap;padding:0.3rem 0;">
        <li class="breadcrumb-item"><a href="{{url('/user-dashboard')}}">{{__("Dashboard")}}</a>
        </li>
        <li class="breadcrumb-item"><a href="{{url('/user/related-keywords/history')}}">{{__("Monthly Detail")}}</a>
        </li>
        <li class="breadcrumb-item"><strong style="color: black;">
                @php
                echo $keywords_data[0]->keyword." (".$keywords_data[0]->related_keyword.")";
                @endphp
            </strong>
        </li>
    </ol>
</div>
@endsection

@section('content')



<!-- BEGIN: Content-->

<div class="app-content content ">

    <div class="content-overlay"></div>

    <div class="header-navbar-shadow"></div>

    <div class="content-wrapper p-0">

        

        <div class="content-body">







            <section id="basic-datatable">

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div style="height:450px;">

                                <table id="table_id" class="table table-bordered datatables-basic">

                                    <thead>

                                        <tr>

                                            <th scope="col" class="text-nowrap text-center">{{__("MONTHLY VOLUME")}}</th>

                                            <th scope="col" class="text-nowrap text-center">{{__("MONTH")}}</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @foreach ($keywords_data as $data)

                                        <tr>

                                            <td class="text-nowrap text-center">{{$data->search_volume}}</td>

                                            <td class="text-nowrap text-center">{{$data->month}}-{{$data->year}}</td>

                                        </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>



            </section>

            <!--/ Basic table -->





        </div>

    </div>

</div>

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{{asset('admin_assets/DataTables/datatables.js')}}"></script>

<script>
$('#table_id').dataTable({
    "pageLength": 10,
    "searching": false,
    "info": false,
    "lengthChange": false,
    'order': [],
    "columnDefs": [{
        "orderable": false,
        "targets": [0, 1]
    }]
});
$('#table_id_paginate').css('float','right');
  
</script>

@endsection