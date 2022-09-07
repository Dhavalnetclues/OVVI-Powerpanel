<!doctype html>
<html>
    <head>
        <title>Form Builder Leads</title>
    </head>
    <body>
        @if(isset($FormBuilderLead) && !empty($FormBuilderLead))
        <div class="row">
            <div class="col-12">
                <table class="search-result allData" id="" border="1">
                    <thead>
                        <tr>
                            <th style="font-weight: bold;text-align:center" colspan="6">{{ Config::get('Constant.SITE_NAME') }} {{ trans("Form Builder Leads") }}</th>
                        </tr>
                        <tr>
                            <th style="font-weight: bold;">{{ trans('template.common.name') }}</th>
                            <th style="font-weight: bold;">{{ trans('template.common.email') }}</th>
                            <th style="font-weight: bold;">Contents</th>
                            <th style="font-weight: bold;">{{ trans('template.contactleadModule.ipAddress') }}</th>
                            <th style="font-weight: bold;">{{ trans('template.contactleadModule.receivedDateTime') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $label = '';
                        foreach ($FormBuilderLead as $row) {
                            $customeformdata = \App\CommonModel::getFormBuilderData($row->fk_formbuilder_id);
                            $requestkey_array = [];
                            $json_customeformdata = (json_decode($customeformdata->varFormDescription));
                            $json_data = (json_decode($row->formdata));
                            $json_Array = (array) $json_data;
                            foreach ($json_data as $key => $va) {
                                $requestkey_array[] = $key;
                            }
                            $requestKeys = $requestkey_array;
                            $inputsOfEmailArray = array();
                            $valueindex = 0;
                            $checkbox = '';
                            $user_email = '';
                            $details = '';
                            foreach ($json_customeformdata as $key => $val) {
                                if (isset($val->name) && in_array($val->name, $requestKeys)) {
                                    if (isset($val->type)) {
                                        $inputsOfEmailArray[$valueindex]['type'] = $val->type;
                                    }
                                    if (isset($val->label)) {
                                        $inputsOfEmailArray[$valueindex]['label'] = $val->label;
                                    }
                                    if (isset($val->subtype)) {
                                        $inputsOfEmailArray[$valueindex]['subtype'] = $val->subtype;
                                    }
                                    if (isset($val->className)) {
                                        $inputsOfEmailArray[$valueindex]['className'] = $val->className;
                                    }
                                    if (isset($val->type) && $val->type == 'checkbox-group') {
                                        $selctedchkvalues = array();
                                        foreach ($json_Array[$val->name] as $chkvalue) {
                                            $chklabel = \App\Helpers\MyLibrary::getLabelforformbuilder($chkvalue, $val->values);
                                            if (!empty($chklabel)) {
                                                array_push($selctedchkvalues, $chklabel);
                                            }
                                        }
                                        $checkbox = implode(",", $selctedchkvalues);
                                        $inputsOfEmailArray[$valueindex]['value'] = $checkbox;
                                    } else if (isset($val->type) && $val->type == 'radio-group') {
                                        $chklabel = \App\Helpers\MyLibrary::getLabelforformbuilder($json_Array[$val->name], $val->values);
                                        $checkbox = $chklabel;
                                        $inputsOfEmailArray[$valueindex]['value'] = $checkbox;
                                    } else if (isset($val->type) && $val->type == 'select') {
                                        $chklabel = \App\Helpers\MyLibrary::getLabelforformbuilder($json_Array[$val->name], $val->values);
                                        $checkbox = $chklabel;
                                        $inputsOfEmailArray[$valueindex]['value'] = $checkbox;
                                    } elseif (isset($val->type) && $val->type == 'textarea') {
                                        if (isset($val->subtype) && $val->subtype == 'tinymce') {
                                            $inputsOfEmailArray[$valueindex]['value'] = $json_Array[$val->name];
                                        } else if (isset($val->subtype) && $val->subtype == 'quill') {
                                            $inputsOfEmailArray[$valueindex]['value'] = nl2br($json_Array[$val->name]);
                                        } else {
                                            $inputsOfEmailArray[$valueindex]['value'] = nl2br($json_Array[$val->name]);
                                        }
                                    } else if (isset($val->className) && $val->className == 'form-control urlclass') {
                                        $url = $json_Array[$val->name];
                                        $currenturl = explode("/", $url);
                                        if (isset($currenturl[2])) {
                                            $wwwurl = explode(".", $currenturl[2]);
                                        } else {
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
                                        $inputsOfEmailArray[$valueindex]['value'] = $url_1 . $url_2 . $json_Array[$val->name];
                                    } elseif (isset($val->subtype) && $val->subtype == 'email') {
                                        $user_email = $json_Array[$val->name];
                                        $inputsOfEmailArray[$valueindex]['value'] = $user_email;
                                    } elseif (isset($val->type) && $val->type == 'text') {
                                        if (isset($val->subtype) && $val->subtype == 'color') {
                                            $inputsOfEmailArray[$valueindex]['value'] = $json_Array[$val->name] . '  <div style="width: 137px;height: 30px;background-color: ' . $json_Array[$val->name] . ';"></div>';
                                        } else {
                                            $inputsOfEmailArray[$valueindex]['value'] = $json_Array[$val->name];
                                        }
                                    } else {
                                        $inputsOfEmailArray[$valueindex]['value'] = $json_Array[$val->name];
                                    }
                                    $valueindex++;
                                } else if (isset($val->type) && $val->type == 'checkbox-group') {
                                    foreach ($val->values as $chkvalue) {
                                        if (isset($json_Array[$chkvalue->value])) {

                                            $inputsOfEmailArray[$valueindex]['type'] = '';
                                            if ($chkvalue->label == 'Country' && (isset($chkvalue->selected) && $chkvalue->selected == 1)) {
                                                $inputsOfEmailArray[$valueindex]['label'] = $chkvalue->label;
                                                $cname = \App\Helpers\MyLibrary::getEmailCountry($json_Array[$chkvalue->value]);
                                                $name = $cname[0]->var_name;
                                            } else if ($chkvalue->label == 'State' && (isset($chkvalue->selected) && $chkvalue->selected == 1)) {
                                                $inputsOfEmailArray[$valueindex]['label'] = $chkvalue->label;
                                                $sname = \App\Helpers\MyLibrary::getEmailState($json_Array[$chkvalue->value]);
                                                $name = $sname[0]->var_name;
                                            } else if ($chkvalue->label == 'Gender' && (isset($chkvalue->selected) && $chkvalue->selected == 1)) {
                                                $inputsOfEmailArray[$valueindex]['label'] = $chkvalue->label;
                                                if ($json_Array[$chkvalue->value] == 'male') {
                                                    $name = 'Male';
                                                } else if ($json_Array[$chkvalue->value] == 'female') {
                                                    $name = 'Female';
                                                } else if ($json_Array[$chkvalue->value] == 'transgender') {
                                                    $name = 'Trans Gender';
                                                }
                                            } else if ($chkvalue->label == 'Month' && (isset($chkvalue->selected) && $chkvalue->selected == 1)) {
                                                $inputsOfEmailArray[$valueindex]['label'] = $chkvalue->label;
                                                if ($json_Array[$chkvalue->value] == '01') {
                                                    $name = 'January';
                                                } else if ($json_Array[$chkvalue->value] == '02') {
                                                    $name = 'February';
                                                } else if ($json_Array[$chkvalue->value] == '03') {
                                                    $name = 'March';
                                                } else if ($json_Array[$chkvalue->value] == '04') {
                                                    $name = 'April';
                                                } else if ($json_Array[$chkvalue->value] == '05') {
                                                    $name = 'May';
                                                } else if ($json_Array[$chkvalue->value] == '06') {
                                                    $name = 'June';
                                                } else if ($json_Array[$chkvalue->value] == '07') {
                                                    $name = 'July';
                                                } else if ($json_Array[$chkvalue->value] == '08') {
                                                    $name = 'August';
                                                } else if ($json_Array[$chkvalue->value] == '09') {
                                                    $name = 'September';
                                                } else if ($json_Array[$chkvalue->value] == '10') {
                                                    $name = 'October';
                                                } else if ($json_Array[$chkvalue->value] == '11') {
                                                    $name = 'November';
                                                } else if ($json_Array[$chkvalue->value] == '12') {
                                                    $name = 'December';
                                                }
                                            } else {
                                                $name = '';
                                            }
                                            $inputsOfEmailArray[$valueindex]['value'] = $name;
                                            $valueindex++;
                                        }
                                    }
                                }
                            }
                            $details = '';
                            if (!empty($inputsOfEmailArray)) {
                                foreach ($inputsOfEmailArray as $input_value) {

                                    if (isset($input_value)) {
                                        if (isset($input_value['label'])) {
                                            if (isset($input_value['subtype']) && $input_value['subtype'] == 'email') {
                                                $details .= '' . $input_value['label'] . '' . ' :- ';
                                                $details .= $input_value['value'] . '<br/>';
                                            } else if (isset($input_value['subtype']) && $input_value['subtype'] == 'url') {
                                                $details .= $input_value['label'] . ' :- ';
                                                $details .= $input_value['value'] . '<br/>';
                                            } else if (isset($input_value['className']) && $input_value['className'] == 'form-control urlclass') {
                                                $details .= $input_value['label'] . ' :- ';
                                                $details .= $input_value['value'] . '<br/>';
                                            } else if (isset($input_value['type']) && $input_value['type'] == 'file') {
                                                $details .= 'File Name' . ' :- ';
                                                $details .= $row->filename . '<br/>';
                                            } else {
                                                $details .= $input_value['label'] . ' :- ';
                                                if ($input_value['value'] != '') {
                                                    $details .= $input_value['value'] . '<br/>';
                                                } else {
                                                    $details .= 'N/A' . '<br/>';
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $details .= '-';
                            }
                            ?>
                            <tr>
                                <td>{{ $customeformdata->varName }}</td>
                                <td>{{ $customeformdata->varEmail }}</td>
                                <td>{!! $details !!}</td>
                                <td>{{ (!empty($row->varIpAddress) ? $row->varIpAddress :'-') }}</td>
                                <td>{{ date(''.Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT').'',strtotime($row->created_at)) }}</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        @endif
</html>
