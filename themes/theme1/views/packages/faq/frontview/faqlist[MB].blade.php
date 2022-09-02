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
<style>
    .password_form {
        padding: 40px;
        background: #fff;
        box-shadow: 0 0 25px rgba(0,0,0,.5);
        max-width: 600px;
        margin: auto;
    }
    .password_form .label-title {    
        font-weight: 400;
        margin-bottom: 5px;
        font-size: 14px;
        color: gray;
    }
    .ac-border {   
        max-width: 200px;
        width: 100%;
        margin-top:10px;
    }
</style>
<script type="text/javascript">
    var ajaxModuleUrl = "{{ App\Helpers\MyLibrary::getFront_Uri('faq')['uri'] }}";
</script>
<script src="{{ $CDN_PATH.'assets/js/packages/faq/faq.js' }}"></script>
@endsection

