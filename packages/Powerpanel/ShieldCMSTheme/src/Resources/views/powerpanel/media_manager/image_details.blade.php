<div class="title_section">
    <h2>Image Details</h2>
    <div class="pull-right">
        <a class="btn btn-green-drake" onclick="MediaManager.backToPreTab(1,'<?php echo $imageObj->fk_folder;?>','<?php echo $imageObj->fk_folder;?>');" href="javascript:void(0);" style="padding:4px 12px">Back to Images</a>
    </div>
</div>
<div class="portlet light pp_image_details">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4">
              <div class="thumbnail_container">
                <div class="thumbnail">
                  <img src="{{ \App\Helpers\resize_image::resize($imageObj->id) }}" />
                </div>
              </div>
            </div>
            <div class="col-md-8">
                <div class="image-info">
                  <div class="form-group">
                    <label><b>Name</b></label>: <span>{{ $imageObj->txtImgOriginalName.'.'.$imageObj->varImageExtension }}</span>
                  </div>
                  <div class="form-group">
                    <label><b>Type</b></label>: <span>{{ $mimeType }}</span>
                  </div>
                  <div class="form-group">
                    <label><b>Size</b></label>: <span>{{ $fileSize }}</span>
                  </div>
                  @if(isset($dimension) && !empty($dimension))
                  <div class="form-group">
                    <label><b>Dimensions</b></label>: <span>{{ $dimension }}</span>
                  </div>
                  @endif
                  <div class="form-group">
                    <label><b>Uploaded On</b></label>:
                    <span>{{ date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '',strtotime($imageObj->created_at)) }}</span>
                  </div>
                  <div class="form-group">
                    <label><b>Image URL</b></label>:
                    @php
                    if($imageObj->varfolder == 'folder'){
                        $folderObj = App\Image::getFolderName($imageObj->fk_folder);
                        $path = $CDN_PATH.'/assets/images/'.$folderObj->foldername.'/'.$imageObj->txtImageName.'.'.$imageObj->varImageExtension;
                    }else{
                        $path = $CDN_PATH.'/assets/images/'.$imageObj->txtImageName.'.'.$imageObj->varImageExtension;
                    }
                    @endphp
                    <span><a target="_blank" href="{{ $path }}">{{ $path }}</a></span>
                  </div>
                </div>
                <hr />

                <div class="form-group form-md-line-input">
                  <input class="form-control input-sm" type="text" name="image_title" value="{{ (!empty($imageObj->varTitle)?$imageObj->varTitle:'') }}" placeholder="Enter Image Title" />
                  <label class="form_title" for="name">
                      Title<span aria-required="true" class="required"> * </span>
                  </label>
                </div>
                <div class="form-group form-md-line-input">
                  <input class="form-control input-sm" name="image_alt" type="text" value="{{ (!empty($imageObj->varAltText)?$imageObj->varAltText:'') }}" placeholder="Enter Alt Text" />
                  <label class="form_title" for="name">
                      Alt Text<span aria-required="true" class="required"> * </span>
                  </label>
                </div>
                <div class="form-group form-md-line-input">
                  <textarea class="form-control" name="image_caption" rows="3" placeholder="Enter Image Caption">{{ (!empty($imageObj->txtCaption)?$imageObj->txtCaption:'') }}</textarea>
                  <label class="form_title" for="name">
                      Caption<span aria-required="true" class="required"> * </span>
                  </label>
                </div>                

                <!-- <label><b>Title</b></label>:
                <input class="form-control input-sm" type="text" name="image_title" value="{{ (!empty($imageObj->varTitle)?$imageObj->varTitle:'') }}" placeholder="Enter image title" /> -->
                <!-- <br /><br />
                <label><b>Caption</b></label>:
                <textarea class="form-control" name="image_caption" placeholder="Enter image Caption">{{ (!empty($imageObj->txtCaption)?$imageObj->txtCaption:'') }}</textarea>
                <br /><br />
                <label><b>Alt Text</b></label>:
                <input class="form-control input-sm" name="image_alt" type="text" value="{{ (!empty($imageObj->varAltText)?$imageObj->varAltText:'') }}" placeholder="Enter alt text" />-->
                <a class="btn btn-green-drake" href="javascript:void(0);" id="save_details" style="padding:4px 12px">Save</a> 
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>