<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionServiceModule" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionServiceModule']) !!}
                    <input type="hidden" name="total_records">
                    <input type="hidden" name="found">
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Service</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label form_title">Caption<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Description</label>
                            {!! Form::textarea('section_description', old('section_description'), array('class' => 'form-control','rows'=>'3','id'=>'section_description','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Configurations<span aria-required="true" class="required"> * </span></label>
                            <select name="section_config" class="form-control bootstrap-select bs-select config-class" id="config">
                                <option value="">Configurations</option>
                                <option value="1">Service Code, Title</option>
                                <option value="2">Service Code, Title, Application Fee</option>
                                <option value="3">Service Code, Title, Application Fee</option>
                                <option value="4">Service Code, Title, Application Fee,Note,NoteLink </option>
                                <option value="5" selected>Service Code, Title, Application Fee,Note,NoteLink </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                            <select name="layoutType" class="form-control bootstrap-select bs-select layout-class" id="Service-layout">
                                <option value="">Select Layout</option>
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>                  
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Extra Class</label>
                            {!! Form::text('extra_class', old('extra_class'), array('maxlength'=>'160','class' => 'form-control','id'=>'extra_class','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <div class="table-container">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group"> 
                                            <select id="servicecategory-id" placeholder="Category" title="Category" class="form-control bootstrap-select bs-select cat-class">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group search_rh_div search_rh_div_pos">                  
                                            <input type="search" class="form-control form-control-solid" placeholder="{{ trans('visualcomposer::template.common.search') }}" id="searchfilter">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group"> 
                                            <select multiple placeholder="Sort by" id="columns" title="Sort By" class="form-control bootstrap-select bs-select sort-class">
                                                <optgroup label="Fields" data-max-options="1">
                                                    <option value="varTitle" selected>Title</option>
                                                                              
                                                </optgroup>
                                                <optgroup label="Order" data-max-options="1">                    
                                                    <option selected value="asc" {{-- data-icon="fa-sort-amount-asc" --}}>Ascending</option>
                                                    <option value="desc" {{-- data-icon="fa-sort-amount-desc" --}}>Descending</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 ">
                                        <table class="responsive new_table_desing table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr role="row" class="heading">
                                                    <th width="1%" align="center">
                                                        <label class="mt-checkbox mt-checkbox-outline">
                                                            <input type="checkbox" class="group-checkable"/>
                                                            <span></span>
                                                        </label>
                                                    </th>
                                                    <th width="20%" align="left">{{ trans('visualcomposer::template.common.title') }}</th>
                                                    <th width="20%" align="left">{{ trans('visualcomposer::template.common.category') }}</th>
                                                    <th width="20%" align="center">Service Code</th>
                                                    <th width="20%" align="center">Application Fee CI$</th>
                                                    <th width="20%" align="center">Updated Date</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div class="table-scrollable full-info-table" id="mcscroll" style="height: 200px">
                                            <table class="responsive new_table_desing table table-striped table-bordered table-hover table-checkable" id="datatable_service_ajax">

                                                <tbody id="record-table"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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