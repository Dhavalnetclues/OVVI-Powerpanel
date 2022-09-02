@if(count($data['careers']) > 0)
    <section class="inner-page-gap careers-jobs n-pt-40 n-pt-lg-80 {{ $data['class'] }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 text-center" data-aos="fade-up">
                    <h2 class="nqtitle">{{ $data['title'] }}</h2>
                    @if(isset($data['desc']) && !empty($data['desc']))
                        <div class="cms n-mt-15">
                            <p>{{ $data['desc'] }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row justify-content-center n-mt-25 n-mt-lg-50">  
                @foreach($data['careers'] as $key => $career)  
                    <div class="col-xl-3 col-lg-4 col-sm-6 d-flex n-gapp-xl-5 n-gapm-xl-4 n-gapm-lg-3 n-gapm-sm-1" data-aos="zoom-in" data-aos-delay="{{ $key }}00">
                        <article class="-items n-bs-1 w-100 n-pa-30 n-bgc-white-500 d-flex flex-column">
                            <h3 class="-title n-fs-22 n-fw-500 n-ff-1 n-fc-balck-500 n-lh-120 n-ti-05">{{ $career->varTitle }}</h3>
                            @if($career->txtPosition > 0)
                                <div class="n-fs-18 n-fw-500 n-fc-a-500 n-lh-120 n-mt-5">{{ $career->txtPosition }} Position(s)</div>
                            @endif
                            <div class="mt-auto n-pt-45">                                
                                <a href="{{ $data['moduelFrontPageUrl'] }}/{{ $career->alias->varAlias }}" class="ac-btn ac-btn-primary ac-small" title="Apply">Apply</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>  

            @if($data['careers']->total() > $data['careers']->perPage())
                <div class="row">
                    <div class="col-12">
                        <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up" id="paginationSection">
                            @include('partial.pagination', ['paginator' => $data['careers']->links()['paginator']])
                        </div>
                    </div>
                </div>
            @endif    
        </div>
    </section>
@else
<section class="inner-page-gap careers-jobs n-pt-40 n-pt-lg-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 text-center" data-aos="fade-up">
                <div class="nqtitle n-fw-800 n-lh-110">Jobs are not available right now</div>
                <div class="desc n-fs-20 n-fw-500 n-lh-130">Check back to this page for latest job updates</div>
            </div>
        </div>
    </div>
</section>
@endif