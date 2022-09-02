<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionButton" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionButton']) !!}
            <input type="hidden" name="editing">
            <div class="modal-header mb-2">
                <h5 class="modal-title" id="myLargeModalLabel">Add Button</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                <div class="cm-floating">
                    <label class="control-label col-form-label">Title<span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                </div>
                <div class="cm-floating section_button_target">
                    <label class="control-label col-form-label">Link Target<span aria-required="true" class="required"> * </span></label>
                    <select class="form-control" name="section_button_target" id="section_button_target" data-choices>
                        <option value="">Select Link Target</option>
                        <option value="_self">Same Window</option>
                        <option value="_blank">New Window</option>
                    </select>
                </div>
                <div class="cm-floating">
                    <label class="control-label col-form-label">Link<span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('section_link', old('section_link'), array('maxlength'=>'255','class' => 'form-control','id'=>'section_link','autocomplete'=>'off')) !!}
                </div>

                <div class="cm-floating">
                    <label class="control-label col-form-label">Extra Class</label>
                    {!! Form::text('extra_class_only_btn', old('extra_class_only_btn'), array('maxlength'=>'160','class' => 'form-control','id'=>'extra_class_only_btn','autocomplete'=>'off')) !!}
                </div>

                <div class="mb-30">
                    <label class="control-label col-form-label config-title pt-0 mt-0">Button align options <span aria-required="true" class="required"> * </span></label>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="imagealign">
                                <li>
                                    <a href="javascript:void(0);" title="Align Left">
                                        <input type="radio" class="form-check-input" id="button-left-image" name="selector" value="button-lft-txt">
                                        <label for="button-left-image"></label>
                                        <div class="check"><div class="inside"></div></div>
                                        <i class="icon"><img src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-button.png' }}" alt=""></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" title="Align Right">
                                        <input type="radio" class="form-check-input" id="button-right-image" name="selector" value="button-rt-txt">
                                        <label for="button-right-image"></label>
                                        <div class="check"><div class="inside"></div></div>
                                        <i class="icon"><img src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-button.png' }}" alt=""></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" title="Align Center">
                                        <input type="radio" class="form-check-input" id="button-center-image" name="selector" value="button-center-txt">
                                        <label for="button-center-image"></label>
                                        <div class="check"><div class="inside"></div></div>
                                        <i class="icon"><img src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-button.png' }}" alt=""></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-start">                
                <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" id="addSection">
                    <div class="flex-shrink-0"><i class="ri-add-line label-icon align-middle fs-20 me-2"></i></div> Add
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label cancel-btn" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Cancel
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>