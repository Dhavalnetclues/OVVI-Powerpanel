<!doctype html>
<html>
  <head>
    <title>Contact Leads</title>
  </head>
  <body>
      @if(isset($ContactLead) && !empty($ContactLead))
          <div class="row">
           <div class="col-12">
              <table class="search-result allData" id="" border="1">
                 <thead>
                  <tr>
                        <th style="font-weight: bold;text-align:center" colspan="6">{{ trans("contactuslead::template.contactleadModule.contactUsLeads") }}</th>
                   </tr>
                    <tr>
                       <th style="font-weight: bold;">{{ trans('contactuslead::template.common.name') }}</th>
                       <th style="font-weight: bold;">{{ trans('contactuslead::template.common.email') }}</th>
                       <th style="font-weight: bold;">{{ trans('contactuslead::template.contactleadModule.phone') }}</th>
                       <th style="font-weight: bold;">{{ trans('contactuslead::template.common.business') }}</th>
                       <th style="font-weight: bold;">{{ trans('contactuslead::template.contactleadModule.message') }}</th>
                       <th style="font-weight: bold;">{{ trans('template.common.ipAddress') }}</th>
                       <th style="font-weight: bold;">{{ trans('contactuslead::template.contactleadModule.receivedDateTime') }}</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach($ContactLead as $row)
                    <tr>
                       <td>{{ $row->varTitle }}</td>
                       <td>{{ \App\Helpers\MyLibrary::decryptLatest($row->varEmailId) }}</td>
                       <td>{{ (!empty($row->varPhoneNumber)?\App\Helpers\MyLibrary::decryptLatest($row->varPhoneNumber):'-') }}</td>
                       <td>{{ (!empty($row->varBusinessName) ? $row->varBusinessName :'-') }}</td>
                       <td>{{ (!empty($row->varMessage)? htmlspecialchars(strip_tags($row->varMessage)) :'-') }}</td>
                       <td>{{ (!empty($row->varIpAddress) ? $row->varIpAddress :'-') }}</td>
                       <td>{{ \App\Helpers\MyLibrary::UTCToTimeZone($row->dtCreateDate, 'UTC', 'America/Chicago')  }}</td>
                    </tr>
                  @endforeach
                 </tbody>
              </table>
           </div>
        </div>
      @endif
  </html>
