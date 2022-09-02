<!doctype html>
<html>
    <head>
        <title>{{ Config::get('Constant.SITE_NAME') }} Careers Leads</title>
    </head>
    <body>
        @if(isset($careersLead) && !empty($careersLead))
        <div class="row">
            <div class="col-12">
                <table class="search-result allData" id="" border="1">
                    <thead>
                        <tr>
                            <th style="font-weight: bold;text-align:center" colspan="11">{{ Config::get('Constant.SITE_NAME') }} {{ trans("careers::template.common.careersLeads") }}</th>
                        </tr>
                        <tr>
                            <th align="center" style="font-weight: bold;">First Name</th>
                            <th align="center" style="font-weight: bold;">Last Name</th>
                            <th align="center" style="font-weight: bold;">Phone No</th>
                            <th align="center" style="font-weight: bold;">Email</th>
                            <th align="center" style="font-weight: bold;">Address 1</th>
                            <th align="center" style="font-weight: bold;">Address 2</th>                            
                            <th align="center" style="font-weight: bold;">Country </th>                          
                            <th align="center" style="font-weight: bold;">State</th>
                            <th align="center" style="font-weight: bold;">City</th>
                            <th align="center" style="font-weight: bold;">Postal Code</th>
                            <th align="center" style="font-weight: bold;">DOB</th>
                            <th align="center" style="font-weight: bold;">Gender</th>  
                            <th align="center" style="font-weight: bold;">Resume</th>   
                            <th align="center" style="font-weight: bold;">Immigration Status</th>   
                            <th align="center" style="font-weight: bold;">Job Opening</th>   
                            <th align="center" style="font-weight: bold;">Describe Experience</th>   
                            <th align="center" style="font-weight: bold;">Reason For Change</th>   
                            <th align="center" style="font-weight: bold;">When To Start</th>   
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($careersLead as $row)
                        <tr>
                            @php
                                if(!empty($row->gender) && $row->gender =="M"){
                                    $gender = "Male";
                                }else {
                                    $gender = "Female";
                                }

                                $email = nl2br(app\Helpers\MyLibrary::getDecryptedString($row->varEmail));
                                $phoneNo = (app\Helpers\MyLibrary::getDecryptedString($row->varPhoneNo));

                            @endphp

                            <td align="center">{{ $row->varTitle }}</td>
                            <td align="center">{{ $row->varLastName }}</td>
                            <td align="center">{{ $phoneNo }}</td>
                            <td align="center">{{ $email }}</td>
                            <td align="center">{{ $row->varAddress1 }}</td>

                            <td align="center">{{ (!empty($row->varAddress2) ? $row->varAddress2 : "-") }}</td>
                            <td align="center">{{ (!empty($row->varCountry) ? $row->varCountry : "-") }}</td>
                            <td align="center">{{ (!empty($row->varState) ? $row->varState : "-") }}</td>
                            <td align="center">{{ (!empty($row->varCity) ? $row->varCity : "-") }}</td>

                            <td align="center">{{ $row->varPostalCode }}</td>
                            <td align="center">{{ date(''.Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT').'',strtotime($row->created_at)) }}</td>
                            <td align="center">{{ $gender }}</td>
                            <td align="center">{{ $row->resume }}</td>

                            <td align="center">{{ (!empty($row->varImmigrationStatus) ? $row->varImmigrationStatus : "-") }}</td>
                            <td align="center">{{ (!empty($row->varJobOpening) ? $row->varJobOpening : "-") }}</td>
                            <td align="center">{{ (!empty($row->varDescribeExp) ? $row->varDescribeExp : "-") }}</td>
                            <td align="center">{{ (!empty($row->varReasonForChange) ? $row->varReasonForChange : "-") }}</td>
                            <td align="center">{{ (!empty($row->varWhenToStart) ? $row->varWhenToStart : "-") }}</td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
</html>
