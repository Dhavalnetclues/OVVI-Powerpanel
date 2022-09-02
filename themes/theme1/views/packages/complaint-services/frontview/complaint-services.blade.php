@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif

@if(!Request::ajax())
    <section class="inner-page-gap">
        @include('layouts.share-email-print')

        <div class="container">
            <div class="row">
               @include('complaint-services::frontview.complaint-services-left-panel')
               @php echo $PageData['response']; @endphp
            </div>
        </div>  
    </section>
@endif

@if(!Request::ajax())
    @section('footer_scripts')

    @endsection
@endsection

@endif