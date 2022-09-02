{{-- @php $banners = App\Banner::getListForBuilder(); @endphp --}}
@php
$sectionConfig=[
'1'=>'Image & Title',
'2'=>'Image, Title & Short Description',
'3'=>'Title, Start Date',
'4'=>'Image, Title, Start Date',
'5'=>'Image, Title, Short Description, Start Date',
'6'=>'Title & Short Description',
'8'=>'Title, Short Description, Start Date',
'9'=>'ac-pt-xs-0',
'10'=>'ac-pt-xs-5',
'11'=>'ac-pt-xs-10',
'12'=>'ac-pt-xs-15',
'13'=>'ac-pt-xs-20',
'14'=>'ac-pt-xs-25',
'15'=>'ac-pt-xs-30',
'16'=>'ac-pt-xs-40',
'17'=>'ac-pt-xs-50'
];

@endphp

<input type="hidden" id="preview-id" name="previewId"/>
<input type="hidden" id="random-id" name="randomId" value="{{ uniqid() }}" />
<div class="row">
    <div class="col-md-12" id="builder-control">
    @if(is_array($sections) && !empty($sections))
        <section class="builder powercomposer" id="has-content" >
            <input type="hidden" name="section" id="builderObj" />
            <div class="portlet light portlet_light menuBody overflow_visible page_builder movable-section">
                <div class="portlet-body">
                    <div class="">
                        <div class=" padding_right_set">
                            <div id="section-container" class="builder-append-data">
                                @if(is_array($sections))
                                @php $ikey = 1;
                                $divstart = 1;
                                 $divend = 1;
                                 $fixTwocolcounter = 2;
                                 $fixThreecolcounter = 3;
                                 $fixFourcolcounter = 4;
                                $class = 'col-sm-6';
                                $rowclass = '';
                                 @endphp
                                 
                                @foreach($sections as $key => $section) 

                                @if($section->type == 'textarea')            
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="only-content hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="section-item text-area" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                        <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip" value="{{ $section->val->content }}"/>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                 @elseif($section->type == 'partitondata')  
                                 @if($section->type == 'partitondata')
                                  @if(isset($section->partitionclass) && $section->partitionclass == 'TwoColumns')
                                  @php  $class = 'col-sm-6';
                                  $rowclass = 'TwoColumns';
                                  @endphp
                                  @elseif(isset($section->partitionclass) && $section->partitionclass == 'ThreeColumns')
                                   @php  $class = 'col-sm-4';
                                   $rowclass = 'ThreeColumns';
                                   @endphp
                                  @elseif(isset($section->partitionclass) && $section->partitionclass == 'FourColumns')
                                   @php  $class = 'col-sm-3'; 
                                   $rowclass = 'FourColumns';
                                   @endphp
                                  @endif
                                  @endif
                                  @if($divstart == 1)         
                                <div class="ui-state-default">
                                    <div class="row {{ $rowclass }}"><div class='col-sm-12'>
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete fa fa-trash"></i>
                                        </a>
                                    <div class="ui-new-section-add col-sm-12">
                                @endif
                                @if($section->subtype == 'TwoColumns_1')
                                <div class="{{ $class }} col_1">
                                        
                                        @if($section->gentype == 'onlytitle')
                                        @php $col_value = 'only-title';@endphp
                                        @elseif($section->gentype == 'contactinfodata')
                                        @php $col_value = 'contact-info';@endphp
                                        @elseif($section->gentype == 'buttondata')
                                        @php $col_value = 'section-button';@endphp
                                        @elseif($section->gentype == 'onlyvideo')
                                        @php $col_value = 'only-video';@endphp
                                        @elseif($section->gentype == 'textarea')
                                        @php $col_value = 'only-content';@endphp
                                        @elseif($section->gentype == 'twotextarea')
                                        @php $col_value = 'two-part-content';@endphp
                                        @elseif($section->gentype == 'onlyimage')
                                        @php $col_value = 'only-image';@endphp
                                        @elseif($section->gentype == 'mapdata')
                                        @php $col_value = 'google-map';@endphp
                                        @elseif($section->gentype == 'onlydocument')
                                        @php $col_value = 'only-document';@endphp
                                        @elseif($section->gentype == 'imgcontent')
                                        @php $col_value = 'image-with-information';@endphp
                                        @elseif($section->gentype == 'videocontent')
                                        @php $col_value = 'video-with-information';@endphp
                                        @elseif($section->gentype == 'homeimgcontent')
                                        @php $col_value = 'home-information';@endphp
                                        @elseif($section->gentype == 'formdata')
                                        @php $col_value = 'only-content';@endphp
                                        @endif
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="columnstwo columns_1 {{ $col_value }}">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module module section-item {{ $rowclass }} maintwocol two_col_1" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12 col-xs-small "></div>
                                           <div class="add-element" data-innerplus="two_col_1">
                                            <div class="twocol1">
                                                @if($section->gentype == 'onlytitle')
                                                <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}" type="hidden" class="txtip colvalue onlytitleclass" value="{{ $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'textarea')
                                                <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip colvalue textareaclass" value="{{ $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'onlyimage')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->alignment }}" type="hidden" class="imgip imageclass" value="{{ $section->val->image }}"/>
                                                                 @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(isset($section->val->title))
                                                    <div class="title-img">
                                                        <h3>{{ $section->val->title }}</h3>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'image-lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-left.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'image-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-right.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'image-center-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-center.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'imgcontent')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-id="{{ $section->val->image }}" data-type="{{ $section->val->alignment }}" data-caption="{{ $section->val->title }}" type="hidden" class="imgip imgcontentclass" value="{{ $section->val->content }}"/>
                                                                  @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="title-img">
                                                        <h3>{!! $section->val->title !!}</h3>
                                                        {!! $section->val->content !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-image.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/top-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'bot-txt')
                                                        <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/bottom-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'center-txt')
                                                        <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-image.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'videocontent')
                                                @php 
                                                $vidIco = $section->val->videoType == 'Vimeo' ? 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' : 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="fa fa-youtube" aria-hidden="true"></i></a>'; 
                                                @endphp
                                                <div class="col-md-9 col-xs-small">
                                                    {!! $vidIco !!} <strong>{!! $section->val->title !!}</strong>
                                                    <input id="{{ 'item-'.$ikey }}" data-caption="{!! $section->val->title !!}" data-id="{!! $section->val->vidId !!}" data-type="{{ $section->val->videoType }}" data-aligntype="{{ $section->val->alignment }}" type="hidden" class="vidip videocontentclass" value="{{ $section->val->content }}"/>
                                                    <div class="title-img">
                                                        {!! $section->val->content !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-video.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/top-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'bot-txt')
                                                        <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/bottom-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'center-txt')
                                                        <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-video.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'homeimgcontent')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{  App\Helpers\resize_image::resize($section->val->image) }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-id="{{ $section->val->image }}" data-type="{{ $section->val->alignment }}" data-caption="{{ $section->val->title }}" type="hidden" class="imgip homeimagecontclass" value="{{ $section->val->content }}"/>
                                                                 @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="title-img">
                                                        <h3>{!! $section->val->title !!}</h3>
                                                        {!! $section->val->content !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'home-lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-left-image.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'home-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-right-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'home-top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-top-image.png' }}" alt=""></i>

                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'formdata')
                                                <div class="col-md-12 col-xs-small "><span><i class="fa fa-file-text-o" aria-hidden="true"></i></span> {!! $section->val->content !!}</div>
                                                <input id="{{ 'item-form' }}"  data-id="{{ $section->val->id }}" type="hidden" class="txtip formclass" value="{{ $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'twotextarea')
                                                <div class="col-md-12">
                                                    <div class="col-md-6"><strong> Left Side Content </strong>{!! $section->val->leftcontent !!}</div>
                                                    <div class="col-md-6"><strong> Right Side Content </strong>{!! $section->val->rightcontent !!}</div>
                                                </div>
                                                <input id="{{ 'item-'.$ikey }}" data-content="{{ $section->val->rightcontent }}" type="hidden" class="txtip twotextareaclass" value="{{ $section->val->leftcontent }}"/>
                                                @endif
                                                @if($section->gentype == 'mapdata')
                                                <div class="col-md-12 col-xs-small">
                                                    <div class="team_box">
                                                        <input id="{{ 'item-'.$ikey }}" data-latitude="{{ $section->val->latitude }}"  data-longitude="{{ $section->val->longitude }}" type="hidden" class="imgip mapclass"/>
                                                    </div>
                                                    @if(isset($section->val->latitude))
                                                    <div class="title-img">
                                                        <h3>Latitude: {{ $section->val->latitude }}</h3>
                                                    </div>
                                                    @endif
                                                    <br/>
                                                    @if(isset($section->val->longitude))
                                                    <div class="title-img">
                                                        <h3>Longitude: {{ $section->val->longitude }}</h3>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif
                                                @if($section->gentype == 'onlydocument')
                                                @if(isset($section->val->document) && $section->val->document != '')
                                                @php
                                                $docsAray = explode(',', $section->val->document);
                                                $docObj   = App\Document::getDocDataByIds($docsAray);
                                                @endphp
                                                <div class="col-md-12">
                                                    <div class="builder_doc_list">
                                                        <ul>
                                                            @if(count($docObj) > 0)
                                                            @foreach($docObj as $value)
                                                            <li id="doc_{{ $value->id }}">
                                                                <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                                    <img  src="{{ $CDN_PATH.'assets/images/document_icon.png' }}" alt="Img" />
                                                                </span>
                                                                <span class="editdoctitle">{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}</span>
                                                            </li>

                                                            @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                                <input id="{{ 'item-'.$ikey }}" type="hidden" class="imgip documentclass" data-type="document" value="{{ $section->val->document }}"/>
                                                    <input type="hidden" id="dochiddenid" name='img1' value="{{ $section->val->document }}">
                                                     @php
                                                                    if(isset($section->val->document)){
                                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($section->val->document);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    }
                                                                    @endphp
                                                </div>@endif
                                                @endif
                                                @if($section->gentype == 'onlyvideo')
                                                @php 
                                                $vidIco = $section->val->videoType == 'Vimeo' ? 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' : 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="fa fa-youtube" aria-hidden="true"></i></a>'; 
                                                @endphp
                                                <div class="col-md-12"><strong>{{ $section->val->title }}</strong> - {!! $vidIco !!}</div>
                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->videoType }}" type="hidden" class="vidip videoclass" value="{{ $section->val->vidId }}"/>
                                                @endif
                                                @if($section->gentype == 'contactinfodata')
                                                <div class="col-md-12"><b>Contact Information</b></div><br/><br/>
                                                <div class="col-md-12"><b>Address:</b>{!! $section->val->section_address !!}</div>
                                                <div class="col-md-12"><b>Email:</b>{!! $section->val->section_email !!}</div>
                                                <div class="col-md-12"><b>Phone #:</b>{!! $section->val->section_phone !!}</div>
                                                <div class="col-md-12"><b>Other Info:</b>{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}" data-address="{{ $section->val->section_address }}" data-email="{{ $section->val->section_email }}" data-phone="{{ $section->val->section_phone }}" type="hidden" class="txtip contactinfoclass" value="{{  $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'buttondata')
                                                <div class="col-md-12"><b>Button Information</b></div><br/><br/>
                                                <div class="col-md-10"><b>Title:</b>{!! $section->val->title !!}</br>
                                                    <b>Link:</b>{!! $section->val->content !!}</br>
                                                    @if($section->val->target=="_blank")
                                                    <b>Target:</b>New Window</br>
                                                    @else
                                                    <b>Link Target:</b>Same Window</br>
                                                    @endif
                                                </div>
                                                <div class="col-md-2 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'button-lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-button.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'button-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-button.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'button-center-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-button.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-linktarget="{{ $section->val->target }}" data-type="{{ $section->val->alignment }}" type="hidden" class="txtip buttonclass" value="{{  $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'spacerdata')
                                                <div class="col-md-12"><label><b>Spacer Class - </b>{{ $sectionConfig[$section->val->config] }}</label></div>
                                                <input id="{{ 'item-'.$ikey }}"  data-config="{{ $section->val->config }}"   type="hidden" class="txtip spacerclass"/>
                                                @endif

                                            </div>
                                            </div>
                                        </div>
                                </div>
                                @endif

                                @if($section->subtype == 'TwoColumns_2')
                                   
                                <div class="{{ $class }} col_1">
                                        
                                        @if($section->gentype == 'onlytitle')
                                        @php $col_value = 'only-title';@endphp
                                        @elseif($section->gentype == 'contactinfodata')
                                        @php $col_value = 'contact-info';@endphp
                                        @elseif($section->gentype == 'buttondata')
                                        @php $col_value = 'section-button';@endphp
                                        @elseif($section->gentype == 'onlyvideo')
                                        @php $col_value = 'only-video';@endphp
                                        @elseif($section->gentype == 'textarea')
                                        @php $col_value = 'only-content';@endphp
                                        @elseif($section->gentype == 'twotextarea')
                                        @php $col_value = 'two-part-content';@endphp
                                        @elseif($section->gentype == 'onlyimage')
                                        @php $col_value = 'only-image';@endphp
                                        @elseif($section->gentype == 'mapdata')
                                        @php $col_value = 'google-map';@endphp
                                        @elseif($section->gentype == 'onlydocument')
                                        @php $col_value = 'only-document';@endphp
                                        @elseif($section->gentype == 'imgcontent')
                                        @php $col_value = 'image-with-information';@endphp
                                        @elseif($section->gentype == 'videocontent')
                                        @php $col_value = 'video-with-information';@endphp
                                        @elseif($section->gentype == 'homeimgcontent')
                                        @php $col_value = 'home-information';@endphp
                                        @elseif($section->gentype == 'formdata')
                                        @php $col_value = 'only-content';@endphp
                                        @endif
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="columnstwo columns_2 {{ $col_value }}">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module module section-item {{ $rowclass }} maintwocol two_col_2" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12 col-xs-small "></div>
                                           <div class="add-element" data-innerplus="two_col_2">
                                            <div class="twocol1">
                                                @if($section->gentype == 'onlytitle')
                                                <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip colvalue onlytitleclass" value="{{ $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'textarea')
                                                <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}" type="hidden" class="txtip colvalue textareaclass" value="{{ $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'onlyimage')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->alignment }}" type="hidden" class="imgip imageclass" value="{{ $section->val->image }}"/>
                                                                 @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(isset($section->val->title))
                                                    <div class="title-img">
                                                        <h3>{{ $section->val->title }}</h3>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'image-lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-left.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'image-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-right.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'image-center-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-center.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'imgcontent')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-id="{{ $section->val->image }}" data-type="{{ $section->val->alignment }}" data-caption="{{ $section->val->title }}" type="hidden" class="imgip imgcontentclass" value="{{ $section->val->content }}"/>
                                                                 @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="title-img">
                                                        <h3>{!! $section->val->title !!}</h3>
                                                        {!! $section->val->content !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-image.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/top-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'bot-txt')
                                                        <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/bottom-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'center-txt')
                                                        <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-image.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'videocontent')
                                                @php 
                                                $vidIco = $section->val->videoType == 'Vimeo' ? 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' : 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="fa fa-youtube" aria-hidden="true"></i></a>'; 
                                                @endphp
                                                <div class="col-md-9 col-xs-small">
                                                    {!! $vidIco !!} <strong>{!! $section->val->title !!}</strong>
                                                    <input id="{{ 'item-'.$ikey }}" data-caption="{!! $section->val->title !!}" data-id="{!! $section->val->vidId !!}" data-type="{{ $section->val->videoType }}" data-aligntype="{{ $section->val->alignment }}" type="hidden" class="vidip videocontentclass" value="{{ $section->val->content }}"/>
                                                    <div class="title-img">
                                                        {!! $section->val->content !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-video.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/top-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'bot-txt')
                                                        <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/bottom-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'center-txt')
                                                        <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-video.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'homeimgcontent')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{  App\Helpers\resize_image::resize($section->val->image) }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-id="{{ $section->val->image }}" data-type="{{ $section->val->alignment }}" data-caption="{{ $section->val->title }}" type="hidden" class="imgip homeimagecontclass" value="{{ $section->val->content }}"/>
                                                                 @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="title-img">
                                                        <h3>{!! $section->val->title !!}</h3>
                                                        {!! $section->val->content !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'home-lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-left-image.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'home-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-right-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'home-top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-top-image.png' }}" alt=""></i>

                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'formdata')
                                                <div class="col-md-12 col-xs-small "><span><i class="fa fa-file-text-o" aria-hidden="true"></i></span> {!! $section->val->content !!}</div>
                                                <input id="{{ 'item-form' }}"  data-id="{{ $section->val->id }}" type="hidden" class="txtip formclass" value="{{ $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'twotextarea')
                                                <div class="col-md-12">
                                                    <div class="col-md-6"><strong> Left Side Content </strong>{!! $section->val->leftcontent !!}</div>
                                                    <div class="col-md-6"><strong> Right Side Content </strong>{!! $section->val->rightcontent !!}</div>
                                                </div>
                                                <input id="{{ 'item-'.$ikey }}" data-content="{{ $section->val->rightcontent }}" type="hidden" class="txtip twotextareaclass" value="{{ $section->val->leftcontent }}"/>
                                                @endif
                                                @if($section->gentype == 'mapdata')
                                                <div class="col-md-12 col-xs-small">
                                                    <div class="team_box">
                                                        <input id="{{ 'item-'.$ikey }}" data-latitude="{{ $section->val->latitude }}"  data-longitude="{{ $section->val->longitude }}" type="hidden" class="imgip mapclass"/>
                                                    </div>
                                                    @if(isset($section->val->latitude))
                                                    <div class="title-img">
                                                        <h3>Latitude: {{ $section->val->latitude }}</h3>
                                                    </div>
                                                    @endif
                                                    <br/>
                                                    @if(isset($section->val->longitude))
                                                    <div class="title-img">
                                                        <h3>Longitude: {{ $section->val->longitude }}</h3>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif
                                                @if($section->gentype == 'onlydocument')
                                                @if(isset($section->val->document) && $section->val->document != '')
                                                @php
                                                $docsAray = explode(',', $section->val->document);
                                                $docObj   = App\Document::getDocDataByIds($docsAray);
                                                @endphp
                                                <div class="col-md-12">
                                                    <div class="builder_doc_list">
                                                        <ul>
                                                            @if(count($docObj) > 0)
                                                            @foreach($docObj as $value)
                                                            <li id="doc_{{ $value->id }}">
                                                                <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                                    <img  src="{{ $CDN_PATH.'assets/images/document_icon.png' }}" alt="Img" />
                                                                </span>
                                                                <span class="editdoctitle">{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}</span>
                                                            </li>

                                                            @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                                <input id="{{ 'item-'.$ikey }}" type="hidden" class="imgip documentclass" data-type="document" value="{{ $section->val->document }}"/>
                                                    <input type="hidden" id="dochiddenid" name='img1' value="{{ $section->val->document }}">
                                                     @php
                                                                    if(isset($section->val->document)){
                                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($section->val->document);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    }
                                                                    @endphp
                                                </div>@endif
                                                @endif
                                                @if($section->gentype == 'onlyvideo')
                                                @php 
                                                $vidIco = $section->val->videoType == 'Vimeo' ? 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' : 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="fa fa-youtube" aria-hidden="true"></i></a>'; 
                                                @endphp
                                                <div class="col-md-12"><strong>{{ $section->val->title }}</strong> - {!! $vidIco !!}</div>
                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->videoType }}" type="hidden" class="vidip videoclass" value="{{ $section->val->vidId }}"/>
                                                @endif
                                                @if($section->gentype == 'contactinfodata')
                                                <div class="col-md-12"><b>Contact Information</b></div><br/><br/>
                                                <div class="col-md-12"><b>Address:</b>{!! $section->val->section_address !!}</div>
                                                <div class="col-md-12"><b>Email:</b>{!! $section->val->section_email !!}</div>
                                                <div class="col-md-12"><b>Phone #:</b>{!! $section->val->section_phone !!}</div>
                                                <div class="col-md-12"><b>Other Info:</b>{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}" data-address="{{ $section->val->section_address }}" data-email="{{ $section->val->section_email }}" data-phone="{{ $section->val->section_phone }}" type="hidden" class="txtip contactinfoclass" value="{{  $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'buttondata')
                                                <div class="col-md-12"><b>Button Information</b></div><br/><br/>
                                                <div class="col-md-10"><b>Title:</b>{!! $section->val->title !!}</br>
                                                    <b>Link:</b>{!! $section->val->content !!}</br>
                                                    @if($section->val->target=="_blank")
                                                    <b>Target:</b>New Window</br>
                                                    @else
                                                    <b>Link Target:</b>Same Window</br>
                                                    @endif
                                                </div>
                                                <div class="col-md-2 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'button-lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-button.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'button-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-button.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'button-center-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-button.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-linktarget="{{ $section->val->target }}" data-type="{{ $section->val->alignment }}" type="hidden" class="txtip buttonclass" value="{{  $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'spacerdata')
                                                <div class="col-md-12"><label><b>Spacer Class - </b>{{ $sectionConfig[$section->val->config] }}</label></div>
                                                <input id="{{ 'item-'.$ikey }}"  data-config="{{ $section->val->config }}"   type="hidden" class="txtip spacerclass"/>
                                                @endif

                                            </div>
                                        </div>
                                 </div>
                                </div>

                                @endif
                                @if(isset($section->partitionclass) && $section->partitionclass == 'TwoColumns') 
                                
                                @if($divend == $fixTwocolcounter)
                                </div>
                                </div></div>
                                </div>
                                @php  $divstart = 0; $divend = 0; @endphp
                                @endif
                                @endif

                                @if($section->subtype == 'TwoColumns_3')
                                   
                                <div class="{{ $class }} col_1">
                                       
                                        @if($section->gentype == 'onlytitle')
                                        @php $col_value = 'only-title';@endphp
                                        @elseif($section->gentype == 'contactinfodata')
                                        @php $col_value = 'contact-info';@endphp
                                        @elseif($section->gentype == 'buttondata')
                                        @php $col_value = 'section-button';@endphp
                                        @elseif($section->gentype == 'onlyvideo')
                                        @php $col_value = 'only-video';@endphp
                                        @elseif($section->gentype == 'textarea')
                                        @php $col_value = 'only-content';@endphp
                                        @elseif($section->gentype == 'twotextarea')
                                        @php $col_value = 'two-part-content';@endphp
                                        @elseif($section->gentype == 'onlyimage')
                                        @php $col_value = 'only-image';@endphp
                                        @elseif($section->gentype == 'mapdata')
                                        @php $col_value = 'google-map';@endphp
                                        @elseif($section->gentype == 'onlydocument')
                                        @php $col_value = 'only-document';@endphp
                                        @elseif($section->gentype == 'imgcontent')
                                        @php $col_value = 'image-with-information';@endphp
                                        @elseif($section->gentype == 'videocontent')
                                        @php $col_value = 'video-with-information';@endphp
                                        @elseif($section->gentype == 'homeimgcontent')
                                        @php $col_value = 'home-information';@endphp
                                        @elseif($section->gentype == 'formdata')
                                        @php $col_value = 'only-content';@endphp
                                        @endif
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="columnstwo columns_3 {{ $col_value }}">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module module section-item {{ $rowclass }} maintwocol two_col_3" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12 col-xs-small "></div>
                                           <div class="add-element" data-innerplus="two_col_3">
                                            <div class="twocol1">
                                                @if($section->gentype == 'onlytitle')
                                                <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip colvalue onlytitleclass" value="{{ $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'textarea')
                                                <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip colvalue textareaclass" value="{{ $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'onlyimage')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->alignment }}" type="hidden" class="imgip imageclass" value="{{ $section->val->image }}"/>
                                                                 @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(isset($section->val->title))
                                                    <div class="title-img">
                                                        <h3>{{ $section->val->title }}</h3>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'image-lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-left.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'image-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-right.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'image-center-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-center.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'imgcontent')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-id="{{ $section->val->image }}" data-type="{{ $section->val->alignment }}" data-caption="{{ $section->val->title }}" type="hidden" class="imgip imgcontentclass" value="{{ $section->val->content }}"/>
                                                                 @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="title-img">
                                                        <h3>{!! $section->val->title !!}</h3>
                                                        {!! $section->val->content !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-image.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/top-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'bot-txt')
                                                        <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/bottom-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'center-txt')
                                                        <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-image.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'videocontent')
                                                @php 
                                                $vidIco = $section->val->videoType == 'Vimeo' ? 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' : 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="fa fa-youtube" aria-hidden="true"></i></a>'; 
                                                @endphp
                                                <div class="col-md-9 col-xs-small">
                                                    {!! $vidIco !!} <strong>{!! $section->val->title !!}</strong>
                                                    <input id="{{ 'item-'.$ikey }}" data-caption="{!! $section->val->title !!}" data-id="{!! $section->val->vidId !!}" data-type="{{ $section->val->videoType }}" data-aligntype="{{ $section->val->alignment }}" type="hidden" class="vidip videocontentclass" value="{{ $section->val->content }}"/>
                                                    <div class="title-img">
                                                        {!! $section->val->content !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-video.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/top-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'bot-txt')
                                                        <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/bottom-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'center-txt')
                                                        <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-video.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'homeimgcontent')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{  App\Helpers\resize_image::resize($section->val->image) }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-id="{{ $section->val->image }}" data-type="{{ $section->val->alignment }}" data-caption="{{ $section->val->title }}" type="hidden" class="imgip homeimagecontclass" value="{{ $section->val->content }}"/>
                                                                 @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="title-img">
                                                        <h3>{!! $section->val->title !!}</h3>
                                                        {!! $section->val->content !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'home-lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-left-image.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'home-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-right-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'home-top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-top-image.png' }}" alt=""></i>

                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'formdata')
                                                <div class="col-md-12 col-xs-small "><span><i class="fa fa-file-text-o" aria-hidden="true"></i></span> {!! $section->val->content !!}</div>
                                                <input id="{{ 'item-form' }}"  data-id="{{ $section->val->id }}" type="hidden" class="txtip formclass" value="{{ $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'twotextarea')
                                                <div class="col-md-12">
                                                    <div class="col-md-6"><strong> Left Side Content </strong>{!! $section->val->leftcontent !!}</div>
                                                    <div class="col-md-6"><strong> Right Side Content </strong>{!! $section->val->rightcontent !!}</div>
                                                </div>
                                                <input id="{{ 'item-'.$ikey }}" data-content="{{ $section->val->rightcontent }}" type="hidden" class="txtip twotextareaclass" value="{{ $section->val->leftcontent }}"/>
                                                @endif
                                                @if($section->gentype == 'mapdata')
                                                <div class="col-md-12 col-xs-small">
                                                    <div class="team_box">
                                                        <input id="{{ 'item-'.$ikey }}" data-latitude="{{ $section->val->latitude }}"  data-longitude="{{ $section->val->longitude }}" type="hidden" class="imgip mapclass"/>
                                                    </div>
                                                    @if(isset($section->val->latitude))
                                                    <div class="title-img">
                                                        <h3>Latitude: {{ $section->val->latitude }}</h3>
                                                    </div>
                                                    @endif
                                                    <br/>
                                                    @if(isset($section->val->longitude))
                                                    <div class="title-img">
                                                        <h3>Longitude: {{ $section->val->longitude }}</h3>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif
                                                @if($section->gentype == 'onlydocument')
                                                @if(isset($section->val->document) && $section->val->document != '')
                                                @php
                                                $docsAray = explode(',', $section->val->document);
                                                $docObj   = App\Document::getDocDataByIds($docsAray);
                                                @endphp
                                                <div class="col-md-12">
                                                    <div class="builder_doc_list">
                                                        <ul>
                                                            @if(count($docObj) > 0)
                                                            @foreach($docObj as $value)
                                                            <li id="doc_{{ $value->id }}">
                                                                <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                                    <img  src="{{ $CDN_PATH.'assets/images/document_icon.png' }}" alt="Img" />
                                                                </span>
                                                                <span class="editdoctitle">{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}</span>
                                                            </li>

                                                            @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                                <input id="{{ 'item-'.$ikey }}" type="hidden" class="imgip documentclass" data-type="document" value="{{ $section->val->document }}"/>
                                                    <input type="hidden" id="dochiddenid" name='img1' value="{{ $section->val->document }}">
                                                     @php
                                                                    if(isset($section->val->document)){
                                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($section->val->document);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    }
                                                                    @endphp
                                                </div>@endif
                                                @endif
                                                @if($section->gentype == 'onlyvideo')
                                                @php 
                                                $vidIco = $section->val->videoType == 'Vimeo' ? 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' : 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="fa fa-youtube" aria-hidden="true"></i></a>'; 
                                                @endphp
                                                <div class="col-md-12"><strong>{{ $section->val->title }}</strong> - {!! $vidIco !!}</div>
                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->videoType }}" type="hidden" class="vidip videoclass" value="{{ $section->val->vidId }}"/>
                                                @endif
                                                @if($section->gentype == 'contactinfodata')
                                                <div class="col-md-12"><b>Contact Information</b></div><br/><br/>
                                                <div class="col-md-12"><b>Address:</b>{!! $section->val->section_address !!}</div>
                                                <div class="col-md-12"><b>Email:</b>{!! $section->val->section_email !!}</div>
                                                <div class="col-md-12"><b>Phone #:</b>{!! $section->val->section_phone !!}</div>
                                                <div class="col-md-12"><b>Other Info:</b>{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}" data-address="{{ $section->val->section_address }}" data-email="{{ $section->val->section_email }}" data-phone="{{ $section->val->section_phone }}" type="hidden" class="txtip contactinfoclass" value="{{  $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'buttondata')
                                                <div class="col-md-12"><b>Button Information</b></div><br/><br/>
                                                <div class="col-md-10"><b>Title:</b>{!! $section->val->title !!}</br>
                                                    <b>Link:</b>{!! $section->val->content !!}</br>
                                                    @if($section->val->target=="_blank")
                                                    <b>Target:</b>New Window</br>
                                                    @else
                                                    <b>Link Target:</b>Same Window</br>
                                                    @endif
                                                </div>
                                                <div class="col-md-2 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'button-lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-button.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'button-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-button.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'button-center-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-button.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-linktarget="{{ $section->val->target }}" data-type="{{ $section->val->alignment }}" type="hidden" class="txtip buttonclass" value="{{  $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'spacerdata')
                                                <div class="col-md-12"><label><b>Spacer Class - </b>{{ $sectionConfig[$section->val->config] }}</label></div>
                                                <input id="{{ 'item-'.$ikey }}"  data-config="{{ $section->val->config }}"   type="hidden" class="txtip spacerclass"/>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                @endif
                                @if(isset($section->partitionclass) && $section->partitionclass == 'ThreeColumns') 
                                @if($divend == $fixThreecolcounter)
                                </div>
                                </div></div>
                                </div>
                                @php $divstart = 0;  $divend = 0; @endphp
                                @endif
                                @endif


                                @if($section->subtype == 'TwoColumns_4')
                                <div class="{{ $class }} col_1">
                                       
                                        @if($section->gentype == 'onlytitle')
                                        @php $col_value = 'only-title';@endphp
                                        @elseif($section->gentype == 'contactinfodata')
                                        @php $col_value = 'contact-info';@endphp
                                        @elseif($section->gentype == 'buttondata')
                                        @php $col_value = 'section-button';@endphp
                                        @elseif($section->gentype == 'onlyvideo')
                                        @php $col_value = 'only-video';@endphp
                                        @elseif($section->gentype == 'textarea')
                                        @php $col_value = 'only-content';@endphp
                                        @elseif($section->gentype == 'twotextarea')
                                        @php $col_value = 'two-part-content';@endphp
                                        @elseif($section->gentype == 'onlyimage')
                                        @php $col_value = 'only-image';@endphp
                                        @elseif($section->gentype == 'mapdata')
                                        @php $col_value = 'google-map';@endphp
                                        @elseif($section->gentype == 'onlydocument')
                                        @php $col_value = 'only-document';@endphp
                                        @elseif($section->gentype == 'imgcontent')
                                        @php $col_value = 'image-with-information';@endphp
                                        @elseif($section->gentype == 'videocontent')
                                        @php $col_value = 'video-with-information';@endphp
                                        @elseif($section->gentype == 'homeimgcontent')
                                        @php $col_value = 'home-information';@endphp
                                        @elseif($section->gentype == 'formdata')
                                        @php $col_value = 'only-content';@endphp
                                        @endif
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="columnstwo columns_4 {{ $col_value }}">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module module section-item {{ $rowclass }} maintwocol two_col_4" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12 col-xs-small "></div>
                                           <div class="add-element" data-innerplus="two_col_4">
                                            <div class="twocol1">
                                                @if($section->gentype == 'onlytitle')
                                                <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip colvalue onlytitleclass" value="{{ $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'textarea')
                                                <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip colvalue textareaclass" value="{{ $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'onlyimage')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->alignment }}" type="hidden" class="imgip imageclass" value="{{ $section->val->image }}"/>
                                                                 @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(isset($section->val->title))
                                                    <div class="title-img">
                                                        <h3>{{ $section->val->title }}</h3>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'image-lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-left.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'image-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-right.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'image-center-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-center.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'imgcontent')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-id="{{ $section->val->image }}" data-type="{{ $section->val->alignment }}" data-caption="{{ $section->val->title }}" type="hidden" class="imgip imgcontentclass" value="{{ $section->val->content }}"/>
                                                                 @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="title-img">
                                                        <h3>{!! $section->val->title !!}</h3>
                                                        {!! $section->val->content !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-image.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/top-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'bot-txt')
                                                        <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/bottom-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'center-txt')
                                                        <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-image.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'videocontent')
                                                @php 
                                                $vidIco = $section->val->videoType == 'Vimeo' ? 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' : 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="fa fa-youtube" aria-hidden="true"></i></a>'; 
                                                @endphp
                                                <div class="col-md-9 col-xs-small">
                                                    {!! $vidIco !!} <strong>{!! $section->val->title !!}</strong>
                                                    <input id="{{ 'item-'.$ikey }}" data-caption="{!! $section->val->title !!}" data-id="{!! $section->val->vidId !!}" data-type="{{ $section->val->videoType }}" data-aligntype="{{ $section->val->alignment }}" type="hidden" class="vidip videocontentclass" value="{{ $section->val->content }}"/>
                                                    <div class="title-img">
                                                        {!! $section->val->content !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-video.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/top-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'bot-txt')
                                                        <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/bottom-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'center-txt')
                                                        <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-video.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'homeimgcontent')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{  App\Helpers\resize_image::resize($section->val->image) }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-id="{{ $section->val->image }}" data-type="{{ $section->val->alignment }}" data-caption="{{ $section->val->title }}" type="hidden" class="imgip homeimagecontclass" value="{{ $section->val->content }}"/>
                                                                 @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="title-img">
                                                        <h3>{!! $section->val->title !!}</h3>
                                                        {!! $section->val->content !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'home-lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-left-image.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'home-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-right-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'home-top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-top-image.png' }}" alt=""></i>

                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'formdata')
                                                <div class="col-md-12 col-xs-small "><span><i class="fa fa-file-text-o" aria-hidden="true"></i></span> {!! $section->val->content !!}</div>
                                                <input id="{{ 'item-form' }}"  data-id="{{ $section->val->id }}" type="hidden" class="txtip formclass" value="{{ $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'twotextarea')
                                                <div class="col-md-12">
                                                    <div class="col-md-6"><strong> Left Side Content </strong>{!! $section->val->leftcontent !!}</div>
                                                    <div class="col-md-6"><strong> Right Side Content </strong>{!! $section->val->rightcontent !!}</div>
                                                </div>
                                                <input id="{{ 'item-'.$ikey }}" data-content="{{ $section->val->rightcontent }}" type="hidden" class="txtip twotextareaclass" value="{{ $section->val->leftcontent }}"/>
                                                @endif
                                                @if($section->gentype == 'mapdata')
                                                <div class="col-md-12 col-xs-small">
                                                    <div class="team_box">
                                                        <input id="{{ 'item-'.$ikey }}" data-latitude="{{ $section->val->latitude }}"  data-longitude="{{ $section->val->longitude }}" type="hidden" class="imgip mapclass"/>
                                                    </div>
                                                    @if(isset($section->val->latitude))
                                                    <div class="title-img">
                                                        <h3>Latitude: {{ $section->val->latitude }}</h3>
                                                    </div>
                                                    @endif
                                                    <br/>
                                                    @if(isset($section->val->longitude))
                                                    <div class="title-img">
                                                        <h3>Longitude: {{ $section->val->longitude }}</h3>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif
                                                @if($section->gentype == 'onlydocument')
                                                @if(isset($section->val->document) && $section->val->document != '')
                                                @php
                                                $docsAray = explode(',', $section->val->document);
                                                $docObj   = App\Document::getDocDataByIds($docsAray);
                                                @endphp
                                                <div class="col-md-12">
                                                    <div class="builder_doc_list">
                                                        <ul>
                                                            @if(count($docObj) > 0)
                                                            @foreach($docObj as $value)
                                                            <li id="doc_{{ $value->id }}">
                                                                <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                                    <img  src="{{ $CDN_PATH.'assets/images/document_icon.png' }}" alt="Img" />
                                                                </span>
                                                                <span class="editdoctitle">{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}</span>
                                                            </li>

                                                            @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                                <input id="{{ 'item-'.$ikey }}" type="hidden" class="imgip documentclass" data-type="document" value="{{ $section->val->document }}"/>
                                                    <input type="hidden" id="dochiddenid" name='img1' value="{{ $section->val->document }}">
                                                     @php
                                                                    if(isset($section->val->document)){
                                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($section->val->document);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    }
                                                                    @endphp
                                                </div>@endif
                                                @endif
                                                @if($section->gentype == 'onlyvideo')
                                                @php 
                                                $vidIco = $section->val->videoType == 'Vimeo' ? 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' : 
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="fa fa-youtube" aria-hidden="true"></i></a>'; 
                                                @endphp
                                                <div class="col-md-12"><strong>{{ $section->val->title }}</strong> - {!! $vidIco !!}</div>
                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->videoType }}" type="hidden" class="vidip videoclass" value="{{ $section->val->vidId }}"/>
                                                @endif
                                                @if($section->gentype == 'contactinfodata')
                                                <div class="col-md-12"><b>Contact Information</b></div><br/><br/>
                                                <div class="col-md-12"><b>Address:</b>{!! $section->val->section_address !!}</div>
                                                <div class="col-md-12"><b>Email:</b>{!! $section->val->section_email !!}</div>
                                                <div class="col-md-12"><b>Phone #:</b>{!! $section->val->section_phone !!}</div>
                                                <div class="col-md-12"><b>Other Info:</b>{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}" data-address="{{ $section->val->section_address }}" data-email="{{ $section->val->section_email }}" data-phone="{{ $section->val->section_phone }}" type="hidden" class="txtip contactinfoclass" value="{{  $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'buttondata')
                                                <div class="col-md-12"><b>Button Information</b></div><br/><br/>
                                                <div class="col-md-10"><b>Title:</b>{!! $section->val->title !!}</br>
                                                    <b>Link:</b>{!! $section->val->content !!}</br>
                                                    @if($section->val->target=="_blank")
                                                    <b>Target:</b>New Window</br>
                                                    @else
                                                    <b>Link Target:</b>Same Window</br>
                                                    @endif
                                                </div>
                                                <div class="col-md-2 text-right">                    
                                                    <div class="image-align-box">
                                                        <h5 class="title">Preview</h5>
                                                        @if($section->val->alignment == 'button-lft-txt')
                                                        <div class="image-align-box">
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-button.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'button-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-button.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'button-center-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-button.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-linktarget="{{ $section->val->target }}" data-type="{{ $section->val->alignment }}" type="hidden" class="txtip buttonclass" value="{{  $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'spacerdata')
                                                <div class="col-md-12"><label><b>Spacer Class - </b>{{ $sectionConfig[$section->val->config] }}</label></div>
                                                <input id="{{ 'item-'.$ikey }}"  data-config="{{ $section->val->config }}"   type="hidden" class="txtip spacerclass"/>
                                                @endif
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                @endif

                                @if(isset($section->partitionclass) && $section->partitionclass == 'FourColumns') 
                                @if($divend == $fixFourcolcounter)
                                </div>
                                </div></div>
                                </div>
                                @php $divstart = 0;  $divend = 0; @endphp
                                @endif
                                @endif

                                 @elseif($section->type == 'formarea')            
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    
                                    <div class="clearfix"></div>
                                    <div class="section-item form-area" data-editor="{{ 'item-form'}}">
                                        <div class="col-md-12 col-xs-small "><span><i class="fa fa-file-text-o" aria-hidden="true"></i></span> {!! $section->val->content !!}</div>
                                        <input id="{{ 'item-form' }}"  data-id="{{ $section->val->id }}" type="hidden" class="txtip" value="{{ $section->val->content }}"/>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'twocontent')            
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="two-part-content hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module module section-item two-part" data-editor="{{ 'item-'.$ikey }}">
                                    <div class="col-md-12">
                                        <div class="col-md-6"><strong> Left Side Content </strong>{!! $section->val->leftcontent !!}</div>
                                        <div class="col-md-6"><strong> Right Side Content </strong>{!! $section->val->rightcontent !!}</div>
                                        </div>
                                        <input id="{{ 'item-'.$ikey }}" data-content="{{ $section->val->rightcontent }}" type="hidden" class="txtip" value="{{ $section->val->leftcontent }}"/>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'map')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="google-map hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="section-item img-map" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12 col-xs-small">
                                            <div class="team_box">
                                                <input id="{{ 'item-'.$ikey }}" data-latitude="{{ $section->val->latitude }}"  data-longitude="{{ $section->val->longitude }}" type="hidden" class="imgip"/>
                                            </div>
                                            @if(isset($section->val->latitude))
                                            <div class="title-img">
                                                <h3>Latitude: {{ $section->val->latitude }}</h3>
                                            </div>
                                            @endif
                                            <br/>
                                            @if(isset($section->val->longitude))
                                            <div class="title-img">
                                                <h3>Longitude: {{ $section->val->longitude }}</h3>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'image')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="only-image hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="section-item img-area" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-10 col-xs-small">
                                            <div class="team_box">
                                                <div class="thumbnail_container">
                                                    <div class="thumbnail">
                                                        <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                        <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->alignment }}" type="hidden" class="imgip" value="{{ $section->val->image }}"/>
                                                         @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                    </div>
                                                </div>
                                            </div>
                                            @if(isset($section->val->title))
                                            <div class="title-img">
                                                <h3>{{ $section->val->title }}</h3>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-2 text-right">                    
                                            <div class="image-align-box">
                                                <h5 class="title">Preview</h5>
                                                @if($section->val->alignment == 'image-lft-txt')
                                                <div class="image-align-box">
                                                    <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-left.png' }}" alt=""></i>
                                                </div>
                                                @elseif($section->val->alignment == 'image-rt-txt')
                                                <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-right.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'image-center-txt')
                                                <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/image-center.png' }}" alt=""></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'image_gallery')
                                    <div id="section-container" class="ui-sortable">
                                       <div class="ui-state-default">
                                          <i title="Drag" class="action-icon move fa fa-arrows-alt ui-sortable-handle"></i><a href="javascript:;" class="close-btn" title="Delete"><i class="action-icon delete fa fa-trash"></i></a><a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="image-gallery hideclass"><i class="action-icon edit ri-pencil-line"></i></a>
                                          <div class="clearfix"></div>
                                          <div class="section-item defoult-module img-gallery-section" data-editor="{{ 'item-'.$ikey }}">
                                             <div class="col-md-12">
                                                <label><b>{!! $section->val->title !!}</b></label>
                                                <ul class="record-list img-gallery">
                                                  @foreach($section->val->images as $images)
                                                   <li data-id="{{ $images->id }}" id="{{ $images->id }}-item-{{ 'item-'.$ikey }}"><img height="50" width="50" src="{!! $images->src !!}"><a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>
                                                  @endforeach
                                                </ul>
                                             </div>
                                             <input class="imgip" id="{{ 'item-'.$ikey }}" type="hidden" data-layout="{{ $section->val->layout }}" data-caption="Image Gallery" data-type="gallery" value="gallery">
                                             <div class="clearfix"></div>
                                          </div>
                                       </div>
                                    </div>
                                     @elseif($section->type == 'document')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="only-document hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>

                                    <div class="section-item img-document" data-editor="{{ 'item-'.$ikey }}">
                                        @if(isset($section->val->document) && $section->val->document != '')
                                        @php
                                        $docsAray = explode(',', $section->val->document);
                                        $docObj   = App\Document::getDocDataByIds($docsAray);
                                        @endphp
                                        <div class="col-md-12">
                                            <div class="builder_doc_list">
                                                <ul>
                                                    @if(count($docObj) > 0)
                                                    @foreach($docObj as $value)
                                                    <li id="doc_{{ $value->id }}">
                                                        <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                            <img  src="{{ $CDN_PATH.'assets/images/document_icon.png' }}" alt="Img" />
                                                        </span>
                                                        <span class="editdoctitle">{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}</span>
                                                    </li>

                                                    @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                            {{-- <input class="image_1 item-data imgip" type="hidden" id="photo_gallery1" data-type="document" name="img1" value="{{ $section->val->document }}"/> --}}
                                            <input id="{{ 'item-'.$ikey }}" type="hidden" class="imgip" data-type="document" value="{{ $section->val->document }}"/>
                                            <input type="hidden" id="dochiddenid" name='img1' value="{{ $section->val->document }}">
                                             @php
                                                                    if(isset($section->val->document)){
                                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($section->val->document);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    }
                                                                    @endphp
                                        </div>
                                        @endif
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'img_content')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="image-with-information hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="section-item  img-rt-area" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-10 col-xs-small">
                                            <div class="team_box">
                                                <div class="thumbnail_container">
                                                    <div class="thumbnail">
                                                        <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                        <input id="{{ 'item-'.$ikey }}" data-id="{{ $section->val->image }}" data-type="{{ $section->val->alignment }}" data-caption="{{ $section->val->title }}" type="hidden" class="imgip" value="{{ $section->val->content }}"/>
                                                         @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="title-img">
                                                <h3>{!! $section->val->title !!}</h3>
                                                {!! $section->val->content !!}
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-right">                    
                                            <div class="image-align-box">
                                                <h5 class="title">Preview</h5>
                                                @if($section->val->alignment == 'lft-txt')
                                                <div class="image-align-box">
                                                    <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-image.png' }}" alt=""></i>
                                                </div>
                                                @elseif($section->val->alignment == 'rt-txt')
                                                <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-image.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'top-txt')
                                                <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/top-image.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'bot-txt')
                                                <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/bottom-image.png' }}" alt=""></i>
                                                 @elseif($section->val->alignment == 'center-txt')
                                                <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-image.png' }}" alt=""></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'video_content')
                                @php 
                                $vidIco = $section->val->videoType == 'Vimeo' ? 
                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' : 
                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="fa fa-youtube" aria-hidden="true"></i></a>'; 
                                @endphp
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="video-with-information hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module module section-item videoContent" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-10 col-xs-small">
                                        {!! $vidIco !!} <strong>{!! $section->val->title !!}</strong>
                                        <input id="{{ 'item-'.$ikey }}" data-caption="{!! $section->val->title !!}" data-id="{!! $section->val->vidId !!}" data-type="{{ $section->val->videoType }}" data-aligntype="{{ $section->val->alignment }}" type="hidden" class="vidip" value="{{ $section->val->content }}"/>
                                            <div class="title-img">
                                                {!! $section->val->content !!}
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-right">                    
                                            <div class="image-align-box">
                                                <h5 class="title">Preview</h5>
                                                @if($section->val->alignment == 'lft-txt')
                                                <div class="image-align-box">
                                                    <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-video.png' }}" alt=""></i>
                                                </div>
                                                @elseif($section->val->alignment == 'rt-txt')
                                                <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-video.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'top-txt')
                                                <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/top-video.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'bot-txt')
                                                <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/bottom-video.png' }}" alt=""></i>
                                                 @elseif($section->val->alignment == 'center-txt')
                                                <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-video.png' }}" alt=""></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'home-img_content')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="home-information hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="section-item  home-img-rt-area" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-10 col-xs-small">
                                            <div class="team_box">
                                                <div class="thumbnail_container">
                                                    <div class="thumbnail">
                                                        <img class="{{ 'item-'.$ikey }}" src="{{  App\Helpers\resize_image::resize($section->val->image) }}">
                                                        <input id="{{ 'item-'.$ikey }}" data-id="{{ $section->val->image }}" data-type="{{ $section->val->alignment }}" data-caption="{{ $section->val->title }}" type="hidden" class="imgip" value="{{ $section->val->content }}"/>
                                                         @php
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        }
		                                                        @endphp
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="title-img">
                                                <h3>{!! $section->val->title !!}</h3>
                                                {!! $section->val->content !!}
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-right">                    
                                            <div class="image-align-box">
                                                <h5 class="title">Preview</h5>
                                                @if($section->val->alignment == 'home-lft-txt')
                                                <div class="image-align-box">
                                                    <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-left-image.png' }}" alt=""></i>
                                                </div>
                                                @elseif($section->val->alignment == 'home-rt-txt')
                                                <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-right-image.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'home-top-txt')
                                                <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/home-top-image.png' }}" alt=""></i>

                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'only_title')              
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="only-title hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="section-item defoult-module module titleOnly" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12">{!! $section->val->content !!}</div>
                                        <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip" value="{{  $section->val->content }}"/>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                                @elseif($section->type == 'conatct_info')              
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="contact-info hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="section-item defoult-module module contactInfoOnly" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><b>Contact Information</b></div><br/><br/>
                                        <div class="col-md-12"><b>Address:</b>{!! $section->val->section_address !!}</div>
                                        <div class="col-md-12"><b>Email:</b>{!! $section->val->section_email !!}</div>
                                        <div class="col-md-12"><b>Phone #:</b>{!! $section->val->section_phone !!}</div>
                                        <div class="col-md-12"><b>Other Info:</b>{!! $section->val->content !!}</div>
                                        <input id="{{ 'item-'.$ikey }}" data-address="{{ $section->val->section_address }}" data-email="{{ $section->val->section_email }}" data-phone="{{ $section->val->section_phone }}" type="hidden" class="txtip" value="{{  $section->val->content }}"/>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                                @elseif($section->type == 'button_info')              
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="section-button hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="section-item defoult-module module buttonInfoOnly" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><b>Button Information</b></div><br/><br/>
                                        <div class="col-md-10"><b>Title:</b>{!! $section->val->title !!}</br>
                                            <b>Link:</b>{!! $section->val->content !!}</br>
                                            @if($section->val->target=="_blank")
                                            <b>Target:</b>New Window</br>
                                            @else
                                            <b>Link Target:</b>Same Window</br>
                                            @endif
                                        </div>
                                        <div class="col-md-2 text-right">                    
                                            <div class="image-align-box">
                                                <h5 class="title">Preview</h5>
                                                @if($section->val->alignment == 'button-lft-txt')
                                                <div class="image-align-box">
                                                    <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/left-button.png' }}" alt=""></i>
                                                </div>
                                                @elseif($section->val->alignment == 'button-rt-txt')
                                                <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/right-button.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'button-center-txt')
                                                <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/center-button.png' }}" alt=""></i>
                                                @endif
                                            </div>
                                        </div>
                                        <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-linktarget="{{ $section->val->target }}" data-type="{{ $section->val->alignment }}" type="hidden" class="txtip" value="{{  $section->val->content }}"/>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                                @elseif($section->type == 'only_video')
                                @php 
                                $vidIco = $section->val->videoType == 'Vimeo' ? 
                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' : 
                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="fa fa-youtube" aria-hidden="true"></i></a>'; 
                                @endphp
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="only-video hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module module section-item videoOnly" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><strong>{{ $section->val->title }}</strong> - {!! $vidIco !!}</div>
                                        <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->videoType }}" type="hidden" class="vidip" value="{{ $section->val->vidId }}"/>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                                @elseif($section->type == 'module')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>

                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>

                                    <a href="javascript:;" title="Edit" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" data-id="{{ 'item-'.$ikey }}" class="{{ $section->val->module }}-module">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>

                                    <div class="clearfix"></div>
                                    <div class="section-item defoult-module module" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12">
                                            <label><b>{{ $section->val->title }}</b>
                                                @if(isset($sectionConfig[$section->val->config]))
                                                ({{ $sectionConfig[$section->val->config] }})
                                                @endif
                                                - <b>{{ $section->val->layout }} View</b>
                                            </label>
                                            <ul class="record-list">                        
                                                @foreach($section->val->records as $id=>$record)
                                                @php $customized = 'true'; @endphp
                                                @if( 
                                                empty($record->custom_fields->img) &&
                                                empty($record->custom_fields->imgsrc) &&
                                                empty($record->custom_fields->imgheight) &&
                                                empty($record->custom_fields->imgwidth) &&
                                                empty($record->custom_fields->imgpoint) &&
                                                empty($record->custom_fields->phone) &&
                                                empty($record->custom_fields->email) &&
                                                empty($record->custom_fields->website) &&
                                                empty($record->custom_fields->address) &&
                                                empty($record->custom_fields->description)
                                                )
                                                @php $customized = 'false'; @endphp
                                                @endif

                                                <li data-id="{{ $record->id }}" id="{{ $id.'-item-'.$ikey }}"
                                                    data-customized="{{ $customized }}"
                                                    data-img="@if(isset($record->custom_fields->img)){{$record->custom_fields->img}}@endif"
                                                    data-imgsrc="@if(isset($record->custom_fields->imgsrc)){{$record->custom_fields->imgsrc}}@endif"
                                                    data-imgheight="@if(isset($record->custom_fields->imgheight)){{$record->custom_fields->imgheight}}@endif"
                                                    data-imgwidth="@if(isset($record->custom_fields->imgwidth)){{$record->custom_fields->imgwidth}}@endif"
                                                    data-imgpoint="@if(isset($record->custom_fields->imgpoint)){{$record->custom_fields->imgpoint}}@endif"                            
                                                    data-phone="@if(isset($record->custom_fields->phone)){{$record->custom_fields->phone}}@endif"
                                                    data-email="@if(isset($record->custom_fields->email)){{$record->custom_fields->email}}@endif"
                                                    data-website="@if(isset($record->custom_fields->website)){{$record->custom_fields->website}}@endif"
                                                    data-address="@if(isset($record->custom_fields->address)){{$record->custom_fields->address}}@endif"
                                                    data-description="@if(isset($record->custom_fields->description)){{$record->custom_fields->description}}@endif"
                                                    >
                                                    <span>{{ $record->title }}</span>

                                                    <a href="javascript:;" class="close-icon" title="Delete">
                                                        <i class="ri-delete-bin-line" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                            <div class="clearfix"></div>
                                            <a data-id="{{ 'item-'.$ikey }}" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" title="Add more" class="add-link {{ $section->val->module }}-module" href="javascript:;">
                                                <i class="fa fa-plus"></i>&nbsp;Add more
                                            </a>
                                        </div>                    
                                        <input id="{{ 'item-'.$ikey }}" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" data-frest="{{ isset($section->val->featured_restaurant_section)?$section->val->featured_restaurant_section:'' }}"  data-template="{{ isset($section->val->template)?$section->val->template:'' }}" data-desc="{{ isset($section->val->desc)?$section->val->desc:'' }}" type="hidden" data-config="{{ $section->val->config }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }} @endif" data-caption="{{ $section->val->title }}" data-type="module" value="{{ $section->val->module }}" />
                                               <div class="clearfix"></div>
                                    </div>
                                </div>

                                @elseif($section->type == 'business_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="business-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item businessTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b>({{ $sectionConfig[$section->val->config] }})</label><ul><li>Template: {{ $section->val->template }}</li></ul></div>
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }} @endif" type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'spacer_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="only-spacer">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item spacerTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>Spacer Class - </b>{{ $sectionConfig[$section->val->config] }}</label></div>
                                        <input id="{{ 'item-'.$ikey }}"  data-config="{{ $section->val->config }}"   type="hidden" class="txtip"/>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'events_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="events-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item eventsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b>({{ $sectionConfig[$section->val->config] }}) - <b>{{ $section->val->layout }} View</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }} @endif"  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }} @endif" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }} @endif" type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'news_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="news-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item newsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b>({{ $sectionConfig[$section->val->config] }}) - <b>{{ $section->val->layout }} View</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }} @endif"  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }} @endif"  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }} @endif" type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'blogs_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="blogs-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item blogsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b>({{ $sectionConfig[$section->val->config] }}) - <b>{{ $section->val->layout }} View</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }} @endif"  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }} @endif"  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }} @endif" type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'publication_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="publication-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item publicationTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b>({{ $sectionConfig[$section->val->config] }}) - <b>{{ $section->val->layout }} View</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }} @endif"  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }} @endif"  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }} @endif" type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'promotions_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move fa fa-arrows-alt"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="promotions-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item promotionsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b>({{ $sectionConfig[$section->val->config] }})</label><ul><li>Template: {{ $section->val->template }}</li></ul></div>
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }} @endif" type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                @endif
                                @php $ikey++;
                                if($section->type == 'partitondata')
                                {
                                    $divstart++;
                                    $divend++; 
                                }
                                @endphp
                                @endforeach 
                                @endif              
                            </div>
                            <div class="ui-new-section-add add-element">
                                <a href="javascript:;" class="add-icon add-element">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    </section>
                  @else
                    <section class="powercomposer" id="no-content" style="display:block">
                        <div class="title form_title">PAGE CONTENT</div>
                        <div class="composerbody">
                            <div class="text-block">
                                <div class="icon">
                                    <img src="{{ $CDN_PATH.'assets/images/composer-icon.png' }}" alt="">
                                </div>
                                You have blank page
                                <br>start adding elements and text blocks
                            </div>
                            <div class="button-block">
                                <a href="javascript:;" class="btn element-btn add-element" title="Add Element">
                                    <i>+</i>Add Element
                                </a>
                                <a href="javascript:;" class="btn textblock-btn only-content" id="add-text-block" title="Add Text Block">
                                    <i class="icon-note"></i>Add Text Block
                                </a>
                            </div>
                        </div>
                    </section>


                    <section class="builder powercomposer hide" id="has-content" style="display:block">
                        <input type="hidden" name="section" id="builderObj" />
                        <div class="portlet light portlet_light menuBody overflow_visible page_builder movable-section">
                            <div class="portlet-body">
                                <div class="">
                                    <div class="padding_right_set">
                                        <div id="section-container" class="builder-append-data">              
                                        </div>
                                        <div class="ui-new-section-add add-element">
                                            <a href="javascript:;" class="add-icon add-element">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                   @endif
                </div>
            </div>
