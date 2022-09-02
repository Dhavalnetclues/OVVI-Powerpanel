<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionTitle']) !!}
            <input type="hidden" name="editing">
            <div class="modal-header mb-2">
                <h5 class="modal-title" id="myLargeModalLabel">Section Title</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                <div class="cm-floating">
                    <label class="control-label col-form-label">Title <span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('title', old('title'), array('class' => 'form-control','id'=>'only_title','autocomplete'=>'off')) !!}
                </div>
                <div class="cm-floating">
                    <label class="control-label col-form-label">Heading Style</label>
                    <select class="form-control" data-choices name="HeadinglayoutType" id="heading_layout_type">
                        <option value="">Select Heading Type</option>
                        <option value="h1">h1</option>
                        <option value="h2">h2</option>
                        <option value="h3">h3</option>
                        <option value="h4">h4</option>
                        <option value="h5">h5</option>
                        <option value="h6">h6</option>
                    </select>
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