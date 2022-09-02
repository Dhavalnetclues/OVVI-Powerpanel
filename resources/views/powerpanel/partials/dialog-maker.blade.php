<!-- Modal -->
<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup" id="pgBuiderSections" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmPageComponantData']) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Add Elements</b></h5>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active" data-tabing="all">
                                <a href="#all" title="All" class="all_tab" data-toggle="tab" aria-controls="all" role="all" aria-expanded="false">
                                    <span class="tab_text">All</span>
                                </a>
                                <div class="clearfix"></div>
                            </li>
                            <li role="presentation" data-tabing="blocks" class="">
                                <a href="#blocks" title="Blocks" data-toggle="tab" aria-controls="blocks" role="tab" aria-expanded="true">
                                    <span class="tab_text">Blocks</span>
                                </a>
                            </li>
                            <li role="presentation" data-tabing="partition" class="partition_tab">
                                <a href="#partition" title="Partition" data-toggle="tab" aria-controls="partition" role="tab" aria-expanded="true">
                                    <span class="tab_text">Partition</span>
                                </a>
                            </li>
                            @if(Auth::user()->can('news-list'))
                            <li role="presentation" data-tabing="news" class="news_tab">
                                <a href="#news" title="News" data-toggle="tab" aria-controls="news" role="tab" aria-expanded="true">
                                    <span class="tab_text">News</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->can('blogs-list'))
                            <li role="presentation" data-tabing="blog"  class="blog_tab">
                                <a href="#blog" title="Blogs" data-toggle="tab" aria-controls="blog" role="tab" aria-expanded="true">
                                    <span class="tab_text">Blogs</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->can('events-list'))
                            <li role="presentation" data-tabing="events"  class="event_tab">
                                <a href="#events" title="Events" data-toggle="tab" aria-controls="events" role="tab" aria-expanded="true">
                                    <span class="tab_text">Events</span>
                                </a>
                            </li>    
                            @endif
                            @if(Auth::user()->can('publications-list'))
                            <li role="presentation" data-tabing="publication"  class="publication_tab">
                                <a href="#publication" title="Publications" data-toggle="tab" aria-controls="publication" role="tab" aria-expanded="true">
                                    <span class="tab_text">Publications</span>
                                </a>
                            </li>
                            @endif
                            @if (Config::get('Constant.DEFAULT_PAGETEMPLATE') == 'Y')
                            <li role="presentation" data-tabing="template"  class="pagetemplate_tab">
                                <a href="#template" title="Templates" data-toggle="tab" aria-controls="template" role="tab" aria-expanded="true">
                                    <span class="tab_text">Templates</span>
                                </a>
                            </li>
                            @endif
                             @if (Config::get('Constant.DEFAULT_FORMBUILDER') == 'Y')
                            <li role="presentation" data-tabing="Form Builder">
                                <a href="#formbuilder" title="Form Builder" data-toggle="tab" aria-controls="Form Builder" role="tab" aria-expanded="true">
                                    <span class="tab_text">Forms</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active mcscroll" role="tabpanel" id="all">
                                <ul>
                                    <li>
                                        <a title="Section Title" class="only-title" href="javascript:;"><span><i class="fa fa-italic" aria-hidden="true"></i></span>Section Title</a>
                                    </li>
                                    <li>
                                        <a title="Text Block" class="only-content" href="javascript:;"><span><i class="fa fa-text-width" aria-hidden="true"></i></span>Text Block</a>
                                    </li>
                                    <li>
                                        <a title="Image Block" class="only-image" href="javascript:;"><span><i class="fa fa-image" aria-hidden="true"></i></span>Image Block</a>
                                    </li>
                                    <!--                                    <li>
                                                                            <a title="Image Gallery" class="image-gallery" href="javascript:;"><span><i class="fa fa-file-image-o" aria-hidden="true"></i></span>Image Gallery</a>
                                                                        </li>-->
                                    <li>
                                        <a title="Document Block" class="only-document" href="javascript:;"><span><i class="fa fa-file-text-o" aria-hidden="true"></i></span>Document Block</a>
                                    </li>
                                    <li>
                                        <a title="Promo Video" class="only-video" href="javascript:;"><span><i class="fa fa-video-camera" aria-hidden="true"></i></span>Promo Video</a>
                                    </li>
                                    <li>
                                        <a title="Image Block With Text" class="image-with-information" href="javascript:;"><span><i class="fa fa-image" aria-hidden="true"></i></span>Image Block With Text</a>
                                    </li>
                                    <li>
                                        <a title="Video Block With Text" class="video-with-information" href="javascript:;"><span><i class="fa fa-video-camera" aria-hidden="true"></i></span>Video Block With Text</a>
                                    </li>
                                    
                                    <li>
                                        <a title="Google Map" class="google-map" href="javascript:;"><span><i class="fa fa-map-marker" aria-hidden="true"></i></span>Google Map</a>
                                    </li>
                                    <li>
                                        <a title="Contact Info" class="contact-info" href="javascript:;"><span><i class="fa fa fa-tasks" aria-hidden="true"></i></span>Contact Info</a>
                                    </li>
                                    <li>
                                        <a title="Button" class="section-button" href="javascript:;"><span><i class="fa fa fa-square" aria-hidden="true"></i></span>Button</a>
                                    </li>
                                    <li>
                                        <a title="2 Part Content" class="two-part-content" href="javascript:;"><span><i class="fa fa-align-justify" aria-hidden="true"></i></span>2 Part Contents</a>
                                    </li>
                                     <li>
                                        <a title="Add Space" class="only-spacer" href="javascript:;"><span><i class="fa fa-arrows-v" aria-hidden="true"></i></span>Add Space</a>
                                    </li>
                                    <li>
                                        <a title="Home Page Welcome Section" class="home-information" href="javascript:;"><span><i class="fa fa-image" aria-hidden="true"></i></span>Home Page Welcome Section</a>
                                    </li>
                                    @if(Auth::user()->can('photo-album-list'))
                                    <li>
                                        <a title="Photo Album" class="photo-gallery" href="javascript:;"><span><i class="fa fa-picture-o" aria-hidden="true"></i></span>Photo Album</a>
                                    </li>
                                    @endif
                                    @if(Auth::user()->can('video-gallery-list'))
                                    <li>
                                        <a title="Video Album" class="video-gallery" href="javascript:;"><span><i class="fa fa-video-camera" aria-hidden="true"></i></span>Video Album</a>
                                    </li>
                                    @endif
                                    @if(Auth::user()->can('news-list'))
                                    <li>
                                        <a href="javascript:;" class="news" data-filter="all-news" title="News"><span><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>News</a>
                                    </li>

                                    <li>
                                        <a href="javascript:;" class="news-template" data-filter="latest-news" title="Latest News"><span><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>Latest News</a>
                                    </li>
                                    @endif
                                    @if(Auth::user()->can('blogs-list'))
                                    <li>
                                        <a href="javascript:;" class="blogs" data-filter="all-blog" title="Blogs"><span><i class="fa fa-briefcase" aria-hidden="true"></i></span>Blogs</a>
                                    </li>

                                    <li>
                                        <a href="javascript:;" class="blogs-template" data-filter="latest-blog" title="Latest Blogs"><span><i class="fa fa-briefcase" aria-hidden="true"></i></span>Latest Blogs</a>
                                    </li>
                                    @endif
                                    @if(Auth::user()->can('events-list'))
                                    <li>
                                        <a href="javascript:;" class="events" title="Events" data-filter="all-events">
                                            <span><i class="fa fa-calendar" aria-hidden="true"></i></span>Events
                                        </a>
                                    </li>

                                    <li>
                                        <a href="javascript:;" class="events-template" title="Latest Events" data-filter="latest-events">
                                            <span><i class="fa fa-calendar" aria-hidden="true"></i></span>Latest Events
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="events-template" title="Current Months Events" data-filter="current-months-events">
                                            <span><i class="fa fa-calendar" aria-hidden="true"></i></span>Current Months Events
                                        </a>
                                    </li>
                                    @endif
                                    @if(Auth::user()->can('publications-list'))
                                    <li>
                                        <a href="javascript:;" class="publication" data-filter="all-publication" title="Publications"><span><i class="fa fa-book" aria-hidden="true"></i></span>Publications</a>
                                    </li>

                                    <li>
                                        <a href="javascript:;" class="publication-template" data-filter="latest-publication" title="Latest Publications"><span><i class="fa fa-book" aria-hidden="true"></i></span>Latest Publications</a>
                                    </li>
                                    @endif
                                    
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="tab-pane mcscroll" role="tabpanel" id="blocks">            
                                <ul>
                                    <li>
                                        <a title="Section Title" class="only-title" href="javascript:;"><span><i class="fa fa-italic" aria-hidden="true"></i></span>Section Title</a>
                                    </li>
                                    <li>
                                        <a title="Text Block" class="only-content" href="javascript:;"><span><i class="fa fa-text-width" aria-hidden="true"></i></span>Text Block</a>
                                    </li>
                                    <li>
                                        <a title="Image Block" class="only-image" href="javascript:;"><span><i class="fa fa-image" aria-hidden="true"></i></span>Image Block</a>
                                    </li>
                                    <!--                                    <li>
                                                                            <a title="Image Gallery" class="image-gallery" href="javascript:;"><span><i class="fa fa-file-image-o" aria-hidden="true"></i></span>Image Gallery</a>
                                                                        </li>-->
                                    <li>
                                        <a title="Document Block" class="only-document" href="javascript:;"><span><i class="fa fa-file-text-o" aria-hidden="true"></i></span>Document Block</a>
                                    </li>
                                    <li>
                                        <a title="Promo Video" class="only-video" href="javascript:;"><span><i class="fa fa-video-camera" aria-hidden="true"></i></span>Promo Video</a>
                                    </li>
                                    <li>
                                        <a title="Image Block With Text" class="image-with-information" href="javascript:;"><span><i class="fa fa-image" aria-hidden="true"></i></span>Image Block With Text</a>
                                    </li>
                                    <li>
                                        <a title="Video Block With Text" class="video-with-information" href="javascript:;"><span><i class="fa fa-video-camera" aria-hidden="true"></i></span>Video Block With Text</a>
                                    </li>
                                   
                                    <li>
                                        <a title="Google Map" class="google-map" href="javascript:;"><span><i class="fa fa-map-marker" aria-hidden="true"></i></span>Google Map</a>
                                    </li>
                                    <li>
                                        <a title="Contact Info" class="contact-info" href="javascript:;"><span><i class="fa fa fa-tasks" aria-hidden="true"></i></span>Contact Info</a>
                                    </li>
                                    <li>
                                        <a title="Button" class="section-button" href="javascript:;"><span><i class="fa fa fa-square" aria-hidden="true"></i></span>Button</a>
                                    </li>
                                     
                                    <li>
                                        <a title="2 Part Content" class="two-part-content" href="javascript:;"><span><i class="fa fa-align-justify" aria-hidden="true"></i></span>2 Part Contents</a>
                                    </li>
                                    <li>
                                        <a title="Add Space" class="only-spacer" href="javascript:;"><span><i class="fa fa-arrows-v" aria-hidden="true"></i></span>Add Space</a>
                                    </li>
                                     <li>
                                        <a title="Home Page Welcome Section" class="home-information" href="javascript:;"><span><i class="fa fa-image" aria-hidden="true"></i></span>Home Page Welcome Section</a>
                                    </li>
                                    @if(Auth::user()->can('photo-album-list'))
                                    <li>
                                        <a title="Photo Album" class="photo-gallery" href="javascript:;"><span><i class="fa fa-picture-o" aria-hidden="true"></i></span>Photo Album</a>
                                    </li>
                                    @endif
                                    @if(Auth::user()->can('video-gallery-list'))
                                    <li>
                                        <a title="Video Album" class="video-gallery" href="javascript:;"><span><i class="fa fa-video-camera" aria-hidden="true"></i></span>Video Album</a>
                                    </li>
                                    @endif
                                   
                                </ul>
                                <div class="clearfix"></div>         
                            </div>
                            <div class="tab-pane mcscroll" role="tabpanel" id="partition">            
                                <ul>
                                    <li>
                                        <a title="Two Columns" class="two-columns" href="javascript:;"><span><i class="fa fa-columns" aria-hidden="true"></i></span>Two Columns</a>
                                    </li>
                                    <li>
                                        <a title="Three Columns" class="three-columns" href="javascript:;"><span><i class="fa fa-columns" aria-hidden="true"></i></span>Three Columns</a>
                                    </li>
                                    <li>
                                        <a title="Four Columns" class="four-columns" href="javascript:;"><span><i class="fa fa-columns" aria-hidden="true"></i></span>Four Columns</a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>         
                            </div>
                            <div class="tab-pane mcscroll" role="tabpanel" id="news">
                                <ul>
                                    <li>
                                        <a href="javascript:;" class="news" data-filter="all-news" title="News"><span><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>News</a>
                                    </li>

                                    <li>
                                        <a href="javascript:;" class="news-template" data-filter="latest-news" title="Latest News"><span><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>Latest News</a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="tab-pane mcscroll" role="tabpanel" id="blog">
                                <ul>
                                    <li>
                                        <a href="javascript:;" class="blogs" data-filter="all-blog" title="Blogs"><span><i class="fa fa-briefcase" aria-hidden="true"></i></span>Blogs</a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="blogs-template" data-filter="latest-blog" title="Latest Blogs"><span><i class="fa fa-briefcase" aria-hidden="true"></i></span>Latest Blogs</a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="tab-pane mcscroll" role="tabpanel" id="events">
                                <ul>              
                                    <li>
                                        <a href="javascript:;" class="events" title="Events" data-filter="all-events">
                                            <span><i class="fa fa-calendar" aria-hidden="true"></i></span>Events
                                        </a>
                                    </li>

                                    <li>
                                        <a href="javascript:;" class="events-template" title="Latest Events" data-filter="latest-events">
                                            <span><i class="fa fa-calendar" aria-hidden="true"></i></span>Latest Events
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="events-template" title="Current Months Events" data-filter="current-months-events">
                                            <span><i class="fa fa-calendar" aria-hidden="true"></i></span>Current Months Events
                                        </a>
                                    </li>
                                </ul>            
                                <div class="clearfix"></div>
                            </div>
                            <div class="tab-pane mcscroll" role="tabpanel" id="publication">
                                <ul>
                                    <li>
                                        <a href="javascript:;" class="publication" data-filter="all-publication" title="Publications"><span><i class="fa fa-book" aria-hidden="true"></i></span>Publications</a>
                                    </li>

                                    <li>
                                        <a href="javascript:;" class="publication-template" data-filter="latest-publication" title="Latest Publications"><span><i class="fa fa-book" aria-hidden="true"></i></span>Latest Publications</a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                             @if (Config::get('Constant.DEFAULT_PAGETEMPLATE') == 'Y')
                            <div class="tab-pane mcscroll" role="tabpanel" id="template">
                                <ul>
                                    @php
                                    $tempaletData = App\Helpers\MyLibrary::GetTemplateData();
                                    if(!empty($tempaletData)){
                                    foreach($tempaletData as $tdata){
                                    $date = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($tdata->created_at));
                                    $userIsAdmin = false;
                                    $currentUserRoleData = auth()->user()->roles->first();
                                      if (!empty($currentUserRoleData)) {
                                          $udata = $currentUserRoleData;
                                      }
                                      if ($udata->chrIsAdmin == 'Y') {
                                            $userdata = App\User::getUserId($tdata->UserID);
                                            $username = 'Created by @' . $userdata->name.' ('.$date.')';
                                     }else{
                                            $username= '';
                                        }
                                    @endphp
                                    <li>
                                        <a href="javascript:;" onclick='GetSetTemplateData({{ $tdata->id }})' data-filter="all-news" title="{{ $tdata->varTemplateName }}"><span><i class="fa fa-align-justify" aria-hidden="true"></i></span><div class="span-title-rh">{{ $tdata->varTemplateName }}<em>{!! $username !!}</em></div></a>
                                    </li>
                                    @php
                                    }
                                    }
                                    @endphp
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                             @endif
                              @if (Config::get('Constant.DEFAULT_FORMBUILDER') == 'Y')
                            <div class="tab-pane mcscroll" role="tabpanel" id="formbuilder">
                                <ul>
                                    @php
                                    $FormBuilderData = App\Helpers\MyLibrary::GetFormBuilderData();
                                    if(!empty($FormBuilderData)){
                                    foreach($FormBuilderData as $fdata){
                                    $date = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($fdata->created_at));
                                     $userIsAdmin = false;
                                    $currentUserRoleData = auth()->user()->roles->first();
                                      if (!empty($currentUserRoleData)) {
                                          $udata = $currentUserRoleData;
                                      }
                                      if ($udata->chrIsAdmin == 'Y') {
                                        $userdata = App\User::getUserId($fdata->UserID);
                                        $username = 'Created by @' . $userdata->name.' ('.$date.')';
                                      }else{
                                        $username= '';
                                     }
                                    @endphp
                                    <li>
                                        <a href="javascript:;" onclick='GetSetFormBuilderData({{ $fdata->id }})' data-filter="all-news" title="{{ $fdata->varName }}"><span><i class="fa fa-file-text-o" aria-hidden="true"></i></span><div class="span-title-rh">{{ $fdata->varName }}<em>{!! $username !!}</em></div></a>
                                    </li>
                                    @php
                                    }
                                    }
                                    @endphp
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                              @endif
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionSpacerTemplate" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionSpacerTemplate']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Spacer Class</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label form_title">Spacer Class<span aria-required="true" class="required"> * </span></label>
                            <select name="section_spacer" class="form-control bootstrap-select bs-select layout-class" id="spacerid">
                                <option value="">Spacer Class</option>
                                <option value="9">ac-pt-xs-0</option>
                                <option value="10">ac-pt-xs-5</option>
                                <option value="11">ac-pt-xs-10</option>
                                <option value="12">ac-pt-xs-15</option>
                                <option value="13">ac-pt-xs-20</option>
                                <option value="14">ac-pt-xs-25</option>
                                <option value="15">ac-pt-xs-30</option>
                                <option value="16">ac-pt-xs-40</option>
                                <option value="17">ac-pt-xs-50</option>
                            </select>
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


