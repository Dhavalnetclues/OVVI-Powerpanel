<style type="text/css">
.vscrollbar{
height: 400px; width:auto; overflow-y: auto; overflow-x: hidden;
}
</style>

	<div class="col-md-3 col-sm-3 col-xs-3">	
		<div class="page-sidebar-wrapper">
		<div class="page-sidebar">
			<div class="scroller" style="max-height:420px;" data-rail-visible="1" data-rail-color="#fff" data-handle-color="#ccc">
				<ul class="page-sidebar-menu" data-keep-expanded="true" data-auto-scroll="true" data-slide-speed="200">
					@php $active = (null!==Session::get('moduleSettting'))?Session::get('moduleSettting'):0;  @endphp
					@foreach($modules as $key=>$module)					
					<li @if($active==$module->id) class="active" @endif data-id="{{$module->id}}">
						<a href="#tab_6_{{$module->id}}" data-toggle="tab"> {{ $module->varTitle }} </a>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
</div>


<div class="col-md-9 col-sm-9 col-xs-9">
	<div class="tab-content">
		@foreach($modules as $key=>$module)
		@php $settingViewPath=$module->varModuleName; @endphp
		
		@if(strtolower($module->varModuleName)=='blogs')
		@php $settingViewPath='blog'; @endphp
		@endif
		@if(strtolower($module->varModuleName)=='contact-us')
		@php $settingViewPath='contact_lead'; @endphp
		@endif
		@if(strtolower($module->varModuleName)=='log')
		@php $settingViewPath='logmanager'; @endphp
		@endif
		@if(strtolower($module->varModuleName)=='pages')
		@php $settingViewPath='cms_pages'; @endphp
		@endif
		@if(strtolower($module->varModuleName)=='photo-gallery')
		@php $settingViewPath='photo_album'; @endphp
		@endif
		@if(strtolower($module->varModuleName)=='video-gallery')
		@php $settingViewPath='video_album'; @endphp
		@endif
		@if(strtolower($module->varModuleName)=='popup')
		@php $settingViewPath='managepopup'; @endphp
		@endif
		@if(strtolower($module->varModuleName)=='product-category')
		@php $settingViewPath='products_category'; @endphp
		@endif
		@if(strtolower($module->varModuleName)=='service-category')
		@php $settingViewPath='services_category'; @endphp
		@endif
		@if(strtolower($module->varModuleName)=='static-block')
		@php $settingViewPath='static_blocks'; @endphp
		@endif
		@if(strtolower($module->varModuleName)=='changeprofile')
		@php $settingViewPath='profile'; @endphp
		@endif
		@if(view()->exists('powerpanel.'. str_replace('-','_',$settingViewPath) .'.partials.settings'))
		<div class="tab-pane @if($module->id==$active) active @else fade @endif" id="tab_6_{{$module->id}}">
			<div class="portlet-form">
				<div class="form-body">
					{!! Form::open(['method' => 'post','id' => $module->varModuleName.'_'.$module->id]) !!}
					{!! Form::hidden('moduleId' , $module->id) !!}
					{!! Form::hidden('moduleName' , $module->varModuleName) !!}
					<div class="row">@include('powerpanel.'. str_replace('-','_',$settingViewPath) .'.partials.settings')</div>
					<a href="javascript:;" data-id="{{$module->varModuleName.'_'.$module->id}}" class="btn btn-green-drake save-module-settings submit">{{ trans('template.common.save') }}</a>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
		@else
		<div class="tab-pane @if($key==0) active @else fade @endif" id="tab_6_{{$key}}">
			<div class="portlet-form">
				<div class="form-body">
					No Setting(s) available for this module
				</div>
			</div>
		</div>
		@endif
		@endforeach
	</div>
</div>