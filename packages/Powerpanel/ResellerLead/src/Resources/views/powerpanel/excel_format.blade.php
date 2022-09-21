<!doctype html>
<html>
  <head>
    <title>{{ Config::get('Constant.SITE_NAME') }} Reseller Leads</title>
  </head>
  <body>
   <?php //print_r($ResellerLead);die;?>
      @if(isset($ResellerLead) && !empty($ResellerLead))
          <div class="row">
           <div class="col-12">
              <table class="search-result allData" id="" border="1">
                 <thead>
                  <tr>
                        <th style="font-weight: bold;text-align:center" colspan="6">{{ trans("resellerlead::template.resellerleadModule.resellerLeads") }}</th>
                   </tr>
                    <tr>
                       <th style="font-weight: bold;">{{ trans('resellerlead::template.common.name') }}</th>
                       <th style="font-weight: bold;">{{ trans('resellerlead::template.common.email') }}</th>
                       <th style="font-weight: bold;">{{ trans('resellerlead::template.resellerleadModule.phone') }}</th>
                       <th style="font-weight: bold;">{{ trans('resellerlead::template.resellerleadModule.company') }}</th>
                       <th style="font-weight: bold;">{{ trans('resellerlead::template.resellerleadModule.resellerdetail') }}</th>
                       <th style="font-weight: bold;">{{ trans('resellerlead::template.resellerleadModule.message') }}</th>
                       <th style="font-weight: bold;">{{ trans('template.common.ipAddress') }}</th>
                       <th style="font-weight: bold;">{{ trans('resellerlead::template.resellerleadModule.receivedDateTime') }}</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach($ResellerLead as $row)   
                  <?php
                  $Satisfied = '-';
                  $Visitfor = '-';
                  $category = '-';
                  $phoneNo = '-';
                  $Email = '-';
                  $userMessage = '-';
                  $Company = '-';
                  $Resellerdetails = '';
                  $Country = (!empty($row->country) ? $row->country  : '');
                  $State = (!empty($row->state) ? $row->state  : '');
                  $City = (!empty($row->city) ? $row->city  : '');

                  if(!empty($row->varAddress)){
                     $Resellerdetails .= 'Street Address :- '.$row->varAddress.'<br>';
                  }
                  if(!empty($Country)){
                     $Resellerdetails .= 'Country :- '.$Country.'<br>';
                  }
                  if(!empty($State)){
                     $Resellerdetails .= 'State :- '.$State.'<br>';
                  }
                  if(!empty($City)){
                     $Resellerdetails .= 'City :- '.$City.'<br>';
                  }
                  if(!empty($row->varBestTimeToCall)){
                     $Resellerdetails .= 'Best Time To Call :- '.$row->varBestTimeToCall.'<br>';
                  }
	                  
                  if (!empty($row->varCompaney) ) {
                        $Company = $row->varCompaney;
                  } 									

                  if (!empty($row->varPhoneNumber)) {
                        $phoneNo = \App\Helpers\MyLibrary::decryptLatest($row->varPhoneNumber);
                  }

                  if (!empty($row->varEmailId)) {
                        $Email = \App\Helpers\MyLibrary::decryptLatest($row->varEmailId);
                  }

                  if (!empty($row->varMessage)) {
                        $userMessage = htmlspecialchars($row->varMessage);
                  }
                  ?>
                    <tr>
                       <td>{{ $row->varTitle }}</td>
                       <td>{{ $Email }}</td>
                       <td>{{ $phoneNo }}</td>
                       <td>{{ $Company }}</td>
                       <td>{!! $Resellerdetails !!}</td>
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
