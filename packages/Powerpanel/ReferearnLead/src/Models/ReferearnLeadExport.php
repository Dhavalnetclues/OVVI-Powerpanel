<?php
namespace Powerpanel\ReferearnLead\Models;
use Powerpanel\ReferearnLead\Models\ReferearnLead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Request;
use Config;

class ReferearnLeadExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        // print_r(Request::all());die;
        $filterArr = array();
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['start'] = !empty(Request::get('start_date')) ? date("Y-m-d", strtotime(Request::get('start_date'))) : '';
        $filterArr['end'] = !empty(Request::get('end_date')) ? date("Y-m-d",strtotime(Request::get('end_date'))) : '';
        if (Request::get('export_type') == 'selected_records') {
            if (null !== Request::get('delete')) {
                $filterArr["checkedIds"] = Request::get('delete');
            }
            $arrResults = ReferearnLead::getListForExport($filterArr);
        } else {
            $arrResults = ReferearnLead::getListForExport($filterArr);
        }
    
        if (count($arrResults) > 0) {
            return view('referearn::powerpanel.excel_format', ['referearnLead' => $arrResults]);
        }
    }

}
