@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
<section class="inner-page-gap event-detail">
   <div>
   <div class="container">
      <div class="row event-row">
         <div class="col-xl-3 col-lg-4">
            @if($events->isRSVP == 'Y')
               <a href="javascript:void(0)" class="ac-btn ac-btn-primary" title="RSVP Registration" data-toggle="modal" data-target="#rsvp" onclick="eventInfo('{{ $events->varTitle }}','{{ $events->dtDateTime }}','{{$events->id}}');">RSVP Registration</a>
            @endif

            <div class="left-panel">
               {{-- <div class="nav-overlay" onclick="closeNav1()"></div> --}}
               <h3>event scheduler</h3>
               <div class="event-post">
                  @foreach($latestList as $re)
                  <div class="event-recent-post">
                     <div class="event-img">
                        <a href="{{url('/events/'.$re->alias->varAlias)}}">
                        	<img src="{{App\Helpers\resize_image::resize($re->fkIntImgId);}}" />
                        </a>
                     </div>
                     <div class="event-body">
                        <h4 class="post-title">
                           <a href="{{url('/events/'.$re->alias->varAlias)}}">{{ $re->varTitle }}</a>
                        </h4>
                        <div class="recent-event-date">
                           <a href="{{url('/events/'.$re->alias->varAlias)}}">{{date('d M, Y',strtotime($events->eventDateTime[0]->endDate))}}</a>
                        </div>
                     </div>
                  </div>
                  @endforeach
               </div>
            </div>
         </div>
         @if(isset($event->custom['img']))
         @php $itemImg = App\Helpers\resize_image::resize($events->custom['img'],1065,708); @endphp
         @else
         @php $itemImg = App\Helpers\resize_image::resize($events->fkIntImgId,1065,708); @endphp
         @endif
         <div class="col-xl-9 col-lg-8">
            <div class="-content evenetd-cont cms">
               @if (isset($txtDescription) && !empty($txtDescription))
               <div class="">
                  <div class="thumbnail-container">
                     <div class="thumbnail">
                        <img src="{{ $itemImg }}" alt="" title="">
                     </div>
                     	@if(isset($events->eventDateTime[0]->startDate) || isset($events->eventDateTime[0]->endDate))
	                     <div class="event-date">
	                        <span>
	                        	{{ (isset($events->eventDateTime[0]->startDate)?date('d M',strtotime($events->eventDateTime[0]->startDate)):'') }}
		                        {{ (isset($events->eventDateTime[0]->startDate) && isset($events->eventDateTime[0]->endDate))?' to ':'' }}
		                        {{ (isset($events->eventDateTime[0]->endDate)?date('d M Y',strtotime($events->eventDateTime[0]->endDate)):'') }}
		                        {{-- 01 Aug to 30 Aug 2022 --}}
		                     </span>
	                     </div>
	                    @endif
                  </div>
                  
                  <div class="eventb-des">
                     @if (!empty($events->varAdminEmail))
                     <p><i class="fa fa-envelope-o" aria-hidden="true"></i> {{ $events->varAdminEmail }}</p>
                     @endif
                     <div class="event-type">
                        <span>
                           <img src=" {{ $CDN_PATH.'assets/images/category.png' }}">
                           {{$events->eventscat->varTitle}}
                        </span>

                        @if(isset($events->eventDateTime[0]->attendees[0]) && !empty($events->eventDateTime[0]->attendees[0]))
                        <span>
                           <img src=" {{ $CDN_PATH.'assets/images/attendance.png' }}">
                           {{$events->eventDateTime[0]->attendees[0]}}
                        </span>
                        @endif
                        <span>
                           <div class="d-flex align-content-between align-items-center">
                              <div class="-eicon">
                                 <i class="n-icon" data-icon="s-clock"></i>
                              </div>
                              <div>
                                 <div class="eventd-time">
                                    {{ $events->eventDateTime[0]->timeSlotFrom[0] }} @if (isset($events->eventDateTime[0]->timeSlotTo[0]) && !empty($events->eventDateTime[0]->timeSlotTo[0]))
                                    to {{ $events->eventDateTime[0]->timeSlotTo[0] }} @endif
                                 </div>
                              </div>
                           </div>
                        </span>
                     </div>
                  </div>                  
                  {!! htmlspecialchars_decode($txtDescription) !!}
               </div>
               @endif
               <div class="cms">                  
                  @if (!empty($events->varAdminPhone) || !empty($events->varAdminEmail))
                  
                  @if (!empty($events->varAdminPhone))
                  <p><strong>Phone:</strong> {{ $events->varAdminPhone }}</p>
                  @endif
                  @endif
                  @if (isset($events->fkIntDocId) && !empty($events->fkIntDocId))
                  @php
                  $docsAray = explode(',', $events->fkIntDocId);
                  $docObj = App\Document::getDocDataByIds($docsAray);
                  @endphp
                  @if (count($docObj) > 0)
                  <h2>Event Documnets</h2>
                  <div class="row">
                     @foreach ($docObj as $key => $val)
                     @php
                     $CDN_PATH = Config::get('Constant.CDN_PATH');
                     if ($val->fk_folder > 0 && !empty($val->foldername)) {
                     if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF') {
                     $docURL = route('viewFolderPDF', ['dir' => 'documents', 'foldername' => $val->foldername, 'filename' => $val->txtSrcDocumentName . '.' . $val->varDocumentExtension]);
                     } else {
                     $docURL = $CDN_PATH . 'documents/' . $val->foldername . '/' . $val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
                     }
                     } else {
                     if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF') {
                     $docURL = route('viewPDF', ['dir' => 'documents', 'filename' => $val->txtSrcDocumentName . '.' . $val->varDocumentExtension]);
                     } else {
                     $docURL = $CDN_PATH . 'documents/' . $val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
                     }
                     }
                     @endphp
                     <div class="col-md-6 n-gapp-3 n-gapm-md-2" data-aos="fade-up">
                        <div class="documents">
                           @if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF')
                           <div class="-doct-img">
                              <i class="n-icon" data-icon="s-pdf"></i>
                              <i class="n-icon" data-icon="s-download"></i>
                           </div>
                           @elseif($val->varDocumentExtension == 'doc' ||
                           $val->varDocumentExtension == 'docx')
                           <div class="-doct-img">
                              <i class="n-icon" data-icon="s-doc"></i>
                              <i class="n-icon" data-icon="s-download"></i>
                           </div>
                           @elseif($val->varDocumentExtension == 'xls' ||
                           $val->varDocumentExtension == 'xlsx')
                           <div class="-doct-img">
                              <i class="n-icon" data-icon="s-xls"></i>
                              <i class="n-icon" data-icon="s-download"></i>
                           </div>
                           @else
                           <div class="-doct-img">
                              <i class="n-icon" data-icon="s-pdf"></i>
                              <i class="n-icon" data-icon="s-download"></i>
                           </div>
                           @endif
                           <div>
                              <a class="-link n-ah-a-500 docHitClick"
                                 data-viewid="{{ $val->id }}" data-viewtype="download"
                                 href="{{ $docURL }}" download=""
                                 title="{{ $val->txtDocumentName }}">{{ $val->txtDocumentName }}</a>
                           </div>
                        </div>
                     </div>
                     @endforeach
                  </div>
               </div>
               @endif
               @endif
            </div>
         </div>
      </div>
   </div>
</section>
<!-- RSVP S -->
@include('events::frontview.rsvp_form')
<!-- RSVP E -->
@endsection
@section('page_scripts')
<script src="{{ $CDN_PATH . 'assets/libraries/masked-input/jquery.mask.min.js' }}"></script>
<script type="text/javascript">
   var sitekey = '{{ Config::get('Constant.GOOGLE_CAPCHA_KEY') }}';
   var onRSVPCallback = function() {
       grecaptcha.render('rsvp_html_element', {
           'sitekey': sitekey
       });
   };
</script>
<script
   src="https://maps.googleapis.com/maps/api/js?key={{ Config::get('Constant.GOOGLE_MAP_KEY') }}&callback=initMap"
   async defer></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onRSVPCallback&render=explicit" async defer></script>
<script src="{{ $CDN_PATH . 'assets/js/packages/events/rsvp_validation.js' }}" type="text/javascript"></script>
@endsection