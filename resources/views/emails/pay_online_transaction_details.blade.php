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
                                <a href="{{url('/')}}" title="{{$SITE_NAME}}" target="_blank">
                                    <img src="{!! App\Helpers\resize_image::resize($FRONT_LOGO_ID,236,135) !!}" alt="{{$SITE_NAME}}">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-bottom:1px solid #ccc;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" bgcolor="#fff" style="padding:20px 0;">
                                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                    @if($user=='admin')
                                        <tr>
                                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:10px 0;">Dear Administrator,</td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:20px; font-weight:400; color:#000000; padding:10px 0;">A new payment has been received for {{ $formData['payment_for'] }}. Please find the transaction detail below:</td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top">&nbsp;</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:600; color:#000000; padding:10px 0;">Dear {{ $formData['personalInfo_name'] }},</td>
                                        </tr>
                                        <tr>
                                            <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:24px;">Your payment for {{ $formData['payment_for'] }} has been processed. The details are as below</td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top">&nbsp;</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td height="20" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:30px; font-weight:600;">Transaction Details :-</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>Email: </strong><a href="mailto:{{ $formData['personalInfo_email'] }}" target="_blank" style="text-decoration:none; color:#000;" title="{{ $formData['personalInfo_email'] }}">{{ $formData['personalInfo_email'] }}</a></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>Transaction Id: </strong>{{ $transactionData['orderID'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>Transaction Status: </strong>{{ $transactionData['FinalStatus'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>Amount: </strong>${{ $formData['paymentInfo_amount'] }} {{ $formData['paymentInfo_currency'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>Card Type: </strong>{{ $formData['paymentInfo_cardType'] }} card</td>
                                                </tr> 
                                                <tr>
                                                    <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px;"><strong>Transaction Date: </strong>{{ date('d-m-Y')  }}</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:16px; line-height:20px; font-weight:600; color:#000000; padding:10px 0 0 0;">Best Regards,</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="padding:0 0 15px 0;"><a href="#" target="_blank" style="font-family:'Segoe UI','Segoe WP','Segoe UI Regular','Helvetica Neue',Helvetica,Tahoma,'Arial Unicode MS',Sans-serif; font-size:14px; line-height:16px; font-weight:400; color:#000000; text-decoration:none;">{{ $SITE_NAME }}</a></td>
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