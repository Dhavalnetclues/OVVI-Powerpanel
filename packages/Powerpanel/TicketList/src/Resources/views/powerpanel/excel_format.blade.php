<!doctype html>
<html>
	<head>
		<title>Submit Tickets Leads</title>
	</head>
	<body>
			@if(isset($SubmitTicketsLead) && !empty($SubmitTicketsLead))
					<div class="row">
					 <div class="col-12">
							<table class="search-result allData" id="" border="1">
								 <thead>
									<tr>
												<th style="font-weight: bold;text-align:center" colspan="6">{{ Config::get('Constant.SITE_NAME') }} {{ trans("ticketlist::template.contactleadModule.contactUsLeads") }}</th>
									 </tr>
										<tr>
											 <th style="font-weight: bold;">{{ trans('ticketlist::template.SubmitTicketsModule.Title') }}</th>
											 <th style="font-weight: bold;">{{ trans('ticketlist::template.SubmitTicketsModule.Type') }}</th>
											 <th style="font-weight: bold;">{{ trans('ticketlist::template.SubmitTicketsModule.message') }}</th>
											 <th style="font-weight: bold;">{{ trans('ticketlist::template.SubmitTicketsModule.Link') }}</th>
											 <th style="font-weight: bold;">{{ trans('ticketlist::template.SubmitTicketsModule.ticketStatus') }}</th>
											 <th style="font-weight: bold;">{{ trans('ticketlist::template.SubmitTicketsModule.receivedDateTime') }}</th>
										</tr>
								 </thead>
								 <tbody>
									@foreach($SubmitTicketsLead as $row)
									@php

										if ($row->chrStatus == 'H') {
		                    $ticketStatus = 'On Hold';
		                } else if ($row->chrStatus == 'G') {
		                    $ticketStatus = 'On Going';
		                } else if ($row->chrStatus == "C") {
		                    $ticketStatus = 'Completed';
		                } else if ($row->chrStatus == 'N') {
		                    $ticketStatus = 'New Implementation';
		                }else{
		                		$ticketStatus = 'Pending';
		                }

										if ($row->intType == 1) {
		                    $type = 'Fixes / Issues';
		                } else if ($row->intType == 2) {
		                    $type = 'Changes';
		                } else if ($row->intType == 3) {
		                    $type = 'Suggestion';
		                } else if ($row->intType == 4) {
		                    $type = 'New Features';
		                }
									@endphp
										<tr>
											 <td>{{ $row->varTitle }}</td>
											 <td>{{ $type }}</td>
											 <td>{{ $row->txtShortDescription }}</td>
											 <td>{{ $row->varLink }}</td>
											 <td>{{ $ticketStatus }}</td>
											 <td>{{ date(''.Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT').'',strtotime($row->created_at)) }}</td>
										</tr>
									@endforeach
								 </tbody>
							</table>
					 </div>
				</div>
			@endif
	</html>
