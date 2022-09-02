@extends('layouts.app')
@section('content')
    @include('layouts.inner_banner')
    @if(isset($PageData['response']) && !empty($PageData['response']))
        <section class="inner-page-gap faq-page">
            {{-- @include('layouts.share-email-print') --}}
            <div class="container">
                <div class="row">
                    {{-- @include('faq::frontview.faq-left-panel') --}}
                    <div class="col-xl-12">
                        @php echo $PageData['response']; @endphp
                    </div>  
                </div>
            </div>  
        </section>
    @else
        @include('coming-soon')
    @endif
@endsection