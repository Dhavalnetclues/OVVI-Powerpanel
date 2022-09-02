<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionPublicationModule" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionPublicationModule']) !!}
            <input type="hidden" name="total_records">
            <input type="hidden" name="found">
            <input type="hidden" name="editing">
            <input type="hidden" name="template">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Publication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="control-label col-form-label">Caption<span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                </div>
                <div class="mb-3">
                    <label class="control-label col-form-label">Description</label>
                    {!! Form::textarea('section_description', old('section_description'), array('class' => 'form-control','rows'=>'3','id'=>'section_description','autocomplete'=>'off')) !!}
                </div>
                <div class="mb-3" style="display: none">
                    <label class="control-label col-form-label">Configurations<span aria-required="true" class="required"> * </span></label>
                    <select class="form-control" data-choices name="section_config" id="config">
                        <option value="">Configurations</option>
                        <option value="1">Image &amp; Title</option>
                        <option value="2">Image &amp;,Title, Short Description</option>
                        <option value="3">Title, Start Date</option>
                        <option value="4">Image, Title, Start Date</option>
                        <option value="5" selected>Image, Title, Short Description, Start Date</option>
                    </select>
                </div>
                <div class="mb-3 " style="display: none">
                    <label class="control-label col-form-label">Layout<span aria-required="true" class="required"> * </span></label>
                    <select class="form-control" data-choices name="layoutType" id="publication-layout">
                        <option value="">Select Layout</option>
                        <option class="list" value="list">List</option>
                        <option class="grid" value="grid_2_col">Grid 2 column</option>
                        <option class="grid" value="grid_3_col">Grid 3 column</option>
                        <option class="grid" value="grid_4_col">Grid 4 column</option>
                    </select>
                </div>
                    <div class="mb-3">
                    <label class="control-label col-form-label">Extra Class</label>
                    {!! Form::text('extra_class', old('extra_class'), array('maxlength'=>'160','class' => 'form-control','id'=>'extra_class','autocomplete'=>'off')) !!}
                </div>
                <div class="mb-3">
                    <div class="table-container">
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <div class="mb-3"> 
                                    <select class="form-control" data-choices name="layoutType" placeholder="Category" title="Category" id="publicationcategory-id">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="mb-3 search_rh_div search_rh_div_pos">
                                    <input type="search" class="form-control form-control-solid" placeholder="{{ trans('visualcomposer::template.common.search') }}" id="searchfilter">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="mb-3"> 
                                    <select class="form-control" id="columns" data-choices data-choices-groups data-placeholder="Sort by" title="Sort By">
                                        <optgroup label="Fields" data-max-options="1">
                                            <option value="varTitle" selected>Title</option>
                                            <option value="dtDateTime">Start Date</option>
                                            <option value="dtEndDateTime">End Date</option>                             
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
                                                <th width="20%" align="center">Start Date</th>
                                            <th width="20%" align="center">End Date</th>
                                            <th width="20%" align="center">Updated Date</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="table-scrollable full-info-table" id="mcscroll" style="height: 200px">
                                    <table class="responsive new_table_desing table table-striped table-bordered table-hover table-checkable" id="datatable_publication_ajax">

                                        <tbody id="record-table"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
