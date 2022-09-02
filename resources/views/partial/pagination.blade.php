@if ($paginator->hasPages())
    @php
    if(Request::segment(1) == 'fuel' && Request::segment(2) == 'retail-fuel-prices'){
        $activePage = 'Documents';
    }else{
        $activePage = Request::segment(1);
    }
    @endphp
    <ul class="pagination justify-content-center align-content-center" id="paginationLink">
        @if ($paginator->hasMorePages())
            <li style="margin-right: auto;">
                <a href="javascript:void(0)" class="ac-btn ac-btn-primary LimitExpend" data-page="{{ $paginator->nextPageUrl() }}" id="paginationLink" title="More News">More {{$activePage}}</a>
            </li>
        @endif

    </ul>
@endif