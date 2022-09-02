<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionAccordian" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionAccordian']) !!}
            <input type="hidden" name="editing">
            <div class="modal-header mb-2">
                <h5 class="modal-title" id="exampleModalLabel">Content</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                <div class="cm-floating form-group">
                    <label class="control-label col-form-label">Title <span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('title', old('title'), array('class' => 'form-control','id'=>'only_title','autocomplete'=>'off')) !!}
                </div>
                <div class="cm-floating form-group">
                    <textarea class="form-control item-data" name="content" id="ck-area" column="40" rows="10"></textarea>
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