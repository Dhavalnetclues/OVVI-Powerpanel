<!doctype html>
<html>
  <head>
    <title>{{ Config::get('Constant.SITE_NAME') }} Getdemo Leads</title>
  </head>
  <body>
      @if(isset($GetdemoLead) && !empty($GetdemoLead))
          <div class="row">
           <div class="col-12">
              <table class="search-result allData" id="" border="1">
                 <thead>
                  <tr>
                        <th style="font-weight: bold;text-align:center" colspan="6">{{ trans("getdemolead::template.getdemoleadModule.getdemoLeads") }}</th>
                   </tr>
                    <tr>
                       <th style="font-weight: bold;">{{ trans('getdemolead::template.common.name') }}</th>
                       <th style="font-weight: bold;">{{ trans('getdemolead::template.common.email') }}</th>
                       <th style="font-weight: bold;">{{ trans('getdemolead::template.getdemoleadModule.phone') }}</th>
                       <th style="font-weight: bold;">{{ trans('getdemolead::template.getdemoleadModule.business') }}</th>
                       <th style="font-weight: bold;">{{ trans('getdemolead::template.getdemoleadModule.message') }}</th>
                       <th style="font-weight: bold;">{{ trans('template.common.ipAddress') }}</th>
                       <th style="font-weight: bold;">{{ trans('getdemolead::template.getdemoleadModule.receivedDateTime') }}</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach($GetdemoLead as $row)   
                  @php
                  $Satisfied = '-';
                  $Visitfor = '-';
                  $category = '-';
                  $phoneNo = '-';
                  $userMessage = '-';
                  $Business = '-';
	                  
                              if (!empty($row->varBusinessName) ) {
                                    $Business = $row->varBusinessName;
                              } 									

										if (!empty($row->varPhoneNo)) {
												$phoneNo = \App\Helpers\MyLibrary::decryptLatest($row->varPhoneNo);
										}

										if (!empty($row->txtUserMessage)) {
												$userMessage = $row->txtUserMessage;
										}
                  @endphp
                    <tr>
                       <td>{{ $row->varName }}</td>
                       <td>{{ \App\Helpers\MyLibrary::decryptLatest($row->varEmail) }}</td>
                       <td>{{ $phoneNo }}</td>
                       <td>{{ $Business }}</td>
                       <td>{{ $userMessage }}</td>
                       <td>{{ (!empty($row->varIpAddress) ? $row->varIpAddress :'-') }}</td>
                       <td>{{ date(''.Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT').'',strtotime($row->created_at)) }}</td>
                    </tr>
                  @endforeach
                 </tbody>
              </table>
           </div>
        </div>
      @endif
  </html>
