<?php
namespace App\Exports;
use App\NewsletterLead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;

class NewsletterLeadExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        if (Request::get('export_type') == 'selected_records') {
            if (null !== Request::get('delete')) {
                $selectedIds = Request::get('delete');
            } else {
                $selectedIds = false;
            }            
            $arrResults = NewsletterLead::getListForExport($selectedIds);
        } else {
            $arrResults = NewsletterLead::getListForExport();
        }

        if (count($arrResults) > 0) {
          return view('powerpanel.newsletter_lead.excel_format', ['newsletterLeads' => $arrResults]);
        }
    }

}
