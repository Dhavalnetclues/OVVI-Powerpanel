@if(!Request::ajax())
@extends('layouts.app')
@section('content')
    @include('layouts.inner_banner')
@endif

@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
    <section class="page_section">
        <div class="container">
            <div class="row">

                @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])

                <div class="col-xl-9" id="pageContent">
                </div>

            </div>
        </div>
    </section>
@else
    @if (isset($PageData['response']) && !empty($PageData['response']) && $PageData['response'] != '[]')
        <section class="page_section ">
            @php echo $PageData['response']; @endphp
        </section>
    @else
        @include('coming-soon')
    @endif
@endif

<script src="{{ $CDN_PATH.'assets/js/packages/services/services.js' }}" type="text/javascript"></script>
@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif
