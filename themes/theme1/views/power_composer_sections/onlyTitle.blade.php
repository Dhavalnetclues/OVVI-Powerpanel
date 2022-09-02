@if(isset($detailPage) && $detailPage == false)
<section class="page_section">
    <div class="container">
        <div class="row">
            <div class="col-12">
@endif
            <h2 class="nq-title {{ $extclass }}">{{ $content['content'] }}</h2>      
@if(isset($detailPage) && $detailPage == false)            
            </div>
        </div>
    </div>
</section>
@endif
