@if(isset($data['events']) && !empty($data['events']) && count($data['events']) > 0)
@php 
// dd($data['events']->toArray());
@endphp
@if(Request::segment(1) == '')
<div class="container event-listing">
	<div class="row">
	   @foreach($data['events'] as $event)
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
	   $dtDateTime = json_decode($event->dtDateTime, true);	   
	   @endphp
	   @else
	   @php
	   $dtDateTime = '';
	   @endphp
	   @endif
	   <div class="col-lg-4 col-md-6 col-xl-4">
	      <article class="-items event-layout">
	         <div class="thumbnail-container" data-thumb="66.66%">
	            <div class="thumbnail">
	               <a href="{{ url('events/'.$event->alias->varAlias) }}" alt="{{ $event->varTitle }}" title="{{ $event->varTitle }}">
	               <img src="{{ $itemImg }}" >
	               </a>
	            </div>
	            @php $colourclass = '';@endphp
	            @if(strtolower($event->varSector) == 'ofreg')
	            @php
	            $colourclass = 'ofreg-tag';
	            @endphp
	            @elseif(strtolower($event->varSector) == 'ict')
	            @php $colourclass = 'ict-tag'; @endphp
	            @elseif(strtolower($event->varSector) == 'water')
	            @php $colourclass = 'water-tag'; @endphp
	            @elseif(strtolower($event->varSector) == 'fuel')
	            @php $colourclass = 'fuel-tag'; @endphp
	            @elseif(strtolower($event->varSector) == 'energy')
	            @php $colourclass = 'energy-tag'; @endphp
	            @endif
	            {{--
	            <div class="-cate n-fs-18 n-fw-600 text-uppercase {{$colourclass}}">{{ $event->varSector }}</div>
	            --}}
	         </div>
	         <div class="event-body">
	            <div class="event-date">
	               @if(isset($dtDateTime[0]['startDate']) && !empty($dtDateTime[0]['startDate']))
	               <span>{{ date('M d ', strtotime($dtDateTime[0]['startDate'])) }}
	               <span>to</span>  {{ date('M d, Y', strtotime($dtDateTime[0]['endDate'])) }}</span>  
	               @endif
	               {{--@if(isset($dtDateTime[0]['endDate']) && !empty($dtDateTime[0]['endDate']))
	               @endif--}}
	            </div>
	            <div class="eventb-des">
	               <div class="event-type">
	                  <span>
	                   <img src="{{url('cdn/assets/images/category.png')}}">
	                     {{$event->eventscat->varTitle}}
	                  </span>
	                  @if(!empty($dtDateTime[0]['startDate']))
	                  <span>	                  	
	                  	<img src="{{url('cdn/assets/images/attendance.png')}}">
	                     {{$dtDateTime[0]['attendees'][0]}}
	                  </span>
	                  @endif
	               </div>
	               <h5 class="title text-truncate"><a href="{{ $recordLinkUrl }}" title="{{$event->varTitle}}">{{$event->varTitle}}</a></h5>
	               @if(isset($event->varShortDescription) && !empty($event->varShortDescription))
	               <div class="cms n-mt-15">
	                  <p>{{$event->varShortDescription}}</p>
	               </div>
	               </div>
	               @endif
	               {{-- @if($event->isRSVP == 'Y')
	               <a href="javascript:void(0)" class="ac-btn ac-btn-primary" title="View Details" data-toggle="modal" data-target="#rsvp" onclick="eventInfo('{{ $event->varTitle }}','{{ $event->dtDateTime }}','{{$event->id}}');">View Details</a>
	               @endif --}}
	               <a href="{{ $recordLinkUrl }}" class="ac-btn ac-btn-primary" title="View details">View Details</a>
	            </div>
	      </article>
	      </div>
	      @endforeach
	   </div>
</div>

