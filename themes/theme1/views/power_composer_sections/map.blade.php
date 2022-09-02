@if(isset($detailPage) && $detailPage == false)
<section class="page_section">
    <div class="container">
        <div class="row">
        <div class="col-12 {{ $extclass }}">
@else
    <div class="{{ $extclass }}">
@endif
            {!! $content !!}
@if(isset($detailPage) && $detailPage == false)
        </div>
        </div>
    </div>
</section>
@else
</div>
@endif
