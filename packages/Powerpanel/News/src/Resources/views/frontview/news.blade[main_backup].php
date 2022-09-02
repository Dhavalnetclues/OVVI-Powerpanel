@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif

@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
    <section class="inner-page-gap news-listing">
        @include('layouts.share-email-print')
        <div class="container">
            <div class="row">
                @include('news::frontview.news-left-panel')   
                @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                <div class="col-xl-9" id="pageContent">
                </div>  
            </div>
        </div>  
    </section>
@else   
        <section class="inner-page-gap news-listing">
            @include('layouts.share-email-print')
            <div class="container">
                <div class="row">
                    @include('news::frontview.news-left-panel')   
                    <div class="col-xl-9" id="pageContent">
                        @if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]') 
                            @php echo $PAGE_CONTENT['response']; @endphp
                        @else 
                            <div class="row">
                                <div class="col-12 text-center" data-aos="fade-up">
                                    <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">No data found</div>
                                    <div class="desc n-fs-20 n-fw-500 n-lh-130">Please reset filter to see the data.</div>
                                </div>  
                            </div>
                        @endif
                    </div>  
                </div>
            </div>  
        </section>
@endif
@php
  $val = json_decode($pageContent->txtDescription);
  if(!empty($val)){
  foreach ($val as $key => $limitval) {
  if ($limitval->type == 'news_template') {
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
<script src="{{ $CDN_PATH.'assets/js/packages/news/news.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    let textDescription = "{{json_encode($txtDescription)}}"
     let Limits = "{{$limit}}"
</script>
@if(!Request::ajax())
    @section('footer_scripts')
        <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script>
    @endsection
@endsection
@endif