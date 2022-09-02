@if(isset($data['layout']) && $data['layout'] == 'slider')

<section class="n-pt-50 n-pt-lg-100 home-short-service home-short-service-slider" data-aos="fade-up">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="owl-carousel owl-theme">
                    @foreach($data['records'] as $key => $val)
                    <div class="item item-m d-flex">
                        <div class="align-self-end">

                            <h2 class="title-m">{{$val['title']}}</h2>
                            <a href="{{$val['link']}}" class="more-info" title="More Info">More Info</a>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>			
        </div>
    </div>
</section>
@endif
