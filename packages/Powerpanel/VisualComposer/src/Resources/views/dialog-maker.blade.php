<!-- Modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="pgBuiderSections" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmPageComponantData']) !!}
            <input type="hidden" name="sectionid" id="sectionid" />
            <input type="hidden" name="editing" id="editing" />
            <div class="modal-header">
                <h5 class="modal-title fs-20" id="exampleModalLabel">Add Elements</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="visualComposer-modal">
                <ul class="nav nav-tabs nav-justified nav-border-bottom nav-border-top-primary mb-4" role="tablist">
                    @foreach($visualData as $key => $data)
                        @php 
                            $active = $data['varTitle']=='All'?'active':'';
                            $expand = $key=='0'?'false':'true';
                            $userAccess = true;
                            if(isset($data['varModuleName']) && !empty($data['varModuleName'])){
                                $userAccess = Auth::user()->can($data['varModuleName'].'-list');
                            } else if( $data['varTitle'] == 'Templates' || $data['varTitle'] == 'Forms' ){
                                if($data['varTitle'] == 'Templates') {
                                  $userAccess = Config::get('Constant.DEFAULT_PAGETEMPLATE') == 'Y';
                                } else {
                                  $userAccess = Config::get('Constant.DEFAULT_FORMBUILDER') == 'Y';
                                }
                            }
                          $classname =  str_replace(" ","",strtolower($data['varTitle']));
                        @endphp
                        @if($userAccess)
                            <li data-moduleid="{{$data['varModuleID']}}" class="nav-item" role="presentation" data-tabing="{{$classname}}" id="{{$classname}}_tab">
                                <a href="#{{$classname}}" title="{{$data['varTitle']}}" class="nav-link {{$classname}}_tab {{$data['varClass']}} {{$active}}" data-bs-toggle="tab" aria-controls="{{strtolower($data['varTitle'])}}" role="{{strtolower($data['varTitle'])}}" aria-expanded="{{$expand}}">
                                    <span class="tab_text">{{$data['varTitle']}}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <div class="tab-content text-muted">
                    @foreach($visualData as $key => $data)
                        @php
                            $active = $data['varTitle']=='All'?'active':'';
                            $userAccess = true;
                            if(isset($data['varModuleName']) && !empty($data['varModuleName'])){
                              $userAccess = Auth::user()->can($data['varModuleName'].'-list');
                            }
                            $classname =  str_replace(" ","",strtolower($data['varTitle']));
                        @endphp
                        @if($userAccess)
                            <div class="tab-pane mcscroll {{$active}}" role="tabpanel" id="{{$classname}}">
                                <ul class="visualComposer-menu">
                                    @foreach($data['child'] as $index => $childData)
                                        @php
                                            $userChildAccess = true;
                                            if(isset($childData['varModuleName']) && !empty($childData['varModuleName'])){
                                                $userChildAccess = Auth::user()->can($childData['varModuleName'].'-list');
                                            }
                                        @endphp
                                        @if($userChildAccess)
                                            @if($data['varTitle'] == 'Templates' || $data['varTitle'] == 'Forms' ) 
                                                <li class="{{$childData['varClass']}}" >
                                                    @if($data['varTitle'] == 'Forms')
                                                      @if(!empty($data['child'])) 
                                                      <a title="{{$childData['varTitle']}}" onclick="GetSetFormBuilderData({{ $childData['id'] }})" href="javascript:;">
                                                      @endif
                                                    @else
                                                      <a title="{{$childData['varTitle']}}" onclick="GetSetTemplateData({{ $childData['id'] }})" href="javascript:;">
                                                    @endif
                                                        <span><i class="{{$childData['varIcon']}}" aria-hidden="true"></i></span>{{$childData['varTitle']}}
                                                    </a>
                                                </li>
                                            @else 
                                                <li class="{{$childData['varClass']}}">
                                                    <a title="{{ $childData['varTitle'] }}" data-filter="{{ (!empty($childData['varFilter'])?$childData['varFilter']:$childData['varClass']) }}"  href="javascript:;">
                                                        <span><i class="{{$childData['varIcon']}}" aria-hidden="true"></i></span>{{$childData['varTitle']}}
                                                    </a>
                                                </li>
                                            @endif
                                        @endif    
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                    @endforeach
                </div>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



@foreach($visualComposerTemplate as $key => $data)
    @if (View::exists($data))    		
        @include($data)
    @endif
@endforeach

<div class="modal fade" tabindex="-1" id="columnModalClass" aria-labelledby="modalForm" aria-hidden="true">
</div>

