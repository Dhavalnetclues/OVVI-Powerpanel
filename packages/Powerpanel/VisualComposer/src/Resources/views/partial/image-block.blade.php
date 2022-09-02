<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionOnlyImage" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionOnlyImage']) !!}
            <input type="hidden" name="editing">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Image</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                @php $imgkey = 1; @endphp
                <div class="mb-30 img_1" id="img1">
                    <div class="team_box cm-documentbox text-center">
                        <div class="thumbnail_container">
                            <a onclick="MediaManager.open('photo_gallery', 1);" data-selected="1" class=" btn-green-drake media_manager pgbuilder-img image_gallery_change_1" title="" href="javascript:void(0);">
                                <div class="thumbnail photo_gallery_1">
                                    <img src="{!! $CDN_PATH.'assets/images/packages/visualcomposer/plus-no-image.png' !!}">                  
                                </div>
                            </a>
                            <div class="nqimg_mask">
                                <div class="nqimg_inner">
                                    <input class="image_1 item-data imgip" type="hidden" id="photo_gallery1" data-type="image" name="img1" value=""/>
                                    <input class="folder_1" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value=""/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cm-floating">
                    <label class="control-label col-form-label">Caption</label>
                    {!! Form::text('img_title', old('img_title'), array('maxlength'=>'160','class' => 'form-control sectiontitlespellingcheck','id'=>'img_title','autocomplete'=>'off')) !!}
                </div>
                <div class="cm-floating">
                    <label class="control-label col-form-label">Extra Class</label>
                    {!! Form::text('extra_class', old('extra_class'), array('maxlength'=>'160','class' => 'form-control','id'=>'extra_class','autocomplete'=>'off')) !!}
                </div>
                <div class="cm-floating">
                    <label class="control-label col-form-label">Data width (For Image)</label>
                    {!! Form::text('data_width', old('data_width'), array('class' => 'form-control','id'=>'data_width','autocomplete'=>'off')) !!}
                </div>            
                <div class="mb-30" style="display:none">
                    <label class="control-label col-form-label config-title pt-0 mt-0">Image align options<span aria-required="true" class="required"> * </span></label>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="imagealign">
                                <li>
                                    <a href="javascript:;" title="Align Left">
                                        <input type="radio" id="image-left-image" name="selector" value="image-lft-txt" checked>
                                        <label for="image-left-image"></label>
                                        <div class="check"><div class="inside"></div></div>
                                        <i class="icon"><img src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-left.png' }}" alt=""></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" title="Align Right">
                                        <input type="radio" id="image-right-image" name="selector" value="image-rt-txt">
                                        <label for="image-right-image"></label>
                                        <div class="check"><div class="inside"></div></div>
                                        <i class="icon"><img src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-right.png' }}" alt=""></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" title="Align Center">
                                        <input type="radio" id="image-center-image" name="selector" value="image-center-txt">
                                        <label for="image-center-image"></label>
                                        <div class="check"><div class="inside"></div></div>
                                        <i class="icon"><img src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-center.png' }}" alt=""></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-start">                
                <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label cancel-btn me-1" id="addSection">
                    <div class="flex-shrink-0"><i class="ri-add-line label-icon align-middle fs-20 me-2"></i></div> Add
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label cancel-btn cancel-btn" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Cancel
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>