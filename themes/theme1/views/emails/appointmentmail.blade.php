<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Book an Appointment - {{$SITE_NAME}}</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: 'Segoe UI', 'Segoe WP', 'Segoe UI Regular', 'Helvetica Neue', Helvetica, Tahoma, 'Arial Unicode MS', Sans-serif;
        }
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
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="left" valign="top"><table width="600" border="0" align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left" valign="middle" style="padding:15px 0;">
                  <a href="{{ url('/') }}" target="_blank" title="{{$SITE_NAME}}"><img src="{!! App\Helpers\resize_image::resize($FRONT_LOGO_ID) !!}" alt="{{$SITE_NAME}}" width="200" height="auto" style="display:inline-block;" /></a>
                </td>
            </tr>
            @if($user=='admin')
            <tr>
                <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:600; color:#222222;">Hello Admin,</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#222222; padding:5px 0 0 0;">A new person has booked appointment, below are the details of that person:</td>
            </tr>
            @else
            <tr>
                <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:600; color:#222222;">
                  Hello {{ $first_name }},
                </td>
            </tr>
            <tr>
                <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#222222; padding:5px 0 0 0;">
                  Thank you! we have received  your appointment request for {{ $appointment_date }}. Someone from our staff will contact you shortly via <br/>email or telephone. In the meantime, feel free to visit our website for more information, or call directly if your concern is <br/>time-sensitive.
                </td>
            </tr>
            @endif
            @if($user=='admin')
            <tr>
                <td align="left" valign="top" style="padding:15px 0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td width="20%" align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:600; color:#222222; padding:0 0 5px 0;">Name :</td>
                            <td width="80%" align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#222222; padding:0 0 5px 0; text-transform:capitalize;">{{ $first_name }}</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:600; color:#222222; padding:0 0 5px 0;">Email ID :</td>
                            <td align="left" valign="top" style="padding:0 0 5px 0;"><a href="mailto:{{ $email }}" target="_blank" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#222222; text-decoration:none;">{{ $email }}</a></td>
                        </tr>
                        @if(isset($phone_number) && !empty($phone_number))
                        <tr>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:600; color:#222222; padding:0 0 5px 0;">Phone :</td>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#222222; padding:0 0 5px 0;">{{ $phone_number }}</td>
                        </tr>
                        @endif
                        @if(isset($service_name) && !empty($service_name))
                        <tr>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:600; color:#222222; padding:0 0 5px 0;">Service :</td>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#222222; padding:0 0 5px 0;">{{ $service_name }}</td>
                        </tr>
                        @endif
                        @if(isset($appointment_date) && !empty($appointment_date))
                        <tr>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:600; color:#222222; padding:0 0 5px 0;">Appoint Date :</td>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#222222; padding:0 0 5px 0;">{{ $appointment_date }}</td>
                        </tr>
                        @endif
                        @if(isset($user_message) && !empty($user_message))
                        <tr>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:600; color:#222222; padding:0 0 5px 0;">Message :</td>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#222222; padding:0 0 5px 0;">{{ $user_message }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
              </td>
            </tr>
            @endif
            <tr>
                <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:600; color:#222222; padding:0;">Best Regards,</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding:0 0 15px 0;"><a href="{{ url('/') }}" target="_blank" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#222222; text-decoration:none;">{{ $SITE_NAME }}</a></td>
            </tr>
            <tr>
                <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#222222; padding:15px 0 0 0; border-top:1px solid #e8e8e8;">{!!$FOOTER_COPYRIGHTS!!} {!! date('Y') !!} {{$SITE_NAME}}. All Rights Reserved.</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#222222; padding:0 0 0 0;">Powered by <a href="https://netclues.com/" rel="nofollow" target="_blank" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#222222; text-decoration:none;">Netclues!</a></td>
            </tr>
        </table></td>
    </tr>
</table>
</body>
</html>
