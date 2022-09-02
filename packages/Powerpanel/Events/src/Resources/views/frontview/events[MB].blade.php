@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
<?php if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]'){
echo $PAGE_CONTENT['response'];
}else{?>
<section class="page_section">
<div class="container">
		<div class="row">
			<div class="col-12 text-center">
				<h2>Coming Soon...</h2>
			</div>	
		</div>
</div>
</section>
<?php } ?>
@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif