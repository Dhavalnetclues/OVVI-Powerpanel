@if($type == "multiple")
<div class="row">
  <div class="col-md-12">
    <div class="image_thumb">
      <div class="form-group {{ $errors->has($name) ? ' has-error' : '' }} ">
        <label class="form_title" for="front_logo">{!! $label !!}</label>
        <div class="clearfix"></div>
        <div class="fileinput fileinput-new" data-provides="fileinput">
          <div class="fileinput-preview thumbnail" data-trigger="fileinput"
            style="width:100%; height:120px;position: relative;">
            <img class="img_opacity" src="{{ $CDN_PATH.'resources\images\upload_file.gif' }}" />
          </div>
          <div class="input-group {{ $id }}">
            <a class="media_manager multiple-selection" data-multiple="true"
              onclick="MediaManager.open('{{ $id }}');"><span class="fileinput-new"></span></a>
            <input class="form-control" type="hidden" id="{{ $id }}" name="{{ $name }}"
              value="{{ isset($data->fkIntImgId)?$data->fkIntImgId:old($name) }}" />
            <input class="form-control" type="hidden" id="image_url" name="image_url"
              value="{{ Request::old('image_url') }}" />
          </div>
        </div>
        <div class="clearfix"></div>
        @if(!empty($data->fkIntImgId) && isset($data->fkIntImgId))
        @php $imageArr = explode(',',$data->fkIntImgId) @endphp
        <div id="{{ $id }}_img">
          <div  class="multi_image_list">
          <ul id="image_sortable" style="cursor: move;">
              @foreach($imageArr as $key => $value)
              <li id="{{ $value }}">
                <span>
                  <img src="{!! App\Helpers\resize_image::resize($value,109,100) !!}" alt="Img" />
                  <a href="javascript:;" onclick="MediaManager.removeImageFromGallery('{{ $value }}');"
                    class="delect_image" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                </span>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
        @else
        <div id="{{ $id }}_img"></div>
        @endif
        @php
        $height = isset($settings->height)?$settings->height:500;
        $width = isset($settings->width)?$settings->width:500;
        @endphp
        <span>{{ trans('template.common.imageSize',['height'=>$height, 'width'=>$width]) }}</span>
      </div>
    </div>
  </div>
</div>

@elseif($type == "single")

<div class="form-group {{ $errors->has($name) ? ' has-error' : '' }} imguploader">
  <div class="image_thumb">
    <label class="form_title" for="front_logo">{!! $label !!}</label>
    <div class="fileinput fileinput-new" data-provides="fileinput">
      <div class="fileinput-preview thumbnail {{ $id }}_img" data-trigger="fileinput" style="width:100%; height:120px;position: relative;">
        @if(Request::old('image_url'))
          <img src="{{ Request::old('image_url') }}" />
        @elseif (isset($data->fkIntImgId) && $data->fkIntImgId != '')
          <img src="{!! App\Helpers\resize_image::resize($data->fkIntImgId) !!}" />
        @else
          <img src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
        @endif
      </div>
      <div class="input-group {{ $id }}">
        <a class="media_manager multiple-selection" data-multiple="false" onclick="MediaManager.open('{{ $id }}')"><span class="fileinput-new"></a>
        <input class="form-control" type="hidden" id="{{ $id }}" name="{{ $name }}" value="{{ (isset($data->fkIntImgId) && !empty($data->fkIntImgId)?$data->fkIntImgId:'') }}" />
        <input class="form-control" type="hidden" id="image_url" name="image_url" value="{{ Request::old('image_url') }}" />
      </div>
      <div class="overflow_layer" style="display:none">
        <a onclick="MediaManager.open('{{ $id }}');" class="media_manager remove_img"><i class="ri-pencil-line"></i></a>
        <a href="javascript:;" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"> <i class="ri-delete-bin-line"></i> </a>
      </div>    
    </div>
    <div class="clearfix"></div>
    @php 
         $height = isset($settings->height)?$settings->height:$height; 
         $width = isset($settings->width)?$settings->width:$width; 
    @endphp
    <span>{{ trans('template.common.imageSize',['height'=> $height, 'width'=>$width]) }}</span>
    @if(isset($errors))
    <span class="help-block">
      {{ $errors->first($name) }}
    </span>
    @endif
  </div>
</div>
@endif