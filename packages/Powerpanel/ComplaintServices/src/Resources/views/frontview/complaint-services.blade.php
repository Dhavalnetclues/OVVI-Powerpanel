@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif

@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
    <section class="inner-page-gap">
        {{-- @include('layouts.shareIcon') --}}

        <div class="container n-pt-lg-130 n-pt-50">
            <div class="row">
                {{-- @include('complaint-services::frontview.complaint-services-left-panel') --}}
                @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                <div class="col-xl-12" id="pageContent">
                </div>
            </div>
        </div>  
    </section>
@else
    @if (isset($PageData['response']) && !empty($PageData['response']) && $PageData['response'] != '[]')
        <section class="inner-page-gap">
            {{-- @include('layouts.shareIcon') --}}

            <div class="container n-pt-lg-130 n-pt-50">
                <div class="row">
                {{-- @include('complaint-services::frontview.complaint-services-left-panel') --}}
                @php echo $PageData['response']; @endphp
                </div>
            </div>  
        </section>
    @else
        <section class="inner-page-gap n-pt-lg-130 n-pt-50">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center" data-aos="fade-up">
                        <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">Coming Soon...</div>
                        <div class="desc n-fs-20 n-fw-500 n-lh-130">We are currently working on a new super awesome page.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endif

@if(!Request::ajax())
    @section('footer_scripts')

    @endsection
@endsection

@endif