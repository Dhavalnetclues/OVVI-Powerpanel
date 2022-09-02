<div class="col-md-6">
    <div class="portlet gallary_manager light Design-preview Design-settings portlet-fullscreen media-manag" id="document_component" style="display:none;">
        <div class="portlet-title">
            <div class="caption">
                <i class="ri-image-2-line fs-20"></i>
                <span class="caption-subject bold font-grey-gallery uppercase">Media Manager</span>
                <span class="caption-helper">All Media in one place</span>
            </div>
            <div class="tools">
                <input type="text" class="hide media_input" name="docName"  placeholder="Search by Document Name">
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
                                    <a class="active" id="upload_document" href="javascript:;" onclick="MediaManager.setDocumentUploadTab();" ><i class="ri-cloud-line"></i>Upload Document</a>
                                </li>
                                <li>
                                    <a href="javascript:;" id="user_uploaded_docs" onclick="MediaManager.setDocumentListTab({{ Auth::user()->id }});"><i class="ri-file-text-line"></i>My Documents</a>
                                </li>
                                <li>
                                    <a href="javascript:;" id="folder_uploaded_docs" onclick="MediaManager.setFolderDocumentListTab({{ Auth::user()->id }});"><i class="ri-folder-open-line"></i>Folder Uploads</a>
                                </li>
                                <li>
                                    <a href="javascript:;" id="trash_docs" onclick="MediaManager.setTrashedDocumentTab({{ Auth::user()->id }});"><i class="ri-delete-bin-3-line"></i>Trash</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="control_id" value=""/>
            <div class="loader" style="display:none;">
                <img src="{{ $CDN_PATH.'resources/images/media_loader.gif' }}">
            </div>
            <div class="right-panel docs_html" style="display:none"></div>
            <div class="right-panel docs_upload" style="display:none">	</div>
            <div class="right-panel user_uploaded_docs" style="display:none">	</div>
            <div class="right-panel trashed_docs" style="display:none">	</div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="deleteMediaDocument" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('template.common.alert') }}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body text-center">Are you sure you want to delete selected documents? </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1 remove_multiple_document" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-delete-bin-line label-icon align-middle fs-20 me-2"></i></div> {{ trans('template.common.delete') }}
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Close
                </button>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="alertModalForDocument" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('template.common.alert') }}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
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
<div class="new_modal modal fade" id="permanentDeleteMediaDocument" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('template.common.alert') }}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body text-center">Are you sure you want to delete selected documents permanently?</div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1 remove_multiple_document_permanently" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-delete-bin-line label-icon align-middle fs-20 me-2"></i></div> {{ trans('template.common.delete') }}
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Close
                </button>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="emptyTrashMediaDocument" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('template.common.alert') }}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body text-center">Are you sure you want to empty trash?</div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1 empty_trash_Document" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-check-line label-icon align-middle fs-20 me-2"></i></div> {{ trans('template.common.yes') }}
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> No
                </button>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="restoreDocumentConfirmBox" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('template.common.alert') }}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body text-center">Are you sure you want to restore selected document(s)?</div>
            <div class="modal-footer justify-content-center bg-gradient waves-effect waves-light btn-label me-1">
                <button type="button" class="btn btn-primary restore_multiple_documents" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-restart-line label-icon align-middle fs-20 me-2"></i></div> Restore
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Close
                </button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
            window.user_id = '{{ Auth::user()->id }}';
            window.segment = '{{ Request::segment(2) }}';
</script>