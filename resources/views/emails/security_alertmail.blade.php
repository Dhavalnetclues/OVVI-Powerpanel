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
                            <td valign="middle"><a href="{{url('/')}}" title="{{$SITE_NAME}}" target="_blank"><img src="{!! App\Helpers\resize_image::resize($FRONT_LOGO_ID,236,135) !!}" alt="{{$SITE_NAME}}"></a></td>
                        </tr>
                        <tr>
                            <td style="border-bottom:1px solid #ccc;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" bgcolor="#fff" style="padding:20px 0;">
                                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:30px;">Dear {{ $name }},</td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:24px;line-height:32px;">New device signed in to</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top"><img width="20px;" height="20px;" src="{{ $logo }}"> <span style="font-size: 14px;line-height: 20px;position: relative;top: -6px;padding-left: 5px;">{{ $email }}</span></td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom:1px solid #ccc;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;">Your {{ $SITE_NAME }} Account was just signed in to from a new {{$msg}} device. You're getting this email to make sure it was you.</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top"><a href="{{ url('/') }}{{$id}}" target="_blank" style="line-height: 16px;color: #ffffff;font-weight: 400;text-decoration: none;font-size: 14px;display: inline-block;padding: 10px 24px;background-color: #1d4da1;border-radius: 5px;min-width: 90px;">Check Activity</a></td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom:1px solid #ccc;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:24px;"><strong>Best Regards,</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="line-height:24px"><a href="{{ url('/') }}" target="_blank" style="font-family:Arial, Helvetica, sans-serif; text-decoration:none; color:#000; font-size:15px;" title="{{ $SITE_NAME }}">{{ $SITE_NAME }}</a></td>
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