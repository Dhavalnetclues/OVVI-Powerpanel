@if(isset($detailPage) && $detailPage == false)
<section class="page_section">
    <div class="container">
        <div class="row">
            <div class="col-12">
@endif          
                <div class="cms {{ $extclass }}">      
                    {!! htmlspecialchars_decode($content) !!}
                </div>
@if(isset($detailPage) && $detailPage == false)
            </div>
        </div>
    </div>
</section>
@endif