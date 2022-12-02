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
        $filterArr = array();
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['start'] = !empty(Request::get('start_date')) ? date("Y-m-d", strtotime(Request::get('start_date'))) : '';
        $filterArr['end'] = !empty(Request::get('end_date')) ? date("Y-m-d",strtotime(Request::get('end_date'))) : '';
        if (Request::get('export_type') == 'selected_records') {
            if (null !== Request::get('delete')) {
                 $filterArr["checkedIds"] = Request::get('delete');
            }
            $arrResults = ResellerLead::getListForExport($filterArr);
            
        } else {
            $arrResults = ResellerLead::getListForExport($filterArr);
        }
        
        if (count($arrResults) > 0) {
            return view('resellerlead::powerpanel.excel_format', ['ResellerLead' => $arrResults]);
        }
    }

}
