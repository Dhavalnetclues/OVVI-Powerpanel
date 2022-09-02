@if ($paginator->hasPages())
    <ul class="pagination justify-content-center align-content-center" id="paginationLink">
        {{--@if ($paginator->onFirstPage())--}}
        <li class="page-item">
            <a class="page-link nq-svg" href="javascript:void(0)" data-page="{{$paginator->previousPageUrl()}}" title="Previous">
                <i class="n-icon" data-icon="s-pagination"></i>
            </a>
        </li>

        @if (is_array($elements))
            @foreach($elements as $page => $url)
                @if (($page) == $paginator->currentPage())
                    <li class="page-item active"><a class="page-link" href="javascript:void(0)" data-page="{{ $url }}" title="{{ $page }}">{{ $page }}</a></li>
                @else
                    <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page="{{ $url }}" title="{{ $page }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif

        {{--@if ($paginator->hasMorePages())--}}
            <li class="page-item">
                <a class="page-link nq-svg" href="javascript:void(0)" data-page="{{ $paginator->nextPageUrl() }}"  title="Next">
                    <i class="n-icon" data-icon="s-pagination"></i>
                </a>
            </li>
        {{--@endif--}}
    </ul>
@endif
