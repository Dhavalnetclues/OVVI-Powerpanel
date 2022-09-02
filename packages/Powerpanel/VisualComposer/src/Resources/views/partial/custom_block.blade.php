<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="customSectionBase" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      {!! Form::open(['method' => 'post','id'=>'frmCustomSectionBase']) !!}
      <input type="hidden" name="editing">      
      <input type="hidden" name="template" value="custom-section">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Custom Cards</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
          <div class="mb-3">
            <label class="control-label col-form-label">Title<span aria-required="true" class="required"> * </span></label>
            {!! Form::text('title', old('title'), array('maxlength'=>'160','class' => 'form-control','id'=>'title','autocomplete'=>'off')) !!}
          </div>
          <div class="mb-3">
            <label class="control-label col-form-label">Subtitle</label>
            {!! Form::text('subtitle', old('subtitle'), array('maxlength'=>'160','class' => 'form-control','id'=>'subtitle','autocomplete'=>'off')) !!}
          </div>
          <div class="mb-3">
             <label class="control-label col-form-label">Layout<span aria-required="true" class="required"> * </span></label>
             <select class="form-control" data-choices name="layoutType" id="business-layout">
                <option value="">Select Layout</option>
                <option  value="grid">Link Grid</option>
                <option  value="list">Link List</option>
                <option selected value="slider">Link Slider</option>
             </select>
          </div>
          <div class="mb-3">
            <label class="control-label col-form-label">Extra Class</label>
            {!! Form::text('extra_class', old('extra_class'), array('maxlength'=>'160','class' => 'form-control','id'=>'extraClass','autocomplete'=>'off')) !!}
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