<?php

/**
 * The FrontController class handels email functions
 * configuration  process (ORM code Updates).
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since     2017-08-17
 * @author    NetQuick
 */

namespace App\Helpers;

use App\EmailLog;
use App\EmailType;
use App\GeneralSettings;
use Config;
use Mail;
use Request;
use App\Helpers\MyLibrary;
use Illuminate\Support\Facades\Crypt;

class Email_sender {

    /**
     * This method handels test email process
     * @return  JSON Object
     * @since   2017-08-17
     * @author  NetQuick
     */
    public static function testMail($to = false) {
        $settings = Self::getSettings();
        $settings["subject"] = "Test email";
        $settings['emailType'] = EmailType::getRecords()->checkEmailType('General')->first()->id;
        $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
        $settings['to'] = isset($to) ? $to : Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
        $settings['sender'] = $settings['SMTP_SENDER_NAME'];
        $settings['receiver'] = $settings['SMTP_SENDER_NAME'];

        $settings['txtBody'] = view('emails.default', $settings)->render();
        $logId = Self::recodLog($settings);

        // unset($settings['txtBody']);

        Self::sendEmail('emails.default', $settings, $logId);
    }

    /**
     * This method handels contact email process for admin and user
     * @return  Flag contactUs
     * @since   2017-08-17
     * @author  NetQuick
     */
    public static function contactUs($data = null, $rec_id = null, $mod_id = null) {
        if ($data != null) {

            $settings = Self::getSettings();
            $settings["user"] = 'admin';
            $settings["first_name"] = $data["first_name"];
            $settings["email"] = $data["contact_email"];
            $settings["phone_number"] = (isset($data["phone_number"]) ? $data["phone_number"] : '');
            $settings["user_message"] = nl2br($data["user_message"]);

            $settings['user_department'] = $data['department_detail']->varTitle;

            #Admin Email================================
            $data['user'] = 'admin';
            $settings["subject"] = 'New Contact Enquiry Received for ' . $data['department_detail']->varTitle;
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Contact Us Lead')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            if (Config::get('Constant.DEFAULT_NOTIFCATION_DEPARTMENT_EMAIL') == 'Y') {
                $department_Email = (isset($data['department_detail']) && $data['department_detail'] != "") ? $data['department_detail']->varEmail : Mylibrary::getLaravelDecryptedString($settings['DEFAULT_CONTACTUS_EMAIL']);
                $settings['to'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_CONTACTUS_EMAIL']) . ',' . $department_Email;
            } else {
                $settings['to'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_CONTACTUS_EMAIL']);
            }
//            $settings['to'] = 'testbynetclues@gmail.com';
            //$settings['to'] = (isset($data['department_detail']) && $data['department_detail'] != "") ? $data['department_detail']->varEmail : Mylibrary::getLaravelDecryptedString($settings['DEFAULT_CONTACTUS_EMAIL']);
            //$settings['to']        = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_CONTACTUS_EMAIL']);
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
            $settings['txtBody'] = view('emails.contactmail', $settings)->render();
            $settings["intFkRecordId"] = $rec_id;
            $settings["intFkModuleId"] = $mod_id;
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            Self::sendEmail('emails.contactmail', $settings, $logId);

            // #User Email================================
            // $settings['user'] = 'user';
            // $settings["subject"] = "Thank you for contacting - " . $settings['SITE_NAME'];
            // $settings['emailType'] = EmailType::getRecords()->checkEmailType('Contact Us Lead')->first()->id;
            // $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            // $settings['to'] = $settings['email'];
            // $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            // $settings['receiver'] = $settings['first_name'];
            // $settings['txtBody'] = view('emails.contactmail', $settings)->render();
            // $logId = Self::recodLog($settings);
            // // unset($settings['txtBody']);
            // Self::sendEmail('emails.contactmail', $settings, $logId);
        }
    }

    /**
     * This method handels contact email process for admin and user
     * @return  Flag contactUs
     * @since   2017-08-17
     * @author  NetQuick
     */
    public static function contactUsLeadReply($data = null) {
        $response = false;
        if ($data != null) {

            $settings = Self::getSettings();
            $settings["user"] = $data["reply_lead_name"];
            $settings["email"] = $data["reply_to_email"];
            $settings["user_message"] = nl2br($data["reply_to_message"]);

            #To Use by backend Email================================
            $data['user'] = $data["reply_lead_name"];
            $settings["subject"] = $data["reply_to_subject"];
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Contact Us Reply')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = $data["reply_to_email"];
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
            $settings['txtBody'] = view('emails.contactreplymail', $settings)->render();
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            $response = Self::sendEmail('emails.contactreplymail', $settings, $logId);
        }
        return $response;
    }

    public static function contactUsLeadForword($data = null, $rec_id = null, $mod_id = null) {
        $response = false;
        if ($data != null) {

            $settings = Self::getSettings();
            $settings["email"] = $data["forword_to_email"];
            $settings["user_message"] = nl2br($data["forword_to_message"]);

            #To Use by backend Email================================
            $text = EmailLog::getRecordByModule($rec_id, $mod_id);
            $settings["subject"] = $data["forword_to_subject"];
            $settings['emailTxtBody'] = $text->txtBody;
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Contact Us Forward')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = $data["forword_to_email"];
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
            $settings['txtBody'] = view('emails.contactforwardmail', $settings)->render();
            $settings["intFkRecordId"] = $rec_id;
            $settings["intFkModuleId"] = $mod_id;
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            $response = Self::sendEmail('emails.contactforwardmail', $settings, $logId);
        }
        return $response;
    }

    public static function FeedBack($data = null) {
        if ($data != null) {

            $settings = Self::getSettings();
            $settings["user"] = 'admin';


            if ($data["chrSatisfied"] != 'N') {
                if ($data["chrSatisfied"] == 'H') {
                    $Satisfied = "Horrible";
                } elseif ($data["chrSatisfied"] == 'B') {
                    $Satisfied = "Bad";
                } elseif ($data["chrSatisfied"] == 'J') {
                    $Satisfied = "Just OK";
                } elseif ($data["chrSatisfied"] == 'G') {
                    $Satisfied = "Good";
                } elseif ($data["chrSatisfied"] == 'S') {
                    $Satisfied = "Super!";
                } else {
                    $Satisfied = '-';
                }
            } else {
                $Satisfied = '-';
            }

            $settings["chrSatisfied"] = $Satisfied;
            $settings["varName"] = $data["varName"];
            $settings["varEmail"] = $data["varEmail"];
            $settings["varPhoneNo"] = (isset($data["varPhoneNo"]) ? $data["varPhoneNo"] : 'N/A');
            $settings["varVisitfor"] = $data["varVisitfor"];

            if ($data['chrCategory'] == '1') {
                $chrCategory = "Suggestions";
            } elseif ($data['chrCategory'] == '2') {
                $chrCategory = "Issues/Bugs";
            } elseif ($data['chrCategory'] == '3') {
                $chrCategory = "Others";
            } else {
                $chrCategory = "";
            }

            $settings["chrCategory"] = $chrCategory;
            $settings["txtUserMessage"] = $data["txtUserMessage"];
            #Admin Email================================
            $settings['user'] = 'admin';
            $settings["subject"] = "A Visitor has Given the Feedback for - " . $settings['SITE_NAME'];
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Feedback Lead')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_FEEDBACK_EMAIL']);
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
            $settings['txtBody'] = view('emails.feedback', $settings)->render();
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            Self::sendEmail('emails.feedback', $settings, $logId);
            #User Email================================
            $settings['user'] = 'user';
            $settings["subject"] = "Thank you for giving feedback";
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Feedback Lead')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = $settings['varEmail'];
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['varName'];
            $settings['txtBody'] = view('emails.feedback', $settings)->render();
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            Self::sendEmail('emails.feedback', $settings, $logId);
        }
    }

    public static function EmailtoFriend($data = null) {
        if ($data != null) {
            $settings = Self::getSettings();


            $settings["varEmailName"] = $data["varEmailName"];
            $settings["varFrommEmail"] = $data["varFrommEmail"];
            $settings["varFriendName"] = $data["varFriendName"];
            $settings["varFriendEmail"] = $data["varFriendEmail"];
            $settings["txtEmailMessage"] = $data["txtEmailMessage"];
            $settings["varPageUrl"] = $data["CurrentPageUrl"];
            $settings["subject"] = "Email from your friend " . $data["varEmailName"];
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Email To Friend')->first()->id;
            $settings['from'] = $settings["varFrommEmail"];
            $settings['to'] = $settings["varFriendEmail"];
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $data["varEmailName"];
            $settings['txtBody'] = view('emails.emailtofriend', $settings)->render();
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            Self::sendEmail('emails.emailtofriend', $settings, $logId);
        }
    }

    public static function formbuilder_email($data = null, $formdata = null, $customeformdata = null, $attechment = null) {

        if ($data != null) {
            $settings = Self::getSettings();
            $json_customeformdata = (json_decode($customeformdata->varFormDescription));
            $requestKeys = array_keys($data);
            $inputsOfEmailArray = array();
            $inputsUserArray = array();
            $valueindex = 0;
            $checkbox = '';
            $user_email = '';
//            echo "<pre/>";
//            print_r($json_customeformdata);
//            exit;
            foreach ($json_customeformdata as $key => $value) {
                if (isset($value->name) && in_array($value->name, $requestKeys)) {
                    if (isset($value->type)) {
                        $inputsOfEmailArray[$valueindex]['type'] = $value->type;
                    }
                    if (isset($value->subtype)) {
                        $inputsOfEmailArray[$valueindex]['subtype'] = $value->subtype;
                    }

                    if (isset($value->label)) {
                        $inputsOfEmailArray[$valueindex]['label'] = $value->label;
                    }
                    if (isset($value->className)) {
                        $inputsOfEmailArray[$valueindex]['className'] = $value->className;
                    }
                    if (isset($value->type) && $value->type == 'checkbox-group') {
                        $selctedchkvalues = array();
                        foreach ($data[$value->name] as $chkvalue) {
                            $chklabel = MyLibrary::getLabelforformbuilder($chkvalue, $value->values);
                            if (!empty($chklabel)) {
                                array_push($selctedchkvalues, $chklabel);
                            }
                        }
                        $checkbox = implode(",", $selctedchkvalues);
                        $inputsOfEmailArray[$valueindex]['value'] = $checkbox;
                    } else if (isset($value->type) && $value->type == 'radio-group') {
                        $chklabel = MyLibrary::getLabelforformbuilder($data[$value->name], $value->values);
                        $checkbox = $chklabel;
                        $inputsOfEmailArray[$valueindex]['value'] = $checkbox;
                    } else if (isset($value->type) && $value->type == 'select') {
                        $chklabel = MyLibrary::getLabelforformbuilder($data[$value->name], $value->values);
                        $checkbox = $chklabel;
                        $inputsOfEmailArray[$valueindex]['value'] = $checkbox;
                    } else if (isset($value->type) && $value->type == 'file') {
                        $inputsOfEmailArray[$valueindex]['value'] = $attechment;
                    } elseif (isset($value->type) && $value->type == 'textarea') {
                        if (isset($value->subtype) && $value->subtype == 'tinymce') {
                            $inputsOfEmailArray[$valueindex]['value'] = $data[$value->name];
                        } else if (isset($value->subtype) && $value->subtype == 'quill') {
                            $inputsOfEmailArray[$valueindex]['value'] = nl2br($data[$value->name]);
                        } else {
                            $inputsOfEmailArray[$valueindex]['value'] = nl2br($data[$value->name]);
                        }
                    } elseif (isset($value->subtype) && $value->subtype == 'email') {
                        $user_email = $data[$value->name];
                        $inputsOfEmailArray[$valueindex]['value'] = $user_email;
                    } else if (isset($value->subtype) && $value->subtype == 'password') {
                        $inputsOfEmailArray[$valueindex]['label'] = '';
                        $inputsOfEmailArray[$valueindex]['value'] = '';
                    } else if (isset($value->className) && $value->className == 'form-control uniqueclass') {
                        $inputsUserArray = $data[$value->name];
                        $inputsOfEmailArray[$valueindex]['value'] = $data[$value->name];
                    } else if (isset($value->className) && $value->className == 'form-control urlclass') {
                        $url = $data[$value->name];
                        $currenturl = explode("/",$url);
                        if(isset($currenturl[2])){
                         $wwwurl = explode(".",$currenturl[2]);
                        }else{
                            $wwwurl[0] = '1';
                        }
                        if (isset($currenturl[0]) && $currenturl[0] != 'http:' && $currenturl[0] != 'https:') {
                            $url_1 = 'http://';
                        } else {
                            $url_1 = '';
                        }
                        if (isset($wwwurl[0]) && $wwwurl[0] == '1') {
                            $url_2 = 'www.';
                        } else {
                            $url_2 = '';
                        }
                        $inputsOfEmailArray[$valueindex]['value'] = $url_1 . $url_2 . $data[$value->name];
                    } elseif (isset($value->type) && $value->type == 'text') {
                        if (isset($value->subtype) && $value->subtype == 'color') {
                            $inputsOfEmailArray[$valueindex]['value'] = $data[$value->name] . '  <div style="width: 137px;height: 30px;background-color: ' . $data[$value->name] . ';"></div>';
                        } else {
                            $inputsOfEmailArray[$valueindex]['value'] = $data[$value->name];
                        }
                    } else {
                        $inputsOfEmailArray[$valueindex]['value'] = $data[$value->name];
                    }
                    $valueindex++;
                } else if (isset($value->type) && $value->type == 'checkbox-group') {
                    foreach ($value->values as $chkvalue) {
                        if (isset($data[$chkvalue->value])) {
                            if ($chkvalue->label == 'Country' && (isset($chkvalue->selected) && $chkvalue->selected == 1)) {
                                $inputsOfEmailArray[$valueindex]['label'] = $chkvalue->label;
                                $cname = MyLibrary::getEmailCountry($data[$chkvalue->value]);
                                $name = $cname[0]->var_name;
                            } else if ($chkvalue->label == 'State' && (isset($chkvalue->selected) && $chkvalue->selected == 1)) {
                                $inputsOfEmailArray[$valueindex]['label'] = $chkvalue->label;
                                $sname = MyLibrary::getEmailState($data[$chkvalue->value]);
                                $name = $sname[0]->var_name;
                            } else if ($chkvalue->label == 'Gender' && (isset($chkvalue->selected) && $chkvalue->selected == 1)) {
                                $inputsOfEmailArray[$valueindex]['label'] = $chkvalue->label;
                                if ($data[$chkvalue->value] == 'male') {
                                    $name = 'Male';
                                } else if ($data[$chkvalue->value] == 'female') {
                                    $name = 'Female';
                                } else if ($data[$chkvalue->value] == 'transgender') {
                                    $name = 'Trans Gender';
                                }
                            } else if ($chkvalue->label == 'Month' && (isset($chkvalue->selected) && $chkvalue->selected == 1)) {
                                $inputsOfEmailArray[$valueindex]['label'] = $chkvalue->label;
                                if ($data[$chkvalue->value] == '01') {
                                    $name = 'January';
                                } else if ($data[$chkvalue->value] == '02') {
                                    $name = 'February';
                                } else if ($data[$chkvalue->value] == '03') {
                                    $name = 'March';
                                } else if ($data[$chkvalue->value] == '04') {
                                    $name = 'April';
                                } else if ($data[$chkvalue->value] == '05') {
                                    $name = 'May';
                                } else if ($data[$chkvalue->value] == '06') {
                                    $name = 'June';
                                } else if ($data[$chkvalue->value] == '07') {
                                    $name = 'July';
                                } else if ($data[$chkvalue->value] == '08') {
                                    $name = 'August';
                                } else if ($data[$chkvalue->value] == '09') {
                                    $name = 'September';
                                } else if ($data[$chkvalue->value] == '10') {
                                    $name = 'October';
                                } else if ($data[$chkvalue->value] == '11') {
                                    $name = 'November';
                                } else if ($data[$chkvalue->value] == '12') {
                                    $name = 'December';
                                }
                            }
                            $inputsOfEmailArray[$valueindex]['value'] = $name;
                            $valueindex++;
                        }
                    }
                }
            }

            $admin_subject = "";
            $admin_email = "";
            $admin_content = "";
            $user_subject = "";
            $user_content = "";

            #Admin Email================================
            if (isset($customeformdata->varAdminSubject) != '') {
                $admin_subject = $customeformdata->varAdminSubject;
            }
            if (isset($customeformdata->varEmail) != '') {
                $admin_email = $customeformdata->varEmail;
            }
            if (isset($customeformdata->varAdminContent) != '') {
                $admin_content = nl2br($customeformdata->varAdminContent);
            }
            $settings['user'] = 'admin';
            $settings["subject"] = $admin_subject;
            $settings["content"] = $admin_content;
            $settings["attachement"] = [url('upload/' . $attechment)];
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Form Builder Lead')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = $admin_email;
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $admin_email;
            $settings['inputsOfEmailArray'] = $inputsOfEmailArray;
            $settings['txtBody'] = view('emails.formbuilder', $settings)->render();
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            Self::sendEmail('emails.formbuilder', $settings, $logId);
            if ($customeformdata->chrCheckUser == 'Y' && isset($user_email)) {
                if (isset($customeformdata->varUserSubject) != '') {
                    $user_subject = $customeformdata->varUserSubject;
                }
                if (isset($customeformdata->varUserContent) != '') {
                    $user_content = nl2br($customeformdata->varUserContent);
                }
                #User Email================================
                if (isset($inputsUserArray)) {
                    $settings['user'] = $inputsUserArray;
                } else {
                    $settings['user'] = 'User';
                }
                $settings["subject"] = $user_subject;
                $settings["user_content"] = $user_content;
                $settings['emailType'] = EmailType::getRecords()->checkEmailType('Form Builder Lead')->first()->id;
                $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
                $settings['to'] = $user_email;
                $settings['sender'] = $settings['SMTP_SENDER_NAME'];
                $settings['receiver'] = $user_email;
                $settings['inputsOfEmailArray'] = $inputsOfEmailArray;
                $settings['txtBody'] = view('emails.formbuilder', $settings)->render();
                $logId = Self::recodLog($settings);
                Self::sendEmail('emails.formbuilder', $settings, $logId);
            }
        }
    }

    public static function submitTicket($data = null) {
        if ($data != null) {
            $settings = Self::getSettings();
            $settings["Name"] = $data["Name"];
            if ($data["varType"] == 1) {
                $varType = "Fixes / Issues";
            }
            if ($data["varType"] == 2) {
                $varType = "Changes";
            }
            if ($data["varType"] == 3) {
                $varType = "Suggestion";
            }
            if ($data["varType"] == 4) {
                $varType = "New Features";
            }
            $settings["varType"] = $varType;
            $settings["varMessage"] = $data["varMessage"];
            $settings["Link"] = $data["Link"];

            $settings["subject"] = "The $varType submitted by " . $settings["Name"];
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Submit Ticket')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = Mylibrary::getLaravelDecryptedString($settings['SUBMIT_TICKET']);
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['txtBody'] = view('emails.submitticket', $settings)->render();
            $logId = Self::recodLog($settings);
            Self::sendEmail('emails.submitticket', $settings, $logId);
        }
    }

    /**
     * This method handels contact email process for admin and user
     * @return  Flag contactUs
     * @since   2017-08-17
     * @author  NetQuick
     */
    public static function bookAppointment($data = null) {
        if ($data != null) {

            $settings = Self::getSettings();
            $settings["user"] = 'admin';
            $settings["first_name"] = $data["first_name"];
            $settings["email"] = Mylibrary::getLaravelDecryptedString($data["contact_email"]);
            $settings["phone_number"] = (isset($data["phone_number"]) ? Mylibrary::getLaravelDecryptedString($data["phone_number"]) : '');
            $settings["user_message"] = $data["user_message"];
            $settings["service_name"] = $data["service_name"];
            $settings["appointment_date"] = date('Y-m-d', strtotime($data['appointment_date']));

            #Admin Email================================
            $data['user'] = 'admin';
            $settings["subject"] = "New Appointment Enquiry Received - " . $settings['SITE_NAME'];
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Appointment Lead')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_CONTACTUS_EMAIL']);
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
            $settings['txtBody'] = view('emails.appointmentmail', $settings)->render();
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            Self::sendEmail('emails.appointmentmail', $settings, $logId);

            #User Email================================
            $settings['user'] = 'user';
            $settings["subject"] = "Thank you for contacting - " . $settings['SITE_NAME'];
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Appointment Lead')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = Mylibrary::getLaravelDecryptedString($settings['email']);
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['first_name'];
            $settings['txtBody'] = view('emails.appointmentmail', $settings)->render();
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            Self::sendEmail('emails.appointmentmail', $settings, $logId);
        }
    }

    public static function Random($data = null, $personalemail = false) {
        if ($data != null) {

            $settings = Self::getSettings();
            $settings["name"] = trim($data["name"]);
            $settings["random_code"] = trim($data["intCode"]);

            #Admin Email================================
            $settings['user'] = 'admin';
            $settings["subject"] = "PowerPanel Login Access Code";
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Two Factor Authentication')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = $personalemail;
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
            $settings['txtBody'] = view('emails.randommail', $settings)->render();
            $logId = Self::recodLog($settings);
//            unset($settings['txtBody']);

            Self::sendEmail('emails.randommail', $settings, $logId);
        }
    }

    public static function Authentication_Otp($name = null, $OTP = null, $personalemail = false, $chrAuthentication = false) {
        if ($OTP != null) {

            $settings = Self::getSettings();
            $settings["name"] = trim($name);
            $settings["random_code"] = trim($OTP);

            #Admin Email================================
            if ($chrAuthentication != 'Y') {
                $settings["subject"] = "2-Step Verification Enable Access Code";
            } else {
                $settings["subject"] = "2-Step Verification Disable Access Code";
            }
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Two Factor Authentication')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = $personalemail;
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
            $settings['txtBody'] = view('emails.randommail', $settings)->render();
            $logId = Self::recodLog($settings);
//            unset($settings['txtBody']);
            Self::sendEmail('emails.randommail', $settings, $logId);
        }
    }

    public static function Authentication_Enable_Disable($name = null, $personalemail = false, $chrAuthentication = false) {
        $settings = Self::getSettings();
        $settings["name"] = trim($name);
        #Admin Email================================
        if ($chrAuthentication == 'Y') {
            $settings["subject"] = "Two Factor Authentication Enabled";
            $settings["content"] = "Two factor authentication for your account is enabled";
        } else {
            $settings["subject"] = "Two Factor Authentication Disabled";
            $settings["content"] = "Two factor authentication for your account is disabled";
        }
        $settings['emailType'] = EmailType::getRecords()->checkEmailType('Two Factor Authentication')->first()->id;
        $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
        $settings['to'] = $personalemail;
        $settings['sender'] = $settings['SMTP_SENDER_NAME'];
        $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
        $settings['txtBody'] = view('emails.authentication_enable_disable', $settings)->render();
        $logId = Self::recodLog($settings);
//            unset($settings['txtBody']);
        Self::sendEmail('emails.authentication_enable_disable', $settings, $logId);
//        }
    }

    public static function Security_Questions_Enable($name = null, $personalemail = null, $body = null) {
        $settings = Self::getSettings();
        $settings["name"] = trim($name);
        if ($body != null) {
            $settings["bodyemail"] = trim($body);
            $settings["subject"] = "Security Questions Enabled";
            $settings["content"] = "Security questions for your account is enabled";
        } else {
            $settings["subject"] = "Security Questions Disabled";
            $settings["content"] = "Security questions for your account is disabled";
        }
        #Admin Email================================
        $settings['emailType'] = EmailType::getRecords()->checkEmailType('Two Factor Authentication')->first()->id;
        $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
        $settings['to'] = $personalemail;
        $settings['sender'] = $settings['SMTP_SENDER_NAME'];
        $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
        $settings['txtBody'] = view('emails.security_questions_enable', $settings)->render();
//        print_r($settings['txtBody']); exit;
        $logId = Self::recodLog($settings);
        Self::sendEmail('emails.security_questions_enable', $settings, $logId);
//        }
    }

    public static function Security_alert($email = null, $personalemail = false, $name = false, $logo = false, $msg = false, $id = false) {
        $settings = Self::getSettings();
        $settings["name"] = $name;
        $settings["email"] = $email;
        $settings["logo"] = $logo;
        $settings["msg"] = $msg;
        $id = base64_encode($id);
        $settings["id"] = '/check_activity?rfn=' . $id;

        #Admin Email================================
        $settings["subject"] = "Security alert";
        $settings['emailType'] = EmailType::getRecords()->checkEmailType('New device signed')->first()->id;
        $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
        $settings['to'] = $personalemail;
        $settings['sender'] = $settings['SMTP_SENDER_NAME'];
        $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
        $settings['txtBody'] = view('emails.security_alertmail', $settings)->render();
        $logId = Self::recodLog($settings);
        Self::sendEmail('emails.security_alertmail', $settings, $logId);
    }

    public static function sendReport($data = null, $Img = null, $table = null, $moduleId = null) {
        $img = env('APP_URL') . 'report_img/' . $Img;
        $response = false;
        if ($data != null) {
            $settings = Self::getSettings();
            $settings["name"] = trim($data["Report_Name"]);
            $settings["year"] = trim($data["year"]);
            $settings["report"] = 'Page Hits';
            $settings["IMG"] = $img;
            $settings["TABLE"] = $table;
            #To Use by backend Email================================
            $settings["subject"] = $data['year'] . ' Page Hits Report';
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Page Hits Report')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = trim($data["Report_email"]);
            $settings['txtBody'] = view('emails.sendreportmail', $settings)->render();
            $settings["intFkModuleId"] = $moduleId;
            $logId = Self::recodLog($settings);
            $response = Self::sendEmail('emails.sendreportmail', $settings, $logId);
        }
        return $response;
    }

    public static function DocsendReport($data = null, $Img = null, $table = null, $moduleId = null) {
        $img = env('APP_URL') . 'report_img/' . $Img;
        $response = false;
        if ($data != null) {
            $settings = Self::getSettings();
            $settings["name"] = trim($data["Report_Name"]);
            $settings["year"] = trim($data["year"]);
            $settings["report"] = 'Document Views & Downloads';
            $settings["IMG"] = $img;
            $settings["TABLE"] = $table;
            #To Use by backend Email================================
            $settings["subject"] = $data['year'] . ' Document Views & Downloads Report';
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Document Views & Downloads Report')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = trim($data["Report_email"]);
            $settings['txtBody'] = view('emails.sendreportmail', $settings)->render();
            $settings["intFkModuleId"] = $moduleId;
            $logId = Self::recodLog($settings);
            $response = Self::sendEmail('emails.sendreportmail', $settings, $logId);
        }
        return $response;
    }

    /**
     * This method handels newsletter subscription email process
     * @return  Flag
     * @since   2017-08-17
     * @author  NetQuick
     */
    public static function newsletter($data = null, $id = null) {
        if ($data != null && $id != null) {
            $settings = Self::getSettings();
            $settings['first_name'] = ucfirst(explode('@', Mylibrary::getDecryptedString($data["varEmail"]))[0]);
            $settings["email"] = Mylibrary::getDecryptedString(trim($data["varEmail"]));
            $settings['user_subscribe'] = url("news-letter/subscription/subscribe/" . $id . "/" . $data["VarToken"]);
            $settings['subject'] = 'Confirm your subscription to ' . Config::get('Constant.SITE_NAME');
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('General')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString(Config::get('Constant.DEFAULT_EMAIL'));
            $settings['to'] = Mylibrary::getDecryptedString(trim($data["varEmail"]));
            $settings['sender'] = Config::get('Constant.SMTP_SENDER_NAME');
            $settings['receiver'] = $settings['first_name'];
            $settings['txtBody'] = view('emails.subscription', $settings)->render();

            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            Self::sendEmail('emails.subscription', $settings, $logId);
        }
    }

    /**
     * This method handels newsletter subscribed email process
     * @return  Flag
     * @since   2017-08-17
     * @author  NetQuick
     */
    public static function newsletterSubscribed($data = null, $id = null) {
        if ($data != null && $id != null) {
            $settings = Self::getSettings();
            $settings['first_name'] = ucfirst(explode('@', Mylibrary::getDecryptedString($data["varEmail"]))[0]);
            $settings["email"] = Mylibrary::getDecryptedString($data["varEmail"]);
            $settings["phone_number"] = isset($data["phone_number"]) ? $data["phone_number"] : '';
            $settings['user_unsubscribe'] = url("news-letter/subscription/unsubscribe/" . $id . "/" . $data["VarToken"]);
            $settings['subject'] = 'Congratulations! Your subscription has been confirmed.';
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('General')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = Mylibrary::getDecryptedString($data["varEmail"]);
            $settings['replyto'] = Mylibrary::getLaravelDecryptedString(Config::get('Constant.DEFAULT_NEWSLETTER_EMAIL'));
            $newsletterunsubscribadmin = $settings['replyto'];
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['first_name'];
            $settings['txtBody'] = view('emails.subscription', $settings)->render();
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            Self::sendEmail('emails.subscription', $settings, $logId, $newsletterunsubscribadmin);
        }
    }

    /**
     * This method handels newsletter subscribed email process
     * @return  Flag
     * @since   2017-08-17
     * @author  NetQuick
     */
    public static function newsletterSubscribed_admin($data = null, $id = null) {
        if ($data != null && $id != null) {
            $settings = Self::getSettings();
            $settings['first_name'] = ucfirst(explode('@', Mylibrary::getDecryptedString($data["varEmail"]))[0]);



            $settings["email"] = Mylibrary::getDecryptedString($data["varEmail"]);
            $settings["phone_number"] = isset($data["phone_number"]) ? $data["phone_number"] : '';

            $settings["content"] = "New web visitor " . $settings["email"] . " has subscribed for our Newsletter.";
            $settings['subject'] = 'Email Subscription Received for our Newsletter';
            $settings['sub'] = "Subscriber's";
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('General')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_NEWSLETTER_EMAIL']);
            $settings['replyto'] = Mylibrary::getDecryptedString($data["varEmail"]);
            $newsletteradmin = $settings['replyto'];
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['first_name'];
            $settings['txtBody'] = view('emails.subscription_admin', $settings)->render();
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            Self::sendEmail('emails.subscription_admin', $settings, $logId, $newsletteradmin);
        }
    }

    /**
     * This method handels newsletter subscribed email process
     * @return  Flag
     * @since   2017-08-17
     * @author  NetQuick
     */
    public static function newsletterUNSubscribed_admin($data = null, $id = null) {
        if ($data != null && $id != null) {
            $settings = Self::getSettings();
            $settings['first_name'] = ucfirst(explode('@', Mylibrary::getDecryptedString($data["varEmail"]))[0]);

            $settings["content"] = "Web visitor " . $settings["email"] . " unsubscribed from the newsletter subscription list.";
            $settings["email"] = Mylibrary::getDecryptedString($data["varEmail"]);
            $settings["phone_number"] = isset($data["phone_number"]) ? $data["phone_number"] : '';
            $settings['subject'] = 'Web Visitor unsubscribed from the newsletter subscription';
            $settings['sub'] = "Unsubscriber's";
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('General')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_NEWSLETTER_EMAIL']);
            $settings['replyto'] = Mylibrary::getDecryptedString($data["varEmail"]);
            $newsletterunsubscribeadmin = $settings['replyto'];
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['first_name'];
            $settings['txtBody'] = view('emails.unsubscription_admin', $settings)->render();
            $logId = Self::recodLog($settings);

            Self::sendEmail('emails.unsubscription_admin', $settings, $logId, $newsletterunsubscribeadmin);
        }
    }

    /**
     * This method sends email for Restaurant reservation
     * @return  Flag
     * @since   2018-07-24
     * @author  NetQuick
     */
    public static function reservation($data = null) {
        if ($data != null) {

            $settings = Self::getSettings();
            $settings["user"] = 'admin';
            $settings["first_name"] = $data["first_name"];
            $settings["email"] = $data["contact_email"];
            $settings["phone_number"] = (isset($data["phone_number"]) ? $data["phone_number"] : '');
            $settings["number_of_people"] = (isset($data["people"]) ? $data["people"] : 1);
            $settings["user_message"] = $data["user_message"];
            $settings["reservation_date"] = (isset($data['reservation_date']) ? date('Y-m-d h:i:s', strtotime($data['reservation_date'])) : '');

            #Admin Email================================
            $data['user'] = 'admin';
            $settings["subject"] = "New Reservation Enquiry Received - " . $settings['SITE_NAME'];
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Reservation Lead')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_CONTACTUS_EMAIL']);
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
            $settings['txtBody'] = view('emails.reservation', $settings)->render();
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            Self::sendEmail('emails.reservation', $settings, $logId);

            #User Email================================
            $settings['user'] = 'user';
            $settings["subject"] = "Thank you for contacting - " . $settings['SITE_NAME'];
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Reservation Lead')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = $settings['email'];
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['first_name'];
            $settings['txtBody'] = view('emails.reservation', $settings)->render();
            $logId = Self::recodLog($settings);
            // unset($settings['txtBody']);
            Self::sendEmail('emails.reservation', $settings, $logId);
        }
    }

    public static function forgotPassword($data = null) {
        if ($data != null) {
            $settings = Self::getSettings();
            $settings["user"] = $data['user'];
            $settings["resetToken"] = $data['resetToken'];

            #Forgot Password Email To User ================================

            $settings["subject"] = "Your Password Reset Link";
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Forgot Password')->first()->id;
            $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = $data['user']->email;
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
            $settings['txtBody'] = view('auth.emails.password', $settings)->render();
            $logId = Self::recodLog($settings);
            //unset($settings['txtBody']);
            Self::sendEmail('auth.emails.password', $settings, $logId);
        }
    }

    /**
     * This method sends email
     * @return  Flag
     * @since   2017-08-17
     * @author  NetQuick
     */
    public static function sendEmail_ViaSMTP($view = null, $settings = null, $logId = null) {
        $reponse = false;
        if (!empty($settings) && $logId > 0 && $view != null) {
            //$mailconfigData = Config::get('mail');
            $replyTo = isset($settings['replyTo']) ? $settings['replyTo'] : Mylibrary::getLaravelDecryptedString(Config::get('Constant.DEFAULT_REPLYTO_EMAIL'));
            if (empty($replyTo)) {
                $settings['replyTo'] = $settings['from'];
            } else {
                $settings['replyTo'] = $replyTo;
            }

            $sent = Mail::send($view, $settings, function ($message) use ($settings) {
                        $message->from($settings['from'], $settings['sender']);
                        $message->to($settings['to'], $settings['receiver'])->replyTo($settings['replyTo'], Config::get('Constant.SMTP_SENDER_NAME'))->subject($settings['subject']);
                    });
            /* $sent = Mail::send($view, $settings, function ($message) use ($settings) {
              $message->from($settings['from'], $settings['sender']);
              $message->to($settings['to'], $settings['receiver'])->replyTo($settings['replyTo']), Config::get('Constant.SMTP_SENDER_NAME'))->subject($settings['subject']);
              }); */
            if (count(Mail::failures()) == 0) {
                EmailLog::updateEmailLog(['id' => $logId], ['chrIsSent' => 'Y']);
                $reponse = true;
            }
        }
        return $response;
    }

    /**
     * This method loads general settings
     * @return  Settings array
     * @since   2017-08-17
     * @author  NetQuick
     */
    public static function getSettings() {
        $settings = [];
        $generalSettings = GeneralSettings::getSettings();
        if (!empty($generalSettings)) {
            foreach ($generalSettings as $key => $value) {
                $settings[$value['fieldName']] = $value['fieldValue'];
            }
        }
        return $settings;
    }

    /**
     * This method stores email log data in database
     * @return  Flag
     * @since   2017-08-17
     * @author  NetQuick
     */
    public static function recodLog($mailData = null) {
        if ($mailData != null) {
            $logData = [];
            $logData['intFkUserId'] = isset($mailData['userId']) ? $mailData['userId'] : 1;
            $logData['intFkEmailType'] = $mailData['emailType'];
            $logData['chrReceiverType'] = isset($mailData['chrReceiverType']) ? $mailData['chrReceiverType'] : '-';
            $logData['intFkModuleId'] = isset($mailData['intFkModuleId']) ? $mailData['intFkModuleId'] : 1;
            $logData['intFkRecordId'] = isset($mailData['intFkRecordId']) ? $mailData['intFkRecordId'] : 1;
            $logData['varFrom'] = Mylibrary::getLaravelEncryptedString($mailData['from']);
            $logData['txtTo'] = Mylibrary::getLaravelEncryptedString($mailData['to']);
            $logData['txtCc'] = isset($mailData['cc']) ? Mylibrary::getLaravelEncryptedString($mailData['cc']) : '-';
            $logData['txtBcc'] = isset($mailData['bcc']) ? Mylibrary::getLaravelEncryptedString($mailData['bcc']) : '-';
            $logData['txtSubject'] = $mailData['subject'];
            $logData['txtBody'] = $mailData['txtBody'];
            $logData['chrAttachment'] = isset($mailData['attachment']) ? $mailData['attachment'] : '-';
            $logData['chrIsSent'] = 'N';
            $logData['chrPublish'] = 'Y';
            $logData['chrDelete'] = 'N';
            $logData['chrIpAddress'] = MyLibrary::get_client_ip();
            $logData['varBrowserInfo'] = Request::header('User-Agent');
            $logData['created_at'] = date('Y-m-d H:i:s');
            $recordId = EmailLog::logEmail($logData);

            return $recordId;
        }
    }

    /**
     * This method handels test email process
     * @return  JSON Object
     * @since   2017-08-17
     * @author  NetQuick
     */
    public static function cronMail($to, $emailContent) {
        $settings = Self::getSettings();
        $settings["subject"] = "Test email";
        $settings['emailType'] = EmailType::getRecords()->checkEmailType('General')->first()->id;
        $settings['from'] = Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
        $settings['to'] = isset($to) ? $to : Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
        $settings['sender'] = $settings['SMTP_SENDER_NAME'];
        $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
        $settings['content'] = $emailContent;
        $settings['txtBody'] = view('emails.workflow', $settings)->render();
        $logId = Self::recodLog($settings);

        // unset($settings['txtBody']);

        Self::sendEmail('emails.workflow', $settings, $logId);
    }

    public static function sendEmail($view = null, $settings = null, $logId = null, $replymail = null) {
        $returnReponse = false;
        if (Config::get('Constant.USE_SMTP_SETTING') == 'Y') {
            $returnReponse = Self::sendEmail_ViaSMTP($view, $settings, $logId);
        } else {
            if (!empty($settings) && $logId > 0 && $view != null) {

                if (isset($replymail) && !empty($replymail)) {
                    $replay = $replymail;
                } else {
                    $replay = Config::get('Constant.DEFAULT_REPLYTO_EMAIL');
                }

                $emailPara = array(
                    'PageUrl' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
                    'SiteName' => $settings['SITE_NAME'],
                    'EmailTo' => $settings['to'],
                    'ReplyToEmail' => $replay,
                    'Message' => $settings['txtBody'],
                    'Subject' => $settings['subject'],
                    'RelpyToName' => Config::get('Constant.SMTP_SENDER_NAME'),
                    'CcEmail' => (isset($settings["cc_email"]) ? $settings["cc_email"] : ''),
                    'BccEmail' => (isset($settings["BccEmail"]) ? $settings["BccEmail"] : ''),
                    'FormName' => $settings['from'],
                    'TestMail' => Config::get('Constant.TEST_MODE'),
                    'AllowAttachment' => '',
                    'Attachment' => '',
                );

                if (isset($settings["attachement"]) && $settings["attachement"] != "") {
                    $emailPara['AllowAttachment'] = 'png,jpg,DOC,pdf,PDF';  //optional if you want to send only pdf,doc then put like 'png,DOC'
                    $emailPara['Attachment'] = array($settings["attachement"][0]); // array('http://www.example.com/upimages/Banner/softtest1.png'); 
                }

                $ReturnArray = array(
                    'EmailPara' => json_encode($emailPara, true),
                    'SiteId' => Config::get('Constant.SITE_ID'), //Define in constant
                    'SiteTocken' => Config::get('Constant.SITE_TOCKEN') //Define in constant
                );

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => Config::get('Constant.MAIL_API_URL'),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $ReturnArray,
                    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                    CURLOPT_USERPWD => Config::get('Constant.MAIL_API_ID') . ":" . Config::get('Constant.MAIL_API_PSW'),
                    CURLOPT_HTTPHEADER => array(
                        "ES-API-KEY:" . Config::get('Constant.SITE_TOCKEN') //Define in constant
                    ),
                    CURLOPT_SSL_VERIFYPEER => FALSE
                        )
                );
                $response = curl_exec($curl);
                curl_close($curl);

                if ($response == 'true') {
                    EmailLog::updateEmailLog(['id' => $logId], ['chrIsSent' => 'Y']);
                    return true;
                } else {
                    return false;
                }

                // $sent = Mail::send($view, $settings, function ($message) use ($settings) {
                // $message->from($settings['from'], $settings['sender']);
                // $message->to($settings['to'], $settings['receiver'])->replyTo(Config::get('Constant.DEFAULT_REPLYTO_EMAIL'), Config::get('Constant.SMTP_SENDER_NAME'))->subject($settings['subject']);
                // });
                // if ($sent) {
                // EmailLog::updateEmailLog(['id' => $logId], ['chrIsSent' => 'Y']);
                // }
            }
        }

        return $returnReponse;
    }

}
