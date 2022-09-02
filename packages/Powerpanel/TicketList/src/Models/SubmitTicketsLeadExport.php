<?php
namespace Powerpanel\TicketList\Models;
use Powerpanel\TicketList\Models\SubmitTickets;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;

class SubmitTicketsLeadExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        if (Request::get('export_type') == 'selected_records') {
            $selectedIds = array();
            if (null !== Request::get('delete')) {
                $selectedIds = Request::get('delete');
            }
            $arrResults = SubmitTickets::getListForExport($selectedIds);
        } else {
            $arrResults = SubmitTickets::getListForExport();
        }

        if (count($arrResults) > 0) {
            return view('ticketlist::powerpanel.excel_format', ['SubmitTicketsLead' => $arrResults]);
        }
    }

}
