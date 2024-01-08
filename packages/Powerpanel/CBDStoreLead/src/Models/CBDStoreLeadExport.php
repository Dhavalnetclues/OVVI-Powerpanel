<?php
namespace Powerpanel\CBDStoreLead\Models;
use Powerpanel\CBDStoreLead\Models\CBDStoreLead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class CBDStoreLeadExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder
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
            $arrResults = CBDStoreLead::getListForExport($filterArr);

        } else {
            $arrResults = CBDStoreLead::getListForExport($filterArr);
        }

        if (count($arrResults) > 0) {
            return view('cbdstorelead::powerpanel.excel_format', ['CBDStoreLead' => $arrResults]);
        }
    }

}