@else
		
	<div class="container event-listing">
	<div class="row">
	   @foreach($data['events'] as $event)
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
	   $dtDateTime = json_decode($event->dtDateTime, true);
	   @endphp
	   @else
	   @php
	   $dtDateTime = '';
	   @endphp
	   @endif
	   <div class="col-lg-4 col-md-6 col-xl-4">
	      <article class="-items event-layout">
	         <div class="thumbnail-container" data-thumb="66.66%">
	            <div class="thumbnail">
	               <a href="{{ url('events/'.$event->alias->varAlias) }}" alt="{{ $event->varTitle }}" title="{{ $event->varTitle }}">
	               <img src="{{ $itemImg }}" >
	               </a>
	            </div>
	            @php $colourclass = '';@endphp
	            @if(strtolower($event->varSector) == 'ofreg')
	            @php
	            $colourclass = 'ofreg-tag';
	            @endphp
	            @elseif(strtolower($event->varSector) == 'ict')
	            @php $colourclass = 'ict-tag'; @endphp
	            @elseif(strtolower($event->varSector) == 'water')
	            @php $colourclass = 'water-tag'; @endphp
	            @elseif(strtolower($event->varSector) == 'fuel')
	            @php $colourclass = 'fuel-tag'; @endphp
	            @elseif(strtolower($event->varSector) == 'energy')
	            @php $colourclass = 'energy-tag'; @endphp
	            @endif
	            {{--
	            <div class="-cate n-fs-18 n-fw-600 text-uppercase {{$colourclass}}">{{ $event->varSector }}</div>
	            --}}
	         </div>
	         <div class="event-body">
	            <div class="event-date">
	               @if(isset($dtDateTime[0]['startDate']) && !empty($dtDateTime[0]['startDate']))
	               <span>{{ date('M d ', strtotime($dtDateTime[0]['startDate'])) }}
	               <span>to</span>  {{ date('M d, Y', strtotime($dtDateTime[0]['endDate'])) }}</span>  
	               @endif
	               {{--@if(isset($dtDateTime[0]['endDate']) && !empty($dtDateTime[0]['endDate']))
	               @endif--}}
	            </div>
	            <div class="eventb-des">
	               <div class="event-type">
	                  <span>
	                   <img src="{{url('cdn/assets/images/category.png')}}">
	                     {{$event->eventscat->varTitle}}
	                  </span>
	                  @if(!empty($dtDateTime[0]['startDate']))
	                  <span>	                  	
	                  	<img src="{{url('cdn/assets/images/attendance.png')}}">
	                     {{$dtDateTime[0]['attendees'][0]}}
	                  </span>
	                  @endif
	               </div>
	               <h5 class="title text-truncate"><a href="{{ $recordLinkUrl }}" title="{{$event->varTitle}}">{{$event->varTitle}}</a></h5>
	               @if(isset($event->varShortDescription) && !empty($event->varShortDescription))
	               <div class="cms n-mt-15">
	                  <p>{{$event->varShortDescription}}</p>
	               </div>
	               </div>
	               @endif
	               {{-- @if($event->isRSVP == 'Y')
	               <a href="javascript:void(0)" class="ac-btn ac-btn-primary" title="View Details" data-toggle="modal" data-target="#rsvp" onclick="eventInfo('{{ $event->varTitle }}','{{ $event->dtDateTime }}','{{$event->id}}');">View Details</a>
	               @endif --}}
	               <a href="{{ $recordLinkUrl }}" class="ac-btn ac-btn-primary" title="View details">View Details</a>
	            </div>
	      </article>
	      </div>
	      @endforeach
	   </div>
</div>

	<div class="row owl-section">
 
     @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
        @if($data['events']->total() > $data['events']->perPage())
            <div id="paginationSection">
                @include('partial.pagination', ['paginator' => $data['events']->links()['paginator'], 'elements' => $data['events']->links()['elements']['0']])
            </div>
        @endif
    @endif
	</div>
	
@endif

@endif