<div class="modal fade" tabindex="-1" id="customSection" aria-labelledby="modalForm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Custom Card</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {!! Form::open(['method' => 'post','id'=>'frmCustomSection']) !!}
            <input type="hidden" name="sectionid" id="sectionid" />
            <input type="hidden" name="editing" id="editing" />
            <div class="modal-body form_pattern">
                <div class="mb-3 imguploader">
                    <label class="form_title" for="front_logo">Image</label>
                    <div class="image_thumb">
                      <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div class="fileinput-preview thumbnail custom_section_image_img" data-trigger="fileinput" style="width:100%; height:120px;position: relative;">
                            <img class="img_opacity" src="{!! $CDN_PATH.'assets/global/img/plus-no-image.png' !!}" />
                          </div>
                          <div class="input-group">
                            <a class="media_manager" onclick="MediaManager.open('custom_section_image');"><span class="fileinput-new"></span></a>
                            <input class="form-control" type="hidden" id="image_url" name="image_url" value="{{ old('image_url') }}" />
                              <input class="form-control imgip" type="hidden" id="custom_section_image" name="img_id" value="" />
                          </div>
                      </div>
                      <div class="clearfix"></div>
                      <span style="color:#e73d4a;margin:0;display:inline;">{{ $errors->first('img_id') }}</span>
                    </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Title</label>
                  {!! Form::text('title', old('title'), array('maxlength'=>'160','class' => 'form-control','id'=>'title','autocomplete'=>'off')) !!}
                </div>
                <div class="mb-3">
                  <label class="form-label">Link</label>
                  {!! Form::text('link', old('link'), array('maxlength'=>'160','class' => 'form-control','id'=>'link','autocomplete'=>'off')) !!}
                </div>
                <div class="mb-3">
                  <label class="form-label">Short Description</label>
                  <textarea class="form-control item-data" name="content" id="ck-area" column="40" rows="10"></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-start">                  
                <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" id="addSection">
                    <div class="flex-shrink-0"><i class="ri-add-line label-icon align-middle fs-20 me-2"></i></div> Add
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Cancel
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="addColumns" aria-labelledby="modalForm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header mb-2">
                <h5 class="modal-title" id="myModalLabel">Add Column(s)</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {!! Form::open(['method' => 'post','id'=>'frmAddColumns']) !!}
            <input type="hidden" name="sectionid" id="sectionid" />
            <input type="hidden" name="editing" id="editing" />
            <div class="modal-body pb-0 form_pattern">
                <div class="cm-floating">
                    <label class="form-label">Select number of Columns<span aria-required="true" class="required"> * </span></label>
                    <select name="no_of_column" class="form-control" id="no-of-column" data-choices data-choices-search-false>
                      @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                      @endfor
                    </select>
                </div>
                <div class="cm-floating">
                    <label class="form-label">Column(s) Class<span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('column_class', old('column_class'), array('class' => 'form-control','id'=>'column-class','autocomplete'=>'off')) !!}
                </div>
                <div class="cm-floating">
                    <label class="form-label">Animation Effect</label>
                    <select name="animation" class="form-select" id="animation-style" data-choices>
                        <option value="">Select Animation</option>
                        <option value="fade">fade</option>
                        <option value="fade-up">fade-up</option>
                        <option value="fade-down">fade-down</option>
                        <option value="fade-left">fade-left</option>
                        <option value="fade-right">fade-right</option>
                        <option value="fade-up-right">fade-up-right</option>
                        <option value="fade">fade-up-left</option>
                        <option value="fade-up-left">fade-down-right</option>
                        <option value="fade-down-left">fade-down-left</option>
                        <option value="flip-up">flip-up</option>
                        <option value="flip-down">flip-down</option>
                        <option value="flip-left">flip-left</option>
                        <option value="flip-right">flip-right</option>
                        <option value="slide-up">slide-up</option>
                        <option value="slide-down">slide-down</option>
                        <option value="slide-left">slide-left</option>
                        <option value="slide-right">slide-right</option>
                        <option value="zoom-in">zoom-in</option>
                        <option value="zoom-in-up">zoom-in-up</option>
                        <option value="zoom-in-down">zoom-in-down</option>
                        <option value="zoom-in-left">zoom-in-left</option>
                        <option value="zoom-in-right">zoom-in-right</option>
                        <option value="zoom-out">zoom-out</option>
                        <option value="zoom-out-up">zoom-out-up</option>
                        <option value="zoom-out-down">zoom-out-down</option>
                        <option value="zoom-out-left">zoom-out-left</option>
                        <option value="zoom-out-right">zoom-out-right</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer justify-content-start">
                <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" id="addSection">
                    <div class="flex-shrink-0"><i class="ri-add-line label-icon align-middle fs-20 me-2"></i></div> Add
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Cancel
                </button>
            </div>
            {!! Form::close() !!}
        </div>
      </div>
</div>

