<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<div class="col-md-6">
    <div class="portlet gallary_manager light Design-preview Design-settings portlet-fullscreen media-manag" id="gallary_component" style="display:none;">
        <div class="portlet-title">
            <div class="caption">
                <i class="ri-image-2-line fs-20"></i>
                <span class="caption-subject bold font-grey-gallery uppercase">Media Manager</span>
                <span class="caption-helper">All Media in one place</span>
            </div>
            <div class="tools">
                <input type="text" class="hide" name="imageName"  placeholder="Search by Image Name">
                <a href="javascript:void(0);" class="remove"> </a>
            </div>
        </div>
        <div class="portlet-body preview-bg">
            <div class="left-panel">
                <div class="info">
                    <div class="tab-content">
                        <div id="tab_6_3" class="tab-pane fade in active tab_6_3" >
                            <ul class="nav">
                                <li>
                                    <a class="active" id="upload_image" href="javascript:;" onclick="MediaManager.setImageUploadTab();" ><i class="ri-cloud-line"></i>Upload Image</a>
                                </li>
                                <!-- <li>
                                <a href="javascript:;" id="inser_url" onclick="MediaManager.setInsertImageFromUrlTab();"><i class="ri-external-link-line"></i>Insert image From Url</a>
                                </li> -->
                                <li>
                                    <a href="javascript:;" id="user_uploaded_image" onclick="MediaManager.setMyUploadTab({{ Auth::user()->id }});"><i class="ri-image-line"></i>My Uploads</a>
                                </li>
                                 <li>
                                    <a href="javascript:;" id="folder_uploaded_image" onclick="MediaManager.setFolderUploadTab({{ Auth::user()->id }});"><i class="ri-folder-open-line"></i>Folder Uploads</a>
                                </li>
                                <li>
                                    <a href="javascript:;" id="recent" onclick="MediaManager.setRecentUploadTab({{ Auth::user()->id }});"><i class="ri-image-line"></i>Recent Uploads</a>
                                </li>
                                <li>
                                    <a href="javascript:;" id="trash" onclick="MediaManager.setTrashedImageTab({{ Auth::user()->id }});"><i class="ri-delete-bin-3-line"></i>Trash</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="data_id" value=""/>
            <input type="hidden" id="recordId" value=""/>
            <div class="loader" style="display:none;">
                <img src="{{ $CDN_PATH.'resources/images/media_loader.gif' }}">
            </div>
            <div class="right-panel image_html" style="display:none"></div>
            <div class="right-panel file_upload" style="display:none">	</div>
            <div class="right-panel user_uploaded" style="display:none">	</div>
            <div class="right-panel insert_from_url" style="display:none">	</div>
            <div class="right-panel trashed_images" style="display:none">	</div>
            <div class="right-panel recent_uploads" style="display:none">	</div>
            <div class="right-panel image_details" style="display:none">	</div>
            <div class="right-panel image_cropper" style="display:none"></div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="imgInUse" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('template.common.alert') }}</h5>
                <button type="button" class="btn-close close fs-10" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center" id="imgInUseMessage"></div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                    <i class="ri-close-line label-icon align-middle fs-20 me-2"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="deleteMediaImage" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('template.common.alert') }}</h5>
                <button type="button" class="btn-close close fs-10" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">Are you sure you want to delete selected image(s)? images are may be used in another records. </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1 remove_multiple_images" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-delete-bin-line label-icon align-middle fs-20 me-2"></i></div> {{ trans('template.common.delete') }}
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Close
                </button>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="alertModalForImage" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('template.common.alert') }}</h5>
                <button type="button" class="btn-close close fs-10" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center alert_msg"></div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-check-line label-icon align-middle fs-20 me-2"></i></div> Ok
                </button>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="permanentDeleteMediaImage" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('template.common.alert') }}</h5>
                <button type="button" class="btn-close close fs-10" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">Are you sure you want to delete selected image(s) permanently?</div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1 remove_multiple_images_permanently" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-delete-bin-line label-icon align-middle fs-20 me-2"></i></div> {{ trans('template.common.delete') }}
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Close
                </button>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="emptyTrashMediaImage" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('template.common.alert') }}</h5>
                <button type="button" class="btn-close close fs-10" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">Are you sure you want to empty trash?</div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1 empty_trash_Image" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-check-line label-icon align-middle fs-20 me-2"></i></div> {{ trans('template.common.yes') }}
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> No
                </button>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="restoreConfirmBox" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('template.common.alert') }}</h5>
                <button type="button" class="btn-close close fs-10" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">Are you sure you want to restore selected image(s)?</div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1 restore_multiple_images" data-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-restart-line label-icon align-middle fs-20 me-2"></i></div> Restore
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Close
                </button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    window.user_id = '{{ Auth::user()->id }}';
    window.segment = '{{ Request::segment(2) }}';
    $(".media_manager").click(function() {
<?php if (Request::segment(4) == 'edit') { ?>
        $("#upload_image").addClass("active");
        $("#user_uploaded_image").removeClass("active");
        $("#upload_image").trigger("click");
<?php } else { ?>
        $("#upload_image").addClass("active");
        $("#user_uploaded_image").removeClass("active");
        $("#upload_image").trigger("click");
<?php } ?>
    });
</script>