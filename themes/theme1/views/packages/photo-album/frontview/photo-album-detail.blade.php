@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
<section>
	<div class="inner-page-container cms events_detail">
       
        <div class="container">
			<!-- Main Section S -->
            <div class="row">
                <div class="col-md-12 col-md-12 col-xs-12 animated fadeInUp">
                    <div class="right_content">
						<div class="back_div">
							@php
							$start_date_time = '';
							if(isset($_REQUEST['start_date_time']) && strip_tags($_REQUEST['start_date_time']) != ''){
								$start_date_time = '&start_date_time='.strip_tags($_REQUEST['start_date_time']);
							}
							$end_date_time = '';
							if(isset($_REQUEST['end_date_time']) && strip_tags($_REQUEST['end_date_time']) != ''){
								$end_date_time = '&end_date_time='.strip_tags($_REQUEST['end_date_time']);
							}
							$page = '';
							if(isset($_REQUEST['page']) && strip_tags($_REQUEST['page']) != ''){
								$page = '&page='.strip_tags($_REQUEST['page']);
							}
							$catid = '';
							if(isset($_REQUEST['catid']) && intval($_REQUEST['catid']) != ''){
								$catid = '&catid='.intval($_REQUEST['catid']);
							}
							if(isset($_REQUEST['N']) && intval($_REQUEST['N']) == 'C'){
								$Nurl = '?N='.strip_tags($_REQUEST['N']);
							}else{
								$Nurl = '?N=N';
							}

							if($Nurl == "?N=N"){
								$backpageUrl = $moduleFrontWithCatUrl;	
							}else{
								$backpageUrl = App\Helpers\MyLibrary::getFront_Uri('photoAlbum')['uri'];
							}
							
							@endphp
							<a href="{{ $backpageUrl }}" title="Back"><i class="fi flaticon-right"></i> Back</a>
						</div>
						
						
						@php
						$blogstartDate = date('l F jS, Y',strtotime($photoAlbum->dtDateTime));
						$blogDisplayDate = $blogstartDate;
						if(!empty($photoAlbum->dtEndDateTime) && $photoAlbum->dtEndDateTime != null){
						$blogExpityDate = date('l F jS, Y',strtotime($blogs->dtEndDateTime));
						$blogDisplayDate = $blogstartDate." to ".$blogExpityDate;
						}
						@endphp
						<h1 class="cms_detail_h2 ac-mb-xs-15">{{ $photoAlbum->varTitle }}</h1>
						<h6 class="ac-mb-xs-15">{{ $blogDisplayDate }}</h6>
						@if(isset($photoAlbum->txtDescription) && !empty($photoAlbum->txtDescription))
						<h6>Description</h6>
						{!! htmlspecialchars_decode($photoAlbum->txtDescription) !!}
						@endif

						@if(!empty($photoAlbum->fkIntDocId))
						@php
						$docsAray = explode(',', $photoAlbum->fkIntDocId);
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