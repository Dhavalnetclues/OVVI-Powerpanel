@if(isset($detailPage) && $detailPage == false)
<section class="page_section">
    <div class="container">
        <div class="row">
        <div class="col-12">
@else
            <div>
@endif            
                <h3>{{ $content['title'] }}</h3>
                <iframe width="100%" height="400" src="https://www.youtube.com/embed/{{ $content['vidId'] }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
@if(isset($detailPage) && $detailPage == false)                
            </div>
        </div>
    </div>
</section>
@else
</div>
@endif
