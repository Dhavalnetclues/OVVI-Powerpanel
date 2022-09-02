<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionContent" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionContent']) !!}
            <input type="hidden" name="editing">

            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Content</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                <div class="cm-floating">
                    <textarea class="form-control item-data" name="content" id="ck-area" column="40" rows="10"></textarea>
                </div>
                <div class="cm-floating">
                    <label class="control-label col-form-label">Extra Class</label>
                    {!! Form::text('extra_class', old('extra_class'), array('class' => 'form-control','id'=>'extraClass','autocomplete'=>'off')) !!}
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->