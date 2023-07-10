@if ($paginator->hasPages())
<div class="col-12 mt-4 pt-2">
    <ul class="pagination float-end">
        @if($paginator->onFirstPage())
        <li class="page-item"><a class="page-link" aria-label="Previous"><i class="fas fa-angle-left"></i></a></li>

        @else
        <li class="page-item"><a class="page-link" href="{{$paginator->previousPageUrl()}}" aria-label="Previous"><i class="fas fa-angle-left"></i></a></li>
        @endif
        @if(is_array($elements[0]))
        @foreach ($elements[0] as $page => $url)
        @if($page == $paginator->currentPage())
        <li class="page-item active"><a class="page-link" href="{{$url}}">{{$page}}</a></li>
        @else
        <li class="page-item"><a class="page-link" href="{{$url}}">{{$page}}</a></li>
        @endif
        @endforeach
        @endif

        @if($paginator->hasMorePages())
        <li class="page-item"><a class="page-link" href="{{$paginator->nextPageUrl()}}" aria-label="Next"><i
                    class="fas fa-angle-right"></i></a></li>
        @else
        <li class="page-item"><a class="page-link" aria-label="Next"><i class="fas fa-angle-right"></i></a></li>
        @endif
        <!-- <li class="page-item active"><a class="page-link" href="javascript:void(0)">1</a></li>
        <li class="page-item"><a class="page-link" href="javascript:void(0)">2</a></li>
        <li class="page-item"><a class="page-link" href="javascript:void(0)">3</a></li>
        <li class="page-item"><a class="page-link" href="javascript:void(0)" aria-label="Next">Next <i
                    class="mdi mdi-arrow-right"></i></a></li> -->
    </ul>
</div>
@endif