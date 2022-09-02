@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
@if(!Request::ajax())
<section>
    <div class="inner-page-container cms faqs_section">
        
        <div class="container">

            <!-- Main Section S -->
            <div class="row">
                {!! $LeftPanelhtml !!}
                <div class="col-md-9 col-md-12 col-xs-12 animated fadeInUp">
                    <div class="right_content">
                      @if(!empty($templateContent))
                      <div class="cms_highite ac-mb-xs-30">
                          {!! $templateContent !!}
                      </div>
                      @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif