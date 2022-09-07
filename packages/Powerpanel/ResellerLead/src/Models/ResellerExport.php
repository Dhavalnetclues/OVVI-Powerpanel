<?php
namespace Powerpanel\ResellerLead\Models;
use Powerpanel\ResellerLead\Models\ResellerLead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;

class ResellerLeadExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        if (Request::get('export_type') == 'selected_records') {
            $selectedIds = array();
            if (null !== Request::get('delete')) {
                $selectedIds = Request::get('delete');
            }
            $arrResults = ResellerLead::getListForExport($selectedIds);
            
        } else {
            $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
            $arrResults = ResellerLead::getListForExport();
        }
        // echo "<pre>";print_r($arrResults);die;

        if (count($arrResults) > 0) {
            return view('getdemolead::powerpanel.excel_format', ['ResellerLead' => $arrResults]);
        }
    }

}
