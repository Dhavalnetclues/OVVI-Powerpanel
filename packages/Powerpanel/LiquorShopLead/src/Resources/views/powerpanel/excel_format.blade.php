<!doctype html>
<html>
  <head>
    <title>Liquor Shop Leads</title>
  </head>
  <body>
      @if(isset($LiquorShopLead) && !empty($LiquorShopLead))
          <div class="row">
           <div class="col-12">
              <table class="search-result allData" id="" border="1">
                 <thead>
                  <tr>
                        <th style="font-weight: bold;text-align:center" colspan="6">{{ trans("liquorshoplead::template.liquorShopleadModule.LiquorShopLead") }}</th>
                   </tr>
                    <tr>
                       <th style="font-weight: bold;">{{ trans('liquorshoplead::template.common.requestNumber') }}</th>
                       <th style="font-weight: bold;">{{ trans('liquorshoplead::template.common.component') }}</th>
                       <th style="font-weight: bold;">{{ trans('liquorshoplead::template.common.totalPOS') }}</th>
                       <th style="font-weight: bold;">{{ trans('liquorshoplead::template.common.zipCode') }}</th>
                       <th style="font-weight: bold;">{{ trans('liquorshoplead::template.common.emailAddress') }}</th>
                       <th style="font-weight: bold;">{{ trans('liquorshoplead::template.common.firstName') }}</th>
                       <th style="font-weight: bold;">{{ trans('liquorshoplead::template.common.lastName') }}</th>
                       <th style="font-weight: bold;">{{ trans('liquorshoplead::template.common.companyName') }}</th>
                       <th style="font-weight: bold;">{{ trans('liquorshoplead::template.common.phoneNumber') }}</th>
                       <th style="font-weight: bold;">{{ trans('template.common.ipAddress') }}</th>
                       <th style="font-weight: bold;">{{ trans('liquorshoplead::template.common.receivedDateTime') }}</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach($LiquorShopLead as $row)
                    <tr>
                       <td>{{ $row->varRequestNumber }}</td>
                       <td>{{ (isset($row->chrChooseComponent) && !empty($row->chrChooseComponent) && $row->chrChooseComponent==1 ) ? 'Complete POS System':'Software Only' }}</td>
                       <td>{{ (isset($row->chrHowManyPOS) && !empty($row->chrHowManyPOS) && $row->chrHowManyPOS==1 ) ? 'One POS':'Two or more POS Systems' }}</td>
                       <td>{{ (isset($row->varOnZipCode) && !empty($row->varOnZipCode) ) ? \App\Helpers\MyLibrary::decryptLatest($row->varOnZipCode):'' }}</td>
                       <td>{{ (isset($row->varOnEmailAddress) && !empty($row->varOnEmailAddress) ) ? \App\Helpers\MyLibrary::decryptLatest($row->varOnEmailAddress):'' }}</td>
                       <td>{{ (isset($row->varOnFirstName) && !empty($row->varOnFirstName) ) ? $row->varOnFirstName:'' }}</td>
                       <td>{{ (isset($row->varOnLastName) && !empty($row->varOnLastName) ) ? $row->varOnLastName:'' }}</td>
                       <td>{{ (isset($row->varOnCompanyName) && !empty($row->varOnCompanyName) ) ? $row->varOnCompanyName:'' }}</td>
                       <td>{{ (isset($row->varOnPhoneNumber) && !empty($row->varOnPhoneNumber) ) ? \App\Helpers\MyLibrary::decryptLatest($row->varOnPhoneNumber):'' }}</td>
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
