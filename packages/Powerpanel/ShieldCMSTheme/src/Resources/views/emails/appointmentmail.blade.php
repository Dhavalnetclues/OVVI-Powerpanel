<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{{$SITE_NAME}}</title>
		<style type="text/css">
		body{margin:0;padding:0;}
		table{border-collapse: collapse}
		table td{border-collapse: collapse}
		img{border:none;}
		@media (max-width: 1920px) {
		a[href*="tel:"] { pointer-events: none; color: #000; text-decoration: none;}
		}
		@media (max-width: 1190px) {
		a[href*="tel:"] { pointer-events: painted; color: #000; text-decoration: none}
		}
		</style>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center" valign="top"><table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; margin:15px 0;">
					<tr>
						<td height="5" align="left" valign="top" bgcolor="#092038" style="height:5px;"></td>
					</tr>
					<tr>
						<td align="center" valign="middle" bgcolor="#f5f5f5" style="padding:3%;">
							<a href="{{url('/')}}" target="_blank" title="{{$SITE_NAME}}" style="text-decoration:none">
								<img src="{!! App\Helpers\resize_image::resize($FRONT_LOGO_ID,287,100) !!}" alt="{{$SITE_NAME}}" />
							</a>
						</td>
					</tr>
					<tr>
						<td align="center" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="padding:15px; border:1px solid #f5f5f5;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
									@if($user=='admin')
									<tr>
										<td style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:18px; font-weight:600; line-height:26px; color:#333534; padding:0 0 1% 0;">Dear <span style="color:#373435;">Administrator</span>,</td>
									</tr>
									<tr>
										<td style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:20px; color:#333534; padding:0 0 4% 0;">A new person has contacted us, below are the details of that person:<br /></td>
									</tr>
									@else
									<tr>
										<td style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:18px; font-weight:600; line-height:26px; color:#333534; padding:0 0 1% 0;">Dear <span style="color:#373435;">{{ $first_name }}</span>,</td>
									</tr>
									<tr>
										<td style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:20px; color:#333534; padding:0 0 4% 0;">We have received your query/feedback which has been forwarded to concern authorities.<br/>You should receive a reply within 48 hours from one of our executive asking for more details on your query/feedback if needed.</td>
									</tr>
									@endif
									@if($user=='admin')
									<tr>
										<td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #092038;">
											<tr>
												<td colspan="2" align="left" valign="middle" bgcolor="#373435" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#fff; font-weight:400; font-size:18px; line-height:normal; padding:1% 2%;background-color: #092038">Enquirer Details:</td>
											</tr>
											<tr>
												<td width="15%" align="right" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:600; font-size:14px; padding:1%;">Name:</td>
												<td width="85%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:400; font-size:14px; padding:1%;">{{ $first_name }}</td>
											</tr>
											<tr>
												<td align="right" valign="middle" bgcolor="#f9f9f9" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif;padding:2%; color:#000; font-weight:600; font-size:14px;padding:1%;">Email:</td>
												<td align="left" valign="middle" bgcolor="#F9F9F9" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:400; font-size:14px; padding:1%;"><a href="mailto:{{ $email }}" target="_blank" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-weight:400; font-size:14px; text-decoration:none; color:#373435;" title="{{ $email }}">{{ $email }}</a></td>
											</tr>
											@if(isset($phone_number) && !empty($phone_number))
											<tr>
												<td align="right" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:600; font-size:14px; padding:1%;">Phone No:</td>
												<td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:400; font-size:14px; padding:1%;"><a href="tel:{{ $phone_number }}" title="{{ $phone_number }}" class="link">{{ $phone_number }}</a></td>
											</tr>
											@endif
											@if(isset($service_name) && !empty($service_name))
											<tr>
												<td align="right" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:600; font-size:14px; padding:1%;">Service:</td>
												<td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:400; font-size:14px; padding:1%;">{{ $service_name }}</td>
											</tr>
											@endif
											@if(isset($appointment_date) && !empty($appointment_date))
											<tr>
												<td align="right" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:600; font-size:14px; padding:1%;">Appoint Date:</td>
												<td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:400; font-size:14px; padding:1%;">{{ $appointment_date }}</td>
											</tr>
											@endif
											@if(isset($user_message) && !empty($user_message))
											<tr>
												<td align="right" valign="top" bgcolor="#f9f9f9" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:600; font-size:14px;padding:1%;">Message:</td>
												<td align="left" valign="top" bgcolor="#f9f9f9" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:400; font-size:14px; line-height:20px; padding:1%;">{{ $user_message }}</td>
											</tr>
											@endif
										</table></td>
									</tr>
									@endif
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:600; font-size:16px;">Best Regards,</td>
									</tr>
									<tr>
										<td align="left" valign="middle" style="text-decoration:none; font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#373435; font-weight:400; font-size:16px;"><a href="#" target="_blank" style="text-decoration:none; font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#373435; font-weight:400; font-size:16px;" title="testbynetclues@gmail.com">testbynetclues@gmail.com</a></td>
									</tr>
								</table></td>
							</tr>
						</table></td>
					</tr>
					<tr>
						<td align="center" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:600; font-size:16px; padding:2%"> Connect with Us </td>
					</tr>
					<tr>
						<td align="center" valign="middle">
							@if(isset($SOCIAL_FB_LINK) && !empty($SOCIAL_FB_LINK))
							<a href="{{$SOCIAL_FB_LINK}}" target="_blank" title="Facebook" style="text-decoration:none">
								<img src="{{ Config::get('Constant.CDN_PATH').'assets/images/socials/fb.png' }}" alt="Facebook"  width="35" height="35" />
							</a>
							@endif
							@if(isset($SOCIAL_TWITTER_LINK) && !empty($SOCIAL_TWITTER_LINK))
							<a href="{{$SOCIAL_TWITTER_LINK}}" target="_blank" title="Twitter" style="text-decoration:none">
								<img src="{{ Config::get('Constant.CDN_PATH').'assets/images/socials/tw.png' }}" alt="Twitter" width="35" height="35" />
							</a>
							@endif
							@if(isset($SOCIAL_LINKEDIN_LINK) && !empty($SOCIAL_LINKEDIN_LINK))
							<a href="{{$SOCIAL_LINKEDIN_LINK}}" target="_blank" title="Linkedin" style="text-decoration:none">
								<img src="{{ Config::get('Constant.CDN_PATH').'assets/images/socials/in.png' }}" alt="Linkedin" width="35" height="35" />
							</a>
							@endif
							@if(isset($Google_Plus_Link) && !empty($Google_Plus_Link))
							<a href="{{$Google_Plus_Link}}" target="_blank" title="Google Plus" style="text-decoration:none">
								<img src="{{ Config::get('Constant.CDN_PATH').'assets/images/socials/gplus.png' }}" alt="GooglePlus" width="35" height="35" />
							</a>
							@endif
						</td>
					</tr>
					<tr>
						<td align="center" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; color:#000; font-weight:400; font-size:14px; padding:1%" >Copyright &copy; {!! date('Y') !!} {{$SITE_NAME}}. All Rights Reserved. </td>
					</tr>
				</table></td>
			</tr>
		</table>
	</body>
</html>