@php
$eventurl = '';
@endphp
@if(isset($data['events']) && !empty($data['events']) && count($data['events']) > 0)
@php 
$cols = 'col-md-4 col-sm-4 col-xs-12';
$grid = '3';
if($data['cols'] == 'grid_2_col'){
$cols = 'col-md-6 col-sm-6 col-xs-12';
$grid = '2';
}elseif ($data['cols'] == 'grid_3_col') {
$cols = 'col-md-4 col-sm-4 col-xs-12';
$grid = '3';
}elseif ($data['cols'] == 'grid_4_col') {
$cols = 'col-md-3 col-sm-6 col-xs-12';
$grid = '4';
}
if(isset($data['class'])){
$class = $data['class'];
}
if(isset($data['paginatehrml']) && $data['paginatehrml'] == true){
$pcol = $cols;
}else{
$pcol = 'item';
}
@endphp
@if(Request::segment(1) == '')
<section class="events_sec owl-section {{ $class }}" data-grid="{{ $grid }}">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 animated fadeInUp">
                <div class="same_title text-center">
                    @if(isset($data['title']) && $data['title'] != '')
                    <h2 class="title_div">{{ $data['title'] }}</h2>
                    @endif
                    @if(isset($data['desc']) && $data['desc'] != '')
                    <p>{!! $data['desc'] !!}</p>
                    @endif
                </div>
            </div>
        </div>   
        <div class="blog_slide"> 
            <div class="row">
                @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
                <div class="col-12">
                    <div class="owl-carousel owl-theme owl-nav-absolute">
                        @endif
                        @foreach($data['events'] as $event)
                        @php
                        if(isset(App\Helpers\MyLibrary::getFront_Uri('events')['uri'])){
                        $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('events')['uri'];
                        $moduleFrontWithCatUrl = ($event->varAlias != false ) ? $moduelFrontPageUrl . '/' . $event->varAlias : $moduelFrontPageUrl;
                        $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('event-category',$event->intFKCategory);
                        $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$event->alias->varAlias;
                        }else{
                        $recordLinkUrl = '';
                        }
                        @endphp
                        @if(isset($event->fkIntImgId))
                        @php                          
                        $itemImg = App\Helpers\resize_image::resize($event->fkIntImgId);
                        @endphp
                        @else 
                        @php
                        $itemImg = $CDN_PATH.'assets/images/event_img1.jpg';
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
                        @if($data['cols'] == 'list')
                        <div class="col-sm-12 col-xs-12 animated fadeInUp">
                            <div class="event_post listing">
                                @if(isset($event->fkIntImgId) && $event->fkIntImgId != '')
                                <div class="image">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $event->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $event->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}">{{ $event->varTitle }}</a></h5>
                                    @if(isset($description) && $description != '')
                                    <p class="cat_div"> {!! (strlen($description) > 150) ? substr($description, 0, 150).'...' : $description !!}</p>
                                    @endif
                                    @if(isset($event->dtDateTime) && $event->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($event->dtDateTime)) }}</div>
                                    @endif
                                    @if(isset($event->dtEndDateTime) && $event->dtEndDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($event->dtEndDateTime)) }}</div>
                                    @endif
                                </div>
                                <!-- <div class="info_more text-right">
                                    <a class="info_link" href="{{ $recordLinkUrl }}" title="Read More">Read More <i class="fa fa-angle-double-right"></i></a>
                                </div> -->
                                            @if( (($event->dtDateTime) >= $today) && (($event->dtEndDateTime) <= $today) )
                                            <h6>RSVP : Event End</h6>
                                            @else
                                            <h6>RSVP : Event Start</h6>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Open modal</button>
                                            @endif
                            </div>
                        </div>
                        @else
                        <div class="{{ $pcol }} animated fadeInUp">
                            <div class="event_post">
                                @if(isset($event->fkIntImgId) && $event->fkIntImgId != '')
                                <div class="image">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $event->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $event->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}">{{ $event->varTitle }}</a></h5>
                                    @if(isset($description) && $description != '')
                                    <p class="cat_div"> {!! (strlen($description) > 80) ? substr($description, 0, 80).'...' : $description !!}</p>
                                    @endif
                                    @if(isset($event->dtDateTime) && $event->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($event->dtDateTime)) }}</div>
                                    @endif
                                    @if(isset($event->dtEndDateTime) && $event->dtEndDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($event->dtEndDateTime)) }}</div>
                                    @endif <br>
                                    <!-- <div class="info_more text-right">
                                        <a class="info_link" href="{{ $recordLinkUrl }}" title="Read More">Read More <i class="fa fa-angle-double-right"></i></a>
                                    </div><br> -->
                                            @if( (($event->dtDateTime) <= ($data['today'])) && (($event->dtEndDateTime) >= ($data['today'])) )
                                            <h6>RSVP : Event End</h6>
                                            @else
                                            <h6>RSVP : Event Start</h6>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Open modal</button>
                                            @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach  
                        @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
                    </div>
                </div>
                @endif
            </div>
        </div>                
        <div class="row">
            <div class="col-sm-12 col-xs-12 animated fadeInUp text-center">               
                <a class="btn ac-border btn-more" href="{!! $eventurl !!}" title="View All Events">View All Events</a>               
            </div>
        </div>
    </div>
</section>
@else
<div class="row">
    <div class="col-sm-12 col-xs-12 animated fadeInUp">
        <div class="same_title text-center">
            @if(isset($data['desc']) && $data['desc'] != '')
            <p>{!! $data['desc'] !!}</p>
            @endif
        </div>
    </div>
</div>    
<div class="row owl-section {{ $class }}" data-grid="{{ $grid }}">
    @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
    <div class="col-12">
        <div class="owl-carousel owl-theme owl-nav-absolute">
            @endif
            @foreach($data['events'] as $event)
            @php
            if(isset(App\Helpers\MyLibrary::getFront_Uri('events')['uri'])){
            $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('events')['uri'];
            $moduleFrontWithCatUrl = ($event->varAlias != false ) ? $moduelFrontPageUrl . '/' . $event->varAlias : $moduelFrontPageUrl;
            $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('events',$event->intFKCategory);
            $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$event->alias->varAlias;
            }else{
            $recordLinkUrl = '';
            }
            @endphp
            @if(isset($event->fkIntImgId))
            @php                          
            $itemImg = App\Helpers\resize_image::resize($event->fkIntImgId);
            @endphp
            @else 
            @php
            $itemImg = $CDN_PATH.'assets/images/event_img1.jpg';
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
            @if($data['cols'] == 'list')
            <div class="col-sm-12 col-xs-12 animated fadeInUp">
                <div class="event_post listing">
                    @if(isset($event->fkIntImgId) && $event->fkIntImgId != '')
                    <div class="image">
                        <div class="thumbnail-container">
                            <div class="thumbnail">
                                <a title="{{ $event->varTitle }}" href="{{ $recordLinkUrl }}">
                                    <img src="{{ $itemImg }}" alt="{{ $event->varTitle }}">
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="info">
                        <h5 class="sub_title"><a href="{{ $recordLinkUrl }}">{{ $event->varTitle }}</a></h5>
                        @if(isset($description) && $description != '')
                        <p class="cat_div"> {!! (strlen($description) > 150) ? substr($description, 0, 150).'...' : $description !!}</p>
                        @endif
                        <!-- <h6>No of attendees : {{ $event->intAttendees }}</h6> -->
                        @if(isset($event->dtDateTime) && $event->dtDateTime != '')
                        <div class="date">{{ date('l d M, Y',strtotime($event->dtDateTime)) }}</div>
                        @endif
                        @if(isset($event->dtEndDateTime) && $event->dtEndDateTime != '')
                        <div class="date">{{ date('l d M, Y',strtotime($event->dtEndDateTime)) }}</div>
                        @endif
                    </div>
                    <!-- <div class="info_more text-right">
                        <a class="info_link" href="{{ $recordLinkUrl }};" title="Read More">Read More <i class="fa fa-angle-double-right"></i></a>
                    </div> -->
                        
                </div>
            </div>
            @else
            <div class="{{ $pcol }} animated fadeInUp">
                <div class="event_post">
                    @if(isset($event->fkIntImgId) && $event->fkIntImgId != '')
                    <div class="image">
                        <div class="thumbnail-container">
                            <div class="thumbnail">
                                <a title="{{ $event->varTitle }}" href="{{ $recordLinkUrl }}">
                                    <img src="{{ $itemImg }}" alt="{{ $event->varTitle }}">
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="info">
                        <h5 class="sub_title"><a href="{{ $recordLinkUrl }}">{{ $event->varTitle }}</a></h5>
                        @if(isset($description) && $description != '')
                        <p class="cat_div"> {!! (strlen($description) > 80) ? substr($description, 0, 80).'...' : $description !!}</p>
                        @endif
                        <!-- <h6>No of attendees : {{ $event->intAttendees }}</h6> -->
                        @if(isset($event->dtDateTime) && $event->dtDateTime != '')
                        <div class="date">{{ date('l d M, Y',strtotime($event->dtDateTime)) }}</div>
                        @endif
                        @if(isset($event->dtEndDateTime) && $event->dtEndDateTime != '')
                        <div class="date">{{ date('l d M, Y',strtotime($event->dtEndDateTime)) }}</div>
                        @endif
                        <!-- <div class="info_more text-right">
                            <a class="info_link" href="{{ $recordLinkUrl }}" title="Read More">Read More <i class="fa fa-angle-double-right"></i></a>
                        </div> -->
                          
                    </div>
                </div>
            </div>
            @endif
            @endforeach  
            @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
        </div>
    </div>
    @endif
     @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
    @if($data['events']->total() > $data['events']->perPage())
    <div class="row">
        <div class="col-sm-12 n-mt-30" data-aos="fade-up">
            {{ $data['events']->links() }}
        </div>
    </div>
    @endif
    @endif
</div>
@endif
@endif




<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>

          <div class="form-group">
            <label for="Full Name" class="col-form-label">Full Name:</label>
            <input type="text" class="form-control" id="fullname">
          </div>
          <div class="form-group">
            <label for="Email" class="col-form-label">Email:</label>
            <input type="text" class="form-control" id="email"></input>
          </div>
          <div class="form-group">
            <label for="Phone No" class="col-form-label">Phone No:</label>
            <input type="text" class="form-control" id="phoneno"></input>
          </div>
          <div class="form-group">
            <label for="" class="col-form-label">Nomber of attendees:</label>
            <input type="text" class="form-control" id="noofattendees"></input>
          </div>
          <div class="form-group">
            <label for="" class="col-form-label">Add an RSVP note to the event:</label>
            <textarea class="form-control" id=""></textarea>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Send message</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){

$('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text('New message to ' + recipient)
  modal.find('.modal-body input').val(recipient)
})

});
</script>