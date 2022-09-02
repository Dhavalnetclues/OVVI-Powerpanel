@if(Config::get('Constant.DEFAULT_EMAILTOFRIENDOPTION') == "Y")
<div id="Modal_emailtofriend" class="modal email_modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Email to Friend</h4>
			</div>
			<div class="modal-body">
				{!! Form::open(['method' => 'post','url' => url('emailtofriend'),'class'=>'form_control equalizer emailtofriend_form','id'=>'emailtofriend_form']) !!}
				{!! Form::hidden('CurrentPageUrl', Request::fullUrl()) !!}
				<div class="row">
					<div class="col-sm-6 col-12">
						<div class="form-group">
							{!! Form::text('varEmailName', old('varEmailName') , array('id' => 'varEmailName','class' => 'form-control','maxlength'=>"150", 'placeholder'=>'Name*')) !!}
						</div>
					</div>
					<div class="col-sm-6 col-12">
						<div class="form-group">
							{!! Form::text('varFrommEmail', old('varFrommEmail') , array('id' => 'varFrommEmail', 'class' => 'form-control','maxlength'=>"150",'placeholder'=>'Email*')) !!}
						</div>
					</div>
					<div class="col-sm-6 col-12">
						<div class="form-group">
							{!! Form::text('varFriendName', old('varFriendName') , array('id' =>'varFriendName', 'class' => 'form-control','maxlength'=>"150",'placeholder'=>"Friend's Name*")) !!}
						</div>
					</div>
					<div class="col-sm-6 col-12">
						<div class="form-group">
							{!! Form::text('varFriendEmail', old('varFriendEmail') , array('id' => 'varFriendEmail', 'class' => 'form-control','maxlength'=>"150",'placeholder'=>"Friend's Email*")) !!}
						</div>
					</div>
					<div class="col-sm-12 col-12">
						<div class="form-group">
							{!! Form::textarea('txtEmailMessage', old('txtEmailMessage') , array( 'class' =>'form-control', 'id' => 'txtEmailMessage', 'maxlength'=>"500",'spellcheck' =>'true' )) !!}
						</div>
					</div>
					<div class="col-sm-12 col-12">
						<div class="captcha_contact">
							<div class="captcha_div form-group">
								<div id="html_element_email_to_friend" class="g-recaptcha"></div>
								<div class="capphitcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}">
									@if (isset($errors) && $errors->has('g-recaptcha-response'))
									<span class="help-block">
										{{ $errors->first('g-recaptcha-response') }}
									</span>
									@endif
								</div>
							</div>
							<button id="emailtofriend_submit" type="submit" class="ac-btn-primary btn btn-more" title="Submit">Submit</button>
						</div>
					</div>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endif