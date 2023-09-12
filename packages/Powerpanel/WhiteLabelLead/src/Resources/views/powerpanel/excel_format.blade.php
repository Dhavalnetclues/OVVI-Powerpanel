<!doctype html>
<html>
  <head>
    <title>Contact Leads</title>
  </head>
  <body>
      @if(isset($WhiteLabelLead) && !empty($WhiteLabelLead))
          <div class="row">
           <div class="col-12">
              <table class="search-result allData" id="" border="1">
                 <thead>
                  <tr>
                        <th style="font-weight: bold;text-align:center" colspan="6">{{ trans("whitelabellead::template.WhiteLabelLeadModule.whiteLabelLeads") }}</th>
                   </tr>
                    <tr>
                       <th style="font-weight: bold;">{{ trans('whitelabellead::template.common.name') }}</th>
                       <th style="font-weight: bold;">{{ trans('whitelabellead::template.common.email') }}</th>
                       <th style="font-weight: bold;">{{ trans('whitelabellead::template.WhiteLabelLeadModule.phone') }}</th>
                       <th style="font-weight: bold;">{{ trans('whitelabellead::template.common.business') }}</th>
                       <th style="font-weight: bold;">{{ trans('whitelabellead::template.WhiteLabelLeadModule.message') }}</th>
                       <th style="font-weight: bold;">{{ trans('template.common.ipAddress') }}</th>
                       <th style="font-weight: bold;">{{ trans('whitelabellead::template.WhiteLabelLeadModule.receivedDateTime') }}</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach($WhiteLabelLead as $row)
                    <tr>
                       <td>{{ $row->varTitle }}</td>
                       <td>{{ \App\Helpers\MyLibrary::decryptLatest($row->varEmailId) }}</td>
                       <td>{{ (!empty($row->varPhoneNumber)?\App\Helpers\MyLibrary::decryptLatest($row->varPhoneNumber):'-') }}</td>
                       <td>{{ (!empty($row->varBusinessName) ? $row->varBusinessName :'-') }}</td>
                       <td>{{ (!empty($row->varMessage)? htmlspecialchars(strip_tags($row->varMessage)) :'-') }}</td>
                       <td>{{ (!empty($row->varIpAddress) ? $row->varIpAddress :'-') }}</td>
                       <td>{{ date(''.Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT').'',strtotime($row->dtCreateDate)) }}</td>
                    </tr>
                  @endforeach
                 </tbody>
              </table>
           </div>
        </div>
      @endif
  </html>
