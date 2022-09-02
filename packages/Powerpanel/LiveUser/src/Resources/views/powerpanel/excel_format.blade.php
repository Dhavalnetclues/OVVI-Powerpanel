<!doctype html>
<html>
  <head>
    <title>{{ Config::get('Constant.SITE_NAME') }} Live User Lists</title>
  </head>
  <body>
      @if(isset($liveUsers) && !empty($liveUsers))
          <div class="row">
           <div class="col-12">
              <table class="search-result allData" id="" border="1">
                 <thead>
                  <tr>
                        <th style="font-weight: bold;text-align:center" colspan="6">{{ Config::get('Constant.SITE_NAME') }} {{ trans("liveuser::template.liveUsersModule.liveusers") }}</th>
                   </tr>
                    <tr>
                       <th style="font-weight: bold;">{{ trans('liveuser::template.liveUsersModule.ipAddress') }}</th>
                       <th style="font-weight: bold;">Continent Code</th>
                       <th style="font-weight: bold;">Continent Name</th>
                       <th style="font-weight: bold;">Country Code 2</th>
                       <th style="font-weight: bold;">Country Code 3</th>
                       <th style="font-weight: bold;">{{ trans('liveuser::template.liveUsersModule.CountryName') }}</th>
                       <th style="font-weight: bold;">State</th>
                       <th style="font-weight: bold;">District</th>
                       <th style="font-weight: bold;">City</th>
                       <th style="font-weight: bold;">Zip Code</th>
                       <th style="font-weight: bold;">Latitude</th>
                       <th style="font-weight: bold;">Longitude</th>
                       <th style="font-weight: bold;">Is EU</th>
                       <th style="font-weight: bold;">Calling Code</th>
                       <th style="font-weight: bold;">Country Tld</th>
                       <th style="font-weight: bold;">Languages</th>
                       <th style="font-weight: bold;">Country Flag</th>
                       <th style="font-weight: bold;">Geo Name Id</th>
                       <th style="font-weight: bold;">ISP</th>
                       <th style="font-weight: bold;">Connection Type</th>
                       <th style="font-weight: bold;">Organization</th>
                       <th style="font-weight: bold;">Currency Code</th>
                       <th style="font-weight: bold;">Currency Name</th>
                       <th style="font-weight: bold;">Currency Symbol</th>
                       <th style="font-weight: bold;">Timezone Name</th>
                       <th style="font-weight: bold;">Timezone Offset</th>
                       <th style="font-weight: bold;">Timezone Current Time</th>
                       <th style="font-weight: bold;">Timezone Current Time Unix</th>
                       <th style="font-weight: bold;">Timezone Is DST</th>
                       <th style="font-weight: bold;">Timezone DST Savings</th>
                       <th style="font-weight: bold;">Browser Information</th>
                       <th style="font-weight: bold;">{{ trans('liveuser::template.liveUsersModule.receivedDateTime') }}</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach($liveUsers as $row)
                    <tr>
                       <td>{{ $row->varIpAddress }}</td>
                       <td>{{ $row->varContinent_code }}</td>
                       <td>{{ $row->varContinent_name }}</td>
                       <td>{{ $row->varCountry_code2 }}</td>
                       <td>{{ $row->varCountry_code3 }}</td>
                       <td>{{$row->varCountry_name}}</td>
                       <td>{{ $row->varCountry_capital }}</td>
                       <td>{{ $row->varState_prov }}</td>
                       <td>{{ $row->varDistrict }}</td>
                       <td>{{ $row->varCity }}</td>
                       <td>{{ $row->varZipcode }}</td>
                       <td>{{ $row->varLatitude }}</td>
                       <td>{{ $row->varLongitude }}</td>
                       <td>{{ $row->varIs_eu }}</td>
                       <td>{{ $row->varCalling_code }}</td>
                       <td>{{ $row->varCountry_tld }}</td>
                       <td>{{ $row->varLanguages }}</td>
                       <td>{{ $row->varCountry_flag }}</td>
                       <td>{{ $row->varGeoname_id }}</td>
                       <td>{{ $row->varIsp }}</td>
                       <td>{{ $row->varConnection_type }}</td>
                       <td>{{ $row->varOrganization }}</td>
                       <td>{{ $row->varCurrencyCode }}</td>
                       <td>{{ $row->varCurrencyName }}</td>
                       <td>{{ $row->varCurrencySymbol }}</td>
                       <td>{{ $row->varTime_zoneName }}</td>
                       <td>{{ $row->varTime_zoneOffset }}</td>
                       <td>{{ $row->varTime_zoneCurrent_time }}</td>
                       <td>{{ $row->varTime_zoneCurrent_time_unix }}</td>
                       <td>{{ $row->varTime_zoneIs_dst }}</td>
                       <td>{{ $row->varTime_zoneDst_savings }}</td>
                       <td>{{ $row->txtBrowserInf }}</td>
                       <td>{{ date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($row->created_at)) }}</td>
                    </tr>
                  @endforeach
                 </tbody>
              </table>
           </div>
        </div>
      @endif
  </html>
