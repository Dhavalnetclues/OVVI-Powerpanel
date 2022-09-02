<div class="new_modal new_share_popup modal fade bs-modal-md" id="addCommentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-vertical">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    Add Comment
                </div>
                <div class="modal-body delMsg text-center">
                    <form role="form" id='frmAddComment'>
                        <div class="form-body">
                            <div class="form-group ">
                                <label class="form_title" for="site_name">Comment <span aria-required="true" class="required"> * </span></label>
                                {!! Form::textarea('comment', null, array('maxlength' => 150, 'class' => 'form-control','placeholder' => 'Comment','autocomplete'=>'off','id'=>'modelComment')) !!}
                                {!! Form::hidden('leadid', null, array('id' => 'selectedlead')) !!}
                                {!! Form::hidden('commentAction', null, array('id' => 'CommentAction')) !!}

                                <span id="commentErr" class="hide" style="color: red;">
                                    Title field is required
                                </span>
                            </div>
                            <a href="javascript:;" data-module="{{$module}}" class="btn btn-green-drake" id="saveComment">Save</a>
                            <a href="javascript:;" class="btn btn-outline red" data-dismiss="modal" aria-hidden="true">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>