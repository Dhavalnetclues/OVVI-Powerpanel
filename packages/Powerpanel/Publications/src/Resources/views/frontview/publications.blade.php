@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif
@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
    <section class="inner-page-gap bod-meetings">
        {{-- @include('layouts.share-email-print')     --}}
        <div class="container">
            <div class="row">
                {{-- @if(isset($pageContent->txtDescription))
                    @include('publications::frontview.publications-left-panel', ['content' => $pageContent->txtDescription])
                @else
                    @include('publications::frontview.publications-left-panel')
                @endif --}}
                @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                <div class="col-xl-12 n-mt-25 n-mt-xl-0" data-aos="fade-up" id="pageContent">
                </div>
            </div>
        </div>
    </section>
@else
    @if(isset($PageData['response']) && !empty($PageData['response']) && $PageData['response'] != '[]')
        <section class="inner-page-gap bod-meetings">
            {{-- @include('layouts.share-email-print')     --}}
            {{-- <div class="container"> --}}
                <div class="row">
                    {{-- @if(isset($pageContent->txtDescription))
                        @include('publications::frontview.publications-left-panel', ['content' => $pageContent->txtDescription])
                    @else
                        @include('publications::frontview.publications-left-panel')
                    @endif --}}
                    <div class="col-xl-12" id="pageContent">
                        @if(isset($PageData['response']) && !empty($PageData['response']) && $PageData['response'] != '[]')
                            @php echo $PageData['response']; @endphp
                        @else 
                            <div class="row">
                                <div class="col-12 text-center">
                                    <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">No data found</div>
                                    <div class="desc n-fs-20 n-fw-500 n-lh-130">Please reset filter to see the data.</div>
                                </div>  
                            </div>
                        @endif
                    </div>
                </div>
            {{-- </div> --}}
        </section>
    @else
        @include('coming-soon')
    @endif
@endif
@php
    $segment1 =  Request::segment(1);
    if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment1))) {
        $segment2 =  Request::segment(2);

        $link = $segment1.'/' . $segment2 ;
        $pagename = $segment2;
    } else{
        $link = $segment1;
        $pagename = $segment1;
    }
    
   $val = json_decode($pageContent->txtDescription);
  if(!empty($val)){
  foreach ($val as $key => $limitval) {
  if ($limitval->type == 'publication_template') {
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
  $sector = false;
  $sector_slug = '';
  if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
  $sector = true;
  $sector_slug = Request::segment(1);
  }
  if(isset($sector) && !empty($sector)){
  
  $pubsector = $sector;
  }
  else{
  $pubsector = '';
  } 
    
@endphp

<script type="text/javascript">
    let Limits = "{{$limit}}"
    let slug = "{{$link}}"
    let publicationsector = "{{$sector_slug}}"
    let pagename = "{{$pagename}}"
</script>
<script src="{{ $CDN_PATH.'assets/js/packages/publications/publications.js' }}" type="text/javascript"></script>
@if(!Request::ajax())
    @section('footer_scripts')
        <!-- <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script> -->
    @endsection
@endsection
@endif
