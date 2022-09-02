<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="sectiontwoContent" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {!! Form::open(['method' => 'post','id'=>'frmSectionTwoContent']) !!}
            <input type="hidden" name="editing">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">2 Part Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="control-label col-form-label">Left Side Content</label>
                    <textarea class="form-control item-data" name="leftcontent" id="leftck-area" column="40" rows="10"></textarea>
                </div>
                <div class="mb-3">
                    <label class="control-label col-form-label">Right Side Content</label>
                    <textarea class="form-control item-data" name="rightcontent" id="rightck-area" column="40" rows="10"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light cancel-btn" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="addSection">Add</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>