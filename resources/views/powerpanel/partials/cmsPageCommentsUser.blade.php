<div class="new_modal modal fade bs-modal-md" id="CmsPageComments1User" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-vertical">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    Comments
                </div>
                <div class="modal-body CmsPageComments1User">
                    <div class="prv_commnent">
                        <ul id="test"></ul>
                    </div>
                    <hr>
                    {!! Form::open(['method' => 'post','class'=>'CommentsForm']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::hidden('id','',array('id' => 'id')) !!}
                                {!! Form::hidden('intRecordID','',array('id' => 'intRecordID')) !!}
                                {!! Form::hidden('fkMainRecord','',array('id' => 'fkMainRecord')) !!}
                                {!! Form::hidden('varModuleNameSpace','',array('id' => 'varModuleNameSpace')) !!}
                                {!! Form::hidden('intCommentBy','',array('id' => 'intCommentBy')) !!}
                                {!! Form::hidden('varModuleTitle','',array('id' => 'varModuleTitle')) !!}
                                <div class="clearfix"></div>
                                {!! Form::textarea('CmsPageComments_user' ,'', array('class'=>'form-control','id'=>'CmsPageComments_user','rows'=>'3','maxlength'=>'500')) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" name="saveandexit" class="btn btn-green-drake" value="saveandexit">Submit</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="new_modal modal fade bs-modal-md" id="CommentAdded_user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-vertical">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    Alert!
                </div>
                <div class="modal-body CommentAdded_user text-center"></div>
                <div class="modal-footer">
                    <button type="button" id="ApprovedSuccess"  class="btn green btn-outline">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/jquery.min.js' }}" type="text/javascript"></script>
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/comment_validations_user.js' }}" type="text/javascript"></script>