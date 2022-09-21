<!doctype html>
<html>
    <head>
        <title>Order Leads</title>
    </head>
    <body>
        @if(isset($OrderLeads) && !empty($OrderLeads))
        <div class="row">
            <div class="col-12">
                <table class="search-result allData" id="" border="1">
                    <thead>
                        <tr>
                            <th style="font-weight: bold;text-align:center" colspan="6">{{ trans("Order Leads") }}</th>
                        </tr>
                        <tr>
                            <th style="font-weight: bold;">{{ trans('orderlead::template.orderleadModule.business') }}</th>
                            <th style="font-weight: bold;">{{ trans('orderlead::template.orderleadModule.fullname') }}</th>
                            <th style="font-weight: bold;">{{ trans('orderlead::template.common.email') }}</th>
                            <th style="font-weight: bold;">Business Detail</th>
                            <th style="font-weight: bold;">Business Information	</th>
                            <th style="font-weight: bold;">{{ trans('orderlead::template.orderleadModule.ipAddress') }}</th>
                            <th style="font-weight: bold;">{{ trans('orderlead::template.orderleadModule.receivedDateTime') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($OrderLeads as $value) {
                            $inputsOfEmailArray = array();
                            $valueindex = 0;
                            $checkbox = '';
                            $user_email = '';
                            $details = '';
                            $label = '';
                            $FullName = (!empty($value->varOnFullName) ? $value->varOnFullName  : '');
                            $Email = (!empty($value->varOnEmailId) ? \App\Helpers\MyLibrary::decryptLatest($value->varOnEmailId)  : '');
                            $BusinessType = (!empty($value->varOnBusinessType) ? $value->varOnBusinessType  : '');
                            $POSBundle = (!empty($value->chrOnPOSBundle) ? $value->chrOnPOSBundle  : '');
                            $POSColor = (!empty($value->chrOnPOSColor) ? $value->chrOnPOSColor  : '');
                            $SoftwareServiceFees = (!empty($value->varOnSoftwareServiceFees) ? $value->varOnSoftwareServiceFees  : '');
                            $Peripherals = (!empty($value->varOnPeripherals) ? $value->varOnPeripherals  : '');
                            $MenuProgramming = (!empty($value->varOnMenuProgramming) ? $value->varOnMenuProgramming  : '');
                            $AdditionalModules = (!empty($value->varOnAdditionalModules) ? $value->varOnAdditionalModules  : '');
                            if(!empty($BusinessType)){
                                $BusinessTypeArr = [
                                    "Fine_Dining_Restaurant" 	=> "Fine Dining Restaurant",
                                    "Quick_Service"          	=> "Quick Service",
                                    "Full_Service_Casual"    	=> "Full Service Casual",
                                    "Pizza"					 	=> "Pizza",
                                    "Bar_and_Lounge"		 	=> "Bar and Lounge",
                                    "Frozen_Yogurt/Ice_Cream" 	=> "Frozen Yogurt/Ice Cream",
                                    "Food_Truck"				=> "Food Truck",
                                    "Cafes/Bistros"				=> "Cafes/Bistros",
                                    "Convenience_Store"			=> "Convenience Store",
                                    "Liquor_Store"				=> "Liquor Store",
                                    "Bakery"					=> "Bakery",
                                    "Grocery_Store"				=> "Grocery Store",
                                    "Clothing_Store"			=> "Clothing Store",
                                    "Other"						=> "Other"
                                ];
                                $BusinesTypeList = explode(',',$BusinessType);
                                $BusinesTypeListArr = array();
                                foreach($BusinesTypeList as $BusinessTypes){
                                    $BusinesTypeListArr[] = $BusinessTypeArr[$BusinessTypes];
                                }
                                $BusinesTypeListDisplay = implode(", ",$BusinesTypeListArr);
                                $details .= 'Business Type :- '.$BusinesTypeListDisplay.'<br>';
                                // $details .= 'Business Type :- '.$BusinessTypeArr[$BusinessType].'<br>';
                            }
                            if(!empty($POSBundle)){
                                $details .= 'No Of POS Bundle :- '.$POSBundle.'<br>';
                            }
                            if(!empty($POSColor)){
                                $details .= 'POS Color :- '.(($POSColor == "B") ? "Black" : "White").'<br>';
                            }
                            if(!empty($SoftwareServiceFees)){
                                $SoftwareServiceFeesArr = ['', '$79.00 / Month (Paid Monthly)','$69.00 / Month (Paid Annually)'];
                                $details .= 'Software And Service Fees :- '.$SoftwareServiceFeesArr[$SoftwareServiceFees].'<br>';
                            }
                            if(!empty($Peripherals)){
                                $PeripheralsArr = [
                                    "EMV - Credit Card Terminal",
                                    "Kitchen Printer",
                                    "Bar Code Scanner",
                                    "Weighing Scale",
                                    "Customer Display – 2 Line – Only Available in Black Color",
                                    "Customer Display – 10 Inch – Only Available in Black Color"
                                ];
                                $PeripheralsList = explode(',',$Peripherals);
                                $PeripheralsListArr = array();
                                foreach($PeripheralsList as $Peripheral){
                                    $PeripheralsListArr[] = $PeripheralsArr[$Peripheral];
                                }
                                $PeripheralsListDisplay = implode(", ",$PeripheralsListArr);
                                $details .= 'Pheripherals :- '.$PeripheralsListDisplay.'<br>';
                            }
                            if(!empty($MenuProgramming)){
                                $MenuProgrammingArr = ['', '1 – 250 items','250 – 500 items','500 + items'];
                                $details .= 'Menu Programming :- '.$MenuProgrammingArr[$MenuProgramming].'<br>';
                            }
                            // echo $result;die;
                            if(!empty($AdditionalModules)){
                                $AdditionalModulesArr = [
                                    "Online Ordering - $39/month",
                                    "Gift Cards Processing - $15/month",
                                    "50+ - 3rd Party App Integration – $100/month (Unlimited Apps)",
                                    "Direct Accounting Integration – QuickBooks, Sage and 10 more - $25/month",
                                    "Direct Payroll Integration – ADT, Gusto and 7 more - $25/month",
                                    "Website Development and Social Media Platforms – TBD"
                                ];
                                $AdditionalModulesList = explode(',',$AdditionalModules);
                                $AdditionalModulesDisplayArr = array();
                                foreach($AdditionalModulesList as $AdditionalModule){
                                    $AdditionalModulesDisplayArr[] = $AdditionalModulesArr[$AdditionalModule];
                                }
                                $AdditionalModuleDisplay = implode(", ",$AdditionalModulesDisplayArr);
                                // print_r($AdditionalModuleDisplay);die;
                                $details .= 'Additional Modules :- '.$AdditionalModuleDisplay.'<br>';
                            }

                            $Businessdetails = '';
                            $Businesslabel = '';
                            $Country = (!empty($value->country) ? $value->country  : '');
                            $State = (!empty($value->state) ? $value->state  : '');
                            $City = (!empty($value->city) ? $value->city  : '');
                            $ZipCode = (!empty($value->varOnZipCode) ? \App\Helpers\MyLibrary::decryptLatest($value->varOnZipCode)  : '');
                            $PhoneNumber = (!empty($value->varOnPhoneNumber) ? \App\Helpers\MyLibrary::decryptLatest($value->varOnPhoneNumber)  : '');
                            $StreetAddress = (!empty($value->varOnStreetAddress) ? trim($value->varOnStreetAddress)  : '');
                            $AdditionalEquipment = (!empty($value->varOnAdditionalEquipment) ? $value->varOnAdditionalEquipment  : '');
                            if(!empty($StreetAddress)){
                                $Businessdetails .= 'Street Address :- '.htmlspecialchars($StreetAddress).'<br>';
                            }
                            if(!empty($Country)){
                                $Businessdetails .= 'Country :- '.$Country.'<br>';
                            }
                            if(!empty($State)){
                                $Businessdetails .= 'State :- '.$State.'<br>';
                            }
                            if(!empty($City)){
                                $Businessdetails .= 'City :- '.$City.'<br>';
                            }
                            if(!empty($ZipCode)){
                                $Businessdetails .= 'ZipCode :- '.$ZipCode.'<br>';
                            }
                            if(!empty($PhoneNumber)){
                                $Businessdetails .= 'PhoneNumber :- '.$PhoneNumber.'<br>';
                            }
                            if(!empty($AdditionalEquipment)){
                                $Businessdetails .= 'AdditionalEquipment :- '.htmlspecialchars($AdditionalEquipment).'<br>';
                            }                            
                            ?>
                            <tr>
                                <td>{{ $value->varTitle }}</td>
                                <td>{{ $FullName }}</td>
                                <td>{{ \App\Helpers\MyLibrary::decryptLatest($value->varOnEmailId) }}</td>
                                <td>{!! $details !!}</td>
                                <td>{!! $Businessdetails !!}</td>
                                <td>{{ (!empty($value->varIpAddress) ? $value->varIpAddress :'-') }}</td>
                                <td>{{ date(''.Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT').'',strtotime($value->created_at)) }}</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        @endif
</html>
