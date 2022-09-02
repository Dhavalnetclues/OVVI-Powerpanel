@if(isset($data['events']) && !empty($data['events']) && count($data['events']) > 0)
<div class="container event-listing">
   <div class="row">
      {{-- @foreach($data['events'] as $event)
      @php
      if(isset(App\Helpers\MyLibrary::getFront_Uri('events')['uri'])){
      $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('events')['uri'];
      $moduleFrontWithCatUrl = ($event->varAlias != false ) ? $moduelFrontPageUrl . '/' . $event->varAlias : $moduelFrontPageUrl;
      $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('events',$event->txtCategories);
      $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$event->alias->varAlias;
      } else {
      $recordLinkUrl = '';
      }
      @endphp
      @if(isset($event->custom['img']))
      @php                          
      $itemImg = App\Helpers\resize_image::resize($event->custom['img']);
      @endphp
      @else 
      @php
      $itemImg = App\Helpers\resize_image::resize($event->fkIntImgId);
      @endphp
      @endif
      @if(isset($event->custom['description']))
      @php
      $description = $event->custom['description'];
      @endphp
      @else 
      @php
      $description = $event->varShortDescription;
      @endphp
      @endif
      @if(isset($event->dtDateTime) && !empty($event->dtDateTime))
      @php
      $dtDateTime = json_decode($event->dtDateTime);
      @endphp
      @else
      @php
      $dtDateTime = '';
      @endphp
      @endif--}}
      <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2">
         <article class="-items n-bs-1 w-100">
            <div class="thumbnail-container" data-thumb="66.66%">
               <div class="thumbnail">
                  <img src="{{ $itemImg }}" alt="{{ $event->varTitle }}" title="{{ $event->varTitle }}">
               </div>
               <div class="-label n-fs-16 n-fw-600">
                  @if($event->isRSVP == 'Y')
                  RSVP Open
                  @else
                  RSVP Close
                  @endif
               </div>
               <div class="-cate n-fs-18 n-fw-600 text-uppercase">{{ $event->varSector }}</div>
               <div class="-loca n-pa-15 n-fw-500 n-ff-2 n-fc-a-500 d-none d-xl-block">
                  <div class="row">
                     @if(isset($dtDateTime[0]) && !empty($dtDateTime[0]))
                     @if(isset($dtDateTime[0]->startDate) && !empty($dtDateTime[0]->startDate))
                     <div class="col-sm-4">
                        <div class="d-flex align-items-center">
                           <i class="n-icon" data-icon="s-calendar"></i>
                           <div class="n-ml-15">
                              <span class="d-block n-fs-12 n-fw-500 n-lh-150 n-fc-a-500">Start Date</span>
                              <div class="n-fs-14 n-fc-dark-500">{{ date('M',strtotime($dtDateTime[0]->startDate)) }} {{ date('d',strtotime($dtDateTime[0]->startDate)) }}, {{ date('Y',strtotime($dtDateTime[0]->startDate)) }}</div>
                           </div>
                        </div>
                     </div>
                     @endif
                     @if(isset($dtDateTime[0]->endDate) && !empty($dtDateTime[0]->endDate))
                     <div class="col-sm-4">
                        <div class="d-flex align-items-center">
                           <i class="n-icon" data-icon="s-calendar"></i>
                           <div class="n-ml-15">
                              <span class="d-block n-fs-12 n-fw-500 n-lh-150 n-fc-a-500">End Date</span>
                              <div class="n-fs-14 n-fc-dark-500">{{ date('M',strtotime($dtDateTime[0]->endDate)) }} {{ date('d',strtotime($dtDateTime[0]->endDate)) }}, {{ date('Y',strtotime($dtDateTime[0]->endDate)) }}</div>
                           </div>
                        </div>
                     </div>
                     @endif
                     @if(isset($dtDateTime[0]->timeSlotFrom[0]) && !empty($dtDateTime[0]->timeSlotFrom[0]))
                     <div class="col-sm-4">
                        <div class="d-flex align-items-center">
                           <i class="n-icon" data-icon="s-clock"></i>
                           <div class="n-ml-15">
                              <span class="d-block n-fs-12 n-fw-500 n-lh-150 n-fc-a-500">Time</span>
                              <div class="n-fs-14 n-fc-dark-500">{{$dtDateTime[0]->timeSlotFrom[0]}} 
                                 @if(isset($dtDateTime[0]->timeSlotTo[0]) && !empty($dtDateTime[0]->timeSlotTo[0]))
                                 - {{$dtDateTime[0]->timeSlotTo[0]}} 
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div>
                     @endif
                     @if(isset($dtDateTime[0]->attendees[0]) && !empty($dtDateTime[0]->attendees[0]))
                     <div class="col-sm-4 n-mt-15">
                        <div class="d-flex align-items-center">
                           <i class="n-icon" data-icon="s-users"></i>
                           <div class="n-ml-15">
                              <span class="d-block n-fs-12 n-fw-500 n-lh-150 n-fc-a-500">No of Attendees</span>
                              <div class="n-fs-14 n-fc-dark-500">{{$dtDateTime[0]->attendees[0]}}</div>
                           </div>
                        </div>
                     </div>
                     @endif
                     @if(isset($event->varAddress) && !empty($event->varAddress))
                     <div class="col-sm-8 n-mt-15">
                        <div class="d-flex align-items-center">
                           <i class="n-icon" data-icon="s-map-pin"></i>
                           <div class="n-ml-15">
                              <span class="d-block n-fs-12 n-fw-500 n-lh-150 n-fc-a-500">Location</span>
                              <div class="n-fs-14 n-fc-dark-500 n-lh-130">{{$event->varAddress}}</div>
                           </div>
                        </div>
                     </div>
                     @endif
                     @endif
                  </div>
               </div>
               @if(isset($dtDateTime[0]->startDate) && !empty($dtDateTime[0]->startDate))
               <div class="-date n-fs-16 n-fw-600 n-ff-2 n-fc-white-500">
                  <span class="d-block n-fs-12 n-fw-500 n-lh-150 n-fc-white-500">Start Date</span>
                  {{$dtDateTime[0]->startDate}}
               </div>
               @endif
               @if(isset($event->varAddress) && !empty($event->varAddress))
               <div class="-sloca n-fs-16 n-fw-600 n-ff-2 n-fc-white-500">
                  <span class="d-block n-fs-12 n-fw-500 n-lh-150 n-fc-white-500">Location</span>
                  {{strlen($event->varAddress) > 20 ? substr($event->varAddress, 0, 20) . '...' : $event->varAddress}}
               </div>
               @endif
            </div>
            <div class="n-pa-40">
               <h2 class="-ntitle n-fs-22 n-ff-1 n-fc-dark-500 n-lh-120 n-fw-500"><a href="{{ $recordLinkUrl }}" title="{{$event->varTitle}}">{{$event->varTitle}}</a></h2>
               @if(isset($event->varShortDescription) && !empty($event->varShortDescription))
               <div class="cms n-mt-20">
                  <p>{{$event->varShortDescription}}</p>
               </div>
               @endif
               @if($event->isRSVP == 'Y')
               <a href="javascript:void(0)" class="ac-btn ac-btn-primary ac-small n-mt-20" title="RSVP Register" data-toggle="modal" data-target="#rsvp" onclick="eventInfo('{{ $event->dtDateTime }}','{{$event->id}}');">RSVP Register</a>
               @endif
            </div>
         </article>
      </div>
      @endforeach
   </div>
</div>
@endif