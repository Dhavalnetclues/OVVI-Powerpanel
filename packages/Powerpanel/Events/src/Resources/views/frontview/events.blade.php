@if(!Request::ajax())
	@extends('layouts.app')
	@section('content')
	@include('layouts.inner_banner')
@endif

	@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
		<section class="inner-page-gap event-page">
			{{--@include('layouts.shareIcon')--}}

			<div class="container">
				<div class="row">
					{{--@include('events::frontview.events-left-panel')--}}

					{{--@include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])--}}
					<div class="col-xl-12" id="pageContent">
					</div>  
				</div>
			</div>
		</section>	
	@else
		@if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]')
			<section class="inner-page-gap event-page">
			 	{{--@include('layouts.shareIcon')--}}

				<div class="container">
					<div class="row">
					{{--@include('events::frontview.events-left-panel')--}}

						<div class="col-xl-12" id="pageContent">
							{!! $PAGE_CONTENT['response'] !!}
						</div>  
					</div>
				</div>
			</section>
		@else 
			@include('coming-soon')
		@endif
	@endif
	<!-- RSVP S -->
	{{-- @include('events::frontview.rsvp_form')	 --}}
	<!-- RSVP E -->
@if(!Request::ajax())
@endsection
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

@section('page_scripts')
	<script type="text/javascript">
		let textDescription = "{{ json_encode($txtDescription) }}";
		var Limits = "{{$limit}}";
	</script>
	<script src="{{ $CDN_PATH.'assets/libraries/masked-input/jquery.mask.min.js' }}"></script>
	<script src="{{ $CDN_PATH.'assets/js/packages/events/events.js' }}" type="text/javascript"></script>	
@endsection