<!doctype html>
<html>
  <head>
    <title>{{ Config::get('Constant.SITE_NAME') }} Feedback Leads</title>
  </head>
  <body>
      @if(isset($FeedbackLead) && !empty($FeedbackLead))
          <div class="row">
           <div class="col-12">
              <table class="search-result allData" id="" border="1">
                 <thead>
                  <tr>
                        <th style="font-weight: bold;text-align:center" colspan="6">{{ Config::get('Constant.SITE_NAME') }} {{ trans("feedbacklead::template.feedbackleadModule.feedbackLeads") }}</th>
                   </tr>
                    <tr>
                       <th style="font-weight: bold;">{{ trans('feedbacklead::template.common.name') }}</th>
                       <th style="font-weight: bold;">{{ trans('feedbacklead::template.common.email') }}</th>
                       <th style="font-weight: bold;">{{ trans('feedbacklead::template.feedbackleadModule.phone') }}</th>
                       <th style="font-weight: bold;">{{ trans('feedbacklead::template.feedbackleadModule.satisfied') }}</th>
                       <th style="font-weight: bold;">{{ trans('feedbacklead::template.feedbackleadModule.visitfor') }} </th>
                       <th style="font-weight: bold;">{{ trans('feedbacklead::template.feedbackleadModule.category') }}</th>
                       <th style="font-weight: bold;">{{ trans('feedbacklead::template.feedbackleadModule.message') }}</th>
                       <th style="font-weight: bold;">{{ trans('template.common.ipAddress') }}</th>
                       <th style="font-weight: bold;">{{ trans('feedbacklead::template.feedbackleadModule.receivedDateTime') }}</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach($FeedbackLead as $row)
                  @php
                  $Satisfied = '-';
                  $Visitfor = '-';
                  $category = '-';
                  $phoneNo = '-';
                  $userMessage = '-';
	                  if ($row->chrSatisfied != 'N') {
												if ($row->chrSatisfied == 'H') {
														$Satisfied = "Horrible";
												} elseif ($row->chrSatisfied == 'B') {
														$Satisfied = "Bad";
												} elseif ($row->chrSatisfied == 'J') {
														$Satisfied = "Just OK";
												} elseif ($row->chrSatisfied == 'G') {
														$Satisfied = "Good";
												} elseif ($row->chrSatisfied == 'S') {
														$Satisfied = "Super!";
												} else {
														$Satisfied = '-';
												}
										} else {
												$Satisfied = '-';
										}

										if (!empty($row->varVisitfor)) {
												$Visitfor = nl2br($row->varVisitfor);
										} else {
												$Visitfor = '-';
										}

										if ($row->chrCategory != '0') {
												if ($row->chrCategory == '1') {
														$category = "Suggestions";
												} elseif ($row->chrCategory == '2') {
														$category = "Issues/bugs";
												} elseif ($row->chrCategory == '3') {
														$category = "Others";
												} else {
														$category = '-';
												}
										} else {
												$category = '-';
										}

										if (!empty($row->varPhoneNo)) {
												$phoneNo = \App\Helpers\MyLibrary::getDecryptedString($row->varPhoneNo);
										}

										if (!empty($row->txtUserMessage)) {
												$userMessage = $row->txtUserMessage;
										}
                  @endphp
                    <tr>
                       <td>{{ $row->varName }}</td>
                       <td>{{ \App\Helpers\MyLibrary::getDecryptedString($row->varEmail) }}</td>
                       <td>{{ $phoneNo }}</td>
                       <td>{{ $Satisfied }}</td>
                       <td>{{ $Visitfor }}</td>
                       <td>{{ $category }}</td>
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
