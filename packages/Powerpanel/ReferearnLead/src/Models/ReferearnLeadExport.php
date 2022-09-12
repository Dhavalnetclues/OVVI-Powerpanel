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
        if (Request::get('export_type') == 'selected_records') {
            if (null !== Request::get('delete')) {
                $selectedIds = Request::get('delete');
            } else {
                $selectedIds = false;
            }
            //$filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
            $arrResults = ReferearnLead::getListForExport($selectedIds);
        } else {
            $arrResults = ReferearnLead::getListForExport();
        }
    
        if (count($arrResults) > 0) {
            return view('referearn::powerpanel.excel_format', ['referearnLead' => $arrResults]);
        }
    }

}
