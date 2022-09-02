@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
<section>
	<div class="inner-page-gap cms blog-details">

		<div class="container">
			<!-- Main Section S -->
			<div class="row">
				<div class="col-md-12 col-md-12 col-xs-12">
					
						<div class="col-md-12 col-md-12 col-xs-12 animated fadeInUp blog_details_sec">
							@php
							$blogstartDate = date('l F jS, Y',strtotime($blogs->dtDateTime));
							$blogDisplayDate = $blogstartDate;
							if(!empty($blogs->dtEndDateTime) && $blogs->dtEndDateTime != null){
								$blogExpityDate = date('l F jS, Y',strtotime($blogs->dtEndDateTime));
								$blogDisplayDate = $blogstartDate." to ".$blogExpityDate;
							}
							@endphp

							@if(isset($blogs->fkIntImgId) && $blogs->fkIntImgId != '')
								@php $itemImg = App\Helpers\resize_image::resize($blogs->fkIntImgId) @endphp
								<img class="img-fluid" src="{{ $itemImg }}" alt="{{ $blogs->varTitle }}">
								
							@endif

							@if(!empty($blogs->fkIntDocId))
							@php
							$docsAray = explode(',', $blogs->fkIntDocId);
							$docObj   = App\Document::getDocDataByIds($docsAray);
							@endphp
							@if(count($docObj) > 0)
							<div class="download_files clearfix">
								<h6>Download(s)</h6>
								<ul>
									@foreach($docObj as $key => $val)
									@php
									if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
									$blank = 'target="_blank"';
									}else{
									$blank = '';
									}
									if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
									$icon = "flaticon-pdf-file";
									}elseif($val->varDocumentExtension == 'doc' || $val->varDocumentExtension == 'docx'){
									$icon = "flaticon-doc-file";
									}elseif($val->varDocumentExtension == 'xls' || $val->varDocumentExtension == 'xlsx'){
									$icon = "flaticon-xls-file";
									}else{
									$icon = "flaticon-doc-file";
									}
									@endphp
									<li><a {!! $blank !!} href="{{ $CDN_PATH.'documents/'.$val->txtSrcDocumentName.'.'.$val->varDocumentExtension }}" data-viewid="{{ $val->id }}" data-viewtype="download" class="docHitClick" title="{{ $val->txtDocumentName }}" ><i class="fi {{ $icon }}"></i>{{ $val->txtDocumentName }}</a></li>
									@endforeach
								</ul>
							</div>
							@endif
							@endif
						</div>

						@if (isset($txtDescription['response']) && !empty($txtDescription['response']))
                <div class="cms n-mt-25">
                    {!! htmlspecialchars_decode($txtDescription['response']) !!}
                </div>
            @else
                <div class="cms n-mt-25">
                    <p>{!! htmlspecialchars_decode($blogs->varShortDescription) !!}</p>
                </div>
            @endif

					</div>
				</div>
			</div>
			<!-- Main Section E -->
		</div>
	</div>
</section>
@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif
