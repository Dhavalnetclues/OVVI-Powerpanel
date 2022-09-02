<?php
namespace Powerpanel\NewsletterLead\Models;
use Powerpanel\NewsletterLead\Models\NewsletterLead;
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
            //$filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
            $arrResults = NewsletterLead::getListForExport($selectedIds);
        } else {
            $arrResults = NewsletterLead::getListForExport();
        }

        if (count($arrResults) > 0) {
            return view('newsletterlead::powerpanel.excel_format', ['newsletterLeads' => $arrResults]);
        }
    }

}
