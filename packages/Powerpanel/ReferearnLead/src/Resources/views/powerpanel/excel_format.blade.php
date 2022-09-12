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
                        <th style="font-weight: bold;text-align:center" colspan="6">{{ Config::get('Constant.SITE_NAME') }} {{ trans("template.referearnsModule.newslettersLeads") }}</th>
                   </tr>
                    <tr>
                       <th style="font-weight: bold;">{{ trans('template.referearnsModule.name') }}</th>
                       <th style="font-weight: bold;">{{ trans('template.common.email') }}</th>
                       <th style="font-weight: bold;">{{ trans('template.referearnsModule.referralname') }}</th>
                       <th style="font-weight: bold;">{{ trans('template.referearnsModule.referralemail') }}</th>
                       <th style="font-weight: bold;">{{ trans('template.referearnsModule.subscribed') }}</th>
                       <th style="font-weight: bold;">{{ trans('template.common.ipAddress') }}</th>
                       <th style="font-weight: bold;">{{ trans('template.contactleadModule.receivedDateTime') }}</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach($referearnLead as $row)
                        <?php 
                        $label = ''; 
                        $varName = (!empty($row->varName) ? $row->varName  : '-');
                        $Email = (!empty($row->varOnEmailId) ? \App\Helpers\MyLibrary::decryptLatest($row->varOnEmailId)  : '');
                        $ReferralFullNameme = (!empty($row->varReferralFullName) ? $row->varReferralFullName  : '-');
                        $ReferralEmailId = (!empty($row->varReferralEmailId) ? \App\Helpers\MyLibrary::decryptLatest($row->varReferralEmailId)  : '');
                        $BusinessType = (!empty($row->varBusinessType) ? $row->varBusinessType  : '');
                        $LookingForPOS = (!empty($row->varLookingForPOS) ? $row->varLookingForPOS  : '');
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
                              $label .= '<b>POS :- </b>'.$LookingForPOSArr[trim($LookingForPOS)].'<br>';
                           }
                     }
                        ?>
                    <tr>
                       <td>{{ $varName }}</td>
                       <td>{!! $Email !!}</td>
                       <td>{{ $ReferralFullNameme }}</td>
                       <td>{!! $ReferralEmailId !!}</td>
                       <td>{{ $label }}</td>
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
