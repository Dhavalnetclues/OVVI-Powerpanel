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
                            <th style="font-weight: bold;text-align:center" colspan="11"> {{ trans("complaintlead::template.complaintleadModule.complaintLeads") }}</th>
                        </tr>
                        <tr>
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.common.name') }}</th>
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.common.email') }}</th>
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.complaintleadModule.message') }}</th>
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.complaintleadModule.phone') }}</th>                           
                            <th style="font-weight: bold;">{{ trans('complaintlead::template.complaintleadModule.receivedDateTime') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ComplaintLead as $row)
                        <tr>
                            <td>{{ $row->varTitle }}</td>
                            @php
                            $email = \App\Helpers\MyLibrary::decryptLatest($row->varEmail);
                            @endphp
                            <td>{{ (!empty($email)?($email):'-') }}</td>
                            <td>{{ (!empty($row->varMessage)?($row->varMessage):'-') }}</td>
                             @php
                            $phone = \App\Helpers\MyLibrary::decryptLatest($row->varPhoneNo);
                            @endphp
                            <td>{{ (!empty($phone) ? $phone : '-')  }}</td>
                            <td>{{ date(''.Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT').'',strtotime($row->created_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
</html>
