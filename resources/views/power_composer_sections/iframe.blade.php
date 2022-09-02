@if(isset($detailPage) && $detailPage == false)
<section class="page_section ">
    <div class="container">
        <div class="row">
            <div class="col-12 {{ $extclass }}">
@else
    <div class="{{ $extclass }}">
@endif
                <iframe src="{{ $content }}" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
@if(isset($detailPage) && $detailPage == false)
            </div>
        </div>
    </div>
</section>
@else
</div>
@endif
