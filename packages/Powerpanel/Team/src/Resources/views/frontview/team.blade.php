@if(!Request::ajax())
@extends('layouts.app')
@section('content')
    @include('layouts.inner_banner')
@endif

@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
    <section class="inner-page-gap directors-listing">
        @include('layouts.share-email-print')
        <div class="container">
            <div class="row">
                @include('team::frontview.team-left-panel')

                @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                <div class="col-xl-9" id="pageContent">
                </div>
            </div>
        </div>
    </section>
@else
    @if (isset($PageData['response']) && !empty($PageData['response']) && $PageData['response'] != '[]')
        <section class="inner-page-gap team-page">
            @php echo $PageData['response']; @endphp
        </section>
    @else
        @include('coming-soon')
    @endif
@endif

<script src="{{ $CDN_PATH.'assets/js/packages/team/team.js' }}" type="text/javascript"></script>
@if(!Request::ajax())
@endsection
@endif
