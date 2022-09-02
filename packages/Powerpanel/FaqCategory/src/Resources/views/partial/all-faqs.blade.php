<style>
    .xdsoft_datetimepicker.xdsoft_noselect.xdsoft_{
        z-index: 100522;
    }
</style>
<div class="ac-modal modal fade bd-example-modal-lg" id="sectionFaqsModuleTemplate" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"> <!-- composer-element-popup ckeditor-popup ckbusiness-popup -->
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionFaqsModuleTemplate']) !!}
            <input type="hidden" name="editing">
            <input type="hidden" name="template">
            <div class="modal-header mb-2">
                <h5 class="modal-title" id="exampleModalLabel">Faqs</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="cm-floating">
                    <label class="control-label form_title">Caption <span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                </div>
                
                <div class="cm-floating">
                    <label class="control-label form_title">Select Category</label>
                    <select name="faqcat" class="form-select" id="faq-template-layout"> <!-- form-control bootstrap-select bs-select layout-class -->
                        <option value="">Select Category</option> 
                    </select>
                </div>
                <div class="cm-floating">
                    <label class="control-label form_title">Start Date</label>
                    {!! Form::text('faq_start_date_time', old('faq_start_date_time'), array('maxlength'=>'160','class' => 'form-control','id'=>'faq_start_date_time','autocomplete'=>'off')) !!}
                </div>
                <div class="cm-floating">
                    <label class="control-label form_title">End Date</label>
                    {!! Form::text('faq_end_date_time', old('faq_end_date_time'), array('maxlength'=>'160','class' => 'form-control','id'=>'faq_end_date_time','autocomplete'=>'off')) !!}
                </div>
                <div class="cm-floating">
                    <label class="control-label form_title">Limit 
                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Note: All the faqs will be shown if Limit not entered">
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