@if (!Request::ajax())
    @extends('layouts.app')
    @section('content')
        @include('layouts.inner_banner')
    @endif

    @if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
        <section class="inner-page-gap whois-information register-of-applications">
            @include('layouts.share-email-print')
            <div class="container">
                <div class="row">
                    @include('register-application::frontview.register-application-left-panel')
                    <div class="col-xl-9 n-mt-25 n-mt-xl-0">
                        <div class="row justify-content-center">
                            <div class="col-sm-8" data-aos="fade-up">
                                <h2 class="nqtitle-ip text-center">Listing and Current Status</h2>
                                <div class="ac-form-wd n-mt-25">
                                    <div class="form-group ac-form-group">
                                        <label class="ac-label" for="search">Search by Title</label>
                                        <input type="text" class="form-control ac-input" id="search" name="search"
                                            minlength="1" maxlength="255" autocomplete="off">
                                        <button class="-search ac-btn ac-btn-primary" type="button" title="Search"
                                            id="searchBtn">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                        <div class="row n-mt-25" id="pageContent">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
    @if (isset($PageData['response']) && !empty($PageData['response']))
        <section class="inner-page-gap whois-information register-of-applications">
            @include('layouts.share-email-print')
            <div class="container">
                <div class="row">
                    @include('register-application::frontview.register-application-left-panel')
                    <div class="col-xl-9 n-mt-25 n-mt-xl-0">
                        <div class="row justify-content-center">
                            <div class="col-sm-8" data-aos="fade-up">
                                <h2 class="nqtitle-ip text-center">Listing and Current Status</h2>
                                <div class="ac-form-wd n-mt-25">
                                    <div class="form-group ac-form-group">
                                        <label class="ac-label" for="search">Search by Title</label>
                                        <input type="text" class="form-control ac-input" id="search" name="search"
                                            minlength="1" maxlength="255" autocomplete="off">
                                        <button class="-search ac-btn ac-btn-primary" type="button" title="Search"
                                            id="searchBtn">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row n-mt-25" id="pageContent">
                            @php echo $PageData['response']; @endphp
                        </div>
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
  if ($limitval->type == 'registerapplication_template') {
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
    
    @if (!Request::ajax())
        @section('page_scripts')
        <script type="text/javascript">
            let textDescription = "{{ json_encode($txtDescription) }}"
            var sectorName =  "{{ Request::segment(1) }}"
            let Limits = "{{$limit}}"
        </script>

        <!-- <script src="{{ $CDN_PATH . 'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script> -->
        <script src="{{ $CDN_PATH . 'assets/js/packages/register-application/register-application.js' }}" type="text/javascript"></script>
        @endsection
    @endsection
@endif
