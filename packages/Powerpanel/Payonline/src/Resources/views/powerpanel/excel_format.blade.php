<!doctype html>
<html>
    <head>
        <title>{{ Config::get('Constant.SITE_NAME') }} Pay Online</title>
    </head>
    <body>
        @if(isset($payonline) && !empty($payonline))
        <div class="row">
            <div class="col-12">
                <table class="search-result allData" id="" border="1">
                    <thead>
                        <tr>
                            <th style="font-weight: bold;text-align:center" colspan="11">{{ Config::get('Constant.SITE_NAME') }} Payment leads</th>
                        </tr>
                        <tr>
                            <th width="10%" align="left">{{ trans('payonline::template.payonlineModule.transactionId') }}</th>	
							<th width="10%" align="left">{{ trans('payonline::template.payonlineModule.name') }}</th>
							<th width="10%" align="left">{{ trans('payonline::template.payonlineModule.companyName') }}</th>
							<th width="10%" align="left">{{ trans('payonline::template.payonlineModule.email') }}</th>
							<th width="10%" align="left">{{ trans('payonline::template.payonlineModule.phone') }}</th>
							{{-- <th width="10%" align="left">{{ trans('payonline::template.payonlineModule.invoiceNumber') }}</th> --}}
							<th width="10%" align="left">{{ trans('payonline::template.payonlineModule.amount') }}</th>
							<th width="10%" align="left">{{ trans('payonline::template.payonlineModule.currency') }}</th>
							<th width="10%" align="left">{{ trans('payonline::template.payonlineModule.cardType') }}</th>
							<th width="10%" align="left">{{ trans('payonline::template.payonlineModule.note') }}</th>
							<th width="10%" align="center">{{ trans('payonline::template.payonlineModule.payment_date') }}</th>
							<th width="10%" align="center">{{ trans('payonline::template.payonlineModule.ipAddress') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payonline as $row)
                        <tr>
                            <td>{{ $row->txnId }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->companyName }}</td>
                            <td>{{ \App\Helpers\MyLibrary::getDecryptedString($row->email) }}</td>
                            <td>{{ (!empty($row->phone)?\App\Helpers\MyLibrary::getDecryptedString($row->phone):'-') }}</td>
                            {{-- <td>{{ $row->invoiceNo }}</td> --}}
                            <td>${{ $row->amount }}</td>
                            <td>{{ $row->currency }}</td>
                            <td>{{ $row->cardType }} Card</td>
                            <td>{{ $row->note }}</td>
                            <td>{{ $row->payment_date }}</td>
                            <td>{{ $row->varIpAddress }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
</html>
