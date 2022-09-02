@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

@if(isset($PageData['response']) && !empty($PageData['response']) && $PageData['response'] != '[]')
		{!! $PageData['response'] !!}
@else 
	<section class="page_section">
		<div class="container">
			<div class="row">
				<div class="col-12 text-center">
					<h2>Coming Soon...</h2>
				</div>	
			</div>
		</div>  
	</section>    
@endif

@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif