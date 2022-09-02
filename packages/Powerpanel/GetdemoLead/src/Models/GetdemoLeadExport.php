<?php
namespace Powerpanel\FeedbackLead\Models;
use Powerpanel\FeedbackLead\Models\FeedbackLead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;

class GetdemoExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        if (Request::get('export_type') == 'selected_records') {
            $selectedIds = array();
            if (null !== Request::get('delete')) {
                $selectedIds = Request::get('delete');
            }
            $arrResults = FeedbackLead::getListForExport($selectedIds);

        } else {
            $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
            $arrResults = FeedbackLead::getListForExport();
        }

        if (count($arrResults) > 0) {
            return view('feedbacklead::powerpanel.excel_format', ['FeedbackLead' => $arrResults]);
        }
    }

}
