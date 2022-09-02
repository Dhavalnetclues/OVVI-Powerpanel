{{-- @php $banners = Powerpanel\Banner\Models\Banner::getListForBuilder(); @endphp --}}
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
        <h4 class="form-section mb-3">Description</h4>
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
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="only-content text-block hideclass">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="section-item text-area" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                            <input id="{{ 'item-'.$ikey }}" data-class="{{ isset($section->val->extclass)?$section->val->extclass:'' }}"  type="hidden" class="txtip" value="{{ isset($section->val->content)?$section->val->content:'' }}"/>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                @elseif($section->type == 'accordianblock')
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="only-content accordian-block hideclass">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="section-item text-accordian" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                            <input id="{{ 'item-'.$ikey }}" data-title="{{ isset($section->val->title)?$section->val->title:'' }}"  type="hidden" class="txtip" value="{{ isset($section->val->content)?$section->val->content:'' }}"/>
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
                                   @elseif(isset($section->partitionclass) && $section->partitionclass == 'OneThreeColumns')
                                    @php
                                        if($section->subtype == 'TwoColumns_1') {
                                            $class = 'col-sm-3';
                                        } else {
                                            $class = 'col-sm-9';
                                        }
                                        $rowclass = 'OneThreeColumns';
                                    @endphp
                                    @elseif(isset($section->partitionclass) && $section->partitionclass == 'ThreeOneColumns')
                                        @php
                                            if($section->subtype == 'TwoColumns_2') {
                                                $class = 'col-sm-9';
                                            } else {
                                                $class = 'col-sm-3';
                                            }
                                            $rowclass = 'ThreeOneColumns';
                                        @endphp
                                  @endif
                                  @endif
                                  @if($divstart == 1)
                                <div class="ui-state-default">
                                    <div class="row {{ $rowclass }}"><div class='col-sm-12'>
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                    <div class="ui-new-section-add col-sm-12">
                                @endif
                                {{--@php echo'<pre>';print_r($section);@endphp--}}
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
                                            @elseif($section->gentype == 'custom_section')
                                            @php $col_value = 'custom-section';@endphp
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
                                                    <input id="{{ 'item-'.$ikey }}" type="hidden" class="txtip colvalue onlytitleclass" value="{{ $section->val->content }}" />
                                                    @endif
                                                    @if($section->gentype == 'textarea')
                                                    <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                    <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip colvalue textareaclass" value="{{ $section->val->content }}"/>
                                                    @endif
                                                    @if($section->gentype == 'accordianblock')
                                                    <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                    <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip colvalue accordianblockclass" value="{{ $section->val->content }}" data-title="{{ $section->val->title  }}"/>
                                                    @endif
                                                    @if($section->gentype == 'onlyimage')
                                                    <div class="col-md-9 col-xs-small">
                                                        <div class="team_box">
                                                            <div class="thumbnail_container">
                                                                <div class="thumbnail">
                                                                    <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                                    <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->alignment }}" type="hidden" class="imgip imageclass" value="{{ $section->val->image }}"/>
                                                                    @php  if (method_exists($MyLibrary, 'GetFolderID')) {
                                                                    if(isset($section->val->image)){
                                                                    $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    } }
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
                                                                <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-left.png' }}" alt=""></i>
                                                            </div>
                                                            @elseif($section->val->alignment == 'image-rt-txt')
                                                                <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-right.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'image-center-txt')
                                                                <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-center.png' }}" alt=""></i>
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
                                                                    @php if (method_exists($MyLibrary, 'GetFolderID')) {
                                                                    if(isset($section->val->image)){
                                                                    $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    } }
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
                                                                <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-image.png' }}" alt=""></i>
                                                            </div>
                                                            @elseif($section->val->alignment == 'rt-txt')
                                                            <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-image.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'top-txt')
                                                            <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/top-image.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'bot-txt')
                                                            <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/bottom-image.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'center-txt')
                                                            <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-image.png' }}" alt=""></i>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if($section->gentype == 'videocontent')
                                                    @php
                                                    $vidIco = $section->val->videoType == 'Vimeo' ?
                                                    '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="ri-video-line" aria-hidden="true"></i></a>' :
                                                    '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="ri-youtube-line" aria-hidden="true"></i></a>';
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
                                                                <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-video.png' }}" alt=""></i>
                                                            </div>
                                                            @elseif($section->val->alignment == 'rt-txt')
                                                            <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-video.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'top-txt')
                                                            <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/top-video.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'bot-txt')
                                                            <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/bottom-video.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'center-txt')
                                                            <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-video.png' }}" alt=""></i>
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
                                                                    @php if (method_exists($MyLibrary, 'GetFolderID')) {
                                                                    if(isset($section->val->image)){
                                                                    $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    } }
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
                                                                <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-left-image.png' }}" alt=""></i>
                                                            </div>
                                                            @elseif($section->val->alignment == 'home-rt-txt')
                                                            <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-right-image.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'home-top-txt')
                                                            <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-top-image.png' }}" alt=""></i>

                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if($section->gentype == 'formdata')
                                                    <div class="col-md-12 col-xs-small "><span><i class="ri-file-text-line" aria-hidden="true"></i></span> {!! $section->val->content !!}</div>
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
                                                                        <img  src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/document_icon.png' }}" alt="Img" />
                                                                    </span>
                                                                    <span class="editdoctitle">{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}</span>
                                                                </li>

                                                                @endforeach
                                                                @endif
                                                            </ul>
                                                        </div>
                                                                    <input id="{{ 'item-'.$ikey }}" type="hidden" class="imgip documentclass" data-type="document" value="{{ $section->val->document }}"/>
                                                        <input type="hidden" id="dochiddenid" name='img1' value="{{ $section->val->document }}">
                                                        @php if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                                        if(isset($section->val->document)){
                                                                        $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($section->val->document);
                                                                        @endphp
                                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                        @endif
                                                                        @php
                                                                        } }
                                                                        @endphp
                                                    </div>@endif
                                                    @endif
                                                    @if($section->gentype == 'onlyvideo')
                                                    @php
                                                    $vidIco = $section->val->videoType == 'Vimeo' ?
                                                    '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="ri-video-line" aria-hidden="true"></i></a>' :
                                                    '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="ri-youtube-line" aria-hidden="true"></i></a>';
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

                                                    @if($section->gentype == 'custom_section')
                                                        <div class="col-md-12">
                                                            <b>{{ $section->val->title }}</b>
                                                            <ul class="record-list" id="record-list-{{ 'item-'.$ikey }}">
                                                                @if(isset($section->val->records) && !empty($section->val->records))
                                                                    @foreach($section->val->records as $id => $record)
                                                                        <li id="crec-item-{{ $ikey.'-'.$id }}">
                                                                            {{ $record->title }}
                                                                            <a href="javascript:;" data-id="{{ 'item-'.$ikey }}" data-img="{{ isset($record->imgid)?$record->imgid:'' }}" data-imgsrc="{{ isset($record->imgsrc)?$record->imgsrc:'' }}" data-title="{{ $record->title }}" data-link="{{ $record->link }}" data-desc="{{ isset($record->desc)?$record->desc:'' }}" data-mode="edit" class="add-custom-record" title="edit">
                                                                                <i class="ri-pencil-line" aria-hidden="true"></i> </a><a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i>
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                @endif
                                                            </ul>
                                                            <a data-id="{{ 'item-'.$ikey }}" title="Add custom record" class="add-custom-record" href="javascript:;"><i class="ri-add-fill"></i>&nbsp;Add custom record</a>
                                                            <input id="{{ 'item-'.$ikey }}"  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}" @endif  @if(isset($section->val->SubTitle)) data-subtitle="{{ $section->val->SubTitle }}" @endif  @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}" @endif type="hidden" class="txtip customSection" value="{{ $section->val->title }}">
                                                        </div>
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
                                                                <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-button.png' }}" alt=""></i>
                                                            </div>
                                                            @elseif($section->val->alignment == 'button-rt-txt')
                                                            <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-button.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'button-center-txt')
                                                            <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-button.png' }}" alt=""></i>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-linktarget="{{ $section->val->target }}" data-type="{{ $section->val->alignment }}" type="hidden" class="txtip buttonclass" value="{{  $section->val->content }}"/>
                                                    @endif
                                                    @if($section->gentype == 'spacerdata')
                                                    <div class="col-md-12"><label><b>Spacer Class</b></label></div>
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
                                            @elseif($section->gentype == 'custom_section')
                                            @php $col_value = 'custom-section';@endphp
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
                                                    <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip colvalue textareaclass" value="{{ $section->val->content }}"/>
                                                    @endif
                                                    @if($section->gentype == 'accordianblock')
                                                    <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                    <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip colvalue accordianblockclass" value="{{ $section->val->content }}" data-title="{{ $section->val->title  }}"/>
                                                    @endif
                                                    @if($section->gentype == 'onlyimage')
                                                    <div class="col-md-9 col-xs-small">
                                                        <div class="team_box">
                                                            <div class="thumbnail_container">
                                                                <div class="thumbnail">
                                                                    <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                                    <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->alignment }}" type="hidden" class="imgip imageclass" value="{{ $section->val->image }}"/>
                                                                    @php if (method_exists($MyLibrary, 'GetFolderID')) {
                                                                    if(isset($section->val->image)){
                                                                    $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    } }
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
                                                                <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-left.png' }}" alt=""></i>
                                                            </div>
                                                            @elseif($section->val->alignment == 'image-rt-txt')
                                                            <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-right.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'image-center-txt')
                                                            <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-center.png' }}" alt=""></i>
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
                                                                    @php if (method_exists($MyLibrary, 'GetFolderID')) {
                                                                    if(isset($section->val->image)){
                                                                    $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    } }
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
                                                                <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-image.png' }}" alt=""></i>
                                                            </div>
                                                            @elseif($section->val->alignment == 'rt-txt')
                                                            <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-image.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'top-txt')
                                                            <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/top-image.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'bot-txt')
                                                            <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/bottom-image.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'center-txt')
                                                            <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-image.png' }}" alt=""></i>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if($section->gentype == 'videocontent')
                                                    @php
                                                    $vidIco = $section->val->videoType == 'Vimeo' ?
                                                    '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="ri-video-line" aria-hidden="true"></i></a>' :
                                                    '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="ri-youtube-line" aria-hidden="true"></i></a>';
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
                                                                <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-video.png' }}" alt=""></i>
                                                            </div>
                                                            @elseif($section->val->alignment == 'rt-txt')
                                                            <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-video.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'top-txt')
                                                            <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/top-video.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'bot-txt')
                                                            <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/bottom-video.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'center-txt')
                                                            <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-video.png' }}" alt=""></i>
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
                                                                    @php if (method_exists($MyLibrary, 'GetFolderID')) {
                                                                    if(isset($section->val->image)){
                                                                    $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    } }
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
                                                                <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-left-image.png' }}" alt=""></i>
                                                            </div>
                                                            @elseif($section->val->alignment == 'home-rt-txt')
                                                            <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-right-image.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'home-top-txt')
                                                            <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-top-image.png' }}" alt=""></i>

                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if($section->gentype == 'formdata')
                                                    <div class="col-md-12 col-xs-small "><span><i class="ri-file-text-line" aria-hidden="true"></i></span> {!! $section->val->content !!}</div>
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
                                                                        <img  src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/document_icon.png' }}" alt="Img" />
                                                                    </span>
                                                                    <span class="editdoctitle">{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}</span>
                                                                </li>

                                                                @endforeach
                                                                @endif
                                                            </ul>
                                                        </div>
                                                                    <input id="{{ 'item-'.$ikey }}" type="hidden" class="imgip documentclass" data-type="document" value="{{ $section->val->document }}"/>
                                                        <input type="hidden" id="dochiddenid" name='img1' value="{{ $section->val->document }}">
                                                        @php if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                                        if(isset($section->val->document)){
                                                                        $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($section->val->document);
                                                                        @endphp
                                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                        @endif
                                                                        @php
                                                                        } }
                                                                        @endphp
                                                    </div>@endif
                                                    @endif
                                                    @if($section->gentype == 'onlyvideo')
                                                    @php
                                                    $vidIco = $section->val->videoType == 'Vimeo' ?
                                                    '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="ri-video-line" aria-hidden="true"></i></a>' :
                                                    '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="ri-youtube-line" aria-hidden="true"></i></a>';
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

                                                    @if($section->gentype == 'custom_section')
                                                        <div class="col-md-12">
                                                            <b>{{ $section->val->title }}</b>
                                                            <ul class="record-list" id="record-list-{{ 'item-'.$ikey }}">
                                                                @if(isset($section->val->records) && !empty($section->val->records))
                                                                    @foreach($section->val->records as $id => $record)
                                                                        <li id="crec-item-{{ $ikey.'-'.$id }}">
                                                                            {{ $record->title }}
                                                                            <a href="javascript:;" data-id="{{ 'item-'.$ikey }}" data-img="{{ isset($record->imgid)?$record->imgid:'' }}" data-imgsrc="{{ isset($record->imgsrc)?$record->imgsrc:'' }}" data-title="{{ $record->title }}" data-link="{{ $record->link }}" data-desc="{{ isset($record->desc)?$record->desc:'' }}" data-mode="edit" class="add-custom-record" title="edit">
                                                                                <i class="ri-pencil-line" aria-hidden="true"></i> </a><a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i>
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                @endif
                                                            </ul>
                                                            <a data-id="{{ 'item-'.$ikey }}" title="Add custom record" class="add-custom-record" href="javascript:;"><i class="ri-add-fill"></i>&nbsp;Add custom record</a>
                                                            <input id="{{ 'item-'.$ikey }}"  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}" @endif  @if(isset($section->val->SubTitle)) data-subtitle="{{ $section->val->SubTitle }}" @endif  @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}"  @endif type="hidden" class="txtip customSection" value="{{ $section->val->title }}">
                                                        </div>
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
                                                                <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-button.png' }}" alt=""></i>
                                                            </div>
                                                            @elseif($section->val->alignment == 'button-rt-txt')
                                                            <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-button.png' }}" alt=""></i>
                                                            @elseif($section->val->alignment == 'button-center-txt')
                                                            <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-button.png' }}" alt=""></i>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-linktarget="{{ $section->val->target }}" data-type="{{ $section->val->alignment }}" type="hidden" class="txtip buttonclass" value="{{  $section->val->content }}"/>
                                                    @endif
                                                    @if($section->gentype == 'spacerdata')
                                                    <div class="col-md-12"><label><b>Spacer Class</b></label></div>
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
                                        @elseif($section->gentype == 'custom_section')
                                        @php $col_value = 'custom-section';@endphp
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
                                                @if($section->gentype == 'accordianblock')
                                                <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip colvalue accordianblockclass" value="{{ $section->val->content }}" data-title="{{ $section->val->title  }}"/>
                                                @endif
                                                @if($section->gentype == 'onlyimage')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->alignment }}" type="hidden" class="imgip imageclass" value="{{ $section->val->image }}"/>
                                                                 @php if (method_exists($MyLibrary, 'GetFolderID')) {
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        } }
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
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-left.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'image-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-right.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'image-center-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-center.png' }}" alt=""></i>
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
                                                                 @php if (method_exists($MyLibrary, 'GetFolderID')) {
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        } }
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
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-image.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/top-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'bot-txt')
                                                        <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/bottom-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'center-txt')
                                                        <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-image.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'videocontent')
                                                @php
                                                $vidIco = $section->val->videoType == 'Vimeo' ?
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="ri-video-line" aria-hidden="true"></i></a>' :
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="ri-youtube-line" aria-hidden="true"></i></a>';
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
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-video.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/top-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'bot-txt')
                                                        <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/bottom-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'center-txt')
                                                        <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-video.png' }}" alt=""></i>
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
                                                                 @php if (method_exists($MyLibrary, 'GetFolderID')) {
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        } }
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
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-left-image.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'home-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-right-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'home-top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-top-image.png' }}" alt=""></i>

                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'formdata')
                                                <div class="col-md-12 col-xs-small "><span><i class="ri-file-text-line" aria-hidden="true"></i></span> {!! $section->val->content !!}</div>
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
                                                                    <img  src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/document_icon.png' }}" alt="Img" />
                                                                </span>
                                                                <span class="editdoctitle">{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}</span>
                                                            </li>

                                                            @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                                <input id="{{ 'item-'.$ikey }}" type="hidden" class="imgip documentclass" data-type="document" value="{{ $section->val->document }}"/>
                                                    <input type="hidden" id="dochiddenid" name='img1' value="{{ $section->val->document }}">
                                                     @php if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                                    if(isset($section->val->document)){
                                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($section->val->document);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    } }
                                                                    @endphp
                                                </div>@endif
                                                @endif
                                                @if($section->gentype == 'onlyvideo')
                                                @php
                                                $vidIco = $section->val->videoType == 'Vimeo' ?
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="ri-video-line" aria-hidden="true"></i></a>' :
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="ri-youtube-line" aria-hidden="true"></i></a>';
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

                                                @if($section->gentype == 'custom_section')
                                                    <div class="col-md-12">
                                                        <b>{{ $section->val->title }}</b>
                                                        <ul class="record-list" id="record-list-{{ 'item-'.$ikey }}">
                                                            @if(isset($section->val->records) && !empty($section->val->records))
                                                                @foreach($section->val->records as $id => $record)
                                                                    <li id="crec-item-{{ $ikey.'-'.$id }}">
                                                                        {{ $record->title }}
                                                                        <a href="javascript:;" data-id="{{ 'item-'.$ikey }}" data-img="{{ isset($record->imgid)?$record->imgid:'' }}" data-imgsrc="{{ isset($record->imgsrc)?$record->imgsrc:'' }}" data-title="{{ $record->title }}" data-link="{{ $record->link }}" data-desc="{{ isset($record->desc)?$record->desc:'' }}" data-mode="edit" class="add-custom-record" title="edit">
                                                                            <i class="ri-pencil-line" aria-hidden="true"></i> </a><a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i>
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                        <a data-id="{{ 'item-'.$ikey }}" title="Add custom record" class="add-custom-record" href="javascript:;"><i class="ri-add-fill"></i>&nbsp;Add custom record</a>
                                                        <input id="{{ 'item-'.$ikey }}"  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}" @endif  @if(isset($section->val->SubTitle)) data-subtitle="{{ $section->val->SubTitle }}" @endif  @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}" @endif type="hidden" class="txtip customSection" value="{{ $section->val->title }}">
                                                    </div>
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
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-button.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'button-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-button.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'button-center-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-button.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-linktarget="{{ $section->val->target }}" data-type="{{ $section->val->alignment }}" type="hidden" class="txtip buttonclass" value="{{  $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'spacerdata')
                                                <div class="col-md-12"><label><b>Spacer Class</b></label></div>
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

                                @if(isset($section->partitionclass) && $section->partitionclass == 'OneThreeColumns')
                                    @if($divend == $fixTwocolcounter)
                                        </div>
                                        </div></div>
                                        </div>
                                        @php $divstart = 0;  $divend = 0; @endphp
                                    @endif
                                @endif

                                @if(isset($section->partitionclass) && $section->partitionclass == 'ThreeOneColumns')
                                    @if($divend == $fixTwocolcounter)
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
                                        @elseif($section->gentype == 'accordianblock')
                                        @php $col_value = 'accordian-block';@endphp
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
                                        @elseif($section->gentype == 'custom_section')
                                        @php $col_value = 'custom-section';@endphp
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
                                                @if($section->gentype == 'accordianblock')
                                                <div class="col-md-12 col-xs-small ">{!! $section->val->content !!}</div>
                                                <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip colvalue accordianblockclass" value="{{ $section->val->content }}" data-title="{{ $section->val->title  }}"/>
                                                @endif
                                                @if($section->gentype == 'onlyimage')
                                                <div class="col-md-9 col-xs-small">
                                                    <div class="team_box">
                                                        <div class="thumbnail_container">
                                                            <div class="thumbnail">
                                                                <img class="{{ 'item-'.$ikey }}" src="{{ $section->val->src }}">
                                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->alignment }}" type="hidden" class="imgip imageclass" value="{{ $section->val->image }}"/>
                                                                 @php if (method_exists($MyLibrary, 'GetFolderID')) {
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        } }
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
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-left.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'image-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-right.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'image-center-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-center.png' }}" alt=""></i>
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
                                                                 @php if (method_exists($MyLibrary, 'GetFolderID')) {
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        } }
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
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-image.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/top-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'bot-txt')
                                                        <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/bottom-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'center-txt')
                                                        <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-image.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'videocontent')
                                                @php
                                                $vidIco = $section->val->videoType == 'Vimeo' ?
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="ri-video-line" aria-hidden="true"></i></a>' :
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="ri-youtube-line" aria-hidden="true"></i></a>';
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
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-video.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/top-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'bot-txt')
                                                        <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/bottom-video.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'center-txt')
                                                        <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-video.png' }}" alt=""></i>
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
                                                                 @php if (method_exists($MyLibrary, 'GetFolderID')) {
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        } }
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
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-left-image.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'home-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-right-image.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'home-top-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-top-image.png' }}" alt=""></i>

                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                @if($section->gentype == 'formdata')
                                                <div class="col-md-12 col-xs-small "><span><i class="ri-file-text-line" aria-hidden="true"></i></span> {!! $section->val->content !!}</div>
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
                                                                    <img  src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/document_icon.png' }}" alt="Img" />
                                                                </span>
                                                                <span class="editdoctitle">{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}</span>
                                                            </li>

                                                            @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                                <input id="{{ 'item-'.$ikey }}" type="hidden" class="imgip documentclass" data-type="document" value="{{ $section->val->document }}"/>
                                                    <input type="hidden" id="dochiddenid" name='img1' value="{{ $section->val->document }}">
                                                     @php if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                                    if(isset($section->val->document)){
                                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($section->val->document);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    } }
                                                                    @endphp
                                                </div>@endif
                                                @endif
                                                @if($section->gentype == 'onlyvideo')
                                                @php
                                                $vidIco = $section->val->videoType == 'Vimeo' ?
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="ri-video-line" aria-hidden="true"></i></a>' :
                                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="ri-youtube-line" aria-hidden="true"></i></a>';
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

                                                @if($section->gentype == 'custom_section')
                                                    <div class="col-md-12">
                                                        <b>{{ $section->val->title }}</b>
                                                        <ul class="record-list" id="record-list-{{ 'item-'.$ikey }}">
                                                            @if(isset($section->val->records) && !empty($section->val->records))
                                                                @foreach($section->val->records as $id => $record)
                                                                    <li id="crec-item-{{ $ikey.'-'.$id }}">
                                                                        {{ $record->title }}
                                                                        <a href="javascript:;" data-id="{{ 'item-'.$ikey }}" data-img="{{ isset($record->imgid)?$record->imgid:'' }}" data-imgsrc="{{ isset($record->imgsrc)?$record->imgsrc:'' }}" data-title="{{ $record->title }}" data-link="{{ $record->link }}" data-desc="{{ isset($record->desc)?$record->desc:'' }}" data-mode="edit" class="add-custom-record" title="edit">
                                                                            <i class="ri-pencil-line" aria-hidden="true"></i> </a><a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i>
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                        <a data-id="{{ 'item-'.$ikey }}" title="Add custom record" class="add-custom-record" href="javascript:;"><i class="ri-add-fill"></i>&nbsp;Add custom record</a>
                                                        <input id="{{ 'item-'.$ikey }}"  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}" @endif @if(isset($section->val->SubTitle)) data-subtitle="{{ $section->val->SubTitle }}" @endif @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}" @endif type="hidden" class="txtip customSection" value="{{ $section->val->title }}">
                                                    </div>
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
                                                            <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-button.png' }}" alt=""></i>
                                                        </div>
                                                        @elseif($section->val->alignment == 'button-rt-txt')
                                                        <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-button.png' }}" alt=""></i>
                                                        @elseif($section->val->alignment == 'button-center-txt')
                                                        <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-button.png' }}" alt=""></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <input id="{{ 'item-'.$ikey }}" data-caption="{{ $section->val->title }}" data-linktarget="{{ $section->val->target }}" data-type="{{ $section->val->alignment }}" type="hidden" class="txtip buttonclass" value="{{  $section->val->content }}"/>
                                                @endif
                                                @if($section->gentype == 'spacerdata')
                                                <div class="col-md-12"><label><b>Spacer Class</b></label></div>
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
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>

                                    <div class="clearfix"></div>
                                    <div class="section-item form-area" data-editor="{{ 'item-form'}}">
                                        <div class="col-md-12 col-xs-small "><span><i class="ri-file-text-line" aria-hidden="true"></i></span> {!! $section->val->content !!}</div>
                                        <input id="{{ 'item-form' }}"  data-id="{{ $section->val->id }}" type="hidden" class="txtip" value="{{ $section->val->content }}"/>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'twocontent')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
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
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
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
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
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
                                                        <input id="{{ 'item-'.$ikey }}" data-extra_class="{{ $section->val->extra_class }}" data-caption="{{ $section->val->title }}" data-type="{{ $section->val->alignment }}" data-width="{{ isset($section->val->data_width)?$section->val->data_width:'' }}" type="hidden" class="imgip" value="{{ $section->val->image }}"/>
                                                         @php if (method_exists($MyLibrary, 'GetFolderID')) {
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        } }
		                                                        @endphp
                                                    </div>
                                                </div>
                                            </div>
                                            @if(isset($section->val->title))
                                            <div class="title-img">
                                                <h3>{{ $section->val->title }}</h3>
                                            </div>
                                            @endif
                                            @if(isset($section->val->extra_class))
                                            <div class="extraClass-img">
                                                <h5>Extra Class: {{ $section->val->extra_class }}</h5>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-2 text-right" style="display:none">
                                            <div class="image-align-box">
                                                <h5 class="title">Preview</h5>
                                                @if($section->val->alignment == 'image-lft-txt')
                                                <div class="image-align-box">
                                                    <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-left.png' }}" alt=""></i>
                                                </div>
                                                @elseif($section->val->alignment == 'image-rt-txt')
                                                <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-right.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'image-center-txt')
                                                <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/image-center.png' }}" alt=""></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'image_gallery')
                                    <div id="section-container" class="ui-sortable">
                                       <div class="ui-state-default">
                                          <i title="Drag" class="action-icon move ri-arrow-left-right-line ui-sortable-handle"></i><a href="javascript:;" class="close-btn" title="Delete"><i class="action-icon delete ri-delete-bin-line"></i></a><a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="image-gallery hideclass"><i class="action-icon edit ri-pencil-line"></i></a>
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
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
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
                                            <div class="builder_doc_list" data-caption="{{(isset($section->val->caption) && !empty($section->val->caption)) ? $section->val->caption : ''}}" data-doc_date_time="{{(isset($section->val->doc_date_time) && !empty($section->val->doc_date_time)) ? $section->val->doc_date_time : ''}}">
                                                <ul>
                                                    @if(count($docObj) > 0)
                                                    @foreach($docObj as $value)
                                                    <li id="doc_{{ $value->id }}">
                                                        <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                            <img  src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/document_icon.png' }}" alt="Img" />
                                                        </span>
                                                        <span class="editdoctitle">{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}</span>
                                                    </li>
                                                    @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                            {{-- <input class="image_1 item-data imgip" type="hidden" id="photo_gallery1" data-type="document" name="img1" value="{{ $section->val->document }}"/> --}}
                                            <input id="{{ 'item-'.$ikey }}" type="hidden" class="imgip" data-caption="{{(isset($section->val->caption) && !empty($section->val->caption)) ? $section->val->caption : ''}}" data-doc_date_time="{{(isset($section->val->doc_date_time) && !empty($section->val->doc_date_time)) ? $section->val->doc_date_time : ''}}" data-type="document" value="{{ $section->val->document }}"/>
                                            <input type="hidden" id="dochiddenid" name='img1' value="{{ $section->val->document }}">
                                             @php if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                                    if(isset($section->val->document)){
                                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($section->val->document);
                                                                    @endphp
                                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                                    <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
                                                                    @endif
                                                                    @php
                                                                    } }
                                                                    @endphp
                                        </div>
                                        @endif
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'img_content')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
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
                                                         @php if (method_exists($MyLibrary, 'GetFolderID')) {
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        } }
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
                                                    <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-image.png' }}" alt=""></i>
                                                </div>
                                                @elseif($section->val->alignment == 'rt-txt')
                                                <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-image.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'top-txt')
                                                <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/top-image.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'bot-txt')
                                                <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/bottom-image.png' }}" alt=""></i>
                                                 @elseif($section->val->alignment == 'center-txt')
                                                <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-image.png' }}" alt=""></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'video_content')
                                @php
                                $vidIco = $section->val->videoType == 'Vimeo' ?
                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="ri-video-line" aria-hidden="true"></i></a>' :
                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="ri-youtube-line" aria-hidden="true"></i></a>';
                                @endphp
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
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
                                                    <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-video.png' }}" alt=""></i>
                                                </div>
                                                @elseif($section->val->alignment == 'rt-txt')
                                                <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-video.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'top-txt')
                                                <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/top-video.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'bot-txt')
                                                <i class="icon"><img title="Align Bottom" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/bottom-video.png' }}" alt=""></i>
                                                 @elseif($section->val->alignment == 'center-txt')
                                                <i class="icon"><img title="Align Center" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-video.png' }}" alt=""></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'home-img_content')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
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
                                                         @php if (method_exists($MyLibrary, 'GetFolderID')) {
		                                                        if(isset($section->val->image)){
		                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($section->val->image);
		                                                        @endphp
		                                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
		                                                        <input class="{{ 'folder_'.$ikey }}" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value="{{ $folderid->fk_folder }}"/>
		                                                        @endif
		                                                        @php
		                                                        } }
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
                                                    <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-left-image.png' }}" alt=""></i>
                                                </div>
                                                @elseif($section->val->alignment == 'home-rt-txt')
                                                <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-right-image.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'home-top-txt')
                                                <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/home-top-image.png' }}" alt=""></i>

                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'only_title')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="only-title hideclass">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="section-item defoult-module module titleOnly" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12">{!! $section->val->content !!}</div>
                                        <input id="{{ 'item-'.$ikey }}"  type="hidden" class="txtip" data-class="{{ (!empty($section->val->extclass)?$section->val->extclass:'') }}" data-headingtype="{{ (!empty($section->val->headingtype)?$section->val->headingtype:'') }}" value="{{  $section->val->content }}"/>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                                @elseif($section->type == 'conatct_info')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
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
                                @elseif($section->type == 'iframe')

                                <div class="ui-state-default">

                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>

                                    <a href="javascript:;" class="close-btn" title="Delete">

                                    <i class="action-icon delete ri-delete-bin-line"></i>

                                    </a>

                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="iframeonly">

                                    <i class="action-icon edit ri-pencil-line"></i>

                                    </a>

                                    <div class="clearfix"></div>

                                    <div class="section-item defoult-module module iframe" data-editor="{{ 'item-'.$ikey }}">

                                            <div class="col-md-12 col-xs-small "><label><b>Iframe</b></label><br/><iframe src="{{ $section->val->content }}" width="600" height="100" frameborder="0" style="border:0;" ></iframe></div>

                                            <input id="{{ 'item-'.$ikey }}" data-class="{{ $section->val->extclass }}" type="hidden" class="txtip" value="{{ $section->val->content }}"/>

                                            <div class="clearfix"></div>

                                    </div>

                                </div>

                                @elseif($section->type == 'button_info')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
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
                                                    <i class="icon"><img title="Align Left" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/left-button.png' }}" alt=""></i>
                                                </div>
                                                @elseif($section->val->alignment == 'button-rt-txt')
                                                <i class="icon"><img title="Align Right" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/right-button.png' }}" alt=""></i>
                                                @elseif($section->val->alignment == 'button-center-txt')
                                                <i class="icon"><img title="Align Top" height="45" width="50" src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/center-button.png' }}" alt=""></i>
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
                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://vimeo.com/'.$section->val->vidId.'"><i class="ri-video-line" aria-hidden="true"></i></a>' :
                                '<a title="'.$section->val->vidId.'" target="_blank" href="https://www.youtube.com/watch?v='.$section->val->vidId.'"><i class="ri-youtube-line" aria-hidden="true"></i></a>';
                                @endphp
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
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
                                @elseif($section->type == 'custom_section')
                                <div class="ui-state-default">
                                <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>
                                <a href="javascript:;" data-filter="custom-section" title="Edit" data-id="{{ 'item-'.$ikey }}" class="custom-section">
                                    <i class="action-icon edit ri-pencil-line"></i>
                                </a>
                                <div class="clearfix"></div>
                                <div class="defoult-module module section-item custom-section-module" data-editor="{{ 'item-'.$ikey }}">
                                    <div class="col-md-12">
                                        <label class="section-head"><b>{{ $section->val->title }}</b></label>
                                        <ul class="record-list" id="record-list-{{ 'item-'.$ikey }}">
                                            @foreach($section->val->records as $id=>$record)
                                            <li id="crec-item-{{ $ikey.'-'.$id }}">
                                                {{ $record->title }}
                                                <a href="javascript:;" data-id="{{ 'item-'.$ikey }}" data-img="{{ $record->imgid }}" data-imgsrc="{{ $record->imgsrc }}" data-title="{{ $record->title }}" data-link="{{ $record->link }}" data-desc="{{ $record->desc }}" data-mode="edit" class="add-custom-record" title="edit">
                                                    <i class="ri-pencil-line" aria-hidden="true"></i> </a><a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                        </ul>
                                        <a data-id="{{ 'item-'.$ikey }}" title="Add custom record" class="add-link add-custom-record" href="javascript:;"><i class="ri-add-fill"></i>&nbsp;Add custom record</a>
                                    </div>
                                        <input id="{{ 'item-'.$ikey }}"  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}" @endif  @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}" @endif @if(isset($section->val->SubTitle)) data-subtitle="{{ $section->val->SubTitle }}" @endif type="hidden" class="txtip" value="{{ $section->val->title }}">
                                    <div class="clearfix"></div>
                                </div>
                                </div>
                                @elseif($section->type == 'row_template')
                                <div class="ui-state-default" id="{{ 'item-'.$ikey }}">
                                    @if(isset($section->val) && !empty($section->val))
                                        <div class="row section-item row-template">
                                            <div class="col-sm-12">
                                                <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                                <a href="javascript:;" data-id="{{ 'item-'.$ikey }}" class="delete-row" title="Delete"><i class="action-icon delete ri-delete-bin-line row-delete"></i></a>
                                                <a href="javascript:;" data-id="{{ 'item-'.$ikey }}" class="edit-row" title="Edit"><i class="action-icon edit ri-pencil-line"></i></a>
                                                <div class="ui-new-section-add col-sm-12">
                                                    <div class="column-list clearfix" data-id="{{ 'item-'.$ikey }}-column-list">
                                                        @foreach($section->val as $vkey => $vval)
                                                            <div id="{{ 'item-'.$ikey }}-row-{{ $vkey + 1 }}" class="col-row">
                                                                <a href="javascript:;" data-id="{{ 'item-'.$ikey }}-row-{{ $vkey + 1 }}"  class="delete-col-row" title="Delete"><i class="action-icon delete ri-delete-bin-line"></i></a>
                                                                <a href="javascript:;" data-id="{{ 'item-'.$ikey }}-row-{{ $vkey + 1 }}"  class="edit-col-row" title="Edit"><i class="action-icon edit ri-pencil-line"></i></a>
                                                                @if(isset($vval->columns) && !empty($vval->columns))
                                                                    <div class="ui-new-section-add row col-sm-12">
                                                                        @foreach($vval->columns as $ckey => $cval)
                                                                            @php $class_divider = (12 / $vval->no_of_col) @endphp
                                                                            <div class="columns col-sm-{{ $class_divider }}" id="{{ 'item-'.$ikey }}-row-{{ $vkey + 1 }}-col-{{ $ckey + 1 }}">
                                                                                <a href="javascript:;" data-id="{{ 'item-'.$ikey }}-row-{{ $vkey + 1 }}-col-{{ $ckey + 1 }}" class="delete-column" title="Delete"><i class="action-icon delete ri-delete-bin-line"></i></a>
                                                                                <a href="javascript:;" data-editor="{{ 'item-'.$ikey }}-row-{{ $vkey + 1 }}-col-{{ $ckey + 1 }}" class="edit-column" title="Edit"><i class="action-icon edit ri-pencil-line"></i></a>
                                                                                <div class="clearfix"></div>
                                                                                <div class="ui-new-section-add {{ 'item-'.$ikey }}-row-{{ $vkey + 1 }}-col-{{ $ckey + 1 }}">
                                                                                    <strong>Column {{ $ckey + 1 }}</strong>
                                                                                    @if(isset($cval->elementObj) && !empty($cval->elementObj))
                                                                                        @if(isset($cval->elementObj->type) && !empty($cval->elementObj->type))
                                                                                              @include('visualcomposer::element-sections',['ikey' => $ikey, 'vkey' => $vkey, 'ckey' => $ckey, 'ekey' => 0, 'eval' => $cval->elementObj])
                                                                                        @else
                                                                                            @foreach($cval->elementObj as $ekey => $eval)
                                                                                                @include('visualcomposer::element-sections',['ikey' => $ikey, 'vkey' => $vkey, 'ckey' => $ckey, 'ekey' => $ekey, 'eval' => $eval])
                                                                                            @endforeach
                                                                                        @endif
                                                                                    @endif
                                                                                </div>
                                                                                <a href="javascript:;" data-id="{{ 'item-'.$ikey }}-row-{{ $vkey + 1 }}-col-{{ $ckey + 1 }}" class="add-icon add-cms-block"><i class="ri-add-fill" aria-hidden="true"></i></a>
                                                                                <input data-id="{{ 'item-'.$ikey }}-row-{{ $vkey + 1 }}-col-{{ $ckey + 1 }}" data-extclass="{{ $cval->column_class }}" data-animation="{{ isset($cval->animation)?$cval->animation:'' }}" type="hidden" value="{{  $vval->no_of_col }}"/>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <input data-id="{{ 'item-'.$ikey }}-row-{{ $vkey + 1 }}" data-extclass="{{ $vval->col_row_class }}" data-column-row-width="{{ isset($vval->column_row_width)?$vval->column_row_width:'' }}" data-animation="{{ isset($vval->col_row_animation)?$vval->col_row_animation:'' }}" type="hidden" value="{{ 'item-'.$ikey }}-row-{{ $vkey + 1 }}"/>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <a href="javascript:;" data-id="{{ 'item-'.$ikey }}" class="add-icon add-columns"><i class="ri-layout-column-line" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                            <input id="{{ 'item-'.$ikey }}-row" data-extclass="{{ isset($section->row_class)?$section->row_class:'' }}" data-animation="{{ isset($section->row_animation)?$section->row_animation:'' }}"  type="hidden" value="{{ 'item-'.$ikey }}-row"/>
                                        </div>
                                    @endif
                                </div>
                                @elseif($section->type == 'module')
                                    @php 
                                        $moduleRecord = ENV('APP_URL').'powerpanel/'.$section->val->module;
                                    @endphp

                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>

                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>

                                        <a href="javascript:;" title="Edit" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" data-id="{{ 'item-'.$ikey }}" class="{{ $section->val->module }}-module">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>

                                        <div class="clearfix"></div>
                                        <div class="section-item defoult-module module" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12">
                                                <label>
                                                    @if(isset($section->val->title) && $section->val->title != '')
                                                    <b>{{ $section->val->title }}</b>
                                                    @endif
                                                    @if(isset($section->val->config) && isset($sectionConfig[$section->val->config]))
                                                    ({{ $sectionConfig[$section->val->config] }})
                                                    @endif

                                                </label> <a class="" target="_blank" title="Manage Records" href="{{$moduleRecord}}">Manage Records</a>
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
                                                    empty($record->custom_fields->extraclass) &&
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
                                                        data-extraclass="@if(isset($record->custom_fields->extraclass)){{$record->custom_fields->extraclass}}@endif"
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
                                                    <i class="ri-add-fill"></i>&nbsp;Add more
                                                </a>
                                            </div>
                                            <input id="{{ 'item-'.$ikey }}" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" data-frest="{{ isset($section->val->featured_restaurant_section)?$section->val->featured_restaurant_section:'' }}"  data-template="{{ isset($section->val->template)?$section->val->template:'' }}" data-desc="{{ isset($section->val->desc)?$section->val->desc:'' }}" data-extraclass="{{ isset($section->val->extraclass)?$section->val->extraclass:'' }}" type="hidden" data-config="{{ isset($section->val->config)?$section->val->config:'' }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif @if(isset($section->val->title)) data-caption="{{ $section->val->title }}"@endif  data-type="module" value="{{ $section->val->module }}" />
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>

                                @elseif($section->type == 'business_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="business-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item businessTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'spacer_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" class="only-spacer">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item spacerTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>Spacer Class</b></label></div>
                                        <input id="{{ 'item-'.$ikey }}"  data-config="{{ $section->val->config }}"   type="hidden" class="txtip"/>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'events_template')
                                    @php 
                                        $eventsRecord = ENV('APP_URL').'powerpanel/events';
                                        if(isset($section->val->eventscat) && !empty($section->val->eventscat)) {
                                            $eventsRecord = ENV('APP_URL').'powerpanel/events?category='.$section->val->eventscat;
                                        }
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="events-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item eventsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$eventsRecord}}">Manage Records</a></div>
                                           @php
                                            if(isset($section->val->categoryname) && $section->val->categoryname != '' && $section->val->categoryname != 'undefined'){@endphp 
                                            <div class="col-md-12"><b>Category:</b>{{ ucfirst($section->val->categoryname) }}</div>
                                         @php
                                         }
                                         if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php
                                         }
                                         @endphp    
                                            <input id="{{ 'item-'.$ikey }}" module="events" data-type="{{ $section->val->template }}" @if(isset($section->val->categoryname)) data-cname="{{ $section->val->categoryname }}"@endif  @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif   @if(isset($section->val->eventscat)) data-eventscat="{{ $section->val->eventscat }}"@endif  @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                @elseif($section->type == 'career_template')
                                    @php 
                                        $careersRecord = ENV('APP_URL').'powerpanel/careers'; 
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="career-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item careerTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12">
                                                <label><b>{{ $section->val->title }}</b></label> 
                                                <a target="_blank" title="Manage Records" href="{{$careersRecord}}">(Manage Records)</a>
                                            </div>
                                            @if(isset($section->val->desc) && $section->val->desc != ''  && $section->val->desc != 'undefined')
                                                <div class="col-md-12"><b>Description:</b>{{ $section->val->desc }}</div>
                                            @endif
                                            <br/>
                                            @if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined')
                                                <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                            @endif
                                            <input id="{{ 'item-'.$ikey }}" module="events" @if(isset($section->val->extclass)) data-class="{{ $section->val->extclass }}" @endif data-type="{{ $section->val->template }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
								@elseif($section->type == 'product_template')

									<div class="ui-state-default">

										<i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>

										<a href="javascript:;" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" class="close-btn" title="Delete">

										<i class="action-icon delete ri-delete-bin-line"></i>

										</a>

										<a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="product-template">

										<i class="action-icon edit ri-pencil-line"></i>

										</a>

										<div class="clearfix"></div>

										<div class="defoult-module section-item productTemplate" data-editor="{{ 'item-'.$ikey }}">

												<div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>

												<input id="{{ 'item-'.$ikey }}" @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}" @endif data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>

												<div class="clearfix"></div>

										</div>

									</div>

                                    @elseif($section->type == 'project_template')

                                    <div class="ui-state-default">

                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>

                                        <a href="javascript:;" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" class="close-btn" title="Delete">

                                        <i class="action-icon delete ri-delete-bin-line"></i>

                                        </a>

                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="project-template">

                                        <i class="action-icon edit ri-pencil-line"></i>

                                        </a>

                                        <div class="clearfix"></div>

                                        <div class="defoult-module section-item projectTemplate" data-editor="{{ 'item-'.$ikey }}">

                                                <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>

                                                <input id="{{ 'item-'.$ikey }}" @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}" @endif data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>

                                                <div class="clearfix"></div>

                                        </div>

                                    </div>

                                     @elseif($section->type == 'client_template')

                                    <div class="ui-state-default">

                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>

                                        <a href="javascript:;" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" class="close-btn" title="Delete">

                                        <i class="action-icon delete ri-delete-bin-line"></i>

                                        </a>

                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="client-template">

                                        <i class="action-icon edit ri-pencil-line"></i>

                                        </a>

                                        <div class="clearfix"></div>

                                        <div class="defoult-module section-item clientTemplate" data-editor="{{ 'item-'.$ikey }}">

                                                <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>

                                                <input id="{{ 'item-'.$ikey }}" @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}" @endif data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>

                                                <div class="clearfix"></div>

                                        </div>

                                    </div>

								@elseif($section->type == 'career_template')

									<div class="ui-state-default">

										<i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>

										<a href="javascript:;" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" class="close-btn" title="Delete">

										<i class="action-icon delete ri-delete-bin-line"></i>

										</a>

										<a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="career-template">

										<i class="action-icon edit ri-pencil-line"></i>

										</a>

										<div class="clearfix"></div>

										<div class="defoult-module section-item careerTemplate" data-editor="{{ 'item-'.$ikey }}">

												<div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>

												<input id="{{ 'item-'.$ikey }}" @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}" @endif data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>

												<div class="clearfix"></div>

										</div>

									</div>

								@elseif($section->type == 'testimonial_template')

									<div class="ui-state-default">

										<i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>

										<a href="javascript:;" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" class="close-btn" title="Delete">

										<i class="action-icon delete ri-delete-bin-line"></i>

										</a>

										<a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="testimonial-template">

										<i class="action-icon edit ri-pencil-line"></i>

										</a>

										<div class="clearfix"></div>

										<div class="defoult-module section-item testimonialTemplate" data-editor="{{ 'item-'.$ikey }}">

												<div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>

												<input id="{{ 'item-'.$ikey }}" @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}" @endif data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>

												<div class="clearfix"></div>

										</div>

									</div>

								@elseif($section->type == 'team_template')
                                    @php 
                                        $teamRecord = ENV('APP_URL').'powerpanel/team';
                                        
                                    @endphp
									<div class="ui-state-default">

										<i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>

										<a href="javascript:;" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" class="close-btn" title="Delete">

										<i class="action-icon delete ri-delete-bin-line"></i>

										</a>

										<a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="team-template">

										<i class="action-icon edit ri-pencil-line"></i>

										</a>

										<div class="clearfix"></div>

										<div class="defoult-module section-item teamTemplate" data-editor="{{ 'item-'.$ikey }}">

												<div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$teamRecord}}">Manage Records</a> <ul><li>Template: {{ $section->val->template }}</li></ul></div>

												<input id="{{ 'item-'.$ikey }}" @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}" @endif data-type="{{ $section->val->template }}"  module="team" type="hidden" class="txtip" value="{{ $section->val->title }}"/>

												<div class="clearfix"></div>

										</div>

									</div>

								@elseif($section->type == 'show_template')

									<div class="ui-state-default">

										<i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>

										<a href="javascript:;" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" class="close-btn" title="Delete">

										<i class="action-icon delete ri-delete-bin-line"></i>

										</a>

										<a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="show-template">

										<i class="action-icon edit ri-pencil-line"></i>

										</a>

										<div class="clearfix"></div>

										<div class="defoult-module section-item showTemplate" data-editor="{{ 'item-'.$ikey }}">

												<div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>

												<input id="{{ 'item-'.$ikey }}" @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}" @endif data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>

												<div class="clearfix"></div>

										</div>

									</div>

								@elseif($section->type == 'gallery_template')

									<div class="ui-state-default">

										<i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>

										<a href="javascript:;" data-filter="{{ isset($section->val->template)?$section->val->template:'' }}" class="close-btn" title="Delete">

										<i class="action-icon delete ri-delete-bin-line"></i>

										</a>

										<a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="gallery-template">

										<i class="action-icon edit ri-pencil-line"></i>

										</a>

										<div class="clearfix"></div>

										<div class="defoult-module section-item galleryTemplate" data-editor="{{ 'item-'.$ikey }}">

												<div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>

												<input id="{{ 'item-'.$ikey }}" @if(isset($section->val->extclass)) data-extclass="{{ $section->val->extclass }}" @endif data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>

												<div class="clearfix"></div>

										</div>

									</div>

                                @elseif($section->type == 'news_template')
                                    @php 
                                        $newsRecord = ENV('APP_URL').'powerpanel/news';
                                        if(isset($section->val->newscat) && !empty($section->val->newscat)) {
                                            $newsRecord = ENV('APP_URL').'powerpanel/news?category='.$section->val->newscat;
                                        }
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="news-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item newsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$newsRecord}}">Manage Records</a> </div>
                                            @php
                                       
                                       
                                        if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php }  @endphp
                                            <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}"  @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif      @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif module="news" type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>
                                @elseif($section->type == 'latest_news_template')
                                    @php 
                                        $latestNewsRecord = ENV('APP_URL').'powerpanel/news';
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="latest-news-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item latestNewsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12">
                                                <label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$latestNewsRecord}}">Manage Records</a>
                                            </div>
                                            <input id="{{ 'item-'.$ikey }}" module="news" data-type="{{ $section->val->template }}" @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                @elseif($section->type == 'publicRecord_template')
                                        @php 
                                            $manageRecord = ENV('APP_URL').'powerpanel/public-record';
                                            if(isset($section->val->newscat) && !empty($section->val->newscat)) {
                                                $manageRecord = ENV('APP_URL').'powerpanel/public-record?category='.$section->val->newscat;
                                            }
                                        @endphp
                                        <div class="ui-state-default">
                                            <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                            <a href="javascript:;" class="close-btn" title="Delete">
                                                <i class="action-icon delete ri-delete-bin-line"></i>
                                            </a>
                                            <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="public-record-template">
                                                <i class="action-icon edit ri-pencil-line"></i>
                                            </a>
                                            <div class="clearfix"></div>
                                            <div class="defoult-module section-item publicRecordTemplate" data-editor="{{ 'item-'.$ikey }}">
                                                <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$manageRecord}}">Manage Records</a></div>
                                                @php
                                       
                                  
                                        if(isset($section->val->categoryname) && $section->val->categoryname != '' && $section->val->categoryname != 'undefined'){@endphp 
                                            <div class="col-md-12"><b>Category:</b>{{ ucfirst($section->val->categoryname) }}</div>
                                         @php
                                         }
                                         if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php
                                         }
                                         @endphp
                                                <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif @if(isset($section->val->categoryname)) data-cname="{{ $section->val->categoryname }}"@endif    @if(isset($section->val->newscat)) data-newscat="{{ $section->val->newscat }}"@endif  @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif module="public-record" type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                    <div class="clearfix"></div>
                                            </div>
                                        </div>
                                @elseif($section->type == 'quick_link_template')
                                    @php 
                                        $quickLinksRecord = ENV('APP_URL').'powerpanel/quick-links';                                        
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="quick-links-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item QuickLinkTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$quickLinksRecord}}">Manage Records</a></div>
                                            @php
                                        if(isset($section->val->sdate) && $section->val->sdate != '' && $section->val->sdate != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Start Date:</b>{{ $section->val->sdate }}</div>
                                        @php }
                                        if(isset($section->val->edate) && $section->val->edate != '' && $section->val->edate != 'undefined'){@endphp
                                        <div class="col-md-12"><b>End Date:</b>{{ $section->val->edate }}</div>
                                        @php }@endphp
                                            <input module="quick-links" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif @if(isset($section->val->sdate)) data-sdate="{{ $section->val->sdate }}"@endif @if(isset($section->val->edate)) data-edate="{{ $section->val->edate }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                @elseif($section->type == 'photoalbum_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="photoalbum-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item photoalbumTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label></div>
                                          @php
					                 if(isset($section->val->sdate) && $section->val->sdate != '' && $section->val->sdate != 'undefined'){@endphp
					                <div class="col-md-12"><b>Start Date:</b>{{ $section->val->sdate }}</div>
					                @php }
					                if(isset($section->val->edate) && $section->val->edate != '' && $section->val->edate != 'undefined'){@endphp
					                <div class="col-md-12"><b>End Date:</b>{{ $section->val->edate }}</div>
					                @php }
					                if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
					                <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
					                @php } if(isset($section->val->desc) && $section->val->desc != ''  && $section->val->desc != 'undefined'){@endphp
					                <div class="col-md-12"><b>Description:</b>{{ $section->val->desc }}</div>
					                @php }  @endphp
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif  @if(isset($section->val->sdate)) data-sdate="{{ $section->val->sdate }}"@endif @if(isset($section->val->edate)) data-edate="{{ $section->val->edate }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'videoalbum_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="videogallery-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item videoalbumTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label></div>
                                         @php
					                 if(isset($section->val->sdate) && $section->val->sdate != '' && $section->val->sdate != 'undefined'){@endphp
					                <div class="col-md-12"><b>Start Date:</b>{{ $section->val->sdate }}</div>
					                @php }
					                if(isset($section->val->edate) && $section->val->edate != '' && $section->val->edate != 'undefined'){@endphp
					                <div class="col-md-12"><b>End Date:</b>{{ $section->val->edate }}</div>
					                @php }
					                if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
					                <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
					                @php } if(isset($section->val->desc) && $section->val->desc != ''  && $section->val->desc != 'undefined'){@endphp
					                <div class="col-md-12"><b>Description:</b>{{ $section->val->desc }}</div>
					                @php }  @endphp
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif  @if(isset($section->val->sdate)) data-sdate="{{ $section->val->sdate }}"@endif @if(isset($section->val->edate)) data-edate="{{ $section->val->edate }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'organizations_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="organizations">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item organizationsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label></div>
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" @if(isset($section->val->parentorg)) data-parentorg="{{ $section->val->parentorg }}"@endif  @if(isset($section->val->orgclass)) data-orgclass="{{ $section->val->orgclass }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'interconnections_template')
                                    @php 
                                        $interconnectionRecord = ENV('APP_URL').'powerpanel/interconnections';
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="interconnections">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item interconnectionsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><a class="" target="_blank" title="Manage Records" href="{{$interconnectionRecord}}">Manage Records</a></div>
                                            @php
                                            if(isset($section->val->categoryname) && $section->val->categoryname != '' && $section->val->categoryname != 'undefined'){@endphp
                                            <div class="col-md-12"><b>Category:</b>{{ ucfirst($section->val->categoryname) }}</div>
                                           @php }
                                            @endphp
                                            @if(isset($section->val->sector) && $section->val->sector != '' && $section->val->sector != 'undefined') 
                                            <div class="col-md-12"><b>Sector:</b>{{ strtoupper($section->val->sector) }}</div>
                                            @endif
                                            <input module="interconnections" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" @if(isset($section->val->categoryname)) data-cname="{{ $section->val->categoryname }}"@endif @if(isset($section->val->sector)) data-sector="{{ $section->val->sector }}"@endif  @if(isset($section->val->parentorg)) data-parentorg="{{ $section->val->parentorg }}"@endif  @if(isset($section->val->orgclass)) data-orgclass="{{ $section->val->orgclass }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>
                                @elseif($section->type == 'alerts_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="alerts-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item alertsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label></div>
                                      @php  if($section->val->alerttype == '1'){
					                   $type = 'High';
					                }else if($section->val->alerttype == '2'){
					                    $type = 'Medium';
					                }else if($section->val->alerttype == '3'){
					                    $type = 'Low';
					                }else{
					                    $type = '';
					                }
					                @endphp
					               @php  if($section->val->alerttype != ''){ @endphp
					               <div class="col-md-12"><b>Alert Type:</b>{{ $type }}</div>
					                @php  }
					                 if(isset($section->val->sdate) && $section->val->sdate != '' && $section->val->sdate != 'undefined'){@endphp
					                <div class="col-md-12"><b>Start Date:</b>{{ $section->val->sdate }}</div>
					                @php }
					                if(isset($section->val->edate) && $section->val->edate != '' && $section->val->edate != 'undefined'){@endphp
					                <div class="col-md-12"><b>End Date:</b>{{ $section->val->edate }}</div>
					                @php }
					                if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
					                <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
					                @php }@endphp
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif @if(isset($section->val->alerttype)) data-alerttype="{{ $section->val->alerttype }}"@endif @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif @if(isset($section->val->sdate)) data-sdate="{{ $section->val->sdate }}"@endif @if(isset($section->val->edate)) data-edate="{{ $section->val->edate }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'department_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="department-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item departmentTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label></div>
                                        @php
					                 if(isset($section->val->sdate) && $section->val->sdate != '' && $section->val->sdate != 'undefined'){@endphp
					                <div class="col-md-12"><b>Start Date:</b>{{ $section->val->sdate }}</div>
					                @php }
					                if(isset($section->val->edate) && $section->val->edate != '' && $section->val->edate != 'undefined'){@endphp
					                <div class="col-md-12"><b>End Date:</b>{{ $section->val->edate }}</div>
					                @php }
					                if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
					                <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
					                @php }@endphp
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif  @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif @if(isset($section->val->sdate)) data-sdate="{{ $section->val->sdate }}"@endif @if(isset($section->val->edate)) data-edate="{{ $section->val->edate }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                 @elseif($section->type == 'link_template')
                                    @php 
                                        $linkRecord = ENV('APP_URL').'powerpanel/links-category';
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="links-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item linkTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$linkRecord}}">Manage Records</a> </div>
                                            @php
                                        if(isset($section->val->sdate) && $section->val->sdate != '' && $section->val->sdate != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Start Date:</b>{{ $section->val->sdate }}</div>
                                        @php }
                                        if(isset($section->val->edate) && $section->val->edate != '' && $section->val->edate != 'undefined'){@endphp
                                        <div class="col-md-12"><b>End Date:</b>{{ $section->val->edate }}</div>
                                        @php }
                                        if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php }@endphp
                                            <input module="links-category" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif   @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif @if(isset($section->val->linkcat)) data-linkcat="{{ $section->val->linkcat }}"@endif @if(isset($section->val->sdate)) data-sdate="{{ $section->val->sdate }}"@endif @if(isset($section->val->edate)) data-edate="{{ $section->val->edate }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>
                                 @elseif($section->type == 'faq_template')
                                    @php 
                                        $faqRecord = ENV('APP_URL').'powerpanel/faq';
                                        if(isset($section->val->faqcat) && !empty($section->val->faqcat)) {
                                            $faqRecord = ENV('APP_URL').'powerpanel/faq?category='.$section->val->faqcat;
                                        }
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="faqs-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item faqTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$faqRecord}}">Manage Records</a> </div>
                                                @php
                                                if(isset($section->val->categoryname) && $section->val->categoryname != '' && $section->val->categoryname != 'undefined'){@endphp 
                                            <div class="col-md-12"><b>Category:</b>{{ ucfirst($section->val->categoryname) }}</div>
                                            @php }
                                            if(isset($section->val->sdate) && $section->val->sdate != '' && $section->val->sdate != 'undefined'){@endphp
                                            <div class="col-md-12"><b>Start Date:</b>{{ $section->val->sdate }}</div>
                                            @php }
                                            if(isset($section->val->edate) && $section->val->edate != '' && $section->val->edate != 'undefined'){@endphp
                                            <div class="col-md-12"><b>End Date:</b>{{ $section->val->edate }}</div>
                                            @php }
                                            if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                            <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                            @php }@endphp
                                                <input module="faq" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" @if(isset($section->val->categoryname)) data-cname="{{ $section->val->categoryname }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif   @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif @if(isset($section->val->faqcat)) data-faqcat="{{ $section->val->faqcat }}"@endif @if(isset($section->val->sdate)) data-sdate="{{ $section->val->sdate }}"@endif @if(isset($section->val->edate)) data-edate="{{ $section->val->edate }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                    <div class="clearfix"></div>
                                            </div>
                                    </div>
                                 @elseif($section->type == 'blogs_template')
                                    @php 
                                        $blogRecordURL = ENV('APP_URL').'powerpanel/blogs';
                                        if(isset($section->val->blogscat) && !empty($section->val->blogscat)) {
                                            $blogRecordURL = ENV('APP_URL').'powerpanel/blogs?category='.$section->val->blogscat;
                                        }
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="blogs-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item blogsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$blogRecordURL}}">Manage Records</a> </div>
                                        @php
                                        if(isset($section->val->sdate) && $section->val->sdate != '' && $section->val->sdate != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Start Date:</b>{{ $section->val->sdate }}</div>
                                        @php }
                                        if(isset($section->val->edate) && $section->val->edate != '' && $section->val->edate != 'undefined'){@endphp
                                        <div class="col-md-12"><b>End Date:</b>{{ $section->val->edate }}</div>
                                        @php }
                                        if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php } if(isset($section->val->desc) && $section->val->desc != ''  && $section->val->desc != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Description:</b>{{ $section->val->desc }}</div>
                                        @php }  @endphp
                                            <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif  @if(isset($section->val->blogscat)) data-blogscat="{{ $section->val->blogscat }}"@endif @if(isset($section->val->sdate)) data-sdate="{{ $section->val->sdate }}"@endif @if(isset($section->val->edate)) data-edate="{{ $section->val->edate }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif module="blogs" type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>
                                @elseif($section->type == 'numberAllocations_template')
                                    @php 
                                        $numberAllocationsRecord = ENV('APP_URL').'powerpanel/number-allocation';
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="number-allocations">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item numberAllocationsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><a class="" target="_blank" title="Manage Records" href="{{$numberAllocationsRecord}}">Manage Records</a></div>
                                        @php
                                        if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php } if(isset($section->val->desc) && $section->val->desc != ''  && $section->val->desc != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Description:</b>{{ $section->val->desc }}</div>
                                        @php }  @endphp
                                            <input module="number-allocation" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>
                                @elseif($section->type == 'service_template')
                                    @php 
                                        $serviceRecord = ENV('APP_URL').'powerpanel/service';
                                        if(isset($section->val->servicecat) && !empty($section->val->servicecat)) {
                                            $serviceRecord = ENV('APP_URL').'powerpanel/service?category='.$section->val->servicecat;
                                        }
                                    @endphp
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="service-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item serviceTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><a class="" target="_blank" title="Manage Records" href="{{$serviceRecord}}">Manage Records</a></div>

					                @php
					                if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
					                <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
					                @php } if(isset($section->val->desc) && $section->val->desc != ''  && $section->val->desc != 'undefined'){@endphp
					                <div class="col-md-12"><b>Description:</b>{{ $section->val->desc }}</div>
					                @php }  @endphp
                                        <input module="service" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif @if(isset($section->val->servicecat)) data-servicecat="{{ $section->val->servicecat }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>
                                @elseif($section->type == 'candwservice_template')
                                    @php 
                                        $candwRecord = ENV('APP_URL').'powerpanel/candwservice';
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="candwservice-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item candwserviceTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><a class="" target="_blank" title="Manage Records" href="{{$candwRecord}}">Manage Records</a></div>
                                        @php

                                        if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>

                                        @php }  @endphp
                                            <input module="candwservice" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}"  @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif   @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>
                                @elseif($section->type == 'consultations_template')
                                @php 
                                    $consultationsRecord = ENV('APP_URL').'powerpanel/consultations';
                                    if(isset($section->val->blogscat) && !empty($section->val->blogscat)) {
                                        $consultationsRecord = ENV('APP_URL').'powerpanel/consultations?category='.$section->val->blogscat;
                                    }
                                @endphp
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="consultations-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item consultationsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><a class="" target="_blank" title="Manage Records" href="{{$consultationsRecord}}">Manage Records</a></div>
                                       @php
					                 if(isset($section->val->sdate) && $section->val->sdate != '' && $section->val->sdate != 'undefined'){@endphp
					                <div class="col-md-12"><b>Start Date:</b>{{ $section->val->sdate }}</div>
					                @php }
					                if(isset($section->val->edate) && $section->val->edate != '' && $section->val->edate != 'undefined'){@endphp
					                <div class="col-md-12"><b>End Date:</b>{{ $section->val->edate }}</div>
					                @php }
					                if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
					                <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
					                @php } if(isset($section->val->desc) && $section->val->desc != ''  && $section->val->desc != 'undefined'){@endphp
					                <div class="col-md-12"><b>Description:</b>{{ $section->val->desc }}</div>
					                @php }  @endphp
                                        <input module="consultations" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif  @if(isset($section->val->blogscat)) data-blogscat="{{ $section->val->blogscat }}"@endif @if(isset($section->val->sdate)) data-sdate="{{ $section->val->sdate }}"@endif @if(isset($section->val->edate)) data-edate="{{ $section->val->edate }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                               <div class="clearfix"></div>
                                    </div>
                                </div>



                                @elseif($section->type == 'complaintservices_template')
                                    @php 
                                        $complaintServiceRecord = ENV('APP_URL').'powerpanel/complaint-services';
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="complaint-services-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item complaintservicesTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$complaintServiceRecord}}">Manage Records</a> </div>

                                        @php
                                        if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php } if(isset($section->val->desc) && $section->val->desc != ''  && $section->val->desc != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Description:</b>{{ $section->val->desc }}</div>
                                        @php }  @endphp
                                            <input module="complaint-services" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>

                                @elseif($section->type == 'fmbroadcasting_template')
                                    @php 
                                        $fmBroadcastingRecord = ENV('APP_URL').'powerpanel/fmbroadcasting';
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="fmbroadcasting-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item fmbroadcastingTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$fmBroadcastingRecord}}">Manage Records</a> </div>

                                        @php
                                        if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php } if(isset($section->val->desc) && $section->val->desc != ''  && $section->val->desc != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Description:</b>{{ $section->val->desc }}</div>
                                        @php }  @endphp
                                            <input module="fmbroadcasting" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>

                                @elseif($section->type == 'boardofdirectors_template')
                                    @php 
                                        $bodRecord = ENV('APP_URL').'powerpanel/boardofdirectors';
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="boardofdirectors-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item boardofdirectorsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$bodRecord}}">Manage Records</a> </div>

                                        @php
                                        if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php } if(isset($section->val->desc) && $section->val->desc != ''  && $section->val->desc != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Description:</b>{{ $section->val->desc }}</div>
                                        @php }  @endphp
                                            <input module="boardofdirectors" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>
                                @elseif($section->type == 'registerapplication_template')
                                    @php 
                                        $registerApplicationRecord = ENV('APP_URL').'powerpanel/register-application';
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}"  class="register-application-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item registerapplicationTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><a class="" target="_blank" title="Manage Records" href="{{$registerApplicationRecord}}">Manage Records</a></div>

                                        @php
                                        if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php } if(isset($section->val->desc) && $section->val->desc != ''  && $section->val->desc != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Description:</b>{{ $section->val->desc }}</div>
                                        @php }  @endphp
                                            <input module="register-application" id="{{ 'item-'.$ikey }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>

                                @elseif($section->type == 'formsandfees_template')
                                    @php 
                                        $formsFeesRecord = ENV('APP_URL').'powerpanel/forms-and-fees';
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="forms-and-fees-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item formsandfeesTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><a class="" target="_blank" title="Manage Records" href="{{$formsFeesRecord}}">Manage Records</a></div>

                                        @php
                                        if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php } if(isset($section->val->desc) && $section->val->desc != ''  && $section->val->desc != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Description:</b>{{ $section->val->desc }}</div>
                                        @php }  @endphp
                                            <input module="forms-and-fees" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>
                                 @elseif($section->type == 'licenceregister_template')
                                    @php 
                                        $licenceRegisterRecord = ENV('APP_URL').'powerpanel/licence-register';
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="licence-register-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item licenceregisterTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$licenceRegisterRecord}}">Manage Records</a></div>

                                        @php
                                        if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php } if(isset($section->val->sector) && $section->val->sector != ''  && $section->val->sector != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Sector:</b>{{ $section->val->sector }}</div>
                                        @php }  @endphp
                                            <input module="licence-register" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-sector="{{ $section->val->sector }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>

                                @elseif($section->type == 'publication_template')
                                    @php 
                                        $publicationRecord = ENV('APP_URL').'powerpanel/publications';
                                        if(isset($section->val->publicationscat) && !empty($section->val->publicationscat)) {
                                            $publicationRecord = ENV('APP_URL').'powerpanel/publications?category='.$section->val->publicationscat;
                                        }
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="publication-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item publicationTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label> <a class="" target="_blank" title="Manage Records" href="{{$publicationRecord}}">Manage Records</a> </div>
                                            @php
                                        if(isset($section->val->sector) && $section->val->sector != '' && $section->val->sector != 'undefined'){@endphp 
                                            <div class="col-md-12"><b>Sector:</b>{{ strtoupper($section->val->sector) }}</div>    
                                        @php }
                                  
                                        if(isset($section->val->categoryname) && $section->val->categoryname != '' && $section->val->categoryname != 'undefined'){@endphp 
                                            <div class="col-md-12"><b>Category:</b>{{ ucfirst($section->val->categoryname) }}</div>
                                         @php
                                         }
                                         if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php
                                         }
                                         @endphp
                                            <input module="publications" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->categoryname)) data-cname="{{ $section->val->categoryname }}"@endif   @if(isset($section->val->sector)) data-sector="{{ $section->val->sector }}"@endif   @if(isset($section->val->publicationscat)) data-publicationscat="{{ $section->val->publicationscat }}"@endif  @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif  type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>
                                @elseif($section->type == 'decision_template')
                                    @php 
                                        $decisionRecord = ENV('APP_URL').'powerpanel/decision';
                                        if(isset($section->val->decisioncat) && !empty($section->val->decisioncat)) {
                                            $decisionRecord = ENV('APP_URL').'powerpanel/decision?category='.$section->val->decisioncat;
                                        }
                                    @endphp
                                    <div class="ui-state-default">
                                        <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                        <a href="javascript:;" class="close-btn" title="Delete">
                                            <i class="action-icon delete ri-delete-bin-line"></i>
                                        </a>
                                        <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="decision-template">
                                            <i class="action-icon edit ri-pencil-line"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                        <div class="defoult-module section-item decisionTemplate" data-editor="{{ 'item-'.$ikey }}">
                                            <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><a class="" target="_blank" title="Manage Records" href="{{$decisionRecord}}">Manage Records</a></div>
                                            @php
                                        if(isset($section->val->sector) && $section->val->sector != '' && $section->val->sector != 'undefined'){@endphp 
                                            <div class="col-md-12"><b>Sector:</b>{{ strtoupper($section->val->sector) }}</div>    
                                        @php }
                                  
                                        if(isset($section->val->categoryname) && $section->val->categoryname != '' && $section->val->categoryname != 'undefined'){@endphp 
                                            <div class="col-md-12"><b>Category:</b>{{ ucfirst($section->val->categoryname) }}</div>
                                         @php
                                         }
                                         if(isset($section->val->limit) && $section->val->limit != '' && $section->val->limit != 'undefined'){@endphp
                                        <div class="col-md-12"><b>Limit:</b>{{ $section->val->limit }}</div>
                                        @php
                                         }
                                         @endphp
                                            <input module="decision" id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->limit)) data-slimit="{{ $section->val->limit }}"@endif  @if(isset($section->val->categoryname)) data-cname="{{ $section->val->categoryname }}"@endif @if(isset($section->val->desc)) data-sdesc="{{ $section->val->desc }}"@endif  @if(isset($section->val->sector)) data-sector="{{ $section->val->sector }}"@endif   @if(isset($section->val->decisioncat)) data-decisioncat="{{ $section->val->decisioncat }}"@endif  @if(isset($section->val->class)) data-class="{{ $section->val->class }}"@endif  type="hidden" class="txtip" value="{{ $section->val->title }}"/>
                                                <div class="clearfix"></div>
                                        </div>
                                    </div>
                                @elseif($section->type == 'promotions_template')
                                <div class="ui-state-default">
                                    <i title="Drag" class="action-icon move ri-arrow-left-right-line"></i>
                                    <a href="javascript:;" class="close-btn" title="Delete">
                                        <i class="action-icon delete ri-delete-bin-line"></i>
                                    </a>
                                    <a href="javascript:;" title="Edit" data-id="{{ 'item-'.$ikey }}" data-filter="{{ $section->val->template }}" class="promotions-template">
                                        <i class="action-icon edit ri-pencil-line"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <div class="defoult-module section-item promotionsTemplate" data-editor="{{ 'item-'.$ikey }}">
                                        <div class="col-md-12"><label><b>{{ $section->val->title }}</b></label><ul><li>Template: {{ $section->val->template }}</li></ul></div>
                                        <input id="{{ 'item-'.$ikey }}" data-type="{{ $section->val->template }}" data-config="{{ $section->val->config }}" @if(isset($section->val->layout)) data-layout="{{ $section->val->layout }}"@endif type="hidden" class="txtip" value="{{ $section->val->title }}"/>
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
                                <a href="javascript:void(0);" class="add-icon add-element">
                                    <i class="ri-add-fill" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    </section>
                @else
                    {{-- <label class="control-label form-label ">Page Content</label> --}}
                    <h4 class="form-section mb-3">Page Content</h4>
                    <section class="powercomposer" id="no-content">
                        <!-- <div class="title form-label">PAGE CONTENT</div> -->
                        <div class="composerbody">
                            <div class="text-block text-center">
                                <div class="icon">
                                    <img src="{{ $CDN_PATH.'assets/images/packages/visualcomposer/composer-icon.png' }}" alt="">
                                </div>
                                <div class="composer-title">Welcome to Blank Page, You Have No Content Yet! <br /> <strong>Add Some Content or Use Predefined Layouts...</strong></div>
                            </div>
                            <div class="button-block text-center">
                                <a href="javascript:;" class="btn btn-primary add-element bg-gradient waves-effect waves-light btn-label">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-add-fill label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">Add Element</div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="btn btn-success only-content text-block bg-gradient waves-effect waves-light btn-label ms-1" id="add-text-block">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-file-edit-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">Add Text Block</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </section>

                    <section class="builder powercomposer d-none" id="has-content">
                        <input type="hidden" name="section" id="builderObj" />
                        <div class="portlet light portlet_light menuBody overflow_visible page_builder movable-section">
                            <div class="portlet-body">
                                <div class="">
                                    <div class="padding_right_set">
                                        <div id="section-container" class="builder-append-data"></div>
                                        <div class="ui-new-section-add add-element">
                                            <a href="javascript:void(0);" class="add-icon add-element">
                                                <i class="ri-add-fill" aria-hidden="true"></i>
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
