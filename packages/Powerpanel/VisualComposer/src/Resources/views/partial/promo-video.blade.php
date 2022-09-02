<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionVideo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionVideo']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Video</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="control-label col-form-label">Caption <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('title', old('title'), array('maxlength'=>'160','class' => 'form-control','id'=>'videoCaption','autocomplete'=>'off')) !!}
                        </div>
                        @php $unid = uniqid().'builder'; @endphp
                        <div class="mb-3">
                            <label class="col-form-label" for="site_name">Video Source</label>
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input class="md-radiobtn" checked type="radio" value="YouTube" name="chrVideoType" id="{{ $unid.'1' }}"> 
                                    <label for="{{ $unid.'1' }}"> <span></span> <span class="check"></span> <span class="box"></span> YouTube </label>
                                </div>
                                <div class="md-radio">
                                    <input class="md-radiobtn" type="radio" value="Vimeo" name="chrVideoType" id="{{ $unid.'2' }}">
                                    <label for="{{ $unid.'2' }}"> <span></span> <span class="check"></span> <span class="box"></span> Vimeo </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="control-label col-form-label">Video Embed URL<span aria-required="true" class="required"> * </span>(eg. https://www.youtube.com/embed/9MoKICpeBb8)</label>
                            {!! Form::text('video_id', old('video_id'), array('maxlength'=>'160','class' => 'form-control','id'=>'videoId','autocomplete'=>'off')) !!}
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
    </div>
</div>