<div class="new_modal modal fade bs-modal-md" id="CmsPageComments1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-vertical">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    Comments
                </div>
                <div class="modal-body CmsPageComments1">
                    <div class="prv_commnent">
                        <!--<h4 class="text-center">Previous Comments</h4>-->
                        <ul id="test"></ul>
                    </div>
                    <hr>
                    {!! Form::open(['method' => 'post','class'=>'CommentsForm']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::hidden('id','',array('id' => 'id')) !!}
                                {!! Form::hidden('UserID','',array('id' => 'UserID')) !!}
                                {!! Form::hidden('fkMainRecord','',array('id' => 'fkMainRecord')) !!}
                                {!! Form::hidden('namespace',Config::get('Constant.MODULE.MODEL_NAME'),array('id' => 'namespace')) !!}
                                {!! Form::hidden('varModuleTitle',$module,array('id' => 'varModuleTitle')) !!}
                                {!! Form::hidden('varModuleId',Config::get('Constant.MODULE.ID'),array('id' => 'varModuleID')) !!}
                                <div class="clearfix"></div>
                                {!! Form::textarea('CmsPageComments' ,'', array('class'=>'form-control','id'=>'CmsPageComments','rows'=>'3','maxlength'=>'500')) !!}
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
<div class="new_modal modal fade bs-modal-md" id="CommentAdded" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-vertical">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    Alert!
                </div>
                <div class="modal-body CommentAdded text-center"></div>
                <div class="modal-footer">
                    <button type="button" id="ApprovedSuccess"  class="btn green btn-outline">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/jquery.min.js' }}" type="text/javascript"></script>
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/comment_validations.js' }}" type="text/javascript"></script>