<?php
namespace Powerpanel\GetdemoLead\Models;
use Powerpanel\GetdemoLead\Models\GetdemoLead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;


class GetdemoLeadExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $filterArr = $arrResults= array();
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['start'] = !empty(Request::get('start_date')) ? date("Y-m-d", strtotime(Request::get('start_date'))) : '';
        $filterArr['end'] = !empty(Request::get('end_date')) ? date("Y-m-d",strtotime(Request::get('end_date'))) : '';
        if (Request::get('export_type') == 'selected_records') {
            if (null !== Request::get('delete')) {
                 $filterArr["checkedIds"] = Request::get('delete');
            }
            $arrResults = GetdemoLead::getListForExport($filterArr);
            
        } else {
            $arrResults = GetdemoLead::getListForExport($filterArr);
        }
        // echo "<pre>";print_r($arrResults);die;

        if (count($arrResults) > 0) {
            return view('getdemolead::powerpanel.excel_format', ['GetdemoLead' => $arrResults]);
        }
    }

}
