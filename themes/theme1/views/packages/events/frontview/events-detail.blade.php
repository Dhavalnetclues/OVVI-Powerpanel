@extends('layouts.app')
@section('content')
@if(isset($events->fkIntImgId) && !empty($events->fkIntImgId))
@php                          
$bannerImage = App\Helpers\resize_image::resize($events->fkIntImgId);
@endphp
@else
@php                          
$bannerImage = 'https://the7.io/elementor-main/wp-content/uploads/sites/77/2020/10/im-main-2007.jpg';
@endphp
@endif
<section class="inner-page-gap event-detail n-pv-0">
   <div class="-ebanner n-pv-35" style="background-image: url({{$bannerImage}});">
      <div class="container">
         <div class="row -bgh align-items-center">
            <div class="col-xl-8 col-lg-6">
               @if(isset($events->varSector) && !empty($events->varSector))
               <span class="-cate d-inline-block n-fs-18 n-fw-600 text-uppercase">{{$events->varSector}}</span>
               @endif
               @if(isset($events->varTitle) && !empty($events->varTitle))
               <h1 class="nqtitle n-fc-white-500 n-mt-15">{{$events->varTitle}}</h1>
               @endif
               @if(isset($events->varShortDescription) && !empty($events->varShortDescription))
               <div class="nqtitle-small n-fc-white-500 n-fw-500 n-mt-15">{{$events->varShortDescription}}</div>
               @endif
               <div class="n-fs-18 n-fw-500 n-lh-150 n-fc-white-500 n-mt-15"><i class="n-icon" data-icon="s-pen-tool"></i>by Netclues!</div>
            </div>
            @if(isset($events->eventDateTime) && !empty($events->eventDateTime))
            <div class="col-xl-4 col-lg-6">
               <div class="row">
                  @if(isset($events->eventDateTime[0]->startDate) && !empty($events->eventDateTime[0]->startDate))
                  <div class="col-6 n-gapp-3">
                     <div class="d-flex align-content-between align-items-center n-fc-white-500">
                        <div>
                           <i class="n-icon" data-icon="s-calendar"></i>
                        </div>
                        <div class="n-ml-15">
                           <span class="d-block n-fs-16 n-fw-500 n-lh-110 n-fc-white-500">Start Date</span>
                           <div class="n-fs-18 n-fw-500 n-lh-110 n-fc-white-500">{{ date('M',strtotime($events->eventDateTime[0]->startDate)) }} {{ date('d',strtotime($events->eventDateTime[0]->startDate)) }}, {{ date('Y',strtotime($events->eventDateTime[0]->startDate)) }}</div>
                        </div>
                     </div>
                  </div>
                  @endif
                  @if(isset($events->eventDateTime[0]->endDate) && !empty($events->eventDateTime[0]->endDate))
                  <div class="col-6 n-gapp-3">
                     <div class="d-flex align-content-between align-items-center n-fc-white-500">
                        <div>
                           <i class="n-icon" data-icon="s-calendar"></i>
                        </div>
                        <div class="n-ml-15">
                           <span class="d-block n-fs-16 n-fw-500 n-lh-110 n-fc-white-500">End Date</span>
                           <div class="n-fs-18 n-fw-500 n-lh-110 n-fc-white-500">{{ date('M',strtotime($events->eventDateTime[0]->endDate)) }} {{ date('d',strtotime($events->eventDateTime[0]->endDate)) }}, {{ date('Y',strtotime($events->eventDateTime[0]->endDate)) }}</div>
                        </div>
                     </div>
                  </div>
                  @endif
                  @if(isset($events->eventDateTime[0]->timeSlotFrom[0]) && !empty($events->eventDateTime[0]->timeSlotFrom[0]))
                  <div class="col-6 n-gapp-3">
                     <div class="d-flex align-content-between align-items-center n-fc-white-500">
                        <div>
                           <i class="n-icon" data-icon="s-clock"></i>
                        </div>
                        <div class="n-ml-15">
                           <span class="d-block n-fs-16 n-fw-500 n-lh-110 n-fc-white-500">Time</span>
                           <div class="n-fs-18 n-fw-500 n-lh-110 n-fc-white-500">{{$events->eventDateTime[0]->timeSlotFrom[0]}} @if(isset($events->eventDateTime[0]->timeSlotTo[0]) && !empty($events->eventDateTime[0]->timeSlotTo[0])) to {{$events->eventDateTime[0]->timeSlotTo[0]}} @endif</div>
                        </div>
                     </div>
                  </div>
                  @endif
                  @if(isset($events->eventDateTime[0]->attendees[0]) && !empty($events->eventDateTime[0]->attendees[0]))
                  <div class="col-6 n-gapp-3">
                     <div class="d-flex align-content-between align-items-center n-fc-white-500">
                        <div>
                           <i class="n-icon" data-icon="s-users"></i>
                        </div>
                        <div class="n-ml-15">
                           <span class="d-block n-fs-16 n-fw-500 n-lh-110 n-fc-white-500">No of Attendees</span>
                           <div class="n-fs-18 n-fw-500 n-lh-110 n-fc-white-500">{{$events->eventDateTime[0]->attendees[0]}}</div>
                        </div>
                     </div>
                  </div>
                  @endif
                  @if(isset($events->varAddress) && !empty($events->varAddress))
                  <div class="col-12 n-gapp-3">
                     <div class="d-flex align-content-between align-items-center n-fc-white-500">
                        <div>
                           <i class="n-icon" data-icon="s-map-pin"></i>
                        </div>
                        <div class="n-ml-15">
                           <span class="d-block n-fs-16 n-fw-500 n-lh-110 n-fc-white-500">Location</span>
                           <div class="n-fs-18 n-fw-500 n-lh-110 n-fc-white-500">{{$events->varAddress}}</div>
                        </div>
                     </div>
                  </div>
                  <div class="col-6 n-gapp-3">
                     <a href="#googleIframe" class="ac-btn ac-btn-primary btn-block ac-small">Map Location</a>
                  </div>
                  @endif
                  @if(isset($events->isRSVP) && !empty($events->isRSVP) && $events->isRSVP == 'Y')
                  <div class="col-6 n-gapp-3">
                     <a href="javascript:void(0)" class="ac-btn ac-btn-primary btn-block ac-small" onclick="eventInfo('{{ $events->dtDateTime }}','{{$events->id}}');" title="RSVP Register" data-toggle="modal" data-target="#rsvp">RSVP Register</a>
                  </div>
                  @endif
               </div>
            </div>
            @endif
         </div>
      </div>
   </div>
   <div class="container n-pv-40 n-pv-xl-80">
      <div class="row">
         <div class="col-xl-3 left-panel">
            <div class="nav-overlay" onclick="closeNav1()"></div>
            <div class="text-right">
               <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu" class="short-menu">Filter & Menu</a>
            </div>
            <div class="menu1" id="menu1">
               <div class="row n-mr-xl-15" data-aos="fade-up">
                  <div class="col-12">
                     <ul class="nqul share-func d-inline-flex n-fs-16 n-fw-600 n-fc-gray-500 n-lh-100 n-ff-2 n-mv-0">
                        <li>
                           <div class="dropdown ac-dropdown ac-noarrow">
                              <a class="dropdown-toggle" href="javascript:void(0)" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="n-icon" data-icon="s-share"></i>
                              Share
                              </a>
                              <div class="dropdown-menu a2a_kit" aria-labelledby="dropdownMenuLink">
                                 <a class="a2a_button_whatsapp dropdown-item d-flex align-items-center"><i class="fa fa-whatsapp"></i> Whats App</a>
                                 <div class="dropdown-divider"></div>
                                 <a class="a2a_button_facebook dropdown-item d-flex align-items-center"><i class="fa fa-facebook"></i> Facebook</a>
                                 <div class="dropdown-divider"></div>
                                 <a class="a2a_button_twitter dropdown-item d-flex align-items-center"><i class="fa fa-twitter"></i> Twitter</a>
                                 <div class="dropdown-divider"></div>
                                 <a class="a2a_button_google_gmail dropdown-item d-flex align-items-center"><i class="fa fa-google"></i> Gmail</a>
                                 <div class="dropdown-divider"></div>
                                 <a class="a2a_button_linkedin dropdown-item d-flex align-items-center"><i class="fa fa-linkedin"></i> Linkedin</a>
                                 <div class="dropdown-divider"></div>
                                 <a class="a2a_button_pinterest dropdown-item d-flex align-items-center"><i class="fa fa-pinterest"></i> Pinterest</a>
                                 <div class="dropdown-divider"></div>
                                 <a class="a2a_button_blogger dropdown-item d-flex align-items-center"><i class="fa fa-rss"></i> Blogger</a>
                              </div>
                           </div>
                        </li>
                        <li>
                           <a href="javascript:void(0)" onclick="window.print()" title="Print">
                           <i class="n-icon" data-icon="s-printer"></i>
                           Print
                           </a>
                        </li>
                        <li>
                           <a href="javascript:void(0)" title="Email" data-toggle="modal" data-target="#emailtoFriendModal">
                           <i class="n-icon" data-icon="s-mail"></i>
                           Email
                           </a>
                        </li>
                     </ul>
                  </div>
                  @if(count($relatedEvents) > 0)
                  <div class="col-12">
                     <article class="n-mt-xl-50 n-mt-25">
                        <div class="nqtitle-small lp-title text-uppercase n-mb-25">Related Posts</div>
                        @foreach($relatedEvents as $key => $event)
                        <div class="related-posts">
                           <div class="-rimg">
                              @if(isset($event->fkIntImgId) && !empty($event->fkIntImgId))
                              @php
                              $itemImg = App\Helpers\resize_image::resize($event->fkIntImgId);
                              @endphp
                              @else
                              @php
                              $itemImg=''
                              @endphp
                              @endif
                              <div class="thumbnail-container" data-thumb="66.66%">
                                 <div class="thumbnail">
                                    <img src="{{ $itemImg }}" alt="{{ $event->varTitle }}" title="{{ $event->varTitle }}">
                                 </div>
                              </div>
                           </div>
                           <div class="-rdesc">
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
                              @if(isset($event->dtDateTime) && !empty($event->dtDateTime))
                              @php
                              $dtDateTime = json_decode($event->dtDateTime);
                              @endphp
                              @else
                              @php
                              $dtDateTime = '';
                              @endphp
                              @endif
                              <a class="n-fs-18 n-fw-400 n-fc-gray-500 n-ah-a-500" href="{{$recordLinkUrl}}">{{ $event->varTitle }}</a>
                              <span class="-ricon d-flex align-items-center n-mt-10 n-fs-14 n-fc-gray-500 n-fw-500">
                              <i class="n-icon" data-icon="s-calendar"></i>
                              @if(isset($dtDateTime[0]) && !empty($dtDateTime[0]))
                              @if(isset($dtDateTime[0]->startDate) && !empty($dtDateTime[0]->startDate))
                              {{ date('M',strtotime($dtDateTime[0]->startDate)) }} {{ date('d',strtotime($dtDateTime[0]->startDate)) }}, {{ date('Y',strtotime($dtDateTime[0]->startDate)) }}
                              @endif
                              @endif
                              </span>
                           </div>
                        </div>
                        @endforeach
                     </article>
                  </div>
                  @endif
                  @if(isset($events->varTags) && !empty($events->varTags))
                  <div class="col-12">
                     <article class="n-mt-xl-50 n-mt-25">
                        <div class="nqtitle-small lp-title text-uppercase n-mb-25">Tags</div>
                        <div class="s-tags">
                           <ul class="nqul d-flex flex-wrap n-fs-14 n-ff-2 n-fw-600 n-fc-white-500">
                              @if(count(explode(",",$events->varTags)) > 0)
                              @foreach(explode(",",$events->varTags) as $tag)
                              <li><a href="javascript:void(0)" class="text-uppercase" title="{{$tag}}">{{$tag}}</a></li>
                              @endforeach
                              @endif
                           </ul>
                        </div>
                     </article>
                  </div>
                  @endif
                  <div class="col-12">
                     <article class="n-mt-xl-50 n-mt-25">
                        <div class="nqtitle-small lp-title text-uppercase n-mb-25">Event Location</div>
                        @php
                        if(isset($events->varAddress) && !empty($events->varAddress)) {
                        $googleMapURL = "https://www.google.com/maps?q={{$events->varAddress}}&output=embed";
                        } else {
                        $googleMapURL = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3765.609161803345!2d-81.37078378468483!3d19.29935544992782!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f258645dc740889%3A0x4e9ab9204d203c25!2sNetclues!5e0!3m2!1sen!2sin!4v1617864898087!5m2!1sen!2sin";
                        }
                        @endphp
                        <iframe id="googleIframe" src="{{$googleMapURL}}" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                     </article>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xl-9 n-mt-25 n-mt-xl-0">
            <div class="n-bs-1 n-pa-20 n-pa-lg-40 -content n-bgc-white-500">
               <div class="cms">
                  <h2>Event Time and Status</h2>
                  <table>
                     <tr>
                        <th width="10%">Start Date</th>
                        <th width="10%">End Date</th>
                        <th width="10%" class="text-center">Start Time</th>
                        <th width="10%" class="text-center">End Time</th>
                        <th width="8%" class="text-center">Attendees</th>
                        @if(isset($events->varAddress) && !empty($events->varAddress) && $events->varAddress != '')
                        <th width="32%"> Location</th>
                        @endif
                        <th width="10%" class="text-center">Status</th>
                     </tr>
                     @foreach($events->eventDateTime as $key => $value)
                     @foreach($value->timeSlotFrom as $valueKey => $data)
                     <tr>
                        <td>
                           @if(isset($value->startDate) && !empty($value->startDate))
                           {{ date('M',strtotime($value->startDate)) }} {{ date('d',strtotime($value->startDate)) }}, {{ date('Y',strtotime($value->startDate)) }}
                           @endif
                        </td>
                        <td>
                           @if(isset($value->endDate) && !empty($value->endDate))
                           {{ date('M',strtotime($value->endDate)) }} {{ date('d',strtotime($value->endDate)) }}, {{ date('Y',strtotime($value->endDate)) }}
                           @endif
                        </td>
                        <td class="text-center">
                           @if(isset($data) && !empty($data))
                           {{$data}}
                           @endif
                        </td>
                        <td class="text-center">
                           @if(isset($value->timeSlotTo[$valueKey]) && !empty($value->timeSlotTo[$valueKey]))
                           {{$value->timeSlotTo[$valueKey]}}
                           @endif
                        </td>
                        <td class="text-center">
                           @if(isset($value->attendees[$valueKey]) && !empty($value->attendees[$valueKey]))
                           {{$value->attendees[$valueKey]}}
                           @endif
                        </td>
                        @if(isset($events->varAddress) && !empty($events->varAddress))
                        <td>
                           {{$events->varAddress}}
                        </td>
                        @endif
                        <td class="text-center">
                           @php
                           $today = date('Y-m-d');
                           if($value->startDate >= $today && $today <= $value->endDate){
                           $time = date('h:i A');
                           $startTime = date('H:i A',strtotime($data));
                           if($value->endDate > $today) {
                           if($value->attendees[$valueKey] > $value->attendeeRegistered[$valueKey]) {
                           $status = 'Open';
                           } else {
                           $status = 'Closed';
                           }
                           } else {
                           if($time > $startTime) {
                           $status = 'Closed';
                           }else {
                           if($value->attendees[$valueKey] > $value->attendeeRegistered[$valueKey]) {
                           $status = 'Open';
                           } else {
                           $status = 'Closed';
                           }
                           }
                           }
                           } else {
                           $status = 'closed';
                           }
                           @endphp
                           {{$status}}
                        </td>
                     </tr>
                     @endforeach
                     @endforeach
                  </table>
               </div>
               @if(isset($txtDescription) && !empty($txtDescription))
               <div class="cms">
                  {!! htmlspecialchars_decode($txtDescription) !!}
               </div>
               @endif
               {{--
               <div class="cms">
                  <h2>What is Lorem Ipsum?</h2>
                  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                  <figure class="image image-style-align-left" style="width: 50%;">
                     <img src="https://cdn.pixabay.com/photo/2020/09/14/17/17/beach-5571533__340.jpg">
                     <figcaption>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</figcaption>
                  </figure>
                  <h2>Lorem ipsum dolor sit amet, consectetur.</h2>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc pulvinar urna sed magna euismod, id bibendum ligula ultrices. Donec non porta ligula. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Fusce eget risus vel nulla vestibulum pharetra. Quisque eleifend tellus ante, quis condimentum felis hendrerit a. Nulla iaculis nunc nisl, nec suscipit velit hendrerit in. Sed at nibh sed ligula imperdiet varius id at nisi. Vivamus nibh metus, pharetra et neque sed, pretium placerat nisl.</p>
                  <div class="page-break"></div>
                  <figure class="image image-style-align-right" style="width: 50%;">
                     <img src="https://cdn.pixabay.com/photo/2020/09/14/17/17/beach-5571533__340.jpg">
                     <figcaption>Lorem ipsum dolor sit amet, consectetur</figcaption>
                  </figure>
                  <h2>Vestibulum consectetur iaculis tempor. Vestibulum lorem ante, efficitur.</h2>
                  <p>Proin scelerisque a odio eget pretium. Integer maximus dictum lectus, eget imperdiet dui varius eget. Mauris ut sapien vestibulum, luctus velit sed, aliquam urna. Curabitur magna tortor, blandit id imperdiet ac, varius at justo. Aliquam erat volutpat. Sed commodo tincidunt hendrerit. Nullam rhoncus facilisis nisl suscipit elementum.</p>
                  <ul>
                     <li>Nulla condimentum nibh fermentum erat euismod gravida.</li>
                     <li>Maecenas vulputate leo ut ligula aliquet accumsan.</li>
                     <li>Duis rutrum ex nec finibus finibus.</li>
                  </ul>
                  <div class="page-break"></div>
                  <blockquote>
                     <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                  </blockquote>
                  <p>Morbi euismod lectus sed euismod sodales. Phasellus pulvinar nisl enim, eget mollis ex finibus nec. Vivamus tellus diam, finibus eu fermentum a, maximus nec velit. Vestibulum scelerisque turpis et nunc efficitur luctus. Aliquam aliquam rhoncus nisl, et dictum mi cursus vitae. Nam vitae convallis nunc. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                  <h2>Vivamus aliquam vel massa et porttitor. Nulla non hendrerit dolor.</h2>
                  <table>
                     <tr>
                        <th>Entry Header 1</th>
                        <th>Entry Header 2</th>
                        <th>Entry Header 3</th>
                        <th>Entry Header 4</th>
                        <th>Entry Header 5</th>
                        <th>Entry Header 6</th>
                        <th>Entry Header 7</th>
                     </tr>
                     <tr>
                        <td>Entry First Line 1</td>
                        <td>Entry First Line 2</td>
                        <td>Entry First Line 3</td>
                        <td>Entry First Line 4</td>
                        <td>Entry First Line 5</td>
                        <td>Entry First Line 6</td>
                        <td>Entry First Line 7</td>
                     </tr>
                     <tr>
                        <td>Entry Line 1</td>
                        <td>Entry Line 2</td>
                        <td>Entry Line 3</td>
                        <td>Entry Line 4</td>
                        <td>Entry Line 5</td>
                        <td>Entry Line 6</td>
                        <td>Entry Line 7</td>
                     </tr>
                     <tr>
                        <td>Entry Line 1</td>
                        <td>Entry Line 2</td>
                        <td>Entry Line 3</td>
                        <td>Entry Line 4</td>
                        <td>Entry Line 5</td>
                        <td>Entry Line 6</td>
                        <td>Entry Line 7</td>
                     </tr>
                     <tr>
                        <td>Entry Last Line 1</td>
                        <td>Entry Last Line 2</td>
                        <td>Entry Last Line 3</td>
                        <td>Entry Last Line 4</td>
                        <td>Entry Last Line 5</td>
                        <td>Entry Last Line 6</td>
                        <td>Entry Last Line 7</td>
                     </tr>
                  </table>
                  <p>Sed tincidunt ut nibh non bibendum. Donec nisl ligula, lacinia in porttitor sed, rhoncus ac massa. Suspendisse eget sem vel dolor volutpat fringilla et vitae arcu. Nulla pretium blandit enim, et aliquam erat. Donec sit amet sagittis diam, sit amet tincidunt lectus. Aenean quis est sit amet quam vehicula fringilla. Donec vel purus accumsan, congue lacus ut, porta diam. Mauris nec augue sem. Cras sed mauris libero.</p>
                  <h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris quis lorem mollis.</h2>
                  <table>
                     <tr>
                        <th rowspan="2">Entry Header 1</th>
                        <th rowspan="2">Entry Header 2</th>
                        <th rowspan="2">Entry Header 3</th>
                        <th colspan="3">Entry Header 4</th>
                        <th rowspan="2">Entry Header 5</th>
                     </tr>
                     <tr>
                        <th>Entry First Line 4.1</th>
                        <th>Entry First Line 4.2</th>
                        <th>Entry First Line 4.3</th>
                     </tr>
                     <tr>
                        <td>Entry First Line 1</td>
                        <td>Entry First Line 2</td>
                        <td>Entry First Line 3</td>
                        <td>Entry First Line 4</td>
                        <td>Entry First Line 5</td>
                        <td>Entry First Line 6</td>
                        <td>Entry First Line 7</td>
                     </tr>
                     <tr>
                        <td>Entry Line 1</td>
                        <td>Entry Line 2</td>
                        <td>Entry Line 3</td>
                        <td>Entry Line 4</td>
                        <td>Entry Line 5</td>
                        <td>Entry Line 6</td>
                        <td>Entry Line 7</td>
                     </tr>
                     <tr>
                        <td>Entry Line 1</td>
                        <td>Entry Line 2</td>
                        <td>Entry Line 3</td>
                        <td>Entry Line 4</td>
                        <td>Entry Line 5</td>
                        <td>Entry Line 6</td>
                        <td>Entry Line 7</td>
                     </tr>
                     <tr>
                        <td>Entry Last Line 1</td>
                        <td>Entry Last Line 2</td>
                        <td>Entry Last Line 3</td>
                        <td>Entry Last Line 4</td>
                        <td>Entry Last Line 5</td>
                        <td>Entry Last Line 6</td>
                        <td>Entry Last Line 7</td>
                     </tr>
                  </table>
                  <h2>Sed at rutrum arcu. Praesent id eleifend tortor. Donec.</h2>
                  <p>Ut eget ullamcorper felis, id ultrices odio. Donec porttitor non felis vitae dapibus. Proin vestibulum erat vel est mattis dictum. Quisque vulputate suscipit massa, ut tincidunt libero commodo sed. Nulla a cursus mi. Nunc suscipit ut lorem vitae tempus. Vestibulum tincidunt nulla ac erat facilisis condimentum. Etiam convallis lacus volutpat pulvinar tempus. Phasellus nec faucibus dolor. Sed elementum tempus lacus, vitae condimentum augue rutrum non. Sed finibus et lacus sit amet maximus. Duis a tincidunt justo, at lobortis orci.</p>
                  <blockquote>
                     <p>Etiam varius eget libero a blandit. Vivamus ornare ante et varius imperdiet. Fusce in erat id dui condimentum dignissim. Integer ornare, diam eu vestibulum bibendum, nulla urna venenatis nisi, vel dignissim magna augue nec nulla.</p>
                  </blockquote>
               </div>
               --}}
            </div>
         </div>
      </div>
   </div>
