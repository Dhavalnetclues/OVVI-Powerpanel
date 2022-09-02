<?php
namespace Powerpanel\ComplaintLead\Models;
use Powerpanel\ComplaintLead\Models\ComplaintLead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class ComplaintLeadExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder
{
    public function view(): View
    {
        if (Request::get('export_type') == 'selected_records') {
            $selectedIds = array();
            if (null !== Request::get('delete')) {
                $selectedIds = Request::get('delete');
            }
            $arrResults = ComplaintLead::getListForExport($selectedIds);

        } else {
            $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
            $arrResults = ComplaintLead::getListForExport(false, $filterArr);
        }

        if (count($arrResults) > 0) {
            return view('complaintlead::powerpanel.excel_format', ['ComplaintLead' => $arrResults]);
        }
    }

}
