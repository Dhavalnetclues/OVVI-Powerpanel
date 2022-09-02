<div class="title_section">
  <h2>Crop Image</h2>
  <div class="pull-right">
    <a class="btn btn-green-drake" onclick="MediaManager.backToPreTab(1,'<?php echo $imageObj->fk_folder;?>');" href="javascript:void(0);">Back to Images</a>
  </div>
</div>
<div class="portlet light">
  <div class="row">
    <div class="col-md-9">
      <div class="img-container">
        <div class="thumbnail_container">
          <div class="thumbnail">
            <img id="image" src="{{ $imageURL }}" data-extension="{{ $imageObj->varImageExtension }}" alt="">
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="docs-data">
        <h3 class="docs-data-title"><strong>Original Size</strong></h3>
        <div class="input-group form-group">
          <div class="input-group-prepend">
            <label class="input-group-text" for="dataWidth">Width</label>
            <span class="input-group-append">
              <span class="input-group-text">(px)</span>
            </span>
          </div>
          <input type="text" class="form-control input-sm" id="dataWidth" placeholder="width" disabled="disabled">

        </div>
        <div class="input-group form-group">
          <div class="input-group-prepend">
            <label class="input-group-text" for="dataHeight">Height</label>
            <span class="input-group-append">
              <span class="input-group-text">(px)</span>
            </span>
          </div>
          <input type="text" class="form-control input-sm" id="dataHeight" placeholder="height" disabled="disabled">

        </div>
        <div class="input-group form-group">
          <div class="input-group-prepend">
            <label class="input-group-text" for="dataRotate">Rotate</label>
            <span class="input-group-append">
              <span class="input-group-text">(Degree)</span>
            </span>
          </div>
          <input type="text" class="form-control input-sm" id="dataRotate" placeholder="rotate" disabled="disabled">
        </div>
      </div>
      <h3></h3>
      <div class="docs-buttons">
        <div class="btn-group btn-group-crop">
          <button type="button" class="btn btn-success" data-method="getCroppedCanvas"
            data-option="{ &quot;maxWidth&quot;: 4096, &quot;maxHeight&quot;: 4096 }">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Save Image">
              Save Image
            </span>
          </button>
        </div>
        @if(!empty($recommadeImageSizeArr))
          <h3><strong>Recommended Size</strong></h3>
          @foreach($recommadeImageSizeArr as $key => $value)
            @php
              $size = explode('*',$value);
              $height =   $size[0];
              $width =   $size[1];
            @endphp
            <div class="btn-group btn-group-crop">
              <button type="button" class="btn btn-success" data-method="getCroppedCanvas"
                data-option="{ &quot;width&quot;: {{ $width }}, &quot;height&quot;: {{$height}} }">
                <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false">
                  {{ $height }}&times;{{ $width }}
                </span>
              </button>
            </div>
          @endforeach
        @endif          

        <!-- <div class="btn-group btn-group-crop">
          <button type="button" class="btn btn-success" data-method="getCroppedCanvas"
            data-option="{ &quot;width&quot;: 320, &quot;height&quot;: 180 }">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false">
              320&times;180
            </span>
          </button>
        </div> -->

      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-9">
      <div class="docs-buttons">
        <h3></h3>
        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="move" title="Move">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Move">
              <span class="ri-drag-move-2-line fs-16"></span>
            </span>
          </button>
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Zoom In">
              <span class="ri-zoom-in-line fs-16"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Zoom Out">
              <span class="ri-zoom-out-line fs-16"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0"
            title="Move Left">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Move Left">
              <span class="ri-arrow-left-line fs-16"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0"
            title="Move Right">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Move Right">
              <span class="ri-arrow-right-line fs-16"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10"
            title="Move Up">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Move Up">
              <span class="ri-arrow-up-line fs-16"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10"
            title="Move Down">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Move Down">
              <span class="ri-arrow-down-line fs-16"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Rotate Left">
              <span class="ri-restart-line ic-rotate fs-16"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Rotate Right">
              <span class="ri-restart-line fs-16"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Flip Horizontal">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Flip Horizontal">
              <span class="ri-arrow-left-right-line fs-16"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Flip Vertical">
              <span class="ri-arrow-up-down-line fs-16"></span>
            </span>
          </button>
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="crop" title="Crop On">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Crop On">
              <span class="ri-check-line fs-16"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="clear" title="Crop Off">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Crop Off">
              <span class="ri-time-line fs-16"></span>
            </span>
          </button>
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="reset" title="Reset">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Reset">
              <span class="ri-refresh-line fs-16"></span>
            </span>
          </button>
        </div>
      </div>
      <div class="docs-toggles">
        <div class="btn-group d-flex flex-nowrap" data-toggle="buttons">
          <label class="btn btn-primary active">
            <input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Square">
              1:1 (Square)
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="0.5">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Vertical">
              1:2 (Vertical)
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="2">
            <span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Horizontal">
              2:1 (Horizontal)
            </span>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="getCroppedCanvasTitle">Cropped Image</h5>        
        <button type="button" class="btn-close fs-11" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <div class="thumbnail_container text-center bg-light">
          <div class="thumbnail cropped_image"></div>
        </div>
      </div>
      <div class="modal-footer justify-content-start">
        <a class="btn btn-primary bg-gradient waves-effect waves-light me-1" data-id="{{ $imageObj->id }}" data-folderid="{{ $imageObj->fk_folder }}" id="save_as_new" href="javascript:void(0);">Save as new</a>
        <a class="btn btn-secondary bg-gradient waves-effect waves-light me-1" data-id="{{ $imageObj->id }}" data-folderid="{{ $imageObj->fk_folder }}"  id="save_and_overwrite" href="javascript:void(0);">Save and Overwrite</a>
        <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>