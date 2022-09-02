<div class="modal fade ac-modal" id="rsvp" tabindex="-1" aria-labelledby="rsvpLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<div class="n-fs-18 n-fw-600 n-ff-2 n-fc-white-500 n-lh-130">RSVP Registration</div>
				<a href="javascript:void(0)" data-dismiss="modal" aria-label="Close" class="ac-close">&times;</a>
			</div>

			<div class="modal-body ac-form-wd">
				
				{!! Form::open(['method' => 'post','class'=>'w-100 eventRSVP_form','id'=>'eventRSVP_form']) !!}
					<input class="form-control" type="hidden" id="eventId" name="eventId" value="" />
					<div class="row">
						<div class="col-sm-12 n-mb-45 text-center">
							<h2 class="nqtitle-small n-fw-500" id="eventTitle"></h2>
						</div>
						<div class="col-lg-4 col-sm-6">
							<div class="form-group ac-form-group ac-active-select">
								<label class="ac-label" for="event_date">Event Date <span class="star">*</span></label>
								<select class="form-control" data-width="100%" data-size="5" title="Select Event Date"  name="event_date" id="event_date" data-choices>
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-sm-6">
							<div class="form-group ac-form-group ac-active-select">
								<label class="ac-label" for="event_time">Event Time <span class="star">*</span></label>
								<select class="form-control" data-width="100%" data-size="5"  title="Select event date to show time" name="event_time" id="event_time"></select>
							</div>
						</div>
						<div class="col-lg-4 col-sm-6">
							<div class="form-group ac-form-group ac-active-select">
								<label class="ac-label" for="no_of_attendee">No of Attendees<span class="star">*</span></label>
								<select class="form-control" data-width="100%" data-size="5"  name="no_of_attendee" id="no_of_attendee" title="Select attendees">
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12" id="attendeList">
							<div class="row" id="attendeList0">
								<div class="col-lg-4 col-sm-6">
									<div class="form-group ac-form-group">
										<label class="ac-label" for="firstName">Name <span class="star">*</span></label>
										{!! Form::text('attendee[0][full_name]', '', array('id'=>'full_name0', 'class'=>'form-control ac-input', 'maxlength'=>'60', 'onpaste'=>'return false;', 'placeholder'=>'First Attendees Name', 'ondrop'=>'return false;')) !!}
										@if ($errors->has('full_name'))
											<span class="error">{{ $errors->first('full_name') }}</span>}}
										@endif
									</div>
								</div>
								<div class="col-lg-4 col-sm-6">
									<div class="form-group ac-form-group">
										<label class="ac-label" for="email">Email <span class="star">*</span></label>
										{!! Form::text('attendee[0][email]', '', array('id'=>'email0', 'class'=>'form-control ac-input',  'spellcheck'=>'true','autocomplete'=>'off', 'placeholder'=>'First Attendees Email','maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
										@if ($errors->has('email'))
											<span class="error">{{ $errors->first('email') }}</span>
										@endif
									</div>
								</div>
								<div class="col-lg-4 col-sm-6">
									<div class="form-group ac-form-group">
										<label class="ac-label" for="email">Phone</label>
										{!! Form::text('attendee[0][phone]', '', array('id'=>'phone0', 'class'=>'form-control ac-input',  'spellcheck'=>'true','autocomplete'=>'off', 'placeholder'=>'First Attendees Phone', 'maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;','onkeypress'=>'javascript: return KeycheckOnlyPhonenumber(event);')) !!}
										@if ($errors->has('phone'))
											<span class="error">{{ $errors->first('phone') }}</span>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group ac-form-group">
								<label class="ac-label" for="message">Message</label>
								{!! Form::textarea('message', old('message'), array('id'=>'message', 'class'=>'form-control ac-textarea', 'rows'=>'4', 'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'600', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
								@if ($errors->has('message'))
									<span class="error">{{ $errors->first('message') }}</span>
								@endif
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group ac-form-group">
								<div id="rsvp_html_element" class="g-recaptcha"></div>
								<div class="capphitcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}">
									@if ($errors->has('g-recaptcha-response'))
										<span class="error">{{ $errors->first('g-recaptcha-response') }}</span>
									@endif
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group ac-form-group n-tar-sm n-tal">
								<button id="event_rsvp" type="submit" title="Register" class="ac-btn ac-btn-primary">Register</button>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group ac-form-group n-mb-0">
								<div class="ac-note">
									<b>Note:</b> In this, you can register a minimum of 1 and a maximum of 5 people, if you want to register more people, please fill the form again or contact the Netclues team. And this is an offline process, so please contact the Netclues team once you registered. <a href="/contact-us" target="_blank" title="Contact Detail">Contact Detail</a>.
								</div>
							</div>
						</div>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>