@php $id  = 'item-'.$ikey.'-row-'.($vkey + 1).'-col-'.($ckey + 1).'-element-'.($ekey + 1); @endphp
@if($eval->type == "only_title")
    <div class="cms-element" data-id="{{ $id }}">
        <a href="javascript:;" title="Delete" data-id="{{ $id }}" class="delete-element">
            <i class="action-icon delete ri-delete-bin-line"></i>
        </a>
        <a href="javascript:;" title="Edit" data-id="{{ $id }}" class="only-title">
            <i class="action-icon edit ri-pencil-line"></i>
        </a>
        <div class="clearfix"></div>
        <div class="ui-new-section-add titleOnly" data-editor="{{ $id }}">
            <div class="col-md-12">{{ $eval->val->content  }}</div>
            <input id="{{ $id }}" data-class="{{ $eval->val->extclass  }}" 
                                    data-headingtype="{{ isset($eval->val->headingtype) ? $eval->val->headingtype : ""  }}" type="hidden" class="txtip" value="{{ $eval->val->content }}"/>
            <div class="clearfix"></div>
        </div>
    </div>
@elseif($eval->type == "textarea")
    <div class="cms-element" data-id="{{ $id }}">
        <a href="javascript:;" title="Delete" data-id="{{ $id }}" class="delete-element">
            <i class="action-icon delete ri-delete-bin-line"></i>
        </a>
        <a href="javascript:;" title="Edit" data-id="{{ $id }}" class="text-block">
            <i class="action-icon edit ri-pencil-line"></i>
        </a>
        <div class="clearfix"></div>
        <div class="ui-new-section-add text-area" data-editor="{{ $id }}">
            <div class="col-md-12">{!! $eval->val->content  !!}</div>
            <input id="{{ $id }}" data-class="{{ $eval->val->extclass  }}" type="hidden" class="txtip" value="{{ $eval->val->content  }}"/>
            <div class="clearfix"></div>
        </div>
    </div>
@elseif($eval->type == "accordianblock")
    <div class="cms-element" data-id="{{ $id }}">
        <a href="javascript:;" title="Delete" data-id="{{ $id }}" class="delete-element">
            <i class="action-icon delete ri-delete-bin-line"></i>
        </a>
        <a href="javascript:;" title="Edit" data-id="{{ $id }}" class="accordian-block">
            <i class="action-icon edit ri-pencil-line"></i>
        </a>
        <div class="clearfix"></div>
        <div class="ui-new-section-add text-accordian" data-editor="{{ $id }}">
            <div class="col-md-12"><b>Title:</b>{!! $eval->val->title  !!}</div>
            <div class="col-md-12"><b>Content:</b>{!! $eval->val->content  !!}</div>
            <input id="{{ $id }}" data-title="{{ $eval->val->title  }}" type="hidden" class="txtip" value="{{ $eval->val->content  }}"/>
            <div class="clearfix"></div>
        </div>
    </div>
@elseif($eval->type == "image")
    <div class="cms-element" data-id="{{ $id }}">
        <a href="javascript:;" title="Delete" data-id="{{ $id }}" class="delete-element">
            <i class="action-icon delete ri-delete-bin-line"></i>
        </a>
        <a href="javascript:;" title="Edit" data-id="{{ $id }}" class="only-image">
            <i class="action-icon edit ri-pencil-line"></i>
        </a>
        <div class="clearfix"></div>
        <div class="ui-new-section-add img-area" data-editor="{{ $id }}">
            <div class="team_box">
                <div class="thumbnail_container">
                    <div class="thumbnail">
                        <img src="{{ $eval->val->src }}">
                        <input id="{{ $id }}" data-caption="{{ $eval->val->title }}" data-extra_class="{{ $eval->val->extra_class }}" data-type="{{ $eval->val->alignment }}" data-width="{{ isset($eval->val->data_width)?$eval->val->data_width:'' }}" type="hidden" class="imgip" value="{{ $eval->val->image }}"/>
                        @if (method_exists($MyLibrary, 'GetFolderID')) 
                            @if(isset($eval->val->image))
                                @php $folderid = App\Helpers\MyLibrary::GetFolderID($eval->val->image); @endphp
                                @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                @endif
                            @endif
                        @endif	
                    </div>
                </div>
            </div>
            <div class="title-img">
                <p>{{  $eval->val->title }}</p>
            </div>
            @if ($eval->val->extra_class != null || $eval->val->extra_class != '')
                <div class="extraClass-img">
                    <h5>Extra Class: {{ $eval->val->extra_class }}</h5>
                </div>																			
            @endif
        </div>
        <div class="clearfix"></div>
    </div>
