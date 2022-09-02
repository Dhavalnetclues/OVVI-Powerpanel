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
        if (Request::get('export_type') == 'selected_records') {
            $selectedIds = array();
            if (null !== Request::get('delete')) {
                $selectedIds = Request::get('delete');
            }
            $arrResults = GetdemoLead::getListForExport($selectedIds);
            
        } else {
            $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
            $arrResults = GetdemoLead::getListForExport();
        }
        // echo "<pre>";print_r($arrResults);die;

        if (count($arrResults) > 0) {
            return view('getdemolead::powerpanel.excel_format', ['GetdemoLead' => $arrResults]);
        }
    }

}
