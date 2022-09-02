<!doctype html>
<html>
    <head>
        <title>{{ Config::get('Constant.SITE_NAME') }} online polling Leads</title>
    </head>
    <body>
        @if(isset($OnlinePollingLead) && !empty($OnlinePollingLead))
       
        <div class="row">
            <div class="col-12">
                <table class="search-result allData" id="" border="1">
                    <thead>
                        <tr>
                            <th style="font-weight: bold;text-align:center" colspan="11">{{ Config::get('Constant.SITE_NAME') }} {{ trans("polls::template.complaintleadModule.complaintLeads") }}</th>
                        </tr>
                        <tr>
                            <th style="font-weight: bold;">{{ trans('polls::template.common.title') }}</th>
                           
                            <th style="font-weight: bold;">{{ trans('Form Details') }}</th>
                            <th style="font-weight: bold;">{{ trans('polls::template.onlinepollingleadModule.message') }}</th>
                           
                            <th style="font-weight: bold;">{{ trans('polls::template.onlinepollingleadModule.receivedDateTime') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($OnlinePollingLead as $row)
                        <tr>
                            <td>{{ $row->varTitle }}</td>
                           
                          
                            <td>{{ date(''.Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT').'',strtotime($row->created_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
</html>
