<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionEventsModule" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionEventsModule']) !!}
                    <input type="hidden" name="total_records">
                    <input type="hidden" name="found">
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Events</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label form_title">Caption<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
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
                                            <select id="category-id" placeholder="Category" title="Category" class="form-control bootstrap-select bs-select cat-class">

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
                                                    <option value="varTitle" >Title</option>
                                                    <option value="dtDateTime" selected>Start Date</option>
                                                    <option value="dtEndDateTime">End Date</option>                            
                                                </optgroup>
                                                <optgroup label="Order" data-max-options="1">                    
                                                    <option  value="asc" {{-- data-icon="fa-sort-amount-asc" --}}>Ascending</option>
                                                    <option selected value="desc" {{-- data-icon="fa-sort-amount-desc" --}}>Descending</option>
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
                                                    <th width="20%" align="center">Start Date</th>
                                                    <th width="20%" align="center">End Date</th>
                                                    <th width="20%" align="center">Updated Date</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div class="table-scrollable full-info-table" id="mcscroll" style="height: 200px">
                                            <table class="responsive new_table_desing table table-striped table-bordered table-hover table-checkable" id="datatable_events_ajax">

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