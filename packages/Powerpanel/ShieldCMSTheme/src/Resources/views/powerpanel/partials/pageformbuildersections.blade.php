@if(is_array($sections))
   @if($contentavalibale == 'N')
   <section class="builder powercomposer" id="has-content">
            <input type="hidden" name="section" id="builderObj" />
            <div class="portlet light portlet_light menuBody overflow_visible page_builder movable-section">
                <div class="portlet-body">
                    <div class="">
                        <div class=" padding_right_set">
                            <div id="section-container">
                            @endif
                               <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                   
                                    <div class="clearfix"></div>
                                    <div class="section-item form-area" data-editor="{{ 'item-form' }}">
                                        <div class="col-md-12 col-xs-small "><span><i class="fa fa-file-text-o" aria-hidden="true"></i></span> {!! $sections[1] !!}</div>
                                        <input id="{{ 'item-form' }}" data-class="{{ $sections[1] }}" data-id="{{ $sections[0] }}" type="hidden" class="txtip" value="{{ $sections[1] }}"/>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            @if($contentavalibale == 'N')
                            </div>
                        </div>
                        </div>
                            <div class="ui-new-section-add add-element">
                                <a href="javascript:;" class="add-icon add-element">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    </section>
                    @endif
@endif 
