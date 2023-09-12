<?php
namespace Powerpanel\WhiteLabelLead\Models;
use Powerpanel\WhiteLabelLead\Models\WhiteLabelLead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class WhiteLabelLeadExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder
{
    public function view(): View
    {
        $filterArr = array();
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['start'] = !empty(Request::get('start_date')) ? date("Y-m-d", strtotime(Request::get('start_date'))) : '';
        $filterArr['end'] = !empty(Request::get('end_date')) ? date("Y-m-d",strtotime(Request::get('end_date'))) : '';
        if (Request::get('export_type') == 'selected_records') {
            if (null !== Request::get('delete')) {
                $filterArr["checkedIds"] = Request::get('delete');
            }
            $arrResults = WhiteLabelLead::getListForExport($filterArr);

        } else {
            $arrResults = WhiteLabelLead::getListForExport($filterArr);
        }

        if (count($arrResults) > 0) {
            return view('whitelabellead::powerpanel.excel_format', ['WhiteLabelLead' => $arrResults]);
        }
    }

}
