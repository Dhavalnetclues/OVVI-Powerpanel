<div class="col-md-6">
	<div class="portlet gallary_manager light Design-preview Design-settings portlet-fullscreen media-manag" id="video_component" style="display:none;">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-picture font-grey-gallery"></i>
				<span class="caption-subject bold font-grey-gallery uppercase">Media Manager</span>
				<span class="caption-helper">All Media in one place</span>
			</div>
			<div class="tools">
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
									<a href="javascript:;" id="upload_video" onclick="MediaManager.setMyUploadVideoTab()"><i class="icon-cloud-upload icons"></i>Upload Video</a>
								</li>
								<li>
									<a href="javascript:;" id="insert_video_frm_url" onclick="MediaManager.setVideoFromUrlTab()"><i class="ri-external-link-line"></i>Insert Video by Url</a>
								</li>
								<li>
									<a href="javascript:;" id="user_uploaded_video" onclick="MediaManager.setMyVideosTab({{ Auth::user()->id }});"><i class="fa fa-video-camera reverse_color"></i>Videos</a>
								</li>
								<li>
									<a href="javascript:;" id="trash_video" onclick="MediaManager.setTrashedVideoTab({{ Auth::user()->id }});"><i class="icon-trash"></i>Trash</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" id="data_id" value=""/>
			<input type="hidden" id="videoRecordId" value=""/>
			<div class="loader" style="display:none;">
				<img src="{{ $CDN_PATH.'resources/images/media_loader.gif' }}">
			</div>
			<div class="right-panel video_upload" style="display:none">	</div>
			<div class="right-panel user_uploaded_video" style="display:none"></div>
			<div class="right-panel insert_video_from_url" style="display:none"></div>
			<div class="right-panel trashed_videos" style="display:none">	</div>
		</div>
	</div>
</div>
<div class="new_modal modal fade" id="deleteMediaVideo" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-vertical">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					{{ trans('template.common.alert') }}
				</div>
				<div class="modal-body text-center">Are you sure you want to delete selected video(s)? Videos are may be used in another records. </div>
				<div class="modal-footer">
					<button type="button" class="btn red btn-outline remove_multiple_videos" data-dismiss="modal">{{ trans('template.common.delete') }}</button>
					<button type="button" class="btn btn-green-drake" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="new_modal modal fade" id="alertModalForVideo" tabindex="-1" role="basic" aria-hidden="true">
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
<div class="new_modal modal fade" id="restoreVideoConfirmBox" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-vertical">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					{{ trans('template.common.alert') }}
				</div>
				<div class="modal-body text-center">Are you sure you want to restore selected items(s)?</div>
				<div class="modal-footer">
					<button type="button" class="btn red btn-outline restore_multiple_videos" data-dismiss="modal">Restore</button>
					<button type="button" class="btn btn-green-drake" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="new_modal modal fade" id="permanentDeleteMediaVideo" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-vertical">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					{{ trans('template.common.alert') }}
				</div>
				<div class="modal-body text-center">Are you sure you want to delete selected video(s) permanently?</div>
				<div class="modal-footer">
					<button type="button" class="btn red btn-outline remove_multiple_videos_permanently" data-dismiss="modal">{{ trans('template.common.delete') }}</button>
					<button type="button" class="btn btn-green-drake" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="new_modal modal fade" id="emptyTrashMediaVideo" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-vertical">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					{{ trans('template.common.alert') }}
				</div>
				<div class="modal-body text-center">Are you sure you want to empty trash?</div>
				<div class="modal-footer">
					<button type="button" class="btn red btn-outline empty_trash_Video" data-dismiss="modal">{{ trans('template.common.yes') }}</button>
					<button type="button" class="btn btn-green-drake" data-dismiss="modal">No</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
						window.user_id = '{{ Auth::user()->id }}';
						window.segment = '{{ Request::segment(2) }}';
</script>