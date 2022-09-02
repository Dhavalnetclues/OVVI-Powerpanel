<div class="new_modal new_share_popup modal fade bs-modal-md" id="addCatModal" tabindex="-1" role="dialog" aria-hidden="true">
	 <div class="modal-dialog modal-md">
			<div class="modal-vertical">
				 <div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							 Add new Category
						</div>
						<div class="modal-body delMsg text-center">
							 <form role="form" id='frmAddCat'>
									<div class="form-body">
										<div class="form-group ">
										<label class="form_title" for="site_name">{{ trans('template.common.title') }} <span aria-required="true" class="required"> * </span></label>
											{!! Form::text('title', null, array('maxlength' => 150, 'class' => 'form-control','data-url' => 'powerpanel/'.$module,'placeholder' => trans('template.common.title'),'autocomplete'=>'off')) !!}
											{!! Form::hidden('selected', null, array('id' => 'selectedCat')) !!}
											
										<span id="catErr" class="hide" style="color: red;">
													Title field is required
											</span>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
													<label class="form_title" for="parent_category_id">{{ trans('template.common.selectparentcategory') }}</label>
														{!!  $categoryHeirarchy !!}
													</div>
												</div>
											</div>
										<a href="javascript:;" data-module="{{$module}}" class="btn btn-green-drake" id="addCat">Add</a>
										<a href="javascript:;" class="btn btn-outline red" data-dismiss="modal" aria-hidden="true">Cancel</a>
									</div>
							 </form>
						</div>
				 </div>
			</div>
	 </div>
</div>