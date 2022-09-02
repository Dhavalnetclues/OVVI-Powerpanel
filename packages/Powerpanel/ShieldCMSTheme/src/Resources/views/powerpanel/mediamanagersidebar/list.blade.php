@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/select2/css/select2.min.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/select2/css/select2-bootstrap.min.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/fancybox/fancybox@3.5.6/dist/jquery.fancybox.min.css' }}" rel="stylesheet" type="text/css"/>
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('content')
@include('powerpanel.partials.breadcrumbs')
<div class="col-md-12">

    <div class="row">
        <div class="portlet light bdisplay_ordered">
            <div class="portlet-body form_pattern">
                <div class="video-gallery">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="media_image_box">
                                <a title="Image Upload" onclick="MediaManager.open('sidebar_gallery');" href="javascript:;" class="media_manager nav-link nav-toggle">
                                    <i class="icon-picture"></i>
                                    <span class="title">Image<br/>Upload</span>
                                </a> 
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="media_image_box">
                                <a title="Document Upload" onclick="MediaManager.openDocumentManager('sidebar_gallery');" data-multiple='false' href="javascript:;" class="document_manager nav-link nav-toggle">
                                    <i class="fa fa-file-text-o"></i>
                                    <span class="title">Document<br/>Upload</span>
                                </a> 
                            </div>
                        </div>
                        @if (Config::get('Constant.DEFAULT_AUDIO') == 'Y')
                        <div class="col-md-3">
                            <div class="media_image_box" style="display: none;">
                                <a title="Audio Upload" onclick="MediaManager.openAudioManager('sidebar_gallery');" data-multiple='false' href="javascript:;" class="audio_manager nav-link nav-toggle">
                                    <i class="fa fa-file-audio-o"></i>
                                    <span class="title">Audio<br/>Upload</span>
                                </a> 
                            </div>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <div class="media_image_box" style="display: none;">
                                <a title="Video Upload" onclick="MediaManager.openVideoManager('sidebar_gallery');" data-multiple='false' href="javascript:;" class="video_manager nav-link nav-toggle">
                                    <i class="fa fa-file-video-o"></i>
                                    <span class="title">Video<br/>Upload</span>
                                </a> 
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<script src="{{ $CDN_PATH.'resources/global/plugins/fancybox/fancybox@3.5.6/lib/jquery-3.2.1.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/fancybox/fancybox@3.5.6/dist/jquery.fancybox.min.js' }}" type="text/javascript"></script>
@endsection
@section('scripts')
@endsection