</section>
@if(!Request::ajax())
<!-- RSVP S -->
<div class="modal fade ac-modal" id="rsvp" tabindex="-1" aria-labelledby="rsvpLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <div class="n-fs-18 n-fw-600 n-ff-2 n-fc-white-500 n-lh-130">RSVP Registration</div>
            <a href="javascript:void(0)" data-dismiss="modal" aria-label="Close" class="ac-close">&times;</a>
         </div>
         <div class="modal-body ac-form-wd">
            {!! Form::open(['method' => 'post','class'=>'w-100 eventRSVP_form','id'=>'eventRSVP_form']) !!}
            <input class="form-control" type="hidden" id="eventId" name="eventId" value="" />
            <div class="row">
               <div class="col-sm-12 n-mb-45 text-center">
                  <h2 class="nqtitle-small n-fw-500">Cloud Covered: What was new with Google Cloud in September</h2>
               </div>
               <div class="col-lg-4 col-sm-6">
                  <div class="form-group ac-form-group ac-active-select">
                     <label class="ac-label" for="event_date">Event Date <span class="star">*</span></label>
                     <select class="selectpicker ac-input" data-width="100%" data-size="5" title="Select Event Date"  name="event_date" id="event_date">
                     </select>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-6">
                  <div class="form-group ac-form-group ac-active-select">
                     <label class="ac-label" for="event_time">Event Time <span class="star">*</span></label>
                     <select class="selectpicker ac-input" data-width="100%" data-size="5"  title="Select Event Time" name="event_time" id="event_time">
                     </select>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-6">
                  <div class="form-group ac-form-group ac-active-select">
                     <label class="ac-label" for="no_of_attendee">No of Attendees<span class="star">*</span></label>
                     <select class="selectpicker ac-input" data-width="100%" data-size="5"  name="no_of_attendee" id="no_of_attendee" title="Select Attendeese">
                        <option value="1" selected>01</option>
                        <option value="2">02</option>
                        <option value="3">03</option>
                        <option value="4">04</option>
                        <option value="5">05</option>
                     </select>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-12" id="attendeList">
                  <div class="row" id="attendeList0">
                     <div class="col-lg-4 col-sm-6">
                        <div class="form-group ac-form-group">
                           <label class="ac-label" for="firstName">Name <span class="star">*</span></label>
                           {!! Form::text('attendee[0][full_name]', '', array('id'=>'full_name0', 'class'=>'form-control ac-input', 'maxlength'=>'60', 'onpaste'=>'return false;', 'placeholder'=>'First Attendees Name', 'ondrop'=>'return false;')) !!}
                           @if ($errors->has('full_name'))
                           <span class="error">{{ $errors->first('full_name') }}</span>}}
                           @endif
                        </div>
                     </div>
                     <div class="col-lg-4 col-sm-6">
                        <div class="form-group ac-form-group">
                           <label class="ac-label" for="email">Email <span class="star">*</span></label>
                           {!! Form::text('attendee[0][email]', '', array('id'=>'email0', 'class'=>'form-control ac-input',  'spellcheck'=>'true','autocomplete'=>'off', 'placeholder'=>'First Attendees Email','maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                           @if ($errors->has('email'))
                           <span class="error">{{ $errors->first('email') }}</span>
                           @endif
                        </div>
                     </div>
                     <div class="col-lg-4 col-sm-6">
                        <div class="form-group ac-form-group">
                           <label class="ac-label" for="email">Phone</label>
                           {!! Form::number('attendee[0][phone]', '', array('id'=>'phone0', 'class'=>'form-control ac-input',  'spellcheck'=>'true','autocomplete'=>'off', 'placeholder'=>'First Attendees Phone', 'maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;','onkeypress'=>'javascript: return KeycheckOnlyPhonenumber(event);')) !!}
                           @if ($errors->has('phone'))
                           <span class="error">{{ $errors->first('phone') }}</span>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-sm-12">
                  <div class="form-group ac-form-group">
                     <label class="ac-label" for="message">Message</label>
                     {!! Form::textarea('message', '', array('id'=>'message', 'class'=>'form-control ac-textarea', 'rows'=>'4', 'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'600', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                     @if ($errors->has('message'))
                     <span class="error">{{ $errors->first('message') }}</span>
                     @endif
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group ac-form-group">
                     <div id="contactus_html_element" class="g-recaptcha"></div>
                     <div class="capphitcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}">
                        @if ($errors->has('g-recaptcha-response'))
                        <span class="error">{{ $errors->first('g-recaptcha-response') }}</span>
                        @endif
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group ac-form-group n-tar-sm n-tal">
                     <button type="submit" title="Register" class="ac-btn ac-btn-primary">Register</button>
                  </div>
               </div>
               <div class="col-sm-12">
                  <div class="form-group ac-form-group n-mb-0">
                     <div class="ac-note">
                        <b>Note:</b> In this, you can register a minimum of 1 and a maximum of 5 people, if you want to register more people, please fill the form again or contact the OfReg team. And this is an offline process, so please contact the OfReg team once you registered. <a href="#" target="_blank" title="Contact Detail">Contact Detail</a>.
                     </div>
                  </div>
               </div>
            </div>
            {!! Form::close() !!}
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   var sitekey = '{{Config::get("Constant.GOOGLE_CAPCHA_KEY")}}';
   var onContactloadCallback = function () {
   	grecaptcha.render('contactus_html_element', {
   		'sitekey': sitekey
   	});
   };
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{Config::get('Constant.GOOGLE_MAP_KEY')}}&callback=initMap" async defer></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onContactloadCallback&render=explicit" async defer></script>
<script src="{{ $CDN_PATH.'assets/js/packages/events/rsvp_validation.js' }}" type="text/javascript"></script>
<!-- RSVP E -->
@section('footer_scripts')
@endsection
@endsection
@endif