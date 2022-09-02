<div class="ac-modal modal fade bd-example-modal-lg" id="sectionBoardofDirectorsTemplate" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"> <!-- composer-element-popup ckeditor-popup ckbusiness-popup -->    
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionBoardofDirectorsTemplate']) !!}
            <input type="hidden" name="editing">
            <input type="hidden" name="template">
            <div class="modal-header mb-2">
                <h5 class="modal-title" id="exampleModalLabel">Board of Directors</h5>
                {{-- <button type="button" class="close" data-dismiss="modal"><span>×</span></button> --}}
                <button type="button" class="btn-close close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="cm-floating">
                    <label class="control-label form_title">Caption <span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                </div>
                
                <div class="cm-floating" style="display: none">
                    <label class="control-label form_title">Description</label>
                    {!! Form::textarea('section_description', old('section_description'), array('class' => 'form-control','rows'=>'3','id'=>'section_description','autocomplete'=>'off')) !!}
                </div>
                <div class="cm-floating" style="display: none">
                    <label class="control-label form_title">Configurations<span aria-required="true" class="required"> * </span></label>
                    <select name="section_config" class="form-select" id="config"> <!-- form-control bootstrap-select bs-select config-class -->
                        <option value="1" selected>Configurations</option>
                        <option value="1">Image &amp; Title</option>
                        <option value="2">Image &amp;,Title, Short Description</option>
                        <option value="3">Title, Start Date</option>
                        <option value="4">Image, Title, Start Date</option>
                        <option value="5">Image, Title, Short Description, Start Date</option>
                        <option value="9">Image, Title, Designation, Department, &amp; Short Description</option>
                    </select>
                </div> 
                <div class="cm-floating" style="display: none">
                    <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                    <select name="layoutType" class="form-select" id="news-template-layout"> <!-- form-control bootstrap-select bs-select layout-class -->
                        <option value="list" selected>Select Layout</option>      
                        <option class="list" value="list">List</option>
                        <option class="grid" value="grid_2_col">Grid 2 column</option>
                        <option class="grid" value="grid_3_col">Grid 3 column</option>
                        <option class="grid" value="grid_4_col">Grid 4 column</option>
                    </select>
                </div>
                
                 <div class="cm-floating">
                    <label class="control-label form_title">Limit 
                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Note: All the Board of Directors will be shown if Limit not entered">
                            <i class="ri-information-line text-danger fs-16"></i>
                        </span>
                    </label>
                    {!! Form::number('section_limit', old('section_limit'), array('maxlength'=>'2','class' => 'form-control','id'=>'section_limit','autocomplete'=>'off','min'=>'1')) !!}
                </div>
                 <div class="cm-floating">
                    <label class="control-label form_title">Extra Class</label>
                    {!! Form::text('extra_class', old('extra_class'), array('maxlength'=>'160','class' => 'form-control','id'=>'extra_class','autocomplete'=>'off')) !!}
                </div>
                <div class="clearfix"></div>
                <div class="footer-btn">                    
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
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>