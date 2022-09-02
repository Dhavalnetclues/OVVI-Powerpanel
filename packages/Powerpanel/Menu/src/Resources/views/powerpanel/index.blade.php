@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-toastr/toastr.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/jquery-nestable/jquery.nestable.css' }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
<!-- @include('powerpanel.partials.breadcrumbs') -->

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
<div class="row">
    <div class="col-lg-4 menu_padding_setting">
        <div class="card">
            <div class="card-header align-items-center">
                <select name="module" class="form-select module-list" data-choices>
                    <option value="">Select Module</option>
                    @foreach($moduleList as $key => $value)
                    @can($value->varModuleName.'-list')
                    <option {{ ($value->varModuleName  == 'pages')?'selected':'' }} value="{{ $value->id }}">{{ $value->varTitle  }}</option>
                    @endcan
                    @endforeach
                </select>
                <!-- <div class="tools">
                    <a href="javascript:void(0);" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('template.menuModule.menuPageHelp') }}" title="{!! trans('template.menuModule.menuPageHelp') !!}"><i class="ri-question-line"></i></a>
                </div> -->
            </div>
            
            <form class="form-horizontal" role="form" id="menupages">
                <div class="card-body pages-scroll" data-simplebar>
                    <div class="form-body" id="page-list"></div>
                </div>
                <div class="card-footer right1">
                    <a href="javascript:void(0);" id="addAllMenuItem" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1">
                        <div class="flex-shrink-0">
                            <i class="ri-arrow-right-up-line label-icon align-middle fs-20 me-2"></i>
                        </div> Assign to Menu
                    </a>
                </div>
            </form>
        </div>

        <!-- Menu Item -->
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">{{ trans('template.menuModule.menuItem') }}</h4>
                <div class="flex-shrink-0">
                    <div class="form-check form-switch form-switch-right form-switch-md pe-0">
                        <a href="javascript:void(0);" class="config" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-content="{{ trans('template.menuModule.menuItemHelp') }}" title="{{ trans('template.menuModule.menuItemHelp') }}"><i class="ri-question-line fs-18"></i></a>
                    </div>
                </div>
            </div>
            
            <form class="form-horizontal manualMenu" role="form" id="menupopup">
                <div class="card-body">
                    <div class="cm-floating">
                        <label class="form-label" for="menuTitle">{{ trans('template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                        <input type="text" maxlength="50" class="form-control" name="menuTitle" id="menuTitle" placeholder="{{ trans('template.common.title') }}" />
                        <span id="menuTitleErr" style="color: red;"></span>
                    </div>
                    <div class="cm-floating mb-1">
                        <label class="form-label" for="link">{{ trans('template.common.url') }} <span aria-required="true" class="required"> * </span></label>
                        <input type="text" class="form-control" name="menuLink" id="menuLink" placeholder="http://www.samplewebsite.com/mychoicepage" />
                        <span id="menuLinkErr" style="color: red;"></span>
                        <p class="mb-0 mt-2 fs-12">{{ trans('template.menuModule.menuUrlHelp') }}</i></p>
                    </div>
                </div>
                <div class="card-footer right1">
                    <a href="javascript:void(0);" id="add-menu-item" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1">
                        <div class="flex-shrink-0">
                            <i class="ri-add-line label-icon align-middle fs-20 me-2"></i>
                        </div> Add to Menu
                    </a>
                </div>
            </form>
        </div> <!-- end Menu Item -->
    </div>

    <!-- Header menu -->
    <div class="col-lg-8">
        <div class="card menuBody">
            <div class="card-header align-items-center d-md-flex">
                <h4 class="card-title mb-3 mb-md-0 flex-grow-1 caption-subject">{{trans('template.menuModule.headerMenu')}}</h4>
                <div class="flex-shrink-0">
                    <div class="form-check form-switch form-switch-right form-switch-md pe-0">
                        @if($netquick_admin)
                        @can('menu-type-delete')
                        <button id="deleteMenu" type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" style="display: none;">
                            <div class="flex-shrink-0">
                                <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                            </div>
                            {{trans('template.common.delete')}}
                        </button>
                        @endcan
                        @can('menu-type-create')
                        <a href="javascript:void(0);" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" data-bs-toggle="modal" data-bs-target="#menu-item-add">
                            <div class="flex-shrink-0">
                                <i class="ri-add-line label-icon align-middle fs-20 me-2"></i>
                            </div>
                            {{trans('template.menuModule.createNew')}}
                        </a>
                        @endcan
                        @endif
                        <button id="saveMenu" type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                            <div class="flex-shrink-0">
                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                            </div>
                            {{trans('template.common.save')}}
                        </button>
                    </div>
                </div>
            </div><!-- end card header -->            
            <div class="card-body">
                <div class="row">
                    <p class="menu-page-note" id="menu-page-note"><strong>Note:</strong> We recommend you to place only 6 parent menus.</p>
                    <div class="col-lg-3 col-md-4 col-sm-5 pb-lg-0 pb-3 padding_right_set">
                        <div class="checked_off_on activation form-switch mb-3">
                            <?php
                            $switchAction = (isset($menuTypes[0]["chrPublish"]) && $menuTypes[0]["chrPublish"]!="") ? $menuTypes[0]["chrPublish"]:"Y";
                            $checked=($switchAction=="Y") ? "checked='true'" : "";?>
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/bootstrap-switch.blade.php') != null)
                                @include('powerpanel.partials.bootstrap-switch',['name'=>'active', 'id'=>'active','checked'=>$checked])
                            @endif  <span class="ms-2">Publish/Unpublish</span>
                        </div>
                        <div class="cm-floating select_box">
                            <div class="overflow_select">
                                <select class="form-control menu_control" size="{{count($menuTypes)<2?2:count($menuTypes)}}" id="menuPosition">
                                    @foreach ($menuTypes as $index => $menuType)
                                    <option value="{{ $menuType['id'] }}" @if($index == 0) selected="selected" @endif>{{ $menuType['varTitle'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="icon_title mt-0">{{trans('template.menuModule.guide')}}</div>
                        <div class="information_icons">
                            <ul>
                                <li>
                                    <span><i class="ri-pencil-line"></i></span>{{trans('template.common.edit')}}
                                    <ul class="hover_info hide d-none">
                                        <li>
                                            <span class="ri-pencil-line"></span>
                                            <div class="sub_info_title">{{trans('template.menuModule.editMenu')}}</div>
                                            <p>{{trans('template.menuModule.editOtherName')}} </p>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span><i class="ri-delete-bin-line"></i></span>{{trans('template.common.delete')}}
                                    <ul class="hover_info hide d-none">
                                        <li>
                                            <span class="ri-delete-bin-line"></span>
                                            <div class="sub_info_title">{{trans('template.menuModule.deleteMenu')}}</div>
                                            <p>{{trans('template.menuModule.clickDeleteMenu')}}</p>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span><i class="ri-checkbox-circle-line"></i></span>{{trans('template.common.active')}}
                                    <ul class="hover_info hide d-none">
                                        <li>
                                            <span class="ri-check-line"></span>
                                            <div class="sub_info_title">{{trans('template.menuModule.activeDeactive')}}</div>
                                            <p>{{trans('template.menuModule.clickActiveDeactive')}}</p>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span><i class="ri-file-list-line"></i></span>{{trans('template.menuModule.megaMenu')}} 
                                    <ul class="hover_info hide d-none">
                                        <li>
                                            <span class="ri-file-list-line"></span>
                                            <div class="sub_info_title">{{trans('template.menuModule.megaMenu')}}</div>
                                            <p>{{trans('template.menuModule.mobileActiveDeactive')}}</p>
                                            <a href="{{ Config::get('Constant.CDN_PATH') . 'assets/images/mega-menu-preview.png'}}" title="Click here to view mega menu sample" class="fancybox-buttons" data-fancybox="fancybox-buttons"><span><i class="ri-file-info-line"></i></span>{{trans('template.menuModule.megaMenuPreview')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-7 padding_left_set">
                        <span></span>
                        <div class="mt-element-ribbon bg-grey-steel">
                            {{-- <div class="checked_off_on activation form-switch">
                                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/bootstrap-switch.blade.php') != null)
                                    @include('powerpanel.partials.bootstrap-switch',['name'=>'active', 'id'=>'active'])
                                @endif  Publish/Unpublish
                                <!-- <input type="checkbox" name="active" id="active" class="make-switch switch-large" data-label-icon="ri-fullscreen-line"> -->
                            </div> --}}
                            <div class="clearfix"></div>
                            <div class="dd dd-main-menu" id="nestable_list_1">
                                {!! $menu !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="card-footer right1">
                <a href="javascript:void(0);" id="add-menu-item" class="btn btn-primary btn-theme">Add to Menu</a>
            </div> --}}
        </div>
    </div> <!-- end Header menu -->
</div>

<!-- Edit Modal -->
<div class="modal fade bs-example-modal-lg" id="menu-item-edit" tabindex="-1" aria-labelledby="menu-item-edit" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header mb-2">
                <h5 class="modal-title" id="menu-item-edit">{{trans('template.common.edit')}}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                <div class="cm-floating">
                    <label class="col-form-label">{{trans('template.common.title')}} <span aria-required="true" class="required"> * </span></label>
                    <input type="hidden" id="menuItemId"/>
                    <input type="text" maxlength="50" class="form-control" name="menuTitle" id="menuTitleEdit" placeholder="{{trans('template.common.title')}}" />
                    <span id="menuTitleErrE" style="color: red;"></span>
                </div>
                <div class="cm-floating">
                    <label class="col-form-label">{{trans('template.pageModule.url')}} <span aria-required="true" class="required"> * </span></label>
                    <input type="text" class="form-control" name="menuLink" id="menuLinkEdit" placeholder="Url" />
                    <span id="menuLinkErrE" style="color: red;"></span>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" data-title="Link target" name="link_target" id="custom_edit_link_target">
                    <label class="form-check-label" for="link_target">Open in New Tab</label>
                </div>
            </div>
            <div class="modal-footer justify-content-start">
                <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" id="saveMenuItem">
                    <div class="flex-shrink-0">
                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                    </div> {{trans('template.common.savechanges')}}
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label cancel-btn" data-bs-dismiss="modal">
                    <div class="flex-shrink-0">
                        <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                    </div> {{trans('template.common.close')}}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade bs-example-modal-lg" id="menu-item-add" tabindex="-1" aria-labelledby="menu-item-add" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header mb-2">
                <h5 class="modal-title" id="menu-item-add">{{trans('template.menuModule.newMenu')}}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                <div class="cm-floating">
                    <label class="col-form-label">{{trans('template.common.title')}} <span aria-required="true" class="required"> * </span></label>
                    <input type="text" maxlength="50" class="form-control hasAlias" data-url = 'powerpanel/menu' name="newMenuTitle" id="newMenuTitle" placeholder="{{trans('template.common.title')}}" />
                    <span id="menuTitleErrT" style="color: red;"></span>
                    {!! Form::hidden('alias', null, array('class' => 'aliasField')) !!}
                </div>
            </div>
            <div class="modal-footer justify-content-start">
                <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" id="saveNewMenu">
                    <div class="flex-shrink-0">
                        <i class="ri-add-line label-icon align-middle fs-20 me-2"></i>
                    </div> {{trans('template.common.add')}}
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label cancel-btn" data-bs-dismiss="modal">
                    <div class="flex-shrink-0">
                        <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                    </div> {{trans('template.common.close')}}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div class="modal fade bs-example-modal-center" id="confirm" tabindex="-1" aria-labelledby="confirm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm">{{ trans('template.common.confirm') }}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                {{trans('template.common.areyosureyouwanttodelete')}}
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" id="delete" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1">
                    <div class="flex-shrink-0">
                        <i class="ri-delete-bin-line label-icon align-middle fs-20 me-2"></i>
                    </div>
                    {{trans('template.common.delete')}}
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" data-bs-dismiss="modal">
                    <div class="flex-shrink-0">
                        <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                    </div>
                    {{trans('template.common.close')}}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    var rootUrl = "{{ URL::to('/') }}";
    var moduleAlias = "";
    var user_action = 'add';
    var pageId = '{{ $pageId }}';
    var cmsPageModuleID = '{{ $cmsPageModuleID }}';
</script>

<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-nestable/jquery.nestable.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-nestable/ui-nestable.js' }}" type="text/javascript"></script>  
<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-nestable/nestablenew.config.js?v='.time() }}" type="text/javascript"></script>  
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-toastr/toastr.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide-with-html.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/menu/menu.js' }}" type="text/javascript"></script>
@endsection