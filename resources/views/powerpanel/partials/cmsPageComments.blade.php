<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" id="CmsPageComments1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body CmsPageComments1">
                <div class="prv_commnent" id="test"> </div>
                <!-- <hr> -->
                {!! Form::open(['method' => 'post','class'=>'CommentsForm']) !!}
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="cm-floating">
                            {!! Form::hidden('id','',array('id' => 'id')) !!}
                            {!! Form::hidden('UserID','',array('id' => 'UserID')) !!}
                            {!! Form::hidden('fkMainRecord','',array('id' => 'fkMainRecord')) !!}
                            {!! Form::hidden('namespace',Config::get('Constant.MODULE.MODEL_NAME'),array('id' => 'namespace')) !!}
                            {!! Form::hidden('varModuleTitle',$module,array('id' => 'varModuleTitle')) !!}
                            {!! Form::hidden('varModuleId',Config::get('Constant.MODULE.ID'),array('id' => 'varModuleID')) !!}
                            <div class="clearfix"></div>
                            <label for="subject">Comments</label>
                            {!! Form::textarea('CmsPageComments' ,'', array('class'=>'form-control','id'=>'CmsPageComments','rows'=>'3','maxlength'=>'500')) !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="">
                            <button type="submit" name="saveandexit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label" value="saveandexit">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="ri-send-plane-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Submit
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" id="CommentAdded" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alert!</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body CommentAdded text-success"></div>
            <div class="modal-footer justify-content-start">
                <button type="button" id="ApprovedSuccess" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            Ok
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/jquery.min.js' }}" type="text/javascript"></script>
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/comment_validations.js' }}" type="text/javascript"></script>