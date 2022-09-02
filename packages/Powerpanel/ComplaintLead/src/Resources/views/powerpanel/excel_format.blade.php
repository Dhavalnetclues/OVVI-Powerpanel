<!doctype html>
<html>
    <head>
        <title>{{ Config::get('Constant.SITE_NAME') }} complaint Leads</title>
    </head>
    <body>
        @if(isset($ComplaintLead) && !empty($ComplaintLead))
        <div class="row">
            <div class="col-12">
                <table class="search-result allData" id="" border="1">
                    <thead>
                        <tr>
                            <th style="font-weight: bold;text-align:center" colspan="11">{{ Config::get('Constant.SITE_NAME') }} {{ trans("complaintlead::template.complaintleadModule.complaintLeads") }}</th>
                        </tr>
                        <tr>
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.common.name') }}</th>
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.common.email') }}</th>
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.complaintleadModule.service') }}</th>
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.complaintleadModule.company') }}</th>
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.complaintleadModule.dateofcomplaint') }}</th>
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.complaintleadModule.phone') }}</th>
                            
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.complaintleadModule.companyresponse') }}</th>
                           
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.complaintleadModule.receivedDateTime') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ComplaintLead as $row)
                        <tr>
                            <td>{{ $row->varTitle }}</td>
                            @php
                            $email = \App\Helpers\MyLibrary::getDecryptedString($row->varEmail);
                            @endphp
                            <td>{{ (!empty($email)?($email):'-') }}</td>
                            <td>{{ (!empty($row->varService)?($row->varService):'-') }}</td>
                            @php
                          $companyname = Powerpanel\Companies\Models\Companies::getRecordById($row->fkIntCompanyId);
                            @endphp
                            <td>{{ $companyname['varTitle'] }}</td>
                            <td>{{ date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ', strtotime($row->complaint_date)) }}</td>
                             @php
                            $phone = \App\Helpers\MyLibrary::getDecryptedString($row->varPhoneNo);
                            @endphp
                            <td>{{ (!empty($phone) ? $phone : '-')  }}</td>
                           
                            <td>{{ (!empty($row->company_response)?strip_tags($row->company_response):'-') }}</td>
                          
                            <td>{{ date(''.Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT').'',strtotime($row->created_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
</html>
