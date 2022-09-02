@if(Request::segment(1) != '')
    <div class="row"> 
        <div class="col-sm-6 col-md-6">
            {!! $data['leftcontent'] !!}
        </div>  
        <div class="col-sm-6 col-md-6">
            {!! $data['rightcontent'] !!}
        </div>            
    </div>
@else

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 animated fadeInUp text-center load"> 
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            {!! $data['leftcontent'] !!}
                        </div>  
                        <div class="col-sm-6 col-md-6">
                            {!! $data['rightcontent'] !!}
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </section>

@endif    