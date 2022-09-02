@if ($paginator->hasPages())
    <ul class="pagination justify-content-center align-content-center" id="paginationLink">
        @if ($paginator->currentPage() >= 4)
            <li class="page-item -previous">
                <a class="page-link nq-svg" href="javascript:void(0)" data-page="{{ \Request::url().'?page='.$paginator->onFirstPage() }}" id="paginationLink" onclick="scrollpagination()" title="First Page">
                    <i class="fa fa-fast-backward" data-icon="s-pagination"></i>
                </a>
            </li>
        @endif
        &nbsp;&nbsp;
        @if (!$paginator->onFirstPage())
            <li class="page-item -previous">
                <a class="page-link nq-svg" href="javascript:void(0)" data-page="{{$paginator->previousPageUrl()}}" id="paginationLink" onclick="scrollpagination()" title="Previous">
                    <i class="n-icon" data-icon="s-pagination"></i>
                </a>
            </li>
        @endif
        @foreach(range(1, $paginator->lastPage()) as $i)
            @if(($i >= $paginator->currentPage() - 1) && ($i <= $paginator->currentPage() + 1))
                @if (($i) == $paginator->currentPage())
                    <li class="page-item active"><span class="page-link" >{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" id="paginationLink" onclick="scrollpagination()" href="javascript:void(0)" data-page="{{ $paginator->url($i) }}" title="{{ $i }}">{{ $i }}</a></li>
                @endif
            @endif
        @endforeach
        @if($paginator->currentPage() < $paginator->lastPage() - 3)
            <li class="page-item"><span class="page-link" >...</span></li>
        @endif
        @if($paginator->currentPage() < $paginator->lastPage() - 2)
            <li class="page-item"><a class="page-link" href="javascript:void(0)" id="paginationLink" onclick="scrollpagination()" data-page="{{ $paginator->url($paginator->lastPage()) }}" title="{{ $paginator->lastPage() }}">{{ $paginator->lastPage() }}</a></li>
        @endif
        @if ($paginator->hasMorePages())
            <li class="page-item -next">
                <a class="page-link nq-svg"  href="javascript:void(0)" data-page="{{ $paginator->nextPageUrl() }}" id="paginationLink" onclick="scrollpagination()"  title="Next">
                    <i class="n-icon" data-icon="s-pagination"></i>
                </a>
            </li>
        @endif
        &nbsp;&nbsp;
        @if($paginator->lastPage() > 4 && $paginator->currentPage() != $paginator->lastPage())
        <li class="page-item -previous">
                <a class="page-link nq-svg" href="javascript:void(0)" data-page="{{ \Request::url().'?page='.$paginator->lastPage() }}" id="paginationLink" onclick="scrollpagination()" title="Last Page">
                    <i class="fa fa-fast-forward" data-icon="s-pagination"></i>
                </a>
            </li>
        @endif
    </ul>
@endif
