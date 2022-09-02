<!doctype html>
<html>
    <head>
        <title>{{ Config::get('Constant.SITE_NAME') }} Events Leads</title>
    </head>
    <body>
        @if(isset($eventsLead) && !empty($eventsLead))
        <div class="row">
            <div class="col-12">
                <table class="search-result allData" id="" border="1">
                    <thead>
                        <tr>
                            <th style="font-weight: bold;text-align:center" colspan="11">{{ Config::get('Constant.SITE_NAME') }} {{ trans("events::template.eventsleadModule.eventsLeads") }}</th>
                        </tr>
                        <tr>
                            <th style="font-weight: bold;">Event Name</th>
                            <th style="font-weight: bold;">Attendees Name</th>
                            <th style="font-weight: bold;">Attendees Email</th>
                            <th style="font-weight: bold;">No of Attendees</th>
                            <th style="font-weight: bold;">Start Date</th>
                            <th style="font-weight: bold;">End Date</th>
                            <!-- <th style="font-weight: bold;">Start Time</th>
                            <th style="font-weight: bold;">End Time</th>              -->
                            <th style="font-weight: bold;">Message</th>
                            <th style="font-weight: bold;">Attendee Details</th>
                            <th style="font-weight: bold;">{{ trans('template.common.ipAddress') }}</th>
                            <th style="font-weight: bold;">Received Date Time</th>   
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventsLead as $row)
                        <tr>
                            @php
                                $eventName = \Powerpanel\Events\Models\Events::getRecordById($row->eventId);
                            @endphp
                            <td>{{ isset($eventName->varTitle) ? $eventName->varTitle : '' }}</td>
                            @php
                                $attendeeDetails = json_decode($row->attendeeDetail);
                            @endphp
                            <td>{{ (isset($attendeeDetails[0]->full_name) && !empty($attendeeDetails[0]->full_name)) ? $attendeeDetails[0]->full_name : '-'  }}</td>
                            <td>{{ (isset($attendeeDetails[0]->email) && !empty($attendeeDetails[0]->email)) ? $attendeeDetails[0]->email : '-'  }}</td>
                            <td>{{ $row->noOfAttendee }}</td>
                            @php 
                                $startDate = date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($row->startDate));
                                $endDate = date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($row->endDate));
                                $startTime = date('h:i a',strtotime($row->startTime));
                                $endTime = date('h:i a',strtotime($row->endTime));
                            @endphp
                            <td>{{ $startDate }}</td>
                            <td>{{ $endDate }}</td>
                            <!-- <td>{{ $startTime }}</td>
                            <td>{{ $endTime }}</td> -->
                            <td>{{ $row->message }}</td>
                            <td>
                            	@if(!empty($attendeeDetails))
                                @foreach($attendeeDetails as $member)
                                    Full Name : {{ $member->full_name }} <br>
                                    Email : {{ $member->email }} <br>
                                    Phone : {{ $member->phone }} <br><br>
                                @endforeach
                              @else
                              	-
                              @endif
                            </td>

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
