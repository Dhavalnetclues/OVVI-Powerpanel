<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionSpacerTemplate" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionSpacerTemplate']) !!}
            <input type="hidden" name="editing">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Spacer Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="control-label col-form-label">Spacer Class<span aria-required="true" class="required"> * </span></label>
                    <select class="form-control" data-choices name="section_spacer" id="spacerid">
                        <option value="">Spacer Class</option>
                        <option value="9">ac-pt-xs-0</option>
                        <option value="10">ac-pt-xs-5</option>
                        <option value="11">ac-pt-xs-10</option>
                        <option value="12">ac-pt-xs-15</option>
                        <option value="13">ac-pt-xs-20</option>
                        <option value="14">ac-pt-xs-25</option>
                        <option value="15">ac-pt-xs-30</option>
                        <option value="16">ac-pt-xs-40</option>
                        <option value="17">ac-pt-xs-50</option>
                    </select>
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