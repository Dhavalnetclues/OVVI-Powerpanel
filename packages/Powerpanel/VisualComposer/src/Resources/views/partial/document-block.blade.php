<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectionOnlyDocument" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionOnlyDocument']) !!}
            <input type="hidden" name="editing">
            <div class="modal-header mb-2">
                <h5 class="modal-title" id="exampleModalLabel">Document</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                <div class="cm-floating">
                    <label class="control-label col-form-label">Caption <span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('caption', old('caption'), array('maxlength'=>'160','class' => 'form-control','id'=>'caption','autocomplete'=>'off')) !!}
                </div>
                <div class="cm-floating">
                    <label class="control-label col-form-label">Date</label>
                    {!! Form::text('doc_date_time', old('doc_date_time'), array('maxlength'=>'160','class' => 'form-control','id'=>'doc_date_time', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'),'autocomplete'=>'off')) !!}
                </div>
                @php $imgkey = 1; @endphp
                <div class="mb-30 img_1" id="img1">
                    <div class="team_box cm-documentbox text-center">
                        <div class="thumbnail_container">
                            <a onclick="MediaManager.openDocumentManager('Composer_doc');" data-multiple='true' data-selected="1" class=" btn-green-drake document_manager pgbuilder-img image_gallery_change_1" title="" href="javascript:void(0);">
                                <div class="thumbnail photo_gallery_1">
                                    <img src="{!! $CDN_PATH.'assets/images/packages/visualcomposer/plus-no-image.png' !!}">
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
            </div>
            <div class="modal-footer justify-content-start">                
                <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" id="addSection">
                    <div class="flex-shrink-0"><i class="ri-add-line label-icon align-middle fs-20 me-2"></i></div> Add
                </button>
                <button type="button" class="btn btn-danger bg-gradient waves-effect waves-light btn-label cancel-btn" data-bs-dismiss="modal">
                    <div class="flex-shrink-0"><i class="ri-close-line label-icon align-middle fs-20 me-2"></i></div> Cancel
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>