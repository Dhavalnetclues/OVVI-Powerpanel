@if($type == 'multiple')
<div class="row viduploader">
  <div class="col-md-12">
    <div class="image_thumb">
      <div class="form-group {{ $errors->has($name) ? ' has-error' : '' }} ">
        <label class="form_title" for="front_logo">{!! $label !!}</label>
        <div class="clearfix"></div>
        <div class="fileinput fileinput-new" data-provides="fileinput">
          <div class="fileinput-preview thumbnail {{ $id }}_vid" data-trigger="fileinput"
            style="width:100%;height:120px;position: relative;">
            @if(Request::old('video_url'))
            <img src="{{ Request::old('video_url') }}" />
            @else
            <img class="img_opacity" src="{{ $CDN_PATH.'resources\images\video_upload_file.gif' }}" />
            @endif
          </div>
          <div class="input-group">
            <a class="video_manager multiple-selection" data-multiple="true"
              onclick="MediaManager.openVideoManager('{{ $id }}');"><span class="fileinput-new"></span></a>
            <input class="form-control" type="hidden" id="{{ $id }}" name="{{ $name }}"
              value="{{ isset($data->fkIntVideoId)?$data->fkIntVideoId:old($name) }}" />
            <input class="form-control" type="hidden" id="video_url" name="video_url"
              value="{{ Request::old('video_url') }}" />
          </div>
          @if(!empty($data->fkIntVideoId) && isset($data->fkIntVideoId))
          <div id="{{ $id }}_vid" class="video_list">
            <div class="multi_image_list">
              <ul>
                @foreach($videoData as $key => $value)
                <li id="{{ $value->id }}">
                  <span>
                    @if(!empty($value->youtubeId))
                    <img title="{{ $value->varVideoName }}"
                      src="https://img.youtube.com/vi/{{ $value->youtubeId }}/mqdefault.jpg">
                    @elseif(!empty($value->vimeoId))
                    @php $vimeoData =
                    unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$value->vimeoId.'.php')); @endphp
                    <img title="{{ $value->varVideoName }}" src="{{ $vimeoData[0]['thumbnail_medium'] }}">
                    @else
                    <img title="{{ $value->txtVideoOriginalName }}" class="img_opacity"
                      src="{{ $CDN_PATH.'resources\images\video_upload_file.gif' }}" >
                    @endif
                    <a href="javascript:;" onclick="MediaManager.removeVideoFromVideoManager('{{ $value->id }}');"
                      class="delect_image" data-dismiss="fileinput"><i class="ri-time-line"></i></a>
                  </span>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
          @else
          <div id="{{ $id }}_vid" class="video_list"></div>
          @endif
        </div>
      </div>
      <div class="clearfix"></div>
      <span>({{ trans('template.common.videoReomandation') }}.)</span> <span style="color:#e73d4a">
        {{ $errors->first($name) }}</span>
    </div>
  </div>
</div>
@elseif($type == "single")
<div class="row viduploader">
  <div class="col-md-12">
    <div class="video_thumb  image_thumb">
      <div class="form-group {{ $errors->has($name) ? ' has-error' : '' }} ">
        <label class="form_title" for="front_logo">{!! $label !!}</label>
        <div class="clearfix"></div>
        <div class="fileinput fileinput-new video-fileinput" data-provides="fileinput">
          <div class="fileinput-preview thumbnail video-fileinput {{ $id }}_vid" data-trigger="fileinput"
            style="width:100%;height:120px;position: relative;">
            
          @if(Request::old('video_url'))          
           <img src="{{ Request::old('video_url') }}" />            
          @elseif(!empty($data->fkIntVideoId) && isset($data->fkIntVideoId))          
                  @if(!empty($videoData->youtubeId))
                     <img title="{{ $videoData->varVideoName }}" src="https://img.youtube.com/vi/{{ $videoData->youtubeId }}/mqdefault.jpg">
                  @elseif(!empty($videoData->vimeoId))
                    @php $vimeoData = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videoData->vimeoId.'.php')); @endphp
                      <img title="{{ $videoData->varVideoName }}" src="{{ $vimeoData[0]['thumbnail_medium'] }}">
                 @else
                    <img title="{{ $videoData->txtVideoOriginalName }}" class="img_opacity" src="{{ $CDN_PATH.'resources/images/video_upload_file.gif' }}">
                  @endif          
          @else
                <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/video_upload_file.gif' }}"/>
          @endif
            
          </div>
          <div class="input-group">
            <a class="video_manager multiple-selection" data-multiple="false" onclick="MediaManager.openVideoManager('{{ $id }}');"><span
                class="fileinput-new"></span> </a>                
                <input class="form-control" type="hidden" id="{{ $id }}" name="{{ $name }}"
          value="{{ (isset($data->fkIntVideoId) && !empty($data->fkIntVideoId)?$data->fkIntVideoId:'') }}" />
              <input class="form-control" type="hidden" id="video_url" name="video_url"
          value="{{ Request::old('video_url') }}" />

          </div>        
          
          
              <div class="overflow_layer" style="display:none">
                <a href="javascript:;" onclick="MediaManager.openVideoManager('{{ $id }}');" class="video_manager editVideo" data-multiple="false"><i class="fa fa-pencil"></i></a>
                <a href="javascript:;" class="fileinput-exists removeVideo" data-dismiss="fileinput"> 
                  <i class="fa fa-trash-o"></i> </a>
              </div>
          

          @if(!empty($data->video->varVideoName))
          <input disabled="disabled" id="video_name" class="form-control" type="text"
            value="{{ $data->video->varVideoName }}.{{ $data->video->varVideoExtension }}" />
          @else
          <input disabled="disabled" id="video_name" class="form-control" type="text"
            value="{{ isset($data->video->varVideoName)?$data->video->varVideoName:'' }}" />
          @endif 

      </div>
      <div class="clearfix"></div>
      <span>({!! trans('template.common.videoReomandation') !!}.)</span> <span style="color:#e73d4a">
        {{ $errors->first($name) }}</span>
    </div>
  </div>
</div>
</div>
@endif