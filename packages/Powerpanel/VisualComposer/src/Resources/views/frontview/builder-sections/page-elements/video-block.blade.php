@if(Request::segment(1) != '')
    <h5>{{ $data['title'] }}</h5>
    <br/>
    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
@else
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-xs-12 cms">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
            </div>
        </div>
    </section>
@endif    