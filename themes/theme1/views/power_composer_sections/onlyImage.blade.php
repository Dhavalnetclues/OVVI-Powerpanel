@if(isset($detailPage) && $detailPage == false)
<section class="page_section">
    <div class="container">
        <div class="row">
            <div class="col-12">
@endif
                <figure class="image"> 
                    <img src="{{ App\Helpers\resize_image::resize( $content['image']) }}"  alt="{{ $content['title'] }}">
                </figure>
@if(isset($detailPage) && $detailPage == false)                
            </div>
        </div>
    </div>
</section>
@endif