<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectionContent"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionContent']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Content</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea class="form-control item-data" name="content" id="ck-area" column="40" rows="10"></textarea>
                        </div>

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

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectiontwoContent"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionTwoContent']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>2 Part Content</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label form_title">Left Side Content</label>
                            <textarea class="form-control item-data" name="leftcontent" id="leftck-area" column="40" rows="10"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Right Side Content</label>
                            <textarea class="form-control item-data" name="rightcontent" id="rightck-area" column="40" rows="10"></textarea>
                        </div>
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

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectionTitle"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionTitle']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Section Title</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea name="title" class="form-control item-data " id="ck-area" column="40" rows="1"></textarea>
                        </div>

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
<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectionContactInfo"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionContactInfo']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Add Contact Info</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label form_title">Address <span aria-required="true" class="required"> * </span></label>
                            {!! Form::textarea('section_address', old('section_address'), array('class' => 'form-control','rows'=>'3','id'=>'section_address','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Email <span aria-required="true" class="required"> * </span></label>
                            {!! Form::email('section_email', old('section_email'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_email','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Phone # <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_phone', old('section_phone'), array('maxlength'=>'20','class' => 'form-control','id'=>'section_phone','onkeypress'=>"javascript: return KeycheckOnlyPhonenumber(event);",'onpaste'=>'return false','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Other Information</label>
                            <textarea name="title" class="form-control item-data" id="ck-area" column="40" rows="1"></textarea>
                        </div>
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

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectionButton"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionButton']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Add Button</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label form_title">Title<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Link Target<span aria-required="true" class="required"> * </span></label>
                            <select name="section_button_target" class="form-control bootstrap-select bs-select buttonsec-class" id="section_button_target">
                                <option value="">Select Link Target</option>
                                <option value="_self">Same Window</option>
                                <option value="_blank">New Window</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Link<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_link', old('section_link'), array('maxlength'=>'255','class' => 'form-control','id'=>'section_link','autocomplete'=>'off')) !!}
                        </div>

                        <div class="form-group imagealign">
                            <label class="control-label form_title config-title">Button align options<span aria-required="true" class="required"> * </span></label>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="imagealign">
                                        <li>
                                            <a href="javascript:;" title="Align Left">
                                                <input type="radio" id="button-left-image" name="selector" value="button-lft-txt">
                                                <label for="button-left-image"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/left-button.png' }}" alt=""></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Right">
                                                <input type="radio" id="button-right-image" name="selector" value="button-rt-txt">
                                                <label for="button-right-image"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/right-button.png' }}" alt=""></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Center">
                                                <input type="radio" id="button-center-image" name="selector" value="button-center-txt">
                                                <label for="button-center-image"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/center-button.png' }}" alt=""></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

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

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectionImage"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionImage']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Image with Information</b></h5>
                    </div>
                    <div class="modal-body">
                        @php $imgkey = 1; @endphp
                        <div class=" img_1" id="img1">
                            <div class="team_box">
                                <div class="thumbnail_container">
                                    <a data-multiple="false" onclick="MediaManager.open('photo_gallery', 1);" data-selected="1" class=" btn-green-drake media_manager pgbuilder-img image_gallery_change_1" title="" href="javascript:void(0);">
                                        <div class="thumbnail photo_gallery_1">
                                            <img src="{!! $CDN_PATH.'assets/global/img/plus-no-image.png' !!}">                  
                                        </div>
                                    </a>
                                    <div class="nqimg_mask">
                                        <div class="nqimg_inner">
                                            <input class="image_1 item-data imgip" type="hidden" id="photo_gallery1" data-type="image" name="img1" value="1"/>
                                             <input class="folder_1" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Caption</label>
                            {!! Form::text('img_title', old('img_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'img_title','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <textarea class="form-control item-data" name="content" id="ck-area" column="40" rows="10"></textarea>
                        </div>
                        <div class="form-group imagealign">
                            <label class="control-label form_title config-title">Image align options<span aria-required="true" class="required"> * </span></label>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="imagealign">
                                        <li>
                                            <a href="javascript:;" title="Align Left">

                                                <input type="radio" id="home-left-image" name="selector" value="lft-txt">
                                                <label for="home-left-image"></label>
                                                <div class="check"><div class="inside"></div></div>

                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/left-image.png' }}" alt=""></i>
                                            </a>

                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Right">
                                                <input type="radio" id="home-right-image" name="selector" value="rt-txt">
                                                <label for="home-right-image"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/right-image.png' }}" alt=""></i>
                                            </a>

                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Top">
                                                <input type="radio" id="home-top-image" name="selector" value="top-txt">
                                                <label for="home-top-image"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/top-image.png' }}" alt=""></i>
                                            </a>

                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Cneter">
                                                <input type="radio" id="home-center-image" name="selector" value="center-txt">
                                                <label for="home-center-image"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/center-image.png' }}" alt=""></i>
                                            </a>

                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Bottom">
                                                <input type="radio" id="bottom-image" name="selector" value="bot-txt">
                                                <label for="bottom-image"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/bottom-image.png' }}" alt=""></i>
                                            </a>

                                        </li>

                                    </ul>
                                </div>

                            </div>
                        </div>
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

<!--<div class="modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectionGalleryImage" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      {!! Form::open(['method' => 'post','id'=>'frmSectionGalleryImage']) !!}
      <input type="hidden" name="editing">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
        <span>Ã—</span>
        </button>
        <h5 class="modal-title" id="exampleModalLabel"><b>Image</b></h5>
      </div>
      <div class="modal-body">
        @php $imgkey = 1; @endphp        
        <label class="form_title" for="front_logo">Photos <span aria-required="true" class="required"> * </span></label>
        <div class="image_thumb">
          <div class="form-group {{ $errors->has('img_id') ? ' has-error' : '' }} ">              
              <div class="clearfix"></div>
              <div class="fileinput fileinput-new" data-provides="fileinput">
                  <div class="fileinput-preview thumbnail photo_gallery_image_img" data-trigger="fileinput" style="width:100%; float:left; height:120px;position: relative;">
                      @if(old('image_url'))
                      <img src="{{ old('image_url') }}" />
                      @else
                      <img class="img_opacity" src="{{ url('resources\images\upload_file.gif') }}" />
                      @endif
                      <div class="input-group">
                    <a class="media_manager multiple-selection" data-multiple="true" onclick="MediaManager.open('photo_gallery_image');"><span class="fileinput-new"></span></a>
                    <input class="form-control" type="hidden" id="photo_gallery_image" name="img_id" value="{{ old('img_id') }}" />
                    <input class="form-control" type="hidden" id="image_url" name="image_url" value="{{ old('image_url') }}" />
                    <input class="form-control" type="hidden" id="select_multiple" value="true" />
                  </div>
                  </div>
                  
                   <input class="form-control" type="hidden" id="photo_gallery_image" name="img_id" value="{{ old('img_id') }}" />
                  <input class="form-control" type="hidden" id="image_url" name="image_url" value="{{ old('image_url') }}" />
                  <input class="form-control" type="hidden" id="select_multiple" value="true" /> 
              </div>
          </div>
          <div class="clearfix"></div>
          <div id="photo_gallery_image_img"></div>            
          <span style="color: red;">
              <strong>{{ $errors->first('img_id') }}</strong>
          </span>
        </div>

        
        <div class="text-right">
          <button type="button" class="btn red btn-outline" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-green-drake" id="addSection">Add</button>
        </div>
        
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>-->
<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectionVideoContent"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmsectionVideoContent']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Video with Information</b></h5>
                    </div>
                    <div class="modal-body">
                        @php $imgkey = 1; @endphp
                        <div class="form-group">
                            <label class="control-label form_title">Caption</label>          
                            {!! Form::text('title', old('title'), array('maxlength'=>'160','class' => 'form-control','id'=>'videoCaption','autocomplete'=>'off')) !!}
                        </div>

                        @php $unid = uniqid().'builder'; @endphp
                        <div class="form-group">          
                            <label class="form_title" for="site_name">Video Source</label>
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input class="md-radiobtn" checked type="radio" value="YouTube" name="chrVideoType" id="{{ $unid.'1' }}"> 
                                    <label for="{{ $unid.'1' }}"> <span></span> <span class="check"></span> <span class="box"></span> YouTube </label>         
                                </div>
                                <div class="md-radio">
                                    <input class="md-radiobtn" type="radio" value="Vimeo" name="chrVideoType" id="{{ $unid.'2' }}">
                                    <label for="{{ $unid.'2' }}"> <span></span> <span class="check"></span> <span class="box"></span> Vimeo </label>        
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label form_title">Video embed code<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('video_id', old('video_id'), array('maxlength'=>'160','class' => 'form-control','id'=>'videoId','autocomplete'=>'off')) !!}
                        </div>  

                        <div class="form-group">
                            <textarea class="form-control item-data" name="content" id="ck-area" column="40" rows="10"></textarea>
                        </div>
                        <div class="form-group imagealign">
                            <label class="control-label form_title config-title">Image align options<span aria-required="true" class="required"> * </span></label>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="imagealign">
                                        <li>
                                            <a href="javascript:;" title="Align Left">
                                                <input type="radio" id="home-left-video" name="selector" value="lft-txt">
                                                <label for="home-left-video"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/left-video.png' }}" alt=""></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Right">
                                                <input type="radio" id="home-right-video" name="selector" value="rt-txt">
                                                <label for="home-right-video"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/right-video.png' }}" alt=""></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Top">
                                                <input type="radio" id="home-top-video" name="selector" value="top-txt">
                                                <label for="home-top-video"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/top-video.png' }}" alt=""></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Center">
                                                <input type="radio" id="home-center-video" name="selector" value="center-txt">
                                                <label for="home-center-video"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/center-video.png' }}" alt=""></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Bottom">
                                                <input type="radio" id="bottom-video" name="selector" value="bot-txt">
                                                <label for="bottom-video"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/bottom-video.png' }}" alt=""></i>
                                            </a>
                                        </li>

                                    </ul>
                                </div>

                            </div>
                        </div>
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

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectionHomeImage" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionHomeImage']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Home Page Welcome Section</b></h5>
                    </div>
                    <div class="modal-body">
                        @php $imgkey = 1; @endphp
                        <div class=" img_1" id="img1">
                            <div class="team_box">
                                <div class="thumbnail_container">
                                    <a data-multiple="false" onclick="MediaManager.open('photo_gallery', 1);" data-selected="1" class=" btn-green-drake media_manager pgbuilder-img image_gallery_change_1" title="" href="javascript:void(0);">
                                        <div class="thumbnail photo_gallery_1">
                                            <img src="{!! $CDN_PATH.'assets/global/img/plus-no-image.png' !!}">                  
                                        </div>
                                    </a>
                                    <div class="nqimg_mask">
                                        <div class="nqimg_inner">
                                            <input class="image_1 item-data imgip" type="hidden" id="photo_gallery1" data-type="image" name="img1" value="1"/>
                                             <input class="folder_1" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Caption<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('img_title', old('img_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'img_title','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <textarea class="form-control item-data" name="content" id="ck-area" column="40" rows="10"></textarea>
                        </div>
                        <div class="form-group imagealign">
                            <label class="control-label form_title config-title">Image align options<span aria-required="true" class="required"> * </span></label>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="imagealign">
                                        <li>
                                            <a href="javascript:;" title="Align Left">

                                                <input type="radio" id="left-image" name="selector" value="home-lft-txt">
                                                <label for="left-image"></label>
                                                <div class="check"><div class="inside"></div></div>

                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/home-left-image.png' }}" alt=""></i>
                                            </a>

                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Right">
                                                <input type="radio" id="right-image" name="selector" value="home-rt-txt">
                                                <label for="right-image"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/home-right-image.png' }}" alt=""></i>
                                            </a>

                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Top">
                                                <input type="radio" id="top-image" name="selector" value="home-top-txt">
                                                <label for="top-image"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/home-top-image.png' }}" alt=""></i>
                                            </a>

                                        </li>

                                    </ul>
                                </div>

                            </div>
                        </div>
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

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectionVideo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionVideo']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Video</b></h5>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="control-label form_title">Caption <span aria-required="true" class="required"> * </span></label>          
                            {!! Form::text('title', old('title'), array('maxlength'=>'160','class' => 'form-control','id'=>'videoCaption','autocomplete'=>'off')) !!}
                        </div>

                        @php $unid = uniqid().'builder'; @endphp
                        <div class="form-group">          
                            <label class="form_title" for="site_name">Video Source</label>
                            <div class="md-radio-inline">
                                <div class="md-radio">
                                    <input class="md-radiobtn" checked type="radio" value="YouTube" name="chrVideoType" id="{{ $unid.'1' }}"> 
                                    <label for="{{ $unid.'1' }}"> <span></span> <span class="check"></span> <span class="box"></span> YouTube </label>         
                                </div>
                                <div class="md-radio">
                                    <input class="md-radiobtn" type="radio" value="Vimeo" name="chrVideoType" id="{{ $unid.'2' }}">
                                    <label for="{{ $unid.'2' }}"> <span></span> <span class="check"></span> <span class="box"></span> Vimeo </label>        
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label form_title">Video embed code<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('video_id', old('video_id'), array('maxlength'=>'160','class' => 'form-control','id'=>'videoId','autocomplete'=>'off')) !!}
                        </div>        


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

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectionOnlyImage" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionOnlyImage']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Image</b></h5>
                    </div>
                    <div class="modal-body">
                        @php $imgkey = 1; @endphp
                        <div class=" img_1" id="img1">
                            <div class="team_box">
                                <div class="thumbnail_container">
                                    <a onclick="MediaManager.open('photo_gallery', 1);" data-selected="1" class=" btn-green-drake media_manager pgbuilder-img image_gallery_change_1" title="" href="javascript:void(0);">
                                        <div class="thumbnail photo_gallery_1">
                                            <img src="{!! $CDN_PATH.'assets/global/img/plus-no-image.png' !!}">                  
                                        </div>
                                    </a>
                                    <div class="nqimg_mask">
                                        <div class="nqimg_inner">
                                            <input class="image_1 item-data imgip" type="hidden" id="photo_gallery1" data-type="image" name="img1" value=""/>
                                            <input class="folder_1" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label form_title">Caption</label>
                            {!! Form::text('img_title', old('img_title'), array('maxlength'=>'160','class' => 'form-control sectiontitlespellingcheck','id'=>'img_title','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group imagealign">
                            <label class="control-label form_title config-title">Image align options<span aria-required="true" class="required"> * </span></label>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="imagealign">
                                        <li>
                                            <a href="javascript:;" title="Align Left">
                                                <input type="radio" id="image-left-image" name="selector" value="image-lft-txt">
                                                <label for="image-left-image"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/image-left.png' }}" alt=""></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Right">
                                                <input type="radio" id="image-right-image" name="selector" value="image-rt-txt">
                                                <label for="image-right-image"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/image-right.png' }}" alt=""></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" title="Align Center">
                                                <input type="radio" id="image-center-image" name="selector" value="image-center-txt">
                                                <label for="image-center-image"></label>
                                                <div class="check"><div class="inside"></div></div>
                                                <i class="icon"><img src="{{ $CDN_PATH.'assets/images/image-center.png' }}" alt=""></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn red btn-outline cancel-btn" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-green-drake"   id="addSection">Add</button>
                        </div>

                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>


<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectionMap" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionMap']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Google Map</b></h5>
                    </div>
                    <div class="modal-body">
                        @php $imgkey = 1; @endphp
                        <!--                        <div class=" img_1" id="img1">
                                                    <div class="team_box">
                                                        <label class="control-label form_title">Marker<span aria-required="true" class="required"> * </span></label>
                                                        <div class="thumbnail_container">
                                                            <a onclick="MediaManager.open('photo_gallery', 1);" data-selected="1" class=" btn-green-drake media_manager pgbuilder-img image_gallery_change_1" title="" href="javascript:void(0);">
                                                                <div class="thumbnail photo_gallery_1">
                                                                    <img src="{!! $CDN_PATH.'assets/global/img/plus-no-image.png' !!}">                  
                                                                </div>
                                                            </a>
                                                            <div class="nqimg_mask">
                                                                <div class="nqimg_inner">
                                                                    <input class="image_1 item-data imgip" type="hidden" id="photo_gallery1" data-type="image" name="img1" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>-->

                        <div class="form-group">
                            <div id="map" style="margin-left: 0px; margin-bottom: 10px; width:100%;height:300px;"></div>
                        </div>
                        <div style="padding-bottom: 20px"></div>
                        <div class="form-group">
                            <label class="control-label form_title">Latitude<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('img_latitude', old('img_latitude'), array('maxlength'=>'500','class' => 'form-control','id'=>'img_latitude','autocomplete'=>'off','readonly'=>'readonly')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Longitude<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('img_longitude', old('img_longitude'), array('maxlength'=>'500','class' => 'form-control','id'=>'img_longitude','autocomplete'=>'off','readonly'=>'readonly')) !!}
                        </div>

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


<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="sectionOnlyDocument" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionOnlyDocument']) !!}
                    <input type="hidden" name="editing">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Document</b></h5>
                    </div>
                    <div class="modal-body">
                        @php $imgkey = 1; @endphp
                        <div class=" img_1" id="img1">
                            <div class="team_box">
                                <div class="thumbnail_container">
                                    <a onclick="MediaManager.openDocumentManager('Composer_doc');" data-multiple='true' data-selected="1" class=" btn-green-drake document_manager pgbuilder-img image_gallery_change_1" title="" href="javascript:void(0);">
                                        <div class="thumbnail photo_gallery_1">
                                            <img src="{!! $CDN_PATH.'assets/global/img/plus-no-image.png' !!}">                  
                                        </div>

                                    </a>
                                    <div class="nqimg_mask">
                                        <div class="nqimg_inner">
                                            <input class="image_1 item-data imgip1" type="hidden" id="photo_gallery1" data-type="document" name="img1" value=""/>
                                            <input class="folder_1" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="Composer_doc_documents">
                                <div class="builder_doc_list">
                                    <ul class="dochtml">
                                    </ul>
                                </div>
                            </div>
                        </div>
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

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup" id="image-resizer" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Adjust Image</b></h5>
                    </div>
                    <div class="modal-body">
                        <img id="resize-source" src="{!! $CDN_PATH.'assets/global/img/plus-no-image.png' !!}"/>
                        <div id="slider" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                            <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 50%;"></a>
                        </div>
                        <div class="text-right">
                            <a class="btn red btn-outline cancel-btn" data-dismiss="modal">Cancel</a>
                            <a class="btn btn-green-drake" id="save-adjusments">Save</a>
                        </div>
                    </div>      
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionEventsModuleTemplate" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionEventsModuleTemplate']) !!}
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
                            <label class="control-label form_title">Caption <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Limit<span aria-required="true" class="required"> * </span></label>
                            {!! Form::number('section_limit', old('section_limit'), array('maxlength'=>'1','class' => 'form-control','id'=>'section_limit','autocomplete'=>'off','min'=>'1')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Description</label>
                            {!! Form::textarea('section_description', old('section_description'), array('class' => 'form-control','rows'=>'3','id'=>'section_description','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
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
                            <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                            <select name="layoutType" class="form-control bootstrap-select bs-select layout-class" id="events-template-layout">
                                <option value="">Select Layout</option>    
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>
                            </select>
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

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionPromotionsModuleTemplate" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionPromotionsModuleTemplate']) !!}
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Add Promotions</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label form_title">Caption<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Configurations<span aria-required="true" class="required"> * </span></label>
                            <select name="section_config" class="form-control bootstrap-select bs-select" id="config">
                                <option value="">Configurations</option>
                                <option value="1">Image &amp; Title</option>
                                <option value="2">Image &amp;,Title, Short Description</option>
                                <option value="3">Title, Start Date</option>
                                <option value="4">Image, Title, Start Date</option>
                                <option value="5" selected>Image, Title, Short Description, Start Date</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                            <select name="layoutType" class="form-control bootstrap-select bs-select" id="promotions-template-layout">
                                <option value="">Select Layout</option>    
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>
                            </select>
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
                            <label class="control-label form_title">Description</label>
                            {!! Form::textarea('section_description', old('section_description'), array('class' => 'form-control','rows'=>'3','id'=>'section_description','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
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
                            <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                            <select name="layoutType" class="form-control bootstrap-select bs-select layout-class" id="events-layout">
                                <option value="">Select Layout</option>
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>                  
                            </select>
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
                                            <input type="search" class="form-control form-control-solid" placeholder="{{ trans('template.common.search') }}" id="searchfilter">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group"> 
                                            <select multiple placeholder="Sort by" id="columns" title="Sort By" class="form-control bootstrap-select bs-select sort-class">
                                                <optgroup label="Fields" data-max-options="1">
                                                    <option value="varTitle" >Title</option>
                                                    <option value="dtDateTime" selected>Start Date</option>
                                                    <option value="dtEndDateTime">End Date</option>
                                                    <option value="hits">Views</option>                              
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
                                                    <th width="32.33%" align="left">{{ trans('template.common.title') }}</th>
                                                    <th width="32.33%" align="left">{{ trans('template.common.category') }}</th>
                                                    <th width="32.33%" align="center">Last Updated</th>
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


<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionNewsModule" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionNewsModule']) !!}
                    <input type="hidden" name="total_records">
                    <input type="hidden" name="found">
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>News</b></h5>
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
                                <option value="6">Title, Short Description</option>
                                <option value="8" selected>Title, Short Description, Start Date</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                            <select name="layoutType" class="form-control bootstrap-select bs-select layout-class" id="news-layout">
                                <option value="">Select Layout</option>
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>                  
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="table-container">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group"> 
                                            <select id="newscategory-id" placeholder="Category" title="Category" class="form-control bootstrap-select bs-select cat-class">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group search_rh_div search_rh_div_pos">                  
                                            <input type="search" class="form-control form-control-solid" placeholder="{{ trans('template.common.search') }}" id="searchfilter">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group"> 
                                            <select multiple placeholder="Sort by" id="columns" title="Sort By" class="form-control bootstrap-select bs-select sort-class">
                                                <optgroup label="Fields" data-max-options="1">
                                                    <option value="varTitle" >Title</option>
                                                    <option value="dtDateTime" selected>Start Date</option>
                                                    <option value="dtEndDateTime">End Date</option>
                                                    <option value="hits">Views</option>                              
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
                                                    <th width="32.33%" align="left">{{ trans('template.common.title') }}</th>
                                                    <th width="32.33%" align="left">{{ trans('template.common.category') }}</th>
                                                    <th width="32.33%" align="center">Last Updated</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div class="table-scrollable full-info-table" id="mcscroll" style="height: 200px">
                                            <table class="responsive new_table_desing table table-striped table-bordered table-hover table-checkable" id="datatable_news_ajax">

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

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionNewsModuleTemplate" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionNewsModuleTemplate']) !!}
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>News</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label form_title">Caption <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Limit<span aria-required="true" class="required"> * </span></label>
                            {!! Form::number('section_limit', old('section_limit'), array('maxlength'=>'1','class' => 'form-control','id'=>'section_limit','autocomplete'=>'off','min'=>'1')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Description</label>
                            {!! Form::textarea('section_description', old('section_description'), array('class' => 'form-control','rows'=>'3','id'=>'section_description','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Configurations<span aria-required="true" class="required"> * </span></label>
                            <select name="section_config" class="form-control bootstrap-select bs-select config-class" id="config">
                                <option value="">Configurations</option>
                                <option value="6">Title, Short Description</option>
                                <option value="8" selected>Title, Short Description, Start Date</option>
                            </select>
                        </div> 
                        <div class="form-group">
                            <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                            <select name="layoutType" class="form-control bootstrap-select bs-select layout-class" id="news-template-layout">
                                <option value="">Select Layout</option>  
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>
                            </select>
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


<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionBlogsModule" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionBlogsModule']) !!}
                    <input type="hidden" name="total_records">
                    <input type="hidden" name="found">
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Blogs</b></h5>
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
                                <option value="1">Image &amp; Title</option>
                                <option value="2">Image &amp;,Title, Short Description</option>
                                <option value="3">Title, Start Date</option>
                                <option value="4">Image, Title, Start Date</option>
                                <option value="5" selected>Image, Title, Short Description, Start Date</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                            <select name="layoutType" class="form-control bootstrap-select bs-select layout-class" id="blogs-layout">
                                <option value="">Select Layout</option>
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>                  
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="table-container">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group"> 
                                            <select id="blogscategory-id" placeholder="Category" title="Category" class="form-control bootstrap-select bs-select cat-class">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group search_rh_div search_rh_div_pos">                  
                                            <input type="search" class="form-control form-control-solid" placeholder="{{ trans('template.common.search') }}" id="searchfilter">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group"> 
                                            <select multiple placeholder="Sort by" id="columns" title="Sort By" class="form-control bootstrap-select bs-select sort-class">
                                                <optgroup label="Fields" data-max-options="1">
                                                    <option value="varTitle" selected>Title</option>
                                                    <option value="dtDateTime">Start Date</option>
                                                    <option value="dtEndDateTime">End Date</option>
                                                    <option value="hits">Views</option>                              
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
                                                    <th width="32.33%" align="left">{{ trans('template.common.title') }}</th>
                                                    <th width="32.33%" align="left">{{ trans('template.common.category') }}</th>
                                                    <th width="32.33%" align="center">Last Updated</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div class="table-scrollable full-info-table" id="mcscroll" style="height: 200px">
                                            <table class="responsive new_table_desing table table-striped table-bordered table-hover table-checkable" id="datatable_blogs_ajax">

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

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionBlogsModuleTemplate" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionBlogsModuleTemplate']) !!}
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Blogs</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label form_title">Caption <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Limit<span aria-required="true" class="required"> * </span></label>
                            {!! Form::number('section_limit', old('section_limit'), array('maxlength'=>'1','class' => 'form-control','id'=>'section_limit','autocomplete'=>'off','min'=>'1')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Description</label>
                            {!! Form::textarea('section_description', old('section_description'), array('class' => 'form-control','rows'=>'3','id'=>'section_description','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
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
                            <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                            <select name="layoutType" class="form-control bootstrap-select bs-select layout-class" id="news-template-layout">
                                <option value="">Select Layout</option>      
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>
                            </select>
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

<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionPublicationModule" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionPublicationModule']) !!}
                    <input type="hidden" name="total_records">
                    <input type="hidden" name="found">
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Publication</b></h5>
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
                                <option value="1">Image &amp; Title</option>
                                <option value="2">Image &amp;,Title, Short Description</option>
                                <option value="3">Title, Start Date</option>
                                <option value="4">Image, Title, Start Date</option>
                                <option value="5" selected>Image, Title, Short Description, Start Date</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                            <select name="layoutType" class="form-control bootstrap-select bs-select layout-class" id="publication-layout">
                                <option value="">Select Layout</option>
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>                  
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="table-container">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group"> 
                                            <select id="publicationcategory-id" placeholder="Category" title="Category" class="form-control bootstrap-select bs-select cat-class">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group search_rh_div search_rh_div_pos">                  
                                            <input type="search" class="form-control form-control-solid" placeholder="{{ trans('template.common.search') }}" id="searchfilter">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group"> 
                                            <select multiple placeholder="Sort by" id="columns" title="Sort By" class="form-control bootstrap-select bs-select sort-class">
                                                <optgroup label="Fields" data-max-options="1">
                                                    <option value="varTitle" selected>Title</option>
                                                    <option value="dtDateTime">Start Date</option>
                                                    <option value="dtEndDateTime">End Date</option>
                                                    <option value="hits">Views</option>                              
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
                                                    <th width="32.33%" align="left">{{ trans('template.common.title') }}</th>
                                                    <th width="32.33%" align="left">{{ trans('template.common.category') }}</th>
                                                    <th width="32.33%" align="center">Last Updated</th>
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


<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionPublicationModuleTemplate" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionPublicationModuleTemplate']) !!}
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Publication</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label form_title">Caption <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Limit<span aria-required="true" class="required"> * </span></label>
                            {!! Form::number('section_limit', old('section_limit'), array('maxlength'=>'1','class' => 'form-control','id'=>'section_limit','autocomplete'=>'off','min'=>'1')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Description</label>
                            {!! Form::textarea('section_description', old('section_description'), array('class' => 'form-control','rows'=>'3','id'=>'section_description','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
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
                            <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                            <select name="layoutType" class="form-control bootstrap-select bs-select layout-class" id="news-template-layout">
                                <option value="">Select Layout</option>      
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>
                            </select>
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


<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionPhotoAlbumModule" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionPhotoAlbumModule']) !!}
                    <input type="hidden" name="total_records">
                    <input type="hidden" name="found">
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Photo Album</b></h5>
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
                                <option value="1">Image &amp; Title</option>
                                <option value="2">Image &amp;,Title, Short Description</option>
                                <option value="3">Title, Start Date</option>
                                <option value="4">Image, Title, Start Date</option>
                                <option value="5" selected>Image, Title, Short Description, Start Date</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                            <select name="layoutType" class="form-control bootstrap-select bs-select layout-class" id="photo-layout">
                                <option value="">Select Layout</option>
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>                  
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="table-container">
                                <div class="row">

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group search_rh_div search_rh_div_pos">                  
                                            <input type="search" class="form-control form-control-solid" placeholder="{{ trans('template.common.search') }}" id="searchfilter">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group"> 
                                            <select multiple placeholder="Sort by" id="columns" title="Sort By" class="form-control bootstrap-select bs-select sort-class">
                                                <optgroup label="Fields" data-max-options="1">
                                                    <option value="varTitle" selected>Title</option>
                                                    <option value="dtDateTime">Start Date</option>
                                                    <option value="dtEndDateTime">End Date</option>
                                                    <option value="hits">Views</option>                              
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
                                                    <th width="32.33%" align="left">{{ trans('template.common.title') }}</th>
                                                    <th width="32.33%" align="center">Image</th>
                                                    <th width="32.33%" align="center">Last Updated</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div class="table-scrollable full-info-table" id="mcscroll" style="height: 200px">
                                            <table class="responsive new_table_desing table table-striped table-bordered table-hover table-checkable" id="datatable_photoalbum_ajax">

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


<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionVideoAlbumModule" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionVideoAlbumModule']) !!}
                    <input type="hidden" name="total_records">
                    <input type="hidden" name="found">
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Video Album</b></h5>
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
                                <option value="1">Image &amp; Title</option>
                                <option value="3">Title, Start Date</option>
                                <option value="4">Image, Title, Start Date</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Layout<span aria-required="true" class="required"> * </span></label>
                            <select name="layoutType" class="form-control bootstrap-select bs-select layout-class" id="video-layout">
                                <option value="">Select Layout</option>
                                <option class="list" value="list">List</option>
                                <option class="grid" value="grid_2_col">Grid 2 column</option>
                                <option class="grid" value="grid_3_col">Grid 3 column</option>
                                <option class="grid" value="grid_4_col">Grid 4 column</option>                  
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="table-container">
                                <div class="row">

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group search_rh_div search_rh_div_pos">                  
                                            <input type="search" class="form-control form-control-solid" placeholder="{{ trans('template.common.search') }}" id="searchfilter">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group"> 
                                            <select multiple placeholder="Sort by" id="columns" title="Sort By" class="form-control bootstrap-select bs-select sort-class">
                                                <optgroup label="Fields" data-max-options="1">
                                                    <option value="varTitle" selected>Title</option>
                                                    <option value="dtDateTime">Start Date</option>
                                                    <option value="dtEndDateTime">End Date</option>
                                                    <option value="hits">Views</option>                              
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
                                                    <th width="32.33%" align="left">{{ trans('template.common.title') }}</th>
                                                    <th width="32.33%" align="center">Image</th>
                                                    <th width="32.33%" align="center">Last Updated</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div class="table-scrollable full-info-table" id="mcscroll" style="height: 200px">
                                            <table class="responsive new_table_desing table table-striped table-bordered table-hover table-checkable" id="datatable_videoalbum_ajax">

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
<script src="{{ $CDN_PATH.'resources/global/plugins/jquery.min.js' }}" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false&amp;libraries=places&key=AIzaSyDMdWyeX2VR9DZVhXh46mOJQveRHpavLWI"></script>
