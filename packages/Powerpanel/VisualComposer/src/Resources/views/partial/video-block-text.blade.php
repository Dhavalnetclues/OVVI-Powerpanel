<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionVideoContent" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmsectionVideoContent']) !!}
            <input type="hidden" name="editing">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Video with Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php $imgkey = 1; @endphp
                <div class="mb-3">
                    <label class="control-label col-form-label">Caption</label>
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
                <div class="mb-3">
                    <textarea class="form-control item-data" name="content" id="ck-area" column="40" rows="10"></textarea>
                </div>
                <div class="mb-3 imagealign">
                    <label class="control-label col-form-label config-title">Image align options<span aria-required="true" class="required"> * </span></label>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="imagealign">
                                <li>
                                    <a href="javascript:;" title="Align Left">
                                        <input type="radio" id="home-left-video" name="selector" value="lft-txt">
                                        <label for="home-left-video"></label>
                                        <div class="check"><div class="inside"></div></div>
                                        <i class="icon"><img src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-video.png' }}" alt=""></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" title="Align Right">
                                        <input type="radio" id="home-right-video" name="selector" value="rt-txt">
                                        <label for="home-right-video"></label>
                                        <div class="check"><div class="inside"></div></div>
                                        <i class="icon"><img src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-video.png' }}" alt=""></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" title="Align Top">
                                        <input type="radio" id="home-top-video" name="selector" value="top-txt">
                                        <label for="home-top-video"></label>
                                        <div class="check"><div class="inside"></div></div>
                                        <i class="icon"><img src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/top-video.png' }}" alt=""></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" title="Align Center">
                                        <input type="radio" id="home-center-video" name="selector" value="center-txt">
                                        <label for="home-center-video"></label>
                                        <div class="check"><div class="inside"></div></div>
                                        <i class="icon"><img src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-video.png' }}" alt=""></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" title="Align Bottom">
                                        <input type="radio" id="bottom-video" name="selector" value="bot-txt">
                                        <label for="bottom-video"></label>
                                        <div class="check"><div class="inside"></div></div>
                                        <i class="icon"><img src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/bottom-video.png' }}" alt=""></i>
                                    </a>
                                </li>

                            </ul>
                        </div>

                    </div>
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