<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionMap" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionMap']) !!}
            <input type="hidden" name="editing">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Google Map</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php $imgkey = 1; @endphp

                <div class="mb-3">
                    <div id="map" style="margin-left: 0px; margin-bottom: 10px; width:100%;height:300px;"></div>
                </div>
                <div style="padding-bottom: 20px"></div>
                <div class="mb-3">
                    <label class="control-label col-form-label">Latitude<span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('img_latitude', old('img_latitude'), array('maxlength'=>'500','class' => 'form-control','id'=>'img_latitude','autocomplete'=>'off','readonly'=>'readonly')) !!}
                </div>
                <div class="mb-3">
                    <label class="control-label col-form-label">Longitude<span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('img_longitude', old('img_longitude'), array('maxlength'=>'500','class' => 'form-control','id'=>'img_longitude','autocomplete'=>'off','readonly'=>'readonly')) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light cancel-btn" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="addSection">Add</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>