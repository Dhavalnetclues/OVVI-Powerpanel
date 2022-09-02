<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionInterconnectionsModule" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionInterconnectionsModule']) !!}
                    <input type="hidden" name="total_records">
                    <input type="hidden" name="found">
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Interconnections</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label form_title">Caption<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Sector</label>
                            <select name="sectortype" class="form-control bootstrap-select bs-select sector-class" id="sector">
                                <option value="">Select Sector</option>      
                                <option class="ict" value="ict">ICT</option>
                                <option class="ofreg" value="ofreg">OFREG</option>
                                <option class="energy" value="energy">Energy</option>
                                <option class="fuel" value="fuel">Fuel</option>
                                <option class="water" value="water">Water</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Select Parents</label>
                            <select name="parentorg" class="form-control bootstrap-select bs-select layout-class" id="interconnections-template-layout">
                                <option value="">Select Parents</option>  
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Extra Class</label>
                            {!! Form::text('extra_class', old('extra_class'), array('maxlength'=>'160','class' => 'form-control','id'=>'extra_class','autocomplete'=>'off')) !!}
                        </div>
                        <div class="clearfix"></div>
                        <div class="text-right">
                            <button type="button" class="btn red btn-outline cancel-btn" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-green-drake" id="addSection">Add</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>