@elseif($eval->type == "document")
@php
    $docsAray = explode(',', $eval->val->document);   
    $docObj   = App\Document::getDocDataByIds($docsAray);
@endphp
    <div class="cms-element" data-id="{{ $id }}">
        <a href="javascript:;" title="Delete" data-id="{{ $id }}" class="delete-element">
            <i class="action-icon delete ri-delete-bin-line"></i>
        </a>
        <a href="javascript:;" title="Edit" data-id="{{ $id }}"   class="only-document">
            <i class="action-icon edit ri-pencil-line"></i>
        </a>
        <div class="clearfix"></div>
        <div class="ui-new-section-add img-document" data-editor="{{ $id }}">
            <div class="docdatahtml">
                @if(count($docObj) > 0)
                    <div class="builder_doc_list">
                        <ul class="grid_dochtml">																																																
                                @foreach($docObj as $value)
                                    <li id="doc_{{ $value->id }}">
                                            <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                    <img  src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/document_icon.png' }}" alt="Img" />
                                            </span>
                                            <span class="editdoctitle">{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}</span>
                                    </li>
                                @endforeach
                        </ul>
                    </div>
                    <input type="hidden" id="dochiddenid" name="img1" value="{{ $eval->val->document }}">
                @endif
            </div>
            <input id="{{ $id }}" type="hidden" class="imgip" data-caption="{{ (isset($eval->val->caption) && !empty($eval->val->caption)) ? $eval->val->caption : ''}}" data-doc_date_time="{{(isset($eval->val->doc_date_time) && !empty($eval->val->doc_date_time)) ? $eval->val->doc_date_time : ''}}" value="{{ $eval->val->document }}"/>
        </div>
        <div class="clearfix"></div>
    </div>
@elseif($eval->type == "button_info")
    <div class="cms-element" data-id="{{ $id }}">
        <a href="javascript:;" title="Delete" data-id="{{ $id }}" class="delete-element">
        <i class="action-icon delete ri-delete-bin-line"></i>
        </a>
        <a href="javascript:;" title="Edit" data-id="{{ $id }}" class="section-button">
        <i class="action-icon edit ri-pencil-line"></i>
        </a>
        <div class="clearfix"></div>
        <div class="ui-new-section-add buttonInfoOnly" data-editor="{{ $id }}">
        <div class="col-md-12">
            <b>Button Information</b></div><br/><br/>
            <div class="col-md-10">
                <b>Title:</b>{{ $eval->val->title }}<br/>
                <b>Link:</b>{{ $eval->val->content }}<br/>
                @if ($eval->val->target == "_blank") 
                    <b>Target:</b>New Window<br/>
                @else
                    <b>Link Target:</b>Same Window<br/>
                @endif
        </div>
        <div class="col-md-2 text-right">
            <div class="image-align-box">
                <h5 class="title">Preview</h5>
                @if ($eval->val->alignment == 'button-lft-txt')
                    <i class="icon"><img height="45" title="Align Left" width="50" src="{{ $CDN_PATH .'assets/images/packages/visualcomposer/left-button.png' }}" alt=""></i>
                @elseif ($eval->val->alignment == 'button-rt-txt') 
                    <i class="icon"><img height="45" title="Align Right" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-button.png' }}" alt=""></i>
                @elseif ($eval->val->alignment == 'button-center-txt')
                    <i class="icon"><img height="45" title="Align Top" width="50" src="{{ $CDN_PATH. 'assets/images/packages/visualcomposer/center-button.png' }}" alt=""></i>
                @endif
            </div>
        </div>
        <div class="clearfix"></div>
        <input id="{{ $id }}" 
                data-caption="{{ $eval->val->title }}" data-linktarget="{{ $eval->val->target }}" 
                data-extclass="{{ $eval->val->extclass }}"  data-type="{{ $eval->val->alignment }}"  type="hidden"  class="txtip" 
                value="{{ $eval->val->content }}"/>
        <div class="clearfix"></div>
        </div>
    </div>
@endif