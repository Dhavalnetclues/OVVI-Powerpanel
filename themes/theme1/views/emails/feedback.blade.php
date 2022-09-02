<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{{$SITE_NAME}}</title>
		<style type="text/css">
		body {}
		table {
			border-collapse: collapse
		}
		table td {
			border-collapse: collapse
		}
		img {
			border: none;
		}
		</style>
	</head>
	<body style="padding:15px;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top">
					<table width="600" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif">
						<tr>
							<td valign="middle">
								<a href="{{ url('/') }}" title="{{$SITE_NAME}}" target="_blank"><img src="{!! App\Helpers\resize_image::resize($FRONT_LOGO_ID) !!}" alt="{{$SITE_NAME}}"></a>
							</td>
						</tr>
						<tr>
							<td style="border-bottom:1px solid #ccc;">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" valign="top" bgcolor="#fff" style="padding:20px 0;">
								<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
									<tr>
										<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:30px;">Dear {{ $user }},</td>
									</tr>
									<tr>
										<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;">A website visitor {{ $varName }} has given the feedback for {{ $SITE_NAME }}.</td>
									</tr>
									<tr>
										<td align="center" valign="top">&nbsp;</td>
									</tr>
									<tr>
										<td>
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td height="20" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:30px;">Below are the feedback details.:</td>
												</tr>
												<tr>
													<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>Name:</strong> {{ $varName }}</td>
												</tr>
												<tr>
													<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>Email:</strong> <a href="mailto:{{ $varEmail }}" target="_blank" style="text-decoration:none; color:#000;" title="{{ $varEmail }}">{{ $varEmail }}</a></td>
												</tr>
												<tr>
													<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>Phone:</strong> {{ $varPhoneNo }} </td>
												</tr>
												<tr>
													<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>Satisfaction of page:</strong> {{ $chrSatisfied }} </td>
												</tr>
												<tr>
													<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>What was the reason for visit?:</strong> {{ $varVisitfor }} </td>
												</tr>
												<tr>
													<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>Feedback category:</strong> {{ $chrCategory }} </td>
												</tr>
												@if(isset($txtUserMessage) && !empty($txtUserMessage))
												<tr>
													<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>Message:</strong> {{ $txtUserMessage }}</td>
												</tr>
												@endif
												<tr>
													<td>&nbsp;</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:24px;"><strong>Best Regards,</strong></td>
									</tr>
									<tr>
										<td style="line-height:24px">
											<a href="{{ url('/') }}" target="_blank" style="font-family:Arial, Helvetica, sans-serif; text-decoration:none; color:#000; font-size:15px;" title="{{ parse_url(url('/'), PHP_URL_HOST).parse_url(url('/'), PHP_URL_PATH) }}">{{ $SITE_NAME }}</a></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td align="center" valign="top">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" valign="top" style="border-top:1px solid #ccc;">
								<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
									<tr>
										<td style="line-height:15px;">&nbsp;</td>
									</tr>
									<tr>
										<td align="center" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; ; line-height:16px;">&nbsp;</td>
									</tr>
									<tr>
										<td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; ; line-height:16px;">
											Copyright &copy; 2002 - {!! date('Y') !!} {{$SITE_NAME}}. All Rights Reserved.
										</td>
									</tr>
									<tr>
										<td align="center" valign="top">&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>