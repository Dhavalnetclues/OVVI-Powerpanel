<!doctype html>
<html>
  <head>
    <title>{{ Config::get('Constant.SITE_NAME') }} Newsletter Leads</title>
  </head>
  <body>
      @if(isset($referearnLead) && !empty($referearnLead))
          <div class="row">
           <div class="col-12">
              <table class="search-result allData" id="" border="1">
                 <thead>
                  <tr>
                        <th style="font-weight: bold;text-align:center" colspan="6">{{ trans("referearn::template.referearnsModule.referearnLeads") }}</th>
                   </tr>
                    <tr>
                       <th style="font-weight: bold;">{{ trans('referearn::template.referearnsModule.name') }}</th>
                       <th style="font-weight: bold;">{{ trans('referearn::template.common.email') }}</th>
                       <th style="font-weight: bold;">{{ trans('referearn::template.referearnsModule.referralname') }}</th>
                       <th style="font-weight: bold;">{{ trans('referearn::template.referearnsModule.referralemail') }}</th>
                       <th style="font-weight: bold;">{{ trans('referearn::template.referearnsModule.details') }}</th>
                       <th style="font-weight: bold;">{{ trans('referearn::template.referearnsModule.message') }}</th>
                       <th style="font-weight: bold;">{{ trans('referearn::template.common.ipAddress') }}</th>
                       <th style="font-weight: bold;">{{ trans('referearn::template.referearnsModule.receivedDateTime') }}</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach($referearnLead as $row)
                        <?php 
                        $label = ''; 
                        $varName = (!empty($row->varName) ? $row->varName  : '-');
                        $Email = (!empty($row->varEmailId) ? \App\Helpers\MyLibrary::decryptLatest($row->varEmailId)  : '');
                        $ReferralFullNameme = (!empty($row->varReferralFullName) ? $row->varReferralFullName  : '-');
                        $ReferralEmailId = (!empty($row->varReferralEmailId) ? \App\Helpers\MyLibrary::decryptLatest($row->varReferralEmailId)  : '');
                        $ReferralPhoneNumber = (!empty($row->varReferralPhoneNumber) ? \App\Helpers\MyLibrary::decryptLatest($row->varReferralPhoneNumber)  : '');
                        $BusinessType = (!empty($row->varBusinessType) ? $row->varBusinessType  : '');
                        $LookingForPOS = (!empty($row->varLookingForPOS) ? $row->varLookingForPOS  : '');
                        $Message = '-';
                        if(!empty($ReferralPhoneNumber)){
                              $label .= 'Referral\'s Phone  :- '.$ReferralPhoneNumber.'<br>';
                        }
                        if(!empty($BusinessType)){
                           $BusinessTypeArr = ["Quick-Serve","Restaurant / Bar","Retail","Bakery","Coffee Shop","Concessions / Snacks", "Food Truck","Ice Cream / Yogurt","Juice Bar","Limited-Service Restaurant","Fine Dining","Bar and Lounge","Pizza","Convenience Store","Liquor Store","Grocery Store","Other"];
                           $BusinessTypeList = explode(',',$BusinessType);
                           $BusinessTypeListArr = array();
                           foreach($BusinessTypeList as $BusinessTypeA){
                              $BusinessTypeListArr[] = $BusinessTypeArr[$BusinessTypeA];
                           }
                           $BusinessTypeDisplay = implode(", ",$BusinessTypeListArr);
                           $label .= 'Business Type :- '.$BusinessTypeDisplay.'<br>';

                           if(!empty($LookingForPOS)){
                              $LookingForPOSArr = ["Immediately","1 Month","2 Months","3-6 Months","Greater Than 6 Months"];                   
                              $label .= 'POS :- '.$LookingForPOSArr[trim($LookingForPOS)].'<br>';
                           }
                           if (!empty($row->varMessage)) {
											$Message = htmlspecialchars($row->varMessage);
									}
                     }
                        ?>
                    <tr>
                       <td>{{ $varName }}</td>
                       <td>{!! $Email !!}</td>
                       <td>{{ $ReferralFullNameme }}</td>
                       <td>{!! $ReferralEmailId !!}</td>
                       <td>{!! $label !!}</td>
                       <td>{{ $Message }}</td>
                       <td>{{ (!empty($row->varIpAddress) ? $row->varIpAddress :'-') }}</td>
                       <td>{{ \App\Helpers\MyLibrary::UTCToTimeZone($row->created_at, 'UTC', 'America/Chicago')  }}</td>
                    </tr>
                  @endforeach
                 </tbody>
              </table>
           </div>
        </div>
      @endif
  </html>
