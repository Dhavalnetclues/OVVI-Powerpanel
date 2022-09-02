@if (!Request::ajax())
    @extends('layouts.app')
    @section('content')
        @include('layouts.inner_banner')
    @endif
    @if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
        <section class="inner-page-gap bod-meetings">
            @include('layouts.share-email-print')
            <div class="container">
                <div class="row">
                    @if(isset($pageContent->txtDescription))
                        @include('public-record::frontview.public-record-left-panel', ['content' => $pageContent->txtDescription])
                    @else
                        @include('public-record::frontview.public-record-left-panel')
                    @endif
                    
                    @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                    
                    <div class="col-xl-9 n-mt-25 n-mt-xl-0" id="pageContent">
                    </div>
                </div>
            </div>
        </section>
    @else
        @if (isset($PageData['response']) && !empty($PageData['response']))
            <section class="inner-page-gap bod-meetings">
                @include('layouts.share-email-print')
                <div class="container">
                    <div class="row">
                        @if(isset($pageContent->txtDescription))
                        @include('public-record::frontview.public-record-left-panel', ['content' => $pageContent->txtDescription])
                    @else
                        @include('public-record::frontview.public-record-left-panel')
                    @endif
                    
                        <div class="col-xl-9 n-mt-25 n-mt-xl-0" id="pageContent">
                            {!! $PageData['response'] !!}
                        </div>
                    </div>
                </div>
            </section>
        @else
            @include('coming-soon')
        @endif
    @endif
@php
  $val = json_decode($pageContent->txtDescription);
  if(!empty($val)){
  foreach ($val as $key => $limitval) {
  if ($limitval->type == 'publicRecord_template') {
  $lim =   $limitval->val->limit;
  }
  }
  if(isset($lim) && !empty($lim)){
  
  $limit = $lim;
  }
  else{
  $limit = '12';
  }
  }
  else{
  $limit = '';
  }
 
@endphp

    <script src="{{ $CDN_PATH . 'assets/js/packages/public-record/public-record.js' }}" type="text/javascript"></script>
    <script type="text/javascript">
        let textDescription = "{{ json_encode($txtDescription) }}"
        let Limits = "{{$limit}}"
    </script>

    @if (!Request::ajax())
        @section('footer_scripts')
            <!-- <script src="{{ $CDN_PATH . 'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" -->
                defer>
            </script>
        @endsection
    @endsection
@endif