<div class="modal fade" tabindex="-1" id="editRow" aria-labelledby="modalForm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header mb-2">
                <h5 class="modal-title" id="myModalLabel">Edit Template Property</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {!! Form::open(['method' => 'post','id'=>'frmEditRow']) !!}
            <input type="hidden" name="sectionid" id="sectionid" />
            <input type="hidden" name="editing" id="editing" />
            <div class="modal-body pb-0 form_pattern">
                <div class="cm-floating">
                    <label class="form-label">Class<span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('row_class', old('row_class'), array('class' => 'form-control','id'=>'row-class','autocomplete'=>'off')) !!}
                </div>
                <div class="cm-floating">
                    <label class="form-label">Animation Effect</label>
                    <select name="animation" class="form-select" id="animation-style" data-choices>
                        <option value="">Select Animation</option>
                        <option value="fade">fade</option>
                        <option value="fade-up">fade-up</option>
                        <option value="fade-down">fade-down</option>
                        <option value="fade-left">fade-left</option>
                        <option value="fade-right">fade-right</option>
                        <option value="fade-up-right">fade-up-right</option>
                        <option value="fade">fade-up-left</option>
                        <option value="fade-up-left">fade-down-right</option>
                        <option value="fade-down-left">fade-down-left</option>
                        <option value="flip-up">flip-up</option>
                        <option value="flip-down">flip-down</option>
                        <option value="flip-left">flip-left</option>
                        <option value="flip-right">flip-right</option>
                        <option value="slide-up">slide-up</option>
                        <option value="slide-down">slide-down</option>
                        <option value="slide-left">slide-left</option>
                        <option value="slide-right">slide-right</option>
                        <option value="zoom-in">zoom-in</option>
                        <option value="zoom-in-up">zoom-in-up</option>
                        <option value="zoom-in-down">zoom-in-down</option>
                        <option value="zoom-in-left">zoom-in-left</option>
                        <option value="zoom-in-right">zoom-in-right</option>
                        <option value="zoom-out">zoom-out</option>
                        <option value="zoom-out-up">zoom-out-up</option>
                        <option value="zoom-out-down">zoom-out-down</option>
                        <option value="zoom-out-left">zoom-out-left</option>
                        <option value="zoom-out-right">zoom-out-right</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer justify-content-start">
                <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" id="addSection">
                    <div class="flex-shrink-0"><i class="ri-pencil-line label-icon align-middle fs-20 me-2"></i></div> Update
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Cancel
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="editColRow" aria-labelledby="modalForm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header mb-2">
                <h5 class="modal-title" id="myModalLabel">Update Column Row</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {!! Form::open(['method' => 'post','id'=>'frmEditColRow']) !!}
            <input type="hidden" name="sectionid" id="sectionid" />
            <input type="hidden" name="editing" id="editing" />
            <div class="modal-body pb-0">
                <div class="cm-floating">
                    <label class="form-label">Class<span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('col_row_class', old('col_row_class'), array('class' => 'form-control','id'=>'col-row-class','autocomplete'=>'off')) !!}
                </div>
                <div class="mb-30">
                  <label class="form-label mb-0"><input name="column_row_width" type="checkbox" id="column_row_width" value="{{ old('column_row_width') }}"> Full Column(s) Width</label>
                </div>
                <div class="cm-floating">
                    <label class="form-label">Animation Effect</label>
                    <select name="animation" class="form-select" id="animation-style" data-choices>
                        <option value="">Select Animation</option>
                        <option value="fade">fade</option>
                        <option value="fade-up">fade-up</option>
                        <option value="fade-down">fade-down</option>
                        <option value="fade-left">fade-left</option>
                        <option value="fade-right">fade-right</option>
                        <option value="fade-up-right">fade-up-right</option>
                        <option value="fade">fade-up-left</option>
                        <option value="fade-up-left">fade-down-right</option>
                        <option value="fade-down-left">fade-down-left</option>
                        <option value="flip-up">flip-up</option>
                        <option value="flip-down">flip-down</option>
                        <option value="flip-left">flip-left</option>
                        <option value="flip-right">flip-right</option>
                        <option value="slide-up">slide-up</option>
                        <option value="slide-down">slide-down</option>
                        <option value="slide-left">slide-left</option>
                        <option value="slide-right">slide-right</option>
                        <option value="zoom-in">zoom-in</option>
                        <option value="zoom-in-up">zoom-in-up</option>
                        <option value="zoom-in-down">zoom-in-down</option>
                        <option value="zoom-in-left">zoom-in-left</option>
                        <option value="zoom-in-right">zoom-in-right</option>
                        <option value="zoom-out">zoom-out</option>
                        <option value="zoom-out-up">zoom-out-up</option>
                        <option value="zoom-out-down">zoom-out-down</option>
                        <option value="zoom-out-left">zoom-out-left</option>
                        <option value="zoom-out-right">zoom-out-right</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer justify-content-start">                
                <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" id="addSection">
                    <div class="flex-shrink-0"><i class="ri-pencil-line label-icon align-middle fs-20 me-2"></i></div> Update
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Cancel
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- <script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/jquery.min.js' }}" type="text/javascript"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false&amp;libraries=places&key=AIzaSyDMdWyeX2VR9DZVhXh46mOJQveRHpavLWI"></script>