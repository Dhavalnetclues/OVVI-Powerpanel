<!doctype html>
<html>
    <head>
        <title>{{ Config::get('Constant.SITE_NAME') }} complaint Leads</title>
    </head>
    <body>
        @if(isset($CareerLead) && !empty($CareerLead))
        <div class="row">
            <div class="col-12">
                <table class="search-result allData" id="" border="1">
                    <thead>
                        <tr>
                            <th style="font-weight: bold;text-align:center" colspan="11"> {{ trans("careerlead::template.careerleadModule.careerLeads") }}</th>
                        </tr>
                        <tr>
                            <th style="font-weight: bold;">{{ trans('careerlead::template.common.name') }}</th>
                            <th style="font-weight: bold;">{{ trans('careerlead::template.common.email') }}</th>
                            <th style="font-weight: bold;">{{ trans('careerlead::template.careerleadModule.phone') }}</th>                           
                            <th style="font-weight: bold;">{{ trans('careerlead::template.careerleadModule.message') }}</th>
                            <th style="font-weight: bold;">{{ trans('careerlead::template.careerleadModule.visitedpage') }}</th>
                            <th style="font-weight: bold;">{{ trans('careerlead::template.careerleadModule.ipaddress') }}</th>
                            <th style="font-weight: bold;">{{ trans('careerlead::template.careerleadModule.receivedDateTime') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($CareerLead as $row)
                        <tr>
                            <td>{{ $row->varTitle }}</td>
                            @php
                            $email = \App\Helpers\MyLibrary::decryptLatest($row->varEmail);
                            @endphp
                            <td>{{ (!empty($email)?($email):'-') }}</td>
                            @php
                            $phone = \App\Helpers\MyLibrary::decryptLatest($row->varPhoneNo);
                            @endphp
                            <td>{{ (!empty($phone) ? $phone : '-')  }}</td>
                            <td>{{ (!empty($row->varMessage)?($row->varMessage):'-') }}</td>
                            <td>{{ (!empty($row->varPageName)?($row->varPageName):'-') }}</td>
                            <td>{{ (!empty($row->varIpAddress)?($row->varIpAddress):'-') }}</td>
                            <td>{{ \App\Helpers\MyLibrary::UTCToTimeZone($row->created_at, 'UTC', 'America/Chicago')  }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
</html>
