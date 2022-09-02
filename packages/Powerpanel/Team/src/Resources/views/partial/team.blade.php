<div class="modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionTeamModule" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionTeamModule']) !!}
            <input type="hidden" name="total_records">
            <input type="hidden" name="found">
            <input type="hidden" name="editing">
            <input type="hidden" name="template">
            <input type="hidden" name="selectedIds">
            <input type="hidden" name="selectedTitles">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>×</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel"><b>Add Team</b></h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label form_title">Caption<span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','autocomplete'=>'off')) !!}
                </div>
                <div class="form-group hide">
                    <label class="control-label form_title">Configurations<span aria-required="true" class="required"> * </span></label>
                    <select name="section_config" class="form-control bootstrap-select bs-select config-class" id="config">
                        <option value="">Configurations</option>
                        <option value="1">Image &amp; Title</option>
                        <option value="2">Image &amp;,Title, Short Description</option>
                        <option value="3">Title, Start Date</option>
                        <option value="4">Image, Title, Start Date</option>
                        <option value="5" selected>Image, Title, Short Description, Start Date</option>
                    </select>
                </div> 
                <div class="form-group">
                    <label class="control-label form_title">Extra Class</label>
                    {!! Form::text('extra_class', old('extra_class'), array('maxlength'=>'160','class' => 'form-control extraClass', 'autocomplete'=>'off')) !!}
                </div>
                <div class="form-group hide">
                    <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                    <select name="layoutType" class="form-control bootstrap-select bs-select" id="team-layout">
                       <option class="grid" value="grid_2_col">Grid 2 column</option>
                        <option class="grid" value="grid_3_col" selected>Grid 3 column</option>
                        <option class="grid" value="grid_4_col">Grid 4 column</option>  
                    </select>
                </div>
                <div class="form-group">
                    <div class="table-container">
                        <div class="row">
                               <div class="col-md-6 col-sm-12 col-md-offset-3">
                                    <div class="form-group search_rh_div search_rh_div_pos">                  
                                        <input type="search" class="form-control form-control-solid" placeholder="Search by title" id="searchfilter">
                                    </div>
                                </div>
                               <div class="col-md-3 col-sm-12">
                                    <select multiple placeholder="Sort by" id="columns" title="Sort By" class="form-control bootstrap-select bs-select">
                                        <optgroup label="Fields" data-max-options="1">
                                            <option value="varTitle" selected>Title</option>
                                        </optgroup>
                                        <optgroup label="Order" data-max-options="1">                    
                                            <option selected value="asc" >Ascending</option>
                                            <option value="desc">Descending</option>
                                        </optgroup>
                                    </select>
                                </div>
                            <div class="col-md-12 col-sm-12">
                                <table class="responsive new_table_desing table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="1%" align="center">
                                                <label class="mt-checkbox mt-checkbox-outline">
                                                    <input type="checkbox" class="group-checkable"/>
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th width="20%" align="left">{{ trans('team::template.common.title') }}</th>
                                            <th width="20%" align="center">Email</th>
                                            <th width="20%" align="center">Designation</th>
                                            <th width="20%" align="center">Update Date</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="table-scrollable full-info-table" id="mcscroll" style="height: 200px">
                                    <table class="responsive new_table_desing table table-striped table-bordered table-hover table-checkable" id="datatable_team_ajax">
                                        <tbody id="record-table"> </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="text-right">
                    <button type="button" class="btn red btn-outline" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-green-drake addSection">Add</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>