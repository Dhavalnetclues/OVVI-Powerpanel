<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{ $SITE_NAME }}</title>
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
                            <td valign="middle"><a href="{{url('/')}}" title="{{$SITE_NAME}}" target="_blank"><img src="{!! App\Helpers\resize_image::resize($FRONT_LOGO_ID,300,200) !!}" alt="{{$SITE_NAME}}"></a></td>
                        </tr>
                        <tr>
                            <td style="border-bottom:1px solid #ccc;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" bgcolor="#fff" style="padding:20px 0;">
                                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">

                                    @if(isset($user_subscribe))
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:30px;">Dear {{ $first_name }},</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;">Welcome to {{ $SITE_NAME }}.</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;">The GDPR Law is in place from 25th of May, 2018. And this gives you the power to give your consent for sharing your personal data on any portal.</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;">In compliance with the same, since we would like to keep our familiarity, we would require your confirmation that allows us to use your personal data, which is simply your email address. To subscribe to our newsletter,</td>
                                    </tr>
                                    <!-- <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;">Please confirm your subscription by clicking on the following link:</td>
                                    </tr> -->
                                    <tr>
                                        <td align="center" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;"><a href="{{ $user_subscribe }}" style="background:#2574db;padding:6px 15px;display:inline-block;text-decoration:none;color:#fff;font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif;font-size:16px;margin:8px 0 0 0;font-weight:bold;">SUBSCRIBE</a></td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;">You need not worry for, we assure you that we will not be spamming your mail feed, and more importantly, your personal data will not be used for any purposes but, to share the latest information that you do not want to miss out on.</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;">In fact, you can unsubscribe whenever you deem fit.</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;">If you aren't familiar with GDPR then, you should surely check it out.</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">&nbsp;</td>
                                    </tr>
                                    @elseif($user_unsubscribe)
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:30px;">Dear {{ $first_name }},</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;">Welcome to {{ $SITE_NAME }}.</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;">Thank you for subscribing to our newsletters.<br/>Your subscription has been confirmed. You've been added to our list and will hear from us soon.<br/>If you do not want to receive any future updates, please click <a href="{{ $user_unsubscribe }}">here</a> to unsubscribe.</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">&nbsp;</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:24px;"><strong>Best Regards,</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="line-height:24px"><a href="{{url('/')}}" target="_blank" style="font-family:Arial, Helvetica, sans-serif; text-decoration:none; color:#000; font-size:15px;" title="{{$SITE_NAME}}">{{$SITE_NAME}}</a></td>
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
                                            Copyright &copy; 2002 - {{ date('Y') }} {{$SITE_NAME}}. All Rights Reserved.
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