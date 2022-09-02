<div class="modal fade bd-example-modal-lg" id="sectionTeamModuleTemplate" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"> <!-- composer-element-popup ckeditor-popup ckbusiness-popup -->
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionTeamModuleTemplate']) !!}
            <input type="hidden" name="editing">
            <input type="hidden" name="template">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal">
                    <span>×</span>
                </button> -->
                <h5 class="modal-title" id="exampleModalLabel"><b>Add Team</b></h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group cm-floating">
                    <label class="control-label form_title">Caption<span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','autocomplete'=>'off')) !!}
                </div>
                <div class="form-group cm-floating" style="display: none">
                    <label class="control-label form_title">Configurations<span aria-required="true" class="required"> * </span></label>
                    <select name="section_config" class="form-control bootstrap-select bs-select config-class" id="config">
                        <option value="">Configurations</option>
                        <option value="1">Image &amp; Title</option>
                        <option value="2">Image &amp;,Title, Short Description</option>
                        <option value="3">Title, Start Date</option>
                        <option value="4">Image, Title, Start Date</option>
                        <option value="5" selected>Image, Title, Short Description, Start Date</option>
                        <option value="9">Image, Title, Designation, Department, &amp; Short Description</option>
                    </select>
                </div> 
                <div class="form-group cm-floating">
                    <label class="control-label form_title">Extra Class</label>
                    {!! Form::text('extra_class', old('extra_class'), array('maxlength'=>'160','class' => 'form-control extraClass','autocomplete'=>'off')) !!}
                </div>
                 <div class="form-group cm-floating" style="display: none">
                    <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                    <select name="layoutType" class="form-control bootstrap-select bs-select" id="team-template-layout">
                        <option value="">Select Layout</option>      
                        <option class="list" value="list">List</option>
                       <option class="grid" value="grid_2_col">Grid 2 column</option>
                        <option class="grid" value="grid_3_col" selected>Grid 3 column</option>
                        <option class="grid" value="grid_4_col">Grid 4 column</option>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div class="text-left mt-2">
                    <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1 addSection">
                        <div class="flex-shrink-0"><i class="ri-add-line label-icon align-middle fs-20 me-2"></i></div> Add
                    </button>
                    <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label red" data-bs-dismiss="modal">
                        <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Cancel
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>