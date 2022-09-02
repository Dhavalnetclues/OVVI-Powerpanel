<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionContactInfo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionContactInfo']) !!}
            <input type="hidden" name="editing">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Contact Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="control-label col-form-label">Address <span aria-required="true" class="required"> * </span></label>
                    {!! Form::textarea('section_address', old('section_address'), array('class' => 'form-control','rows'=>'3','id'=>'section_address','autocomplete'=>'off')) !!}
                </div>
                <div class="mb-3">
                    <label class="control-label col-form-label">Email <span aria-required="true" class="required"> * </span></label>
                    {!! Form::email('section_email', old('section_email'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_email','autocomplete'=>'off')) !!}
                </div>
                <div class="mb-3">
                    <label class="control-label col-form-label">Phone # <span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('section_phone', old('section_phone'), array('maxlength'=>'20','class' => 'form-control','id'=>'section_phone','onkeypress'=>"javascript: return KeycheckOnlyPhonenumber(event);",'onpaste'=>'return false','autocomplete'=>'off')) !!}
                </div>
                <div class="mb-3">
                    <label class="control-label col-form-label">Other Information</label>
                    <textarea name="title" class="form-control item-data" id="ck-area" column="40" rows="1"></textarea>
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