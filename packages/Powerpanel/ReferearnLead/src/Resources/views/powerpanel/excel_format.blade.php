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
                              $label .= '<b>Referral\'s Phone  :- </b>'.$ReferralPhoneNumber.'<br>';
                        }
                        if(!empty($BusinessType)){
                           $BusinessTypeArr = [
                              "Quick_Serve" 			=> "Quick-Serve",
                              "Restaurant_/_Bar"		=> "Restaurant / Bar",
                              "Retail"				=> "Retail",
                              "Bakery"				=> "Bakery",
                              "Coffee_Shop" 			=> "Coffee Shop",
                              "Concessions_/_Snacks" 	=> "Concessions / Snacks",
                              "Food_Truck"			=> "Food Truck",
                              "Ice_Cream_/_Yogurt" 	=> "Ice Cream / Yogurt",
                              "Juice_Bar"				=> "Juice Bar",
                              "Limited-Service_Restaurant" => "Limited-Service Restaurant",
                              "Fine_Dining"			=> "Fine Dining",
                              "Bar_and_Lounge"		=> "Bar and Lounge",
                              "Pizza"					=> "Pizza",
                              "Convenience_Store"		=> "Convenience Store",
                              "Liquor_Store"			=> "Liquor Store",
                              "Grocery_Store"			=> "Grocery Store",
                              "Other"					=> "Other"
                           ];
                           $BusinessTypeList = explode(',',$BusinessType);
                           $BusinessTypeListArr = array();
                           foreach($BusinessTypeList as $BusinessTypeA){
                              $BusinessTypeListArr[] = $BusinessTypeArr[$BusinessTypeA];
                           }
                           $BusinessTypeDisplay = implode(", ",$BusinessTypeListArr);
                           $label .= '<b>Business Type :- </b>'.$BusinessTypeDisplay.'<br>';

                           if(!empty($LookingForPOS)){
                              $LookingForPOSArr = [
                                 "Immediately"   => "Immediately",
                                 "Immediatel"   => "Immediately",
                                 "1"             => "1 Month",
                                 "2"             => "2 Months",
                                 "3-6"             => "3-6 Months",
                                 "6+"            => "Greater Than 6 Months"
                              ];          
                              $label .= 'POS :- '.$LookingForPOSArr[trim($LookingForPOS)].'<br>';
                           }
                           if (!empty($row->varMessage)) {
											$Message = $row->varMessage;
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
                       <td>{{ date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($row->created_at)) }}</td>
                    </tr>
                  @endforeach
                 </tbody>
              </table>
           </div>
        </div>
      @endif
  </html>
