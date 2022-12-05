<?php
namespace Powerpanel\OrderLead\Models;
use Powerpanel\OrderLead\Models\OrderLead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;

class OrderLeadExport implements FromView, ShouldAutoSize
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
            $arrResults = OrderLead::getListForExport($filterArr);
            
        } else {
            $arrResults = OrderLead::getListForExport($filterArr);
        }
        // echo "<pre>";print_r(count($arrResults));die;
        if (count($arrResults) > 0) {
            return view('orderlead::powerpanel.excel_format', ['OrderLeads' => $arrResults]);
        }
    }

}
