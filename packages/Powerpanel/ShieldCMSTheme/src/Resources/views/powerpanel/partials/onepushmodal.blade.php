<div class="new_modal new_share_popup modal fade bs-modal-md" data-keyboard="false" data-backdrop="static" id="confirm_share" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-vertical">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    Netclues One Push
                </div>
                <div class="modal-body delMsg text-center">
                    <div class="row">								
                        <form role="form" id='frmshareoption' method="post">
                            <div class="form-body">
                                <div class="" id="shareDetailDivClass" style="padding: 10px;">
                                    <div class="form-group">
                                        <label>Post Description: <span aria-required="true" class="required"> * </span></label>
                                        <textarea name="txtDescription" id="txtDescription" class="form-control" placeholder="Post Description" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="image_thumb multi_upload_images">
                                                    <div class="form-group">
                                                        <label class="form_title" for="front_logo">{{ trans('template.common.selectimage') }} <span aria-required="true" class="required"> * </span></label>
                                                        <div class="clearfix"></div>
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail onePushImage_img" data-trigger="fileinput" style="width:100%;float:left; height:120px;position: relative;">
                                                                <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
                                                            </div>
                                                            <div class="input-group">
                                                                <a class="media_manager" data-multiple="false" onclick="MediaManager.open('onePushImage');"><span class="fileinput-new"></span></a>
                                                                <input class="form-control" type="hidden" id="onePushImage" name="socialImage" value="" />
                                                                    <input class="form-control" type="hidden" id="folder_id" name="folder_id" value="" />
                                                            
                                                                <input class="form-control" type="hidden" id="image_url" name="image_url" value="" />
                                                            </div>
                                                            <!-- <div class="overflow_layer">
                                                                <a onclick="MediaManager.open('onePushImage');" class="media_manager remove_img"><i class="ri-pencil-line"></i></a>
                                                                <a href="javascript:;" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                                            </div> -->
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        @php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp <span>{{ trans('template.common.imageSize',['height'=>$height, 'width'=>$width]) }}</span>
                                                    </div>
                                                    <span class="help-block">
                                                        {{ $errors->first('socialImage') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class=" img_1 my-5" id="img1">
                                            <div class="team_box">
                                                <div class="thumbnail_container">
                                                    <a onclick="MediaManager.open('photo_gallery', 1);" data-selected="1" class=" btn-green-drake media_manager pgbuilder-img image_gallery_change_1" title="" href="javascript:void(0);">
                                                        <div class="thumbnail photo_gallery_1">
                                                            <img src="{!! url('assets/images/packages/visualcomposer/plus-no-image.png') !!}">                  
                                                        </div>
                                                    </a>
                                                    <div class="nqimg_mask">
                                                        <div class="nqimg_inner">
                                                            <input class="image_1 item-data imgip" type="hidden" id="socialImage" data-type="image" name="socialImage" value=""/>
                                                            <input class="folder_1" type="hidden" id="vfolder_id" data-type="folder" name="vfolder_id" value=""/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>--}}
                                    </div>
                                    <div class="kt-divider"><span></span><span>FACEBOOK &nbsp;&nbsp;&&nbsp;&nbsp; TWITTER </span><span></span></div>
                                    <div class="form-group text-center">
                                        <div class="mt-radio-inline fb_share_social">
                                            <label class="checkbox-inline">
                                                <input name="socialmedia[]" class="socialShare" type="checkbox" value="facebook">
                                                <span class="btn btn-green-drake fb_btn">
                                                    <span class="check"></span>
                                                    <i class="fa fa-facebook"></i> Facebook
                                                </span>    
                                            </label> 
                                            <label class="checkbox-inline">
                                                <input name="socialmedia[]" class="socialShare" type="checkbox" value="twitter">
                                                <span class="btn btn-green-drake tw_btn">
                                                    <span class="check"></span>
                                                    <i class="fa fa-twitter"></i> Twitter
                                                </span>  
                                                
                                            </label> 
                                        </div>
                                    </div>
                                    <div id="social_preview"></div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-green-drake btn-push-social">Push</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>