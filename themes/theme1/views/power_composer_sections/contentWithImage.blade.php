@if(isset($detailPage) && $detailPage == false)
<section class="page_section">
    <div class="container">
        <div class="row">
            <div class="col-12 cms {{ $extclass }}">
@else
            <div class="cms {{ $extclass }}">  
@endif
            <figure class="image image_resized {{ $content['alignment'] }}"> 
                <img src="{{ App\Helpers\resize_image::resize( $content['image']) }}"  alt="{{ $content['title'] }}">
            </figure>
            <h2>{{ $content['title'] }}</h2>
            <p>{!! htmlspecialchars_decode($content['content']) !!}</p>
            
@if(isset($detailPage) && $detailPage == false)                
            </div>
        </div>
    </div>
</section>
@else

    </div>
@endif