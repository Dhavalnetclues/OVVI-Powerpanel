<div class="title_bar">
    <div class="page-head">
        <div class="page-title">
            <h1>{{ $breadcrumb['title']}} </h1>
        </div>
    </div>	
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <span aria-hidden="true" class="icon-home"></span>
            <a href="{{ url('powerpanel') }}">{{ trans('template.common.home') }}</a>
            <i class="fa fa-circle"></i>
        </li>
        @if(isset($breadcrumb['url']))
        <li>
            <a href="{{ url($breadcrumb['url']) }}">{{ $breadcrumb['module'] }}</a>
            <i class="fa fa-circle"></i>
        </li>
        @endif
        @if(isset($breadcrumb['inner_title']))			
        <li class="active">
            {{ $breadcrumb['inner_title'] }}
        </li>
        @else
        <li class="active">
            {{ $breadcrumb['title']}}
        </li>
        @endif
    </ul>		
    @if(isset($breadcrumb['url']))
    <div class="add_category_button pull-right">
        <a title="Go to list" class="add_category" href="{{ url($breadcrumb['url']) }}">
            <span title="Go to list">{{ trans('template.common.back') }}</span> <i class="la la-arrow-left"></i>
        </a>
    </div>
    @else
    <div class="add_category_button pull-right">
        <a title="Help" class="add_category" target="_blank" href="{{ $CDN_PATH.'assets/videos/Shield_CMS_WorkFlow.mp4'}}">
            <span title="Help">Help</span> <i class="la la-question-circle"></i>
        </a>
         @if(isset($_REQUEST['id']) && isset($_REQUEST['mid']))
        <a title="Go to list" class="add_category" href="{{ url('powerpanel/pages') }}">
            <span title="Go to list">Back</span> <i class="la la-arrow-left"></i>
        </a>
         @endif
    </div>	
   
    @endif

</div>