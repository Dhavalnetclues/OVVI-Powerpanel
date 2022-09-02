<div class="col-md-6">
    <div class="portlet gallary_manager light Design-preview Design-settings portlet-fullscreen media-manag" id="audio_component" style="display:none;">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-picture font-grey-gallery"></i>
                <span class="caption-subject bold font-grey-gallery uppercase">Media Manager</span>
                <span class="caption-helper">All Media in one place</span>
            </div>
            <div class="tools">
                <input type="text" class="hide media_input" name="audioName"  placeholder="Search by Audio Name">
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
                                    <a class="active" id="upload_audio" href="javascript:;" onclick="MediaManager.setAudioUploadTab();" ><i class="icon-cloud-upload icons"></i>Upload Audio</a>
                                </li>
                                <li>
                                    <a href="javascript:;" id="user_uploaded_audios" onclick="MediaManager.setAudioListTab({{ Auth::user()->id }});"><i class="fa fa-file-audio-o"></i>My Audios</a>
                                </li>
                                <li>
                                    <a href="javascript:;" id="folder_uploaded_audios" onclick="MediaManager.setFolderAudioListTab({{ Auth::user()->id }});"><i class="fa fa-folder-open-o"></i>Folder Uploads</a>
                                </li>
                                <li>
                                    <a href="javascript:;" id="trash_audios" onclick="MediaManager.setTrashedAudioTab({{ Auth::user()->id }});"><i class="icon-trash"></i>Trash</a>
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
            <div class="right-panel audios_html" style="display:none"></div>
            <div class="right-panel audios_upload" style="display:none">	</div>
            <div class="right-panel user_uploaded_audios" style="display:none">	</div>
            <div class="right-panel trashed_audios" style="display:none">	</div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="deleteMediaAudio" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-vertical">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    {{ trans('template.common.alert') }}
                </div>
                <div class="modal-body text-center">Are you sure you want to delete selected audios? </div>
                <div class="modal-footer">
                    <button type="button" class="btn red btn-outline remove_multiple_audio" data-dismiss="modal">{{ trans('template.common.delete') }}</button>
                    <button type="button" class="btn btn-green-drake" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="alertModalForAudio" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-vertical">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    {{ trans('template.common.alert') }}
                </div>
                <div class="modal-body text-center alert_msg"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-green-drake" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="permanentDeleteMediaAudio" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-vertical">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    {{ trans('template.common.alert') }}
                </div>
                <div class="modal-body text-center">Are you sure you want to delete selected audios permanently?</div>
                <div class="modal-footer">
                    <button type="button" class="btn red btn-outline remove_multiple_audio_permanently" data-dismiss="modal">{{ trans('template.common.delete') }}</button>
                    <button type="button" class="btn btn-green-drake" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="emptyTrashMediaAudio" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-vertical">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    {{ trans('template.common.alert') }}
                </div>
                <div class="modal-body text-center">Are you sure you want to empty trash?</div>
                <div class="modal-footer">
                    <button type="button" class="btn red btn-outline empty_trash_Audio" data-dismiss="modal">{{ trans('template.common.yes') }}</button>
                    <button type="button" class="btn btn-green-drake" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="restoreAudioConfirmBox" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-vertical">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    {{ trans('template.common.alert') }}
                </div>
                <div class="modal-body text-center">Are you sure you want to restore selected items(s)?</div>
                <div class="modal-footer">
                    <button type="button" class="btn red btn-outline restore_multiple_audios" data-dismiss="modal">Restore</button>
                    <button type="button" class="btn btn-green-drake" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
            window.user_id = '{{ Auth::user()->id }}';
            window.segment = '{{ Request::segment(2) }}';
</script>