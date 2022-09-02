<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Complaint - {{$SITE_NAME}}</title>
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
                <td height="15" align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" valign="top"><table width="600" border="0" align="left" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="left" valign="middle" style="padding:15px 0;">
                            <a href="{{ url('/') }}" target="_blank" title="{{$SITE_NAME}}"><img src="{!! App\Helpers\resize_image::resize($FRONT_LOGO_ID) !!}" alt="{{$SITE_NAME}}" width="120px" height="120px" style="display:inline-block;" /></a>
                        </td>
                    </tr>
                    @if($user=='admin')
                        <tr>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:10px 0;">Dear Administrator,</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:20px; font-weight:400; color:#000000; padding:10px 0;">{{ $first_name }} has submitted an application for {{ $careers }}. Below are the application details.</td>
                        </tr>
                    @else
                        <tr>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:10px 0;">
                            Dear ABC,
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:20px; font-weight:400; color:#000000; padding:10px 0;">
                                Your complaint has been submitted against  and below are the details for it:
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:13px; line-height:19.5px; font-weight:400; color:#000000; padding:5px 0 0 0;">
                            Applications Details :
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="padding:10px 0 0 0;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td width="30%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">First Name:</td>
                                        <td width="70%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px; text-transform:capitalize;">
                                        {{ $first_name }}</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Last Name:</td>
                                        <td width="70%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px; text-transform:capitalize;">
                                        {{ $last_name }}</td>
                                    </tr>
                                    @if(isset($phone_no) && !empty($phone_no))
                                        <tr>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Phone Number :</td>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px;">{{ $phone_no }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td width="30%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Email:</td>
                                        <td width="70%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px; text-transform:capitalize;">
                                        {{ $email }}</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Address 1:</td>
                                        <td width="70%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px; text-transform:capitalize;">
                                        {{ $address1 }}</td>
                                    </tr>
                                    @if(isset($address2) && !empty($address2))
                                        <tr>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Address 2:</td>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px;">{{ $address2 }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td width="30%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Country:</td>
                                        <td width="70%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px; text-transform:capitalize;">
                                        {{ $country }}</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">State:</td>
                                        <td width="70%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px; text-transform:capitalize;">
                                        {{ $state }}</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">City:</td>
                                        <td width="70%" align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px; text-transform:capitalize;">
                                        {{ $city }}</td>
                                    </tr>
                                    @if(isset($postalCode) && !empty($postalCode))
                                        <tr>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Postal Code:</td>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px;">{{ $postalCode }}</td>
                                        </tr>
                                    @endif
                                    @if(isset($dob) && !empty($dob))
                                        <tr>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Date Of Birth:</td>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px;">{{ $dob }}</td>
                                        </tr>
                                    @endif
                                    @if(isset($gender) && !empty($gender))
                                        <tr>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Gender:</td>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px;">{{ $gender }}</td>
                                        </tr>
                                    @endif

                                    @if(isset($immigrationStatus) && !empty($immigrationStatus))
                                        <tr>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Immigration Status:</td>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px;">{{ $immigrationStatus }}</td>
                                        </tr>
                                    @endif
                                    @if(isset($jobOpening) && !empty($jobOpening))
                                        <tr>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">job Opening:</td>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px;">{{ $jobOpening }}</td>
                                        </tr>
                                    @endif
                                    @if(isset($describeExp) && !empty($describeExp))
                                        <tr>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Describe Exp:</td>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px;">{{ $describeExp }}</td>
                                        </tr>
                                    @endif
                                    @if(isset($reasonForChange) && !empty($reasonForChange))
                                        <tr>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Reason For Change:</td>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px;">{{ $reasonForChange }}</td>
                                        </tr>
                                    @endif
                                    @if(isset($whenToStart) && !empty($whenToStart))
                                        <tr>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">When To Start:</td>
                                            <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px;">{{ $whenToStart }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:0 0 10px 0;">Resume:</td>
                                        <td align="left" valign="middle" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; padding:0 0 10px 5px;"><a href="{{ $resume }}">Download File</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:16px; line-height:20px; font-weight:600; color:#000000; padding:10px 0 0 0;">Best Regards,</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="padding:0 0 15px 0;"><a href="#" target="_blank" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; text-decoration:none;">{{ $SITE_NAME }}</a></td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif;font-size:13px;line-height:19.5px;font-weight:400;color:#222222;padding:15px 0 0 0;border-top:1px solid #e8e8e8">{!!$FOOTER_COPYRIGHTS!!} {!! date('Y') !!} {{$SITE_NAME}}. All Rights Reserved.</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif;font-size:13px;line-height:19.5px;font-weight:400;color:#222222;padding:0 0 0 0">Powered by <a href="https://www.netclues.ky/" rel="nofollow" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif;font-size:13px;line-height:19.5px;font-weight:400;color:#222222;text-decoration:none" target="_blank">Netclues!</a></td>
                    </tr>
                </table></td>
            </tr>
        </table>
    </body>

</html>
