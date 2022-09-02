@if (!Request::ajax())
    @extends('layouts.app')
    @section('content')
        @include('layouts.inner_banner')
    @endif

    @php
    $ignoreLeftPanel = ['about-us', 'who-we-are', 'terms-of-use','foi', 'ict','energy','fuel','water','spectrum','survey'];
    $sectorMainPages = ['energy','fuel','water','ict','spectrum'];
    $sector = '';
    $segment = Request::segment(1);
    if ((in_array(Request::segment(1),$sectorMainPages)) && !empty(Request::segment(2))) {
        $sector = Request::segment(1);
        $segment = Request::segment(2);
    }
    @endphp
    @if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
        <section class="inner-page-gap">
            <div class="container">
                <div class="row">
                    @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                    <div class="col-xl-12" id="pageContent"></div>
                </div>
            </div>
        </section>
    @else
        @if (isset($PageData['response']) && !empty($PageData['response']) && $PageData['response'] != '[]')
            <section class="inner-page-gap">
                @if(isset($PassPropage) && $PassPropage == 'PU' && $isContent)
                    @include('layouts.share-email-print')
                @endif
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12" id="pageContent">
                            {!! $PageData['response'] !!}
                        </div>
                    </div>
                </div>
            </section>
        @else
            @include('coming-soon')
        @endif
    @endif

    @if (!Request::ajax())
    @endsection
@endif
