<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionDecisionModuleTemplate" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionDecisionModuleTemplate']) !!}
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header mb-2">
                        <h5 class="modal-title" id="exampleModalLabel">Decision</h5>
                        <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pb-0">
                        <div class="cm-floating">
                            <label class="control-label col-form-label">Caption <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                        </div>
                         <div class="cm-floating">
                            <label class="control-label col-form-label">Sector</label>
                            <select class="form-control sector-class" data-choices name="sectortype" id="sector">
                                <option value="">Select Sector</option>
                                <option class="ict" value="ict">ICT</option>
                                <option class="energy" value="energy">Energy</option>
                                <option class="fuel" value="fuel">Fuel</option>
                                <option class="water" value="water">Water</option>
                            </select>
                        </div>
                        <div class="cm-floating">
                            <label class="control-label col-form-label">Select Category</label>
                            <select class="form-control cat-class" data-choices name="decisioncat" id="cat-template-layout">
                                <option value="">Select Category</option>
                            </select>
                        </div>
                        <div class="cm-floating"style="display: none" >
                            <label class="control-label col-form-label">Description</label>
                            {!! Form::textarea('section_description', old('section_description'), array('class' => 'form-control','rows'=>'3','id'=>'section_description','autocomplete'=>'off')) !!}
                        </div>
                        <div class="cm-floating" style="display: none">
                            <label class="control-label col-form-label">Configurations<span aria-required="true" class="required"> * </span></label>
                            <select class="form-control config-class" data-choices name="section_config" id="config">
                                <option value="">Configurations</option>
                                <option value="1">Image &amp; Title</option>
                                <option value="2">Image &amp;,Title, Short Description</option>
                                <option value="3">Title, Start Date</option>
                                <option value="4">Image, Title, Start Date</option>
                                <option value="5" selected>Image, Title, Short Description, Start Date</option>
                            </select>
                        </div> 
                        <div class="cm-floating"style="display: none">
                            <label class="control-label col-form-label">Layout<span aria-required="true" class="required"> * </span></label>
                            <select class="form-control layout-class" data-choices name="layoutType" id="news-template-layout">
                                <option value="">Select Layout</option>
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>
                            </select>
                        </div>
                        <div class="cm-floating" style="display: none">
                            <label class="control-label col-form-label">Start Date</label>
                            {!! Form::text('publications_start_date_time', old('publications_start_date_time'), array('maxlength'=>'160','class' => 'form-control','id'=>'publications_start_date_time','autocomplete'=>'off')) !!}
                        </div>
                        <div class="cm-floating" style="display: none">
                            <label class="control-label col-form-label">End Date</label>
                            {!! Form::text('publications_end_date_time', old('publications_end_date_time'), array('maxlength'=>'160','class' => 'form-control','id'=>'publications_end_date_time','autocomplete'=>'off')) !!}
                        </div>
                        <div class="cm-floating">
                            <label class="control-label col-form-label">Limit 
                                <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Note: All the decision will be shown if Limit not entered">
                                    <i class="ri-information-line text-danger fs-16"></i>
                                </span>
                            </label>
                            {!! Form::number('section_limit', old('section_limit'), array('maxlength'=>'2','class' => 'form-control','id'=>'section_limit','autocomplete'=>'off','min'=>'1')) !!}
                        </div>
                        <div class="cm-floating">
                            <label class="control-label col-form-label">Extra Class</label>
                            {!! Form::text('extra_class', old('extra_class'), array('maxlength'=>'160','class' => 'form-control','id'=>'extra_class','autocomplete'=>'off')) !!}
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" id="addSection">
                            <div class="flex-shrink-0">
                                <i class="ri-add-line label-icon align-middle fs-20 me-2"></i>
                            </div> Add
                        </button>
                        <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label cancel-btn" data-bs-dismiss="modal">
                            <div class="flex-shrink-0">
                                <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                            </div> Cancel